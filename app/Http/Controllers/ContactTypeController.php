<?php

namespace App\Http\Controllers;

use App\Models\ContactType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ContactTypeController extends Controller
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
     * Check if user is admin
     */
    private function authorizeAdmin(): void
    {
        $user = $this->getAuthUser();
        
        if (!$user->isAdmin()) {
            abort(403, 'Only administrators can manage contact types.');
        }
    }

    /**
     * Display a listing of contact types.
     */
    public function index()
    {
        $contactTypes = ContactType::withCount('businessContacts')->latest()->paginate(15);

        return view('contact-types.index', compact('contactTypes'));
    }

    /**
     * Show the form for creating a new contact type.
     */
    public function create()
    {
        $this->authorizeAdmin();

        return view('contact-types.create');
    }

    /**
     * Store a newly created contact type in storage.
     */
    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'platform_name' => 'required|string|max:255',
            'icon_class' => 'nullable|string|max:255',
        ]);

        $contactType = ContactType::create($validated);

        return redirect()
            ->route('contact-types.index')
            ->with('success', 'Contact type created successfully!');
    }

    /**
     * Display the specified contact type.
     */
    public function show(ContactType $contactType)
    {
        $contactType->load('businessContacts.business');

        return view('contact-types.show', compact('contactType'));
    }

    /**
     * Show the form for editing the specified contact type.
     */
    public function edit(ContactType $contactType)
    {
        $this->authorizeAdmin();

        return view('contact-types.edit', compact('contactType'));
    }

    /**
     * Update the specified contact type in storage.
     */
    public function update(Request $request, ContactType $contactType)
    {
        $this->authorizeAdmin();

        $validated = $request->validate([
            'platform_name' => 'required|string|max:255',
            'icon_class' => 'nullable|string|max:255',
        ]);

        $contactType->update($validated);

        return redirect()
            ->route('contact-types.index')
            ->with('success', 'Contact type updated successfully!');
    }

    /**
     * Remove the specified contact type from storage.
     */
    public function destroy(ContactType $contactType)
    {
        $this->authorizeAdmin();

        // Check if any business contacts are using this type
        if ($contactType->businessContacts()->count() > 0) {
            return redirect()
                ->route('contact-types.index')
                ->with('error', 'Cannot delete contact type that is in use.');
        }

        $contactType->delete();

        return redirect()
            ->route('contact-types.index')
            ->with('success', 'Contact type deleted successfully!');
    }
}
