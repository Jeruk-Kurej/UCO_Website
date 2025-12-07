<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;

class UserController extends Controller
{
    /**
     * Get authenticated user as User instance
     */
    private function getAuthUser(): User
    {
        /** @var User $user */
        $user = Auth::user();
        
        if (!$user) {
            abort(401, 'Unauthenticated.');
        }
        
        return $user;
    }

    /**
     * Display a listing of users.
     */
    public function index()
    {
        $users = User::with('businesses')
            ->latest()
            ->paginate(20);

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created user in storage.
     * Admin creates users manually.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:student,alumni,admin',
            'is_active' => 'required|boolean',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $newUser = User::create($validated);

        return redirect()
            ->route('users.index')
            ->with('success', 'User created successfully!');
    }

    /**
     * Display the specified user.
     */
    public function show(User $userToShow)
    {
        $userToShow->load('businesses.products');

        return view('users.show', compact('userToShow'));
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $userToEdit)
    {
        return view('users.edit', compact('userToEdit'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $userToUpdate)
    {
        $validated = $request->validate([
            'username' => 'required|string|max:255|unique:users,username,' . $userToUpdate->id,
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $userToUpdate->id,
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'role' => 'required|in:student,alumni,admin',
            'is_active' => 'required|boolean',
        ]);

        // Only update password if provided
        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $userToUpdate->update($validated);

        return redirect()
            ->route('users.index')
            ->with('success', 'User updated successfully!');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $userToDelete)
    {
        $currentUser = $this->getAuthUser();

        // Prevent deleting yourself
        if ($userToDelete->id === $currentUser->id) {
            return redirect()
                ->route('users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $userToDelete->delete();

        return redirect()
            ->route('users.index')
            ->with('success', 'User deleted successfully!');
    }
}
