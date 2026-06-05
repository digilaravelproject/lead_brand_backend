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
        'language',
        'status',
    ];

    /**
     * Relationship: A training hub resource belongs to a specific category.
     */
    public function category()
    {
        return $this->belongsTo(TrainingCategory::class, 'training_category_id');
    }
}
