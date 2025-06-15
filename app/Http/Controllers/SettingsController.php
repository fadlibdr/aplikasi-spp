<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use App\Models\Setting;
use ZipArchive;

class SettingsController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        // Pull all settings from DB as key=>value
        $dbSettings = Setting::pluck('value', 'key')->toArray();

        // Define defaults for any missing keys
        $defaults = [
            'app_name' => config('app.name'),
            'app_url' => config('app.url'),
            'midtrans_server_key' => env('MIDTRANS_SERVER_KEY', ''),
            'midtrans_client_key' => env('MIDTRANS_CLIENT_KEY', ''),
            'mail_mailer' => env('MAIL_MAILER', 'smtp'),
            'mail_host' => env('MAIL_HOST', ''),
            'mail_port' => env('MAIL_PORT', ''),
            'mail_username' => env('MAIL_USERNAME', ''),
            'mail_password' => env('MAIL_PASSWORD', ''),
            'mail_encryption' => env('MAIL_ENCRYPTION', ''),
            'mail_from_address' => env('MAIL_FROM_ADDRESS', ''),
            'mail_from_name' => env('MAIL_FROM_NAME', ''),
            'activation_key' => env('ACTIVATION_KEY', ''),
            'backup_frequency' => 'daily',
            'backup_max_files' => '7',
        ];

        // Merge: DB overrides defaults
        $settings = array_merge($defaults, $dbSettings);

        // Now load backup files…
        $disk = config('backup.backup.destination.disks')[0];
        $folder = config('backup.backup.name');
        $files = Storage::disk($disk)->files($folder);
        $backups = collect($files)
            ->filter(fn($f) => str_ends_with($f, '.zip'))
            ->sortDesc()
            ->values();

        return view('settings.index', compact('settings', 'backups', 'folder'));
    }
    public function update(Request $r)
    {
        $v = $r->validate([
            'app_name' => 'required|string',
            'app_url' => 'required|url',
            'midtrans_server_key' => 'required|string',
            'midtrans_client_key' => 'required|string',
            'mail_mailer' => 'required|string',
            'mail_host' => 'required|string',
            'mail_port' => 'required|numeric',
            'mail_username' => 'required|string',
            'mail_password' => 'required|string',
            'mail_encryption' => 'required|string',
            'mail_from_address' => 'required|email',
            'mail_from_name' => 'required|string',
            'activation_key' => 'required|string',
            'backup_frequency' => 'required|in:daily,weekly,monthly,yearly',
            'backup_max_files' => 'required|integer|min:1',
        ]);

        // list semua key yang kita simpan di tabel settings
        $keys = [
            'app_name',
            'app_url',
            'midtrans_server_key',
            'midtrans_client_key',
            'mail_mailer',
            'mail_host',
            'mail_port',
            'mail_username',
            'mail_password',
            'mail_encryption',
            'mail_from_address',
            'mail_from_name',
            'activation_key',
            'backup_frequency',
            'backup_max_files',
        ];

        foreach ($keys as $key) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $v[$key]]
            );
        }

        return back()->with('success', 'Pengaturan berhasil diperbarui.');
    }

    public function backup()
    {
        Artisan::call('backup:run --only-db');
        return back()->with('success', 'Backup database selesai.');
    }

    public function restore(Request $r)
    {
        $r->validate(['file' => 'required|string']);
        $disk = config('backup.backup.destination.disks')[0];
        $zipPath = Storage::disk($disk)->path($r->file);

        $zip = new ZipArchive;
        if ($zip->open($zipPath) === true) {
            $tmp = storage_path('app/restore_tmp');
            File::deleteDirectory($tmp);
            File::makeDirectory($tmp, 0755, true);
            $zip->extractTo($tmp);
            $zip->close();

            $sql = collect(File::allFiles($tmp))
                ->first(fn($f) => str_ends_with($f->getFilename(), '.sql'));
            if ($sql) {
                DB::unprepared(File::get($sql->getRealPath()));
                File::deleteDirectory($tmp);
                return back()->with('success', 'Restore database berhasil.');
            }
        }
        return back()->with('error', 'Gagal restore database.');
    }

    protected function writeEnv(array $data)
    {
        $path = base_path('.env');
        $env = file_get_contents($path);
        foreach ($data as $key => $val) {
            $k = strtoupper($key);
            $pattern = "/^{$k}=.*/m";
            $replacement = "{$k}=\"{$val}\"";
            if (preg_match($pattern, $env)) {
                $env = preg_replace($pattern, $replacement, $env);
            } else {
                $env .= "\n{$replacement}";
            }
        }
        file_put_contents($path, $env);
    }

    public function downloadBackup(string $file)
    {
        $disk = config('backup.backup.destination.disks')[0];
        return Storage::disk($disk)->download($file);
    }

    /**
     * Restore from uploaded ZIP
     */
    public function restoreUpload(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file|mimes:zip',
        ]);

        // 1) Simpan ZIP ke storage/app/temp_restore
        /** @var UploadedFile $upload */
        $upload = $request->file('backup_file');
        $filename = time() . '_' . $upload->getClientOriginalName();
        $tmpZip = $upload->storeAs('temp_restore', $filename);

        $fullPath = storage_path('app/' . $tmpZip);
        $extractPath = storage_path('app/temp_restore_extracted');

        try {
            $zip = new ZipArchive;
            if ($zip->open($fullPath) !== true) {
                throw new \Exception("Tidak bisa membuka ZIP");
            }

            // 2) Ekstrak semua isi ZIP
            File::deleteDirectory($extractPath);
            File::makeDirectory($extractPath, 0755, true);
            $zip->extractTo($extractPath);
            $zip->close();

            // 3) Cari file SQL (.sql atau .sql.gz)
            $allFiles = File::allFiles($extractPath);
            $dumpFile = collect($allFiles)
                ->first(fn($f) => in_array($f->getExtension(), haystack: ['sql', 'gz']));

            if (!$dumpFile) {
                throw new \Exception("Tidak ada file .sql atau .gz dalam ZIP");
            }

            $dumpPath = $dumpFile->getRealPath();
            $sql = '';

            if ($dumpFile->getExtension() === 'gz') {
                // 4a) Dekompresi .gz
                $gz = gzopen($dumpPath, 'rb');
                if (!$gz) {
                    throw new \Exception("Gagal membuka file gzip");
                }
                while (!gzeof($gz)) {
                    $sql .= gzread($gz, 4096);
                }
                gzclose($gz);
            } else {
                // 4b) Baca .sql biasa
                $sql = File::get($dumpPath);
            }

            // 5) Eksekusi semua statement SQL
            DB::unprepared($sql);

            // 6) Bersihkan tmp files
            File::deleteDirectory($extractPath);
            Storage::disk('local')->delete($tmpZip);

            return back()->with('success', 'Restore dari upload berhasil.');

        } catch (\Throwable $e) {
            // log error untuk debugging
            Log::error("Restore upload error: " . $e->getMessage());
            // hapus file sementara agar tidak menumpuk
            File::deleteDirectory($extractPath);
            Storage::disk('local')->delete($tmpZip);

            return back()->with('error', 'Gagal restore dari upload: ' . $e->getMessage());
        }
    }
}
