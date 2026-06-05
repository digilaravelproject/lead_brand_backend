<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CalendarYear extends Model
{
    protected $fillable = ['year', 'status'];

    /**
     * Get the calendar contents (PDFs) associated with the year.
     */
    public function contents()
    {
        return $this->hasMany(CalendarContent::class, 'calendar_year_id');
    }
}
