<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TrainingCategory;
use App\Models\TrainingHub;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    /**
     * Get active training categories.
     * Endpoint: GET /api/training-categories
     */
    public function getCategories()
    {
        $categories = TrainingCategory::where('status', 1)
            ->select('id', 'category_name', 'description')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $categories
        ], 200);
    }

    /**
     * Get training data based on type (pdf/video) and category filter.
     * Endpoint: GET /api/trainings
     */
    public function getTrainings(Request $request)
    {
        $request->validate([
            'type' => ['required', 'in:pdf,video'],
            'category_id' => ['nullable', 'string'] // Can be an integer string or 'all'
        ]);

        $type = $request->type;
        $categoryId = $request->input('category_id', 'all');

        $query = TrainingHub::with(['category:id,category_name'])
            ->where('status', 1)
            ->where('type', $type);

        if ($categoryId && $categoryId !== 'all') {
            $query->where('training_category_id', $categoryId);
        }

        $trainings = $query->latest()->get()->map(function ($item) {
            // Include absolute file URL
            $item->file_url = asset($item->file_path);
            return $item;
        });

        return response()->json([
            'success' => true,
            'type' => $type,
            'category_filter' => $categoryId,
            'count' => $trainings->count(),
            'data' => $trainings
        ], 200);
    }

    /**
     * Search pdf/video items.
     * Endpoint: GET /api/trainings/search
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => ['required', 'string', 'min:1'],
            'type' => ['nullable', 'in:pdf,video'],
        ]);

        $searchQuery = $request->q;
        $type = $request->type;

        $query = TrainingHub::with(['category:id,category_name'])
            ->where('status', 1)
            ->where(function ($subQuery) use ($searchQuery) {
                $subQuery->where('title', 'like', '%' . $searchQuery . '%')
                         ->orWhere('description', 'like', '%' . $searchQuery . '%');
            });

        if ($type) {
            $query->where('type', $type);
        }

        $results = $query->latest()->get()->map(function ($item) {
            $item->file_url = asset($item->file_path);
            return $item;
        });

        return response()->json([
            'success' => true,
            'query' => $searchQuery,
            'type_filter' => $type ?: 'all',
            'count' => $results->count(),
            'data' => $results
        ], 200);
    }

    /**
     * Get a specific training resource by ID (e.g. for getting a specific video).
     * Endpoint: GET /api/trainings/{id}
     */
    public function show($id)
    {
        $training = TrainingHub::with(['category:id,category_name'])
            ->where('status', 1)
            ->findOrFail($id);

        $training->file_url = asset($training->file_path);

        return response()->json([
            'success' => true,
            'data' => $training
        ], 200);
    }
}
