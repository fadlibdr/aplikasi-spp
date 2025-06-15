<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;
use Carbon\Carbon;

class AutomaticBackup extends Command
{
    protected $signature = 'backup:automatic';
    protected $description = 'Jalankan auto backup dan prune berdasarkan pengaturan';

    public function handle()
    {
        // 1) Ambil setting
        $freq = Setting::where('key', 'backup_frequency')->value('value') ?? 'daily';
        $max = (int) Setting::where('key', 'backup_max_files')->value('value') ?? 7;

        $now = Carbon::now();

        // 2) Tentukan apakah hari ini perlu backup
        $should = match ($freq) {
            'daily' => true,
            'weekly' => $now->isMonday(),
            'monthly' => $now->day === 1,
            'yearly' => $now->month === 1 && $now->day === 1,
            default => false,
        };

        if (!$should) {
            return 0;
        }

        // 3) Jalankan backup database saja
        $this->info("Running scheduled backup:{$freq}");
        Artisan::call('backup:run --only-db');
        $this->info(Artisan::output());

        // 4) Prune file lama jika melebihi $max
        $disk = config('backup.backup.destination.disks')[0];
        $folder = config('backup.backup.name');
        $files = collect(Storage::disk($disk)->files($folder))
            ->filter(fn($f) => str_ends_with($f, '.zip'))
            // urut ascending => yang paling lama di depan
            ->sortBy(fn($f) => Storage::disk($disk)->lastModified($f))
            ->values();

        if ($files->count() > $max) {
            $toDelete = $files->take($files->count() - $max);
            foreach ($toDelete as $f) {
                Storage::disk($disk)->delete($f);
                $this->info("Deleted old backup: {$f}");
            }
        }

        return 0;
    }
}
