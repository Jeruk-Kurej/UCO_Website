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
        
        if (!$business->canBeManagedBy($user)) {
            abort(403, 'Unauthorized action.');
        }
    }

    /**
     * Display a listing of services for a business.
     * ✅ CHANGED: Redirect to business show page with services tab
     */
    public function index(Business $business)
    {
        return redirect()->route('businesses.show', $business)->with('activeTab', 'services');
    }

    /**
     * Show the form for creating a new service.
     */
    public function create(Business $business)
    {
        $this->authorizeBusinessAccess($business);

        // Prevent creating services if NOT in service mode
        if (!$business->isServiceMode()) {
            return redirect()
                ->route('businesses.show', $business)
                ->withErrors(['business_mode' => 'This business is not in Service mode.']);
        }

        return view('services.create', compact('business'));
    }

    /**
     * Store a newly created service in storage.
     */
    public function store(Request $request, Business $business)
    {
        $this->authorizeBusinessAccess($business);

        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'required|string',
                'price_type' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
            ]);

            $validated['business_id'] = $business->id;

            $service = Service::create($validated);

            // ✅ FIXED: Redirect to business show page with services tab active
            return redirect()
                ->route('businesses.show', $business)
                ->with('success', "Success! The service '{$service->name}' has been added to '{$business->name}'.")
                ->with('activeTab', 'services');
        } catch (\Illuminate\Validation\ValidationException $e) {
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'An error occurred while creating the service. Please try again.'])->withInput();
        }
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

        // ✅ FIXED: Redirect to business show page
        return redirect()
            ->route('businesses.show', $business)
            ->with('success', "Success! The details for '{$service->name}' have been updated.")
            ->with('activeTab', 'services');
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

        // ✅ FIXED: Redirect to business show page
        return redirect()
            ->route('businesses.show', $business)
            ->with('success', "Success! The service '{$service->name}' has been removed.")
            ->with('activeTab', 'services');
    }
}
