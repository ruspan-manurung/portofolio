<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->get();

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Accessed User Management Page',
            'target' => 'User Management Page',
            'status' => 'Success',
            'details' => 'User successfully accessed the user management page.',
            'ip_address' => request()->ip(),
        ]);

        return Inertia::render('User', [
            'users' => $users,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:participant,admin,assessor',
        ]);

        $rawPassword = $validated['password'];

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'role' => $validated['role'],
            'password' => Hash::make($rawPassword),
        ]);

        // Kirim email ke user
        Mail::to($user->email)->send(new \App\Mail\UserCreatedMail($user, $rawPassword));

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Create User',
            'target' => $validated['email'],
            'status' => 'Success',
            'details' => 'User created successfully.',
            'ip_address' => $request->ip(),
        ]);

        return redirect()->back()->with('success', 'User created successfully & email sent.');
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'required|in:participant,admin,assessor',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6', // opsional
        ]);

        $before = $user->toArray();

        // Update field yang tidak berhubungan dengan password
        $user->update([
            'name' => $validated['name'],
            'role' => $validated['role'],
            'email' => $validated['email'],
        ]);

        // Jika password diisi, update juga
        if (!empty($validated['password'])) {
            $user->update([
                'password' => Hash::make($validated['password']),
            ]);
        }

        $after = $user->fresh()->toArray();
        // Ambil hanya perubahan (diff)
        $changes = [];
        foreach ($before as $key => $value) {
            if (!array_key_exists($key, $after)) {
                continue;
            }

            if ($after[$key] !== $value) {
                $changes[$key] = [
                    'before' => $value,
                    'after' => $after[$key],
                ];
            }
        }

        $formattedDetails = "User updated successfully. Changes:\n";
        foreach ($changes as $field => $change) {
            $formattedDetails .= "- {$field}: '{$change['before']}' → '{$change['after']}'\n";
        }

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Update User',
            'target' => $user->email,
            'status' => 'Success',
            'details' => $formattedDetails,
            'ip_address' => $request->ip(),
        ]);

        return redirect()->back()->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        AuditLog::create([
            'user_id' => auth()->id(),
            'action' => 'Delete User',
            'target' => $user->email,
            'status' => 'Success',
            'details' => 'User deleted successfully.',
            'ip_address' => request()->ip(),
        ]);

        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully.');
    }
}