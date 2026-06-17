<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ToolMedia extends Model
{
    use HasFactory;

    protected $table = 'tool_media';

    protected $fillable = [
        'tool_id',
        'subtool_id',
        'title',
        'file_path',
        'media_type',
        'thumbnail',
        'info_image',
        'pdf',
        'description',
        'language',
        'status',
    ];

    /**
     * Get the tool this media belongs to.
     */
    public function tool()
    {
        return $this->belongsTo(Tool::class, 'tool_id');
    }

    /**
     * Get the subtool this media belongs to (if any).
     */
    public function subtool()
    {
        return $this->belongsTo(Subtool::class, 'subtool_id');
    }
}
