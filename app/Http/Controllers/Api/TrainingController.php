<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TrainingCategory;
use App\Models\TrainingHub;
use Illuminate\Http\Request;

class TrainingController extends Controller
{
    /**
     * Resolve the requested language code (en, mr, hi, gu).
     */
    private function resolveLanguage(Request $request)
    {
        // 1. Check if user is authenticated via Bearer token (Access Token) or request resolver
        $user = $request->user('sanctum') ?: $request->user();
        if ($user && $user->language) {
            return $user->language;
        }
        
        // 2. Check X-Language header
        if ($request->hasHeader('X-Language')) {
            $lang = strtolower($request->header('X-Language'));
            if (in_array($lang, ['en', 'mr', 'hi', 'gu'])) {
                return $lang;
            }
        }
        
        // 3. Check language parameter in request
        if ($request->has('language')) {
            $lang = strtolower($request->input('language'));
            if (in_array($lang, ['en', 'mr', 'hi', 'gu'])) {
                return $lang;
            }
        }

        // Default to English
        return 'en';
    }

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
     * Get training data based on type (pdf/video) and category filter, filtered by language.
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
        $language = $this->resolveLanguage($request);

        $query = TrainingHub::with(['category:id,category_name'])
            ->where('status', 1)
            ->where('language', $language)
            ->where('type', $type);

        if ($categoryId && $categoryId !== 'all') {
            $query->where('training_category_id', $categoryId);
        }

        $trainings = $query->latest()->get()->map(function ($item) {
            // Include absolute file URL
            $item->file_url = asset($item->file_path);
            $item->thumbnail_url = $item->thumbnail ? asset($item->thumbnail) : null;
            return $item;
        });

        return response()->json([
            'success' => true,
            'type' => $type,
            'language' => $language,
            'category_filter' => $categoryId,
            'count' => $trainings->count(),
            'data' => $trainings
        ], 200);
    }

    /**
     * Search pdf/video items filtered by language.
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
        $language = $this->resolveLanguage($request);

        $query = TrainingHub::with(['category:id,category_name'])
            ->where('status', 1)
            ->where('language', $language)
            ->where(function ($subQuery) use ($searchQuery) {
                $subQuery->where('title', 'like', '%' . $searchQuery . '%')
                         ->orWhere('description', 'like', '%' . $searchQuery . '%');
            });

        if ($type) {
            $query->where('type', $type);
        }

        $results = $query->latest()->get()->map(function ($item) {
            $item->file_url = asset($item->file_path);
            $item->thumbnail_url = $item->thumbnail ? asset($item->thumbnail) : null;
            return $item;
        });

        return response()->json([
            'success' => true,
            'query' => $searchQuery,
            'type_filter' => $type ?: 'all',
            'language' => $language,
            'count' => $results->count(),
            'data' => $results
        ], 200);
    }

    /**
     * Get a specific training resource by ID, matching the requested language.
     * Endpoint: GET /api/trainings/{id}
     */
    public function show(Request $request, $id)
    {
        $language = $this->resolveLanguage($request);
        $training = TrainingHub::with(['category:id,category_name'])
            ->where('status', 1)
            ->where('language', $language)
            ->findOrFail($id);

        $training->file_url = asset($training->file_path);
        $training->thumbnail_url = $training->thumbnail ? asset($training->thumbnail) : null;

        return response()->json([
            'success' => true,
            'language' => $language,
            'data' => $training
        ], 200);
    }
}
