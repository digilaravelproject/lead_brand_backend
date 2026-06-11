<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    /**
     * Display a listing of all leads.
     */
    public function index(Request $request)
    {
        $query = Lead::with('user');

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($uq) use ($search) {
                      $uq->where('name', 'like', "%{$search}%")
                         ->orWhere('email', 'like', "%{$search}%");
                  });
            });
        }

        $leads = $query->latest()->paginate(10);
        return view('admin.leads.index', compact('leads'));
    }

    /**
     * Return lead details in JSON format for modals.
     */
    public function show($id)
    {
        $lead = Lead::with('user')->findOrFail($id);
        return response()->json($lead);
    }

    /**
     * Update the specified lead in storage.
     */
    public function update(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);

        $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:20'],
            'status' => ['required', 'string', 'in:hot_lead,appointment,followup,done'],
            'is_active' => ['required', 'in:0,1'],
        ]);

        $lead->update([
            'full_name' => $request->full_name,
            'phone_number' => $request->phone_number,
            'status' => $request->status,
            'is_active' => $request->is_active,
        ]);

        return redirect()->route('admin.leads.index')
            ->with('success', 'Lead updated successfully.');
    }

    /**
     * Toggle active/inactive status via AJAX.
     */
    public function toggleStatus($id)
    {
        $lead = Lead::findOrFail($id);
        $lead->is_active = $lead->is_active == 1 ? 0 : 1;
        $lead->save();

        return response()->json([
            'success' => true,
            'is_active' => $lead->is_active,
            'message' => 'Lead status toggled successfully.'
        ]);
    }

    /**
     * Change lead status class category via AJAX.
     */
    public function changeStatus(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);

        $request->validate([
            'status' => ['required', 'string', 'in:hot_lead,appointment,followup,done']
        ]);

        $lead->status = $request->status;
        $lead->save();

        return response()->json([
            'success' => true,
            'status' => $lead->status,
            'message' => 'Lead status category changed successfully.'
        ]);
    }

    /**
     * Remove the specified lead from storage.
     */
    public function destroy($id)
    {
        $lead = Lead::findOrFail($id);
        $lead->delete();

        return redirect()->route('admin.leads.index')
            ->with('success', 'Lead deleted successfully.');
    }
}
