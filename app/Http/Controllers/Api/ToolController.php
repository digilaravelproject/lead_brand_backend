<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use App\Models\ToolMedia;
use Illuminate\Http\Request;

class ToolController extends Controller
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
     * Get all active tools, their active subtools, and associated media matching the user's language.
     */
    public function index(Request $request)
    {
        try {
            $language = $this->resolveLanguage($request);

            $tools = Tool::where('status', 1)
                ->with([
                    'subtools' => function ($query) {
                        $query->where('status', 1)->orderBy('created_at', 'asc');
                    },
                    'subtools.media' => function ($query) use ($language) {
                        $query->where('status', 1)
                              ->where('language', $language)
                              ->orderBy('created_at', 'desc');
                    },
                    'media' => function ($query) use ($language) {
                        $query->where('status', 1)
                              ->where('language', $language)
                              ->orderBy('created_at', 'desc');
                    }
                ])
                ->orderBy('created_at', 'asc')
                ->get();

            // Format outputs and resolve full URLs
            $formattedTools = $tools->map(function ($tool) {
                // Map root tool level media
                $formattedMedia = $tool->media->map(function ($media) {
                    return [
                        'id' => $media->id,
                        'title' => $media->title,
                        'file_path' => $media->file_path,
                        'full_url' => asset($media->file_path),
                        'media_type' => $media->media_type,
                        'thumbnail' => $media->thumbnail,
                        'thumbnail_url' => $media->thumbnail ? asset($media->thumbnail) : null,
                        'info_image' => $media->info_image,
                        'info_image_url' => $media->info_image ? asset($media->info_image) : null,
                        'language' => $media->language,
                        'pdf' => $media->pdf,
                        'pdf_url' => $media->pdf ? asset($media->pdf) : null,
                        'description' => $media->description,
                        'created_at' => $media->created_at->toIso8601String(),
                    ];
                });

                // Map subtools
                $formattedSubtools = $tool->subtools->map(function ($subtool) {
                    $subtoolMedia = $subtool->media->map(function ($media) {
                        return [
                            'id' => $media->id,
                            'title' => $media->title,
                            'file_path' => $media->file_path,
                            'full_url' => asset($media->file_path),
                            'media_type' => $media->media_type,
                            'thumbnail' => $media->thumbnail,
                            'thumbnail_url' => $media->thumbnail ? asset($media->thumbnail) : null,
                            'language' => $media->language,
                            'pdf' => $media->pdf,
                            'pdf_url' => $media->pdf ? asset($media->pdf) : null,
                            'description' => $media->description,
                            'created_at' => $media->created_at->toIso8601String(),
                        ];
                    });

                    return [
                        'id' => $subtool->id,
                        'tool_id' => $subtool->tool_id,
                        'title' => $subtool->title,
                        'description' => $subtool->description,
                        'media' => $subtoolMedia,
                        'created_at' => $subtool->created_at->toIso8601String(),
                    ];
                });

                return [
                    'id' => $tool->id,
                    'title' => $tool->title,
                    'description' => $tool->description,
                    'icon' => $tool->icon,
                    'media' => $formattedMedia,
                    'subtools' => $formattedSubtools,
                    'created_at' => $tool->created_at->toIso8601String(),
                ];
            });

            return response()->json([
                'status' => 'success',
                'language' => $language,
                'tools' => $formattedTools
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch tools: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get specific tool data by its ID (including its subtools and media assets filtered by language).
     */
    public function show(Request $request, $id)
    {
        try {
            $language = $this->resolveLanguage($request);

            $tool = Tool::where('status', 1)
                ->with([
                    'subtools' => function ($query) {
                        $query->where('status', 1)->orderBy('created_at', 'asc');
                    },
                    'subtools.media' => function ($query) use ($language) {
                        $query->where('status', 1)
                              ->where('language', $language)
                              ->orderBy('created_at', 'desc');
                    },
                    'media' => function ($query) use ($language) {
                        $query->where('status', 1)
                              ->where('language', $language)
                              ->orderBy('created_at', 'desc');
                    }
                ])
                ->find($id);

            if (!$tool) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Business Tool not found or inactive.'
                ], 404);
            }

            // Formatting
            $formattedMedia = $tool->media->map(function ($media) {
                return [
                    'id' => $media->id,
                    'title' => $media->title,
                    'file_path' => $media->file_path,
                    'full_url' => asset($media->file_path),
                    'media_type' => $media->media_type,
                    'thumbnail' => $media->thumbnail,
                    'thumbnail_url' => $media->thumbnail ? asset($media->thumbnail) : null,
                    'info_image' => $media->info_image,
                    'info_image_url' => $media->info_image ? asset($media->info_image) : null,
                    'language' => $media->language,
                    'pdf' => $media->pdf,
                    'pdf_url' => $media->pdf ? asset($media->pdf) : null,
                    'description' => $media->description,
                    'created_at' => $media->created_at->toIso8601String(),
                ];
            });

            $formattedSubtools = $tool->subtools->map(function ($subtool) {
                $subtoolMedia = $subtool->media->map(function ($media) {
                    return [
                        'id' => $media->id,
                        'title' => $media->title,
                        'file_path' => $media->file_path,
                        'full_url' => asset($media->file_path),
                        'media_type' => $media->media_type,
                        'thumbnail' => $media->thumbnail,
                        'thumbnail_url' => $media->thumbnail ? asset($media->thumbnail) : null,
                        'info_image' => $media->info_image,
                        'info_image_url' => $media->info_image ? asset($media->info_image) : null,
                        'language' => $media->language,
                        'pdf' => $media->pdf,
                        'pdf_url' => $media->pdf ? asset($media->pdf) : null,
                        'description' => $media->description,
                        'created_at' => $media->created_at->toIso8601String(),
                    ];
                });

                return [
                    'id' => $subtool->id,
                    'tool_id' => $subtool->tool_id,
                    'title' => $subtool->title,
                    'description' => $subtool->description,
                    'media' => $subtoolMedia,
                    'created_at' => $subtool->created_at->toIso8601String(),
                ];
            });

            return response()->json([
                'status' => 'success',
                'language' => $language,
                'tool' => [
                    'id' => $tool->id,
                    'title' => $tool->title,
                    'description' => $tool->description,
                    'icon' => $tool->icon,
                    'media' => $formattedMedia,
                    'subtools' => $formattedSubtools,
                    'created_at' => $tool->created_at->toIso8601String(),
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve tool details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get details of a specific tool media asset by its ID if it matches the language.
     */
    public function showMedia(Request $request, $id)
    {
        try {
            $language = $this->resolveLanguage($request);
            $media = ToolMedia::where('status', 1)
                ->where('language', $language)
                ->find($id);

            if (!$media) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Media asset not found or inactive in the selected language.'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'media' => [
                    'id' => $media->id,
                    'tool_id' => $media->tool_id,
                    'subtool_id' => $media->subtool_id,
                    'title' => $media->title,
                    'file_path' => $media->file_path,
                    'full_url' => asset($media->file_path),
                    'media_type' => $media->media_type,
                    'thumbnail' => $media->thumbnail,
                    'thumbnail_url' => $media->thumbnail ? asset($media->thumbnail) : null,
                    'info_image' => $media->info_image,
                    'info_image_url' => $media->info_image ? asset($media->info_image) : null,
                    'language' => $media->language,
                    'pdf' => $media->pdf,
                    'pdf_url' => $media->pdf ? asset($media->pdf) : null,
                    'description' => $media->description,
                    'created_at' => $media->created_at->toIso8601String(),
                ]
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch media details: ' . $e->getMessage()
            ], 500);
        }
    }
}
