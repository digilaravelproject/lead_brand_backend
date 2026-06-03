<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subtool extends Model
{
    use HasFactory;

    protected $table = 'subtools';

    protected $fillable = [
        'tool_id',
        'title',
        'description',
        'status',
    ];

    /**
     * Get the tool this subtool belongs to.
     */
    public function tool()
    {
        return $this->belongsTo(Tool::class, 'tool_id');
    }

    /**
     * Get the media uploaded to this subtool.
     */
    public function media()
    {
        return $this->hasMany(ToolMedia::class, 'subtool_id');
    }
}
