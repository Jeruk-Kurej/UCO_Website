<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Business;
use Illuminate\Http\Request;

class BusinessApprovalController extends Controller
{
    /**
     * Display a listing of businesses awaiting approval.
     */
    public function index(Request $request)
    {
        $status = $request->get('status', Business::STATUS_PENDING);
        
        $businesses = Business::with(['user', 'businessType'])
            ->where('status', $status)
            ->latest()
            ->paginate(15);

        return view('admin.business-approvals.index', compact('businesses', 'status'));
    }

    /**
     * Display the specified business for review.
     */
    public function show(Business $business)
    {
        $business->load([
            'user',
            'businessType',
            'products.productCategory',
            'products.photos',
            'services',
            'photos',
            'contacts.contactType'
        ]);

        return view('admin.business-approvals.show', compact('business'));
    }

    /**
     * Approve the specified business.
     */
    public function approve(Business $business)
    {
        $business->update([
            'status' => Business::STATUS_APPROVED,
            'rejection_reason' => null
        ]);

        return redirect()
            ->route('admin.business-approvals.index')
            ->with('success', "Business '{$business->name}' has been approved and is now public.");
    }

    /**
     * Reject the specified business with a reason.
     */
    public function reject(Request $request, Business $business)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $business->update([
            'status' => Business::STATUS_REJECTED,
            'rejection_reason' => $request->rejection_reason
        ]);

        return redirect()
            ->route('admin.business-approvals.index')
            ->with('success', "Business '{$business->name}' has been rejected. The owner will see your feedback.");
    }
}
