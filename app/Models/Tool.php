<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tool extends Model
{
    use HasFactory;

    protected $table = 'tools';

    protected $fillable = [
        'title',
        'description',
        'icon',
        'status',
    ];

    /**
     * Get the subtools under this tool.
     */
    public function subtools()
    {
        return $this->hasMany(Subtool::class, 'tool_id');
    }

    /**
     * Get the media directly uploaded to this tool (without subtools).
     */
    public function media()
    {
        return $this->hasMany(ToolMedia::class, 'tool_id')->whereNull('subtool_id');
    }

    /**
     * Get all media uploaded to this tool, including media inside subtools.
     */
    public function allMedia()
    {
        return $this->hasMany(ToolMedia::class, 'tool_id');
    }
}
