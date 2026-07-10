<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CalendarYear;
use App\Models\CalendarContent;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class CalendarController extends Controller
{
    /**
     * Display a listing of calendar years with their translated content.
     */
    public function index()
    {
        $years = CalendarYear::with('contents')->orderBy('year', 'desc')->paginate(10);
        return view('admin.calendar_contents.index', compact('years'));
    }

    /**
     * Store a newly created calendar year and its multi-language PDF uploads.
     */
    public function store(Request $request)
    {
        $request->validate([
            'year' => ['required', 'integer', 'min:2000', 'max:2100', 'unique:calendar_years,year'],
            'status' => ['required', 'in:0,1'],
            'pdf_en' => ['nullable', 'file', 'mimes:pdf', 'max:51200'], // Max 50MB
            'pdf_hi' => ['nullable', 'file', 'mimes:pdf', 'max:51200'],
            'pdf_gu' => ['nullable', 'file', 'mimes:pdf', 'max:51200'],
            'pdf_mr' => ['nullable', 'file', 'mimes:pdf', 'max:51200'],
            'pdf_bn' => ['nullable', 'file', 'mimes:pdf', 'max:51200'],
            'pdf_te' => ['nullable', 'file', 'mimes:pdf', 'max:51200'],
            'pdf_ta' => ['nullable', 'file', 'mimes:pdf', 'max:51200'],
            'pdf_kn' => ['nullable', 'file', 'mimes:pdf', 'max:51200'],
            'pdf_pa' => ['nullable', 'file', 'mimes:pdf', 'max:51200'],
        ]);

        $year = CalendarYear::create([
            'year' => $request->year,
            'status' => $request->status,
        ]);

        $languages = ['en', 'hi', 'gu', 'mr', 'bn', 'te', 'ta', 'kn', 'pa'];
        $uploadedCount = 0;

        foreach ($languages as $lang) {
            $inputName = 'pdf_' . $lang;
            if ($request->hasFile($inputName)) {
                $file = $request->file($inputName);
                
                // Construct file name and move it
                $filename = time() . '_' . $lang . '_' . preg_replace('/[^A-Za-z0-9\-.]/', '', $file->getClientOriginalName());
                $destinationPath = public_path('uploads/calendars');
                if (!file_exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                $file->move($destinationPath, $filename);
                $filePath = 'uploads/calendars/' . $filename;

                CalendarContent::create([
                    'calendar_year_id' => $year->id,
                    'language' => $lang,
                    'pdf_path' => $filePath,
                ]);

                $uploadedCount++;
            }
        }

        // Add Notification
        try {
            Notification::create([
                'title' => "📅 New Calendar Created",
                'message' => "Calendar Year {$year->year} has been created with {$uploadedCount} translations uploaded.",
                'type' => 'calendar',
                'is_read' => false,
            ]);
        } catch (\Exception $e) {
            logger()->error('Notification creation failed for Calendar Store: ' . $e->getMessage());
        }

        return redirect()->route('admin.calendar-contents.index')
            ->with('success', 'Calendar Year and PDF files uploaded successfully.');
    }

    /**
     * Return details of the year and its contents as JSON.
     */
    public function show($id)
    {
        $year = CalendarYear::with('contents')->findOrFail($id);
        return response()->json($year);
    }

    /**
     * Update the year and replace/add language PDFs.
     */
    public function update(Request $request, $id)
    {
        $year = CalendarYear::findOrFail($id);

        $request->validate([
            'year' => ['required', 'integer', 'min:2000', 'max:2100', 'unique:calendar_years,year,' . $id],
            'status' => ['required', 'in:0,1'],
            'pdf_en' => ['nullable', 'file', 'mimes:pdf', 'max:51200'],
            'pdf_hi' => ['nullable', 'file', 'mimes:pdf', 'max:51200'],
            'pdf_gu' => ['nullable', 'file', 'mimes:pdf', 'max:51200'],
            'pdf_mr' => ['nullable', 'file', 'mimes:pdf', 'max:51200'],
            'pdf_bn' => ['nullable', 'file', 'mimes:pdf', 'max:51200'],
            'pdf_te' => ['nullable', 'file', 'mimes:pdf', 'max:51200'],
            'pdf_ta' => ['nullable', 'file', 'mimes:pdf', 'max:51200'],
            'pdf_kn' => ['nullable', 'file', 'mimes:pdf', 'max:51200'],
            'pdf_pa' => ['nullable', 'file', 'mimes:pdf', 'max:51200'],
        ]);

        $year->update([
            'year' => $request->year,
            'status' => $request->status,
        ]);

        $languages = ['en', 'hi', 'gu', 'mr', 'bn', 'te', 'ta', 'kn', 'pa'];

        foreach ($languages as $lang) {
            $inputName = 'pdf_' . $lang;
            if ($request->hasFile($inputName)) {
                $file = $request->file($inputName);

                // Find existing content record
                $content = CalendarContent::where('calendar_year_id', $year->id)
                    ->where('language', $lang)
                    ->first();

                // Delete old physical file if exists
                if ($content && $content->pdf_path && file_exists(public_path($content->pdf_path))) {
                    @unlink(public_path($content->pdf_path));
                }

                // Construct file name and move it
                $filename = time() . '_replace_' . $lang . '_' . preg_replace('/[^A-Za-z0-9\-.]/', '', $file->getClientOriginalName());
                $destinationPath = public_path('uploads/calendars');
                if (!file_exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0755, true);
                }
                $file->move($destinationPath, $filename);
                $filePath = 'uploads/calendars/' . $filename;

                if ($content) {
                    $content->update([
                        'pdf_path' => $filePath,
                    ]);
                } else {
                    CalendarContent::create([
                        'calendar_year_id' => $year->id,
                        'language' => $lang,
                        'pdf_path' => $filePath,
                    ]);
                }
            }
        }

        return redirect()->route('admin.calendar-contents.index')
            ->with('success', 'Calendar Year and PDF files updated successfully.');
    }

    /**
     * Toggle the active status of the year.
     */
    public function toggleStatus($id)
    {
        $year = CalendarYear::findOrFail($id);
        $year->status = $year->status == 1 ? 0 : 1;
        $year->save();

        return response()->json([
            'success' => true,
            'status' => $year->status,
            'message' => "Calendar Year status updated successfully."
        ]);
    }

    /**
     * Remove the calendar year and its physical PDF uploads.
     */
    public function destroy($id)
    {
        $year = CalendarYear::with('contents')->findOrFail($id);

        // Delete all associated files physically
        foreach ($year->contents as $content) {
            if ($content->pdf_path && file_exists(public_path($content->pdf_path))) {
                @unlink(public_path($content->pdf_path));
            }
        }

        $year->delete();

        return redirect()->route('admin.calendar-contents.index')
            ->with('success', 'Calendar Year and all related translation files deleted successfully.');
    }
}
