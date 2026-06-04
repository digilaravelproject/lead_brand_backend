<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'heading',
        'services',
        'image',
        'status',
    ];

    /**
     * Get the services list as an array.
     */
    public function getServicesArrayAttribute()
    {
        if (empty($this->services)) {
            return [];
        }
        return array_map('trim', explode(',', $this->services));
    }
}
