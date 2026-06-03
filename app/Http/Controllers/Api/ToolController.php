<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Tool;
use App\Models\ToolMedia;
use Illuminate\Http\Request;

class ToolController extends Controller
{
    /**
     * Get all active tools, their active subtools, and associated media.
     */
    public function index()
    {
        try {
            $tools = Tool::where('status', 1)
                ->with([
                    'subtools' => function ($query) {
                        $query->where('status', 1)->orderBy('created_at', 'asc');
                    },
                    'subtools.media' => function ($query) {
                        $query->where('status', 1)->orderBy('created_at', 'desc');
                    },
                    'media' => function ($query) {
                        $query->where('status', 1)->orderBy('created_at', 'desc');
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
     * Get specific tool data by its ID (including its subtools and media assets).
     */
    public function show($id)
    {
        try {
            $tool = Tool::where('status', 1)
                ->with([
                    'subtools' => function ($query) {
                        $query->where('status', 1)->orderBy('created_at', 'asc');
                    },
                    'subtools.media' => function ($query) {
                        $query->where('status', 1)->orderBy('created_at', 'desc');
                    },
                    'media' => function ($query) {
                        $query->where('status', 1)->orderBy('created_at', 'desc');
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
     * Get details of a specific tool media asset (photo/video) by its ID.
     */
    public function showMedia($id)
    {
        try {
            $media = ToolMedia::where('status', 1)->find($id);

            if (!$media) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Media asset not found or inactive.'
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
