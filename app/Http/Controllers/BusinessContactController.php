<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\BusinessContact;
use App\Models\ContactType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BusinessContactController extends Controller
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
     * Check if user can manage business
     */
    private function authorizeBusinessAccess(Business $business): void
    {
        $user = $this->getAuthUser();
        
        if ($business->user_id !== $user->id && !$user->isAdmin()) {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Display a listing of contacts for a business.
     */
    public function index(Business $business)
    {
        $contacts = $business->contacts()->with('contactType')->get();

        return view('business-contacts.index', compact('business', 'contacts'));
    }

    /**
     * Show the form for creating a new contact.
     */
    public function create(Business $business)
    {
        $this->authorizeBusinessAccess($business);

        // Fetch all contact types (Master Data)
        $contactTypes = ContactType::all();

        return view('business-contacts.create', compact('business', 'contactTypes'));
    }

    /**
     * Store a newly created contact in storage.
     */
    public function store(Request $request, Business $business)
    {
        $this->authorizeBusinessAccess($business);

        $validated = $request->validate([
            'contact_type_id' => 'required|exists:contact_types,id',
            'contact_value' => 'required|string|max:255',
            'is_primary' => 'boolean',
        ]);

        $validated['business_id'] = $business->id;
        $validated['is_primary'] = $request->has('is_primary') ? true : false;

        // If this is marked as primary, unmark other contacts
        if ($validated['is_primary']) {
            $business->contacts()->update(['is_primary' => false]);
        }

        $contact = BusinessContact::create($validated);

        return redirect()
            ->route('businesses.contacts.index', $business)
            ->with('success', 'Contact created successfully!');
    }

    /**
     * Display the specified contact.
     */
    public function show(Business $business, BusinessContact $contact)
    {
        // Ensure contact belongs to this business
        if ($contact->business_id !== $business->id) {
            abort(404);
        }

        $contact->load('contactType');

        return view('business-contacts.show', compact('business', 'contact'));
    }

    /**
     * Show the form for editing the specified contact.
     */
    public function edit(Business $business, BusinessContact $contact)
    {
        $this->authorizeBusinessAccess($business);

        // Ensure contact belongs to this business
        if ($contact->business_id !== $business->id) {
            abort(404);
        }

        // Fetch all contact types (Master Data)
        $contactTypes = ContactType::all();

        return view('business-contacts.edit', compact('business', 'contact', 'contactTypes'));
    }

    /**
     * Update the specified contact in storage.
     */
    public function update(Request $request, Business $business, BusinessContact $contact)
    {
        $this->authorizeBusinessAccess($business);

        // Ensure contact belongs to this business
        if ($contact->business_id !== $business->id) {
            abort(404);
        }

        $validated = $request->validate([
            'contact_type_id' => 'required|exists:contact_types,id',
            'contact_value' => 'required|string|max:255',
            'is_primary' => 'boolean',
        ]);

        $validated['is_primary'] = $request->has('is_primary') ? true : false;

        // If this is marked as primary, unmark other contacts
        if ($validated['is_primary']) {
            $business->contacts()->where('id', '!=', $contact->id)->update(['is_primary' => false]);
        }

        $contact->update($validated);

        return redirect()
            ->route('businesses.contacts.index', $business)
            ->with('success', 'Contact updated successfully!');
    }

    /**
     * Remove the specified contact from storage.
     */
    public function destroy(Business $business, BusinessContact $contact)
    {
        $this->authorizeBusinessAccess($business);

        // Ensure contact belongs to this business
        if ($contact->business_id !== $business->id) {
            abort(404);
        }

        $contact->delete();

        return redirect()
            ->route('businesses.contacts.index', $business)
            ->with('success', 'Contact deleted successfully!');
    }
}
