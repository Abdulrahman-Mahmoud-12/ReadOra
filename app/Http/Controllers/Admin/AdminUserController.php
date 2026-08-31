<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AdminUserController extends Controller
{
    public function __construct(
        protected AuditLogger $auditLogger
    ) {}

    public function index(Request $request): View
    {
        $search = $request->string('search')->trim()->toString();
        $role = $request->string('role')->trim()->toString();

        $users = User::query()
            ->withCount(['borrowings', 'activeBorrowings', 'favorites'])
            ->when($search !== '', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            })
            ->when($role !== '', fn ($q) => $q->where('role', $role))
            ->latest('id')
            ->paginate(15)
            ->withQueryString();

        return view('admin.users.index', [
            'users' => $users,
            'search' => $search,
            'role' => $role,
            'totalUsers' => User::count(),
            'adminCount' => User::where('role', 'admin')->count(),
            'patronCount' => User::where('role', 'user')->count(),
        ]);
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'role' => ['required', 'string', 'in:user,admin'],
        ]);

        $newRole = $validated['role'];

        // Safety safeguard: Do not allow demoting the last administrator account
        if ($user->isAdmin() && $newRole === 'user') {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return back()->with('error', 'Action denied: Cannot demote the last remaining administrator account.');
            }
        }

        $oldRole = $user->role;
        $user->update(['role' => $newRole]);

        $this->auditLogger->log('user.role_updated', $user, ['role' => $oldRole], ['role' => $newRole]);

        return back()->with('status', "User \"{$user->name}\" role updated to ".ucfirst($newRole).'.');
    }

    public function destroy(Request $request, User $user): RedirectResponse
    {
        // Safety safeguard 1: Do not delete current logged in admin
        if ($user->id === $request->user()->id) {
            return back()->with('error', 'Action denied: You cannot delete your own logged-in account.');
        }

        // Safety safeguard 2: Do not delete last admin
        if ($user->isAdmin()) {
            $adminCount = User::where('role', 'admin')->count();
            if ($adminCount <= 1) {
                return back()->with('error', 'Action denied: Cannot delete the last remaining administrator account.');
            }
        }

        // Safety safeguard 3: Do not delete user with active unreturned loans
        if ($user->activeBorrowings()->count() > 0) {
            return back()->with('error', "Action denied: User \"{$user->name}\" currently has {$user->activeBorrowings()->count()} active unreturned book loans.");
        }

        $oldData = $user->toArray();
        $name = $user->name;
        $user->delete();

        $this->auditLogger->log('user.deleted', null, $oldData, null);

        return back()->with('status', "User \"{$name}\" removed successfully.");
    }
}
