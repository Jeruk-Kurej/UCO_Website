<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Service;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ServiceController extends Controller
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
     * Display a listing of services for a business.
     */
    public function index(Business $business)
    {
        $services = $business->services()->latest()->paginate(10);

        return view('services.index', compact('business', 'services'));
    }

    /**
     * Show the form for creating a new service.
     */
    public function create(Business $business)
    {
        $this->authorizeBusinessAccess($business);

        return view('services.create', compact('business'));
    }

    /**
     * Store a newly created service in storage.
     */
    public function store(Request $request, Business $business)
    {
        $this->authorizeBusinessAccess($business);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price_type' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $validated['business_id'] = $business->id;

        $service = Service::create($validated);

        return redirect()
            ->route('businesses.services.index', $business)
            ->with('success', 'Service created successfully!');
    }

    /**
     * Display the specified service.
     */
    public function show(Business $business, Service $service)
    {
        // Ensure service belongs to this business
        if ($service->business_id !== $business->id) {
            abort(404);
        }

        return view('services.show', compact('business', 'service'));
    }

    /**
     * Show the form for editing the specified service.
     */
    public function edit(Business $business, Service $service)
    {
        $this->authorizeBusinessAccess($business);

        // Ensure service belongs to this business
        if ($service->business_id !== $business->id) {
            abort(404);
        }

        return view('services.edit', compact('business', 'service'));
    }

    /**
     * Update the specified service in storage.
     */
    public function update(Request $request, Business $business, Service $service)
    {
        $this->authorizeBusinessAccess($business);

        // Ensure service belongs to this business
        if ($service->business_id !== $business->id) {
            abort(404);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price_type' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $service->update($validated);

        return redirect()
            ->route('businesses.services.index', $business)
            ->with('success', 'Service updated successfully!');
    }

    /**
     * Remove the specified service from storage.
     */
    public function destroy(Business $business, Service $service)
    {
        $this->authorizeBusinessAccess($business);

        // Ensure service belongs to this business
        if ($service->business_id !== $business->id) {
            abort(404);
        }

        $service->delete();

        return redirect()
            ->route('businesses.services.index', $business)
            ->with('success', 'Service deleted successfully!');
    }
}
