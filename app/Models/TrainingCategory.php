<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingCategory extends Model
{
    use HasFactory;

    protected $table = 'training_categories';

    protected $fillable = [
        'category_name',
        'description',
        'status',
    ];

    /**
     * Relationship: A category has many training hub resources.
     */
    public function trainings()
    {
        return $this->hasMany(TrainingHub::class, 'training_category_id');
    }
}
