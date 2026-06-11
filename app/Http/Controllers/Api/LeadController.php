<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class LeadController extends Controller
{
    /**
     * Helper to normalize incoming status strings.
     */
    private function normalizeStatus($status)
    {
        if (empty($status)) {
            return 'hot_lead';
        }

        $status = strtolower(trim($status));
        
        switch ($status) {
            case 'hot lead':
            case 'hot_lead':
            case 'hot':
                return 'hot_lead';
            case 'appointment':
            case 'appointments':
                return 'appointment';
            case 'followup':
            case 'follow up':
            case 'follow_up':
                return 'followup';
            case 'done':
            case 'completed':
                return 'done';
            default:
                return 'hot_lead';
        }
    }

    /**
     * Create a new lead.
     * POST /api/leads
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:20'],
            'status' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = $request->user();
        $status = $this->normalizeStatus($request->input('status'));

        $lead = Lead::create([
            'user_id' => $user->id,
            'full_name' => $request->full_name,
            'phone_number' => $request->phone_number,
            'status' => $status,
            'is_active' => true
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lead created successfully.',
            'data' => $lead
        ], 201);
    }

    /**
     * Listing with counts.
     * GET /api/leads
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        // Base query for counts: all active leads of this user
        $baseQuery = Lead::where('user_id', $user->id)->where('is_active', true);

        // Fetch counts for header
        $totalCount = (clone $baseQuery)->count();
        $hotCount = (clone $baseQuery)->where('status', 'hot_lead')->count();
        $appointmentCount = (clone $baseQuery)->where('status', 'appointment')->count();
        $followupCount = (clone $baseQuery)->where('status', 'followup')->count();
        $doneCount = (clone $baseQuery)->where('status', 'done')->count();

        // Listing Query
        $listingQuery = Lead::where('user_id', $user->id)->where('is_active', true);

        // Filter by status if specified and not 'all'
        if ($request->has('status') && strtolower(trim($request->status)) !== 'all') {
            $status = $this->normalizeStatus($request->status);
            $listingQuery->where('status', $status);
        }

        // Search lead by name or phone
        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $listingQuery->where(function ($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('phone_number', 'like', "%{$search}%");
            });
        }

        $leads = $listingQuery->latest()->get();

        return response()->json([
            'success' => true,
            'counts' => [
                'total' => $totalCount,
                'hot_lead' => $hotCount,
                'appointment' => $appointmentCount,
                'followup' => $followupCount,
                'done' => $doneCount
            ],
            'data' => $leads
        ], 200);
    }

    /**
     * Get details of a single lead by ID.
     * GET /api/leads/{id}
     */
    public function show(Request $request, $id)
    {
        $user = $request->user();
        $lead = Lead::where('user_id', $user->id)->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $lead
        ], 200);
    }

    /**
     * Change lead status.
     * POST /api/leads/{id}/change-status
     */
    public function changeStatus(Request $request, $id)
    {
        $user = $request->user();
        $lead = Lead::where('user_id', $user->id)->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'status' => ['required', 'string']
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $status = $this->normalizeStatus($request->status);
        $lead->status = $status;
        $lead->save();

        return response()->json([
            'success' => true,
            'message' => 'Lead status updated successfully.',
            'data' => $lead
        ], 200);
    }

    /**
     * Edit lead details.
     * PUT /api/leads/{id}
     */
    public function update(Request $request, $id)
    {
        $user = $request->user();
        $lead = Lead::where('user_id', $user->id)->findOrFail($id);

        $validator = Validator::make($request->all(), [
            'full_name' => ['required', 'string', 'max:255'],
            'phone_number' => ['required', 'string', 'max:20'],
            'status' => ['nullable', 'string'],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $status = $request->has('status') ? $this->normalizeStatus($request->status) : $lead->status;

        $lead->update([
            'full_name' => $request->full_name,
            'phone_number' => $request->phone_number,
            'status' => $status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lead updated successfully.',
            'data' => $lead
        ], 200);
    }

    /**
     * Delete lead.
     * DELETE /api/leads/{id}
     */
    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $lead = Lead::where('user_id', $user->id)->findOrFail($id);
        $lead->delete();

        return response()->json([
            'success' => true,
            'message' => 'Lead deleted successfully.'
        ], 200);
    }

    /**
     * Get lead counts and weekly growth percentages.
     * GET /api/leads/stats
     */
    public function getStats(Request $request)
    {
        $user = $request->user();

        // 1. Hot Leads Stats
        $hotLeadsQuery = Lead::where('user_id', $user->id)
            ->where('is_active', true)
            ->where('status', 'hot_lead');
        
        $hotLeadsCount = (clone $hotLeadsQuery)->count();
        $hotLeadsPercentage = $this->calculateWeeklyPercentage(clone $hotLeadsQuery);

        // 2. Appointments Today Stats
        $appointmentsQuery = Lead::where('user_id', $user->id)
            ->where('is_active', true)
            ->where('status', 'appointment');
        
        $appointmentsTodayCount = (clone $appointmentsQuery)
            ->whereDate('created_at', Carbon::today())
            ->count();
        
        $appointmentsPercentage = $this->calculateWeeklyPercentage(clone $appointmentsQuery);

        // 3. Follow-ups Pending Stats
        $followupsQuery = Lead::where('user_id', $user->id)
            ->where('is_active', true)
            ->where('status', 'followup');
        
        $followupsCount = (clone $followupsQuery)->count();
        $followupsPercentage = $this->calculateWeeklyPercentage(clone $followupsQuery);

        // 4. Done Leads Stats
        $doneQuery = Lead::where('user_id', $user->id)
            ->where('is_active', true)
            ->where('status', 'done');
        
        $doneCount = (clone $doneQuery)->count();
        $donePercentage = $this->calculateWeeklyPercentage(clone $doneQuery);

        return response()->json([
            'success' => true,
            'data' => [
                'hot_leads' => [
                    'count' => $hotLeadsCount,
                    'percentage' => $hotLeadsPercentage,
                    'trend' => $hotLeadsPercentage >= 0 ? 'up' : 'down'
                ],
                'appointments_today' => [
                    'count' => $appointmentsTodayCount,
                    'percentage' => $appointmentsPercentage,
                    'trend' => $appointmentsPercentage >= 0 ? 'up' : 'down'
                ],
                'followups_pending' => [
                    'count' => $followupsCount,
                    'percentage' => $followupsPercentage,
                    'trend' => $followupsPercentage >= 0 ? 'up' : 'down'
                ],
                'done_leads' => [
                    'count' => $doneCount,
                    'percentage' => $donePercentage,
                    'trend' => $donePercentage >= 0 ? 'up' : 'down'
                ]
            ]
        ], 200);
    }

    /**
     * Calculate percentage growth this week vs last week.
     */
    private function calculateWeeklyPercentage($queryBuilder)
    {
        $now = Carbon::now();
        
        $startOfThisWeek = $now->copy()->startOfWeek();
        $endOfThisWeek = $now->copy()->endOfWeek();
        
        $startOfLastWeek = $now->copy()->subWeek()->startOfWeek();
        $endOfLastWeek = $now->copy()->subWeek()->endOfWeek();

        $thisWeekCount = (clone $queryBuilder)
            ->whereBetween('created_at', [$startOfThisWeek, $endOfThisWeek])
            ->count();

        $lastWeekCount = (clone $queryBuilder)
            ->whereBetween('created_at', [$startOfLastWeek, $endOfLastWeek])
            ->count();

        if ($lastWeekCount > 0) {
            $percentage = (($thisWeekCount - $lastWeekCount) / $lastWeekCount) * 100;
        } else {
            $percentage = $thisWeekCount > 0 ? 100.0 : 0.0;
        }

        return round($percentage, 1);
    }
}
