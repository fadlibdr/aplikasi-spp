<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:admin']);
    }

    public function index()
    {
        $users = User::with('roles')->paginate(10);
        return view('users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('users.form', [
            'mode' => 'create',
            'action' => route('users.store'),
            'user' => new User,
            'roles' => $roles,
            'selected' => [],
        ]);
    }

    public function store(Request $r)
    {
        $v = $r->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:6',
            'roles' => 'required|array|min:1',
        ]);

        $u = User::create([
            'name' => $v['name'],
            'email' => $v['email'],
            'password' => Hash::make($v['password']),
        ]);
        $u->syncRoles($v['roles']);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil dibuat.');
    }

    public function edit(User $user)
    {
        $roles = Role::all();
        return view('users.form', [
            'mode' => 'edit',
            'action' => route('users.update', $user),
            'user' => $user,
            'roles' => $roles,
            'selected' => $user->roles->pluck('name')->toArray(),
        ]);
    }

    public function update(Request $r, User $user)
    {
        $v = $r->validate([
            'name' => 'required',
            'email' => "required|email|unique:users,email,{$user->id}",
            'password' => 'nullable|confirmed|min:6',
            'roles' => 'required|array|min:1',
        ]);

        $user->update([
            'name' => $v['name'],
            'email' => $v['email'],
            'password' => $v['password'] ? Hash::make($v['password']) : $user->password,
        ]);
        $user->syncRoles($v['roles']);

        return redirect()->route('users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return back()->with('success', 'User berhasil dihapus.');
    }
}
