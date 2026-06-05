<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingHub extends Model
{
    use HasFactory;

    protected $table = 'training_hubs';

    protected $fillable = [
        'training_category_id',
        'type',
        'title',
        'description',
        'file_path',
        'thumbnail',
        'language',
        'status',
    ];

    /**
     * Appended attributes automatically returned in JSON responses.
     */
    protected $appends = ['thumbnail_url'];

    /**
     * Relationship: A training hub resource belongs to a specific category.
     */
    public function category()
    {
        return $this->belongsTo(TrainingCategory::class, 'training_category_id');
    }

    /**
     * Get the full URL to the thumbnail file.
     */
    public function getThumbnailUrlAttribute()
    {
        return $this->thumbnail ? asset($this->thumbnail) : null;
    }
}
