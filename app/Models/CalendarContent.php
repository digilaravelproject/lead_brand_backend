<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CalendarContent extends Model
{
    protected $fillable = ['calendar_year_id', 'language', 'pdf_path'];

    /**
     * Appended attributes automatically returned in JSON responses.
     */
    protected $appends = ['pdf_url'];

    /**
     * Get the year associated with this calendar content.
     */
    public function year()
    {
        return $this->belongsTo(CalendarYear::class, 'calendar_year_id');
    }

    /**
     * Get the full URL to the PDF file.
     */
    public function getPdfUrlAttribute()
    {
        return $this->pdf_path ? asset($this->pdf_path) : null;
    }
}
