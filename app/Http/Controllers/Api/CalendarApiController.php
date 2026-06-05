<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CalendarYear;
use App\Models\CalendarContent;
use Illuminate\Http\Request;

class CalendarApiController extends Controller
{
    /**
     * Resolve the requested language code (en, mr, hi, gu).
     */
    private function resolveLanguage(Request $request)
    {
        // 1. Check logged in user language
        $user = $request->user('sanctum') ?: $request->user();
        if ($user && $user->language) {
            return $user->language;
        }

        // 2. Check X-Language header
        if ($request->hasHeader('X-Language')) {
            $lang = strtolower($request->header('X-Language'));
            if (in_array($lang, ['en', 'mr', 'hi', 'gu'])) {
                return $lang;
            }
        }

        // 3. Check language parameter in request
        if ($request->has('language')) {
            $lang = strtolower($request->input('language'));
            if (in_array($lang, ['en', 'mr', 'hi', 'gu'])) {
                return $lang;
            }
        }

        // Default to English
        return 'en';
    }

    /**
     * Fetch calendar PDF url for a passing year matching user's preferred language.
     * Endpoint: GET /api/calendar
     */
    public function getCalendar(Request $request)
    {
        $request->validate([
            'year' => ['required', 'integer', 'min:2000', 'max:2100']
        ]);

        $yearNumber = $request->year;
        $language = $this->resolveLanguage($request);

        // Find active calendar year
        $calendarYear = CalendarYear::where('year', $yearNumber)
            ->where('status', 1)
            ->first();

        if (!$calendarYear) {
            return response()->json([
                'success' => false,
                'message' => "Calendar year {$yearNumber} not found or inactive."
            ], 404);
        }

        // Find calendar content matching the language
        $content = CalendarContent::where('calendar_year_id', $calendarYear->id)
            ->where('language', $language)
            ->first();

        if (!$content) {
            return response()->json([
                'success' => false,
                'language' => $language,
                'message' => "Calendar PDF for year {$yearNumber} is not available in the preferred language."
            ], 404);
        }

        return response()->json([
            'success' => true,
            'year' => $calendarYear->year,
            'language' => $language,
            'pdf_url' => $content->pdf_url,
            'data' => [
                'id' => $content->id,
                'calendar_year_id' => $content->calendar_year_id,
                'language' => $content->language,
                'pdf_path' => $content->pdf_path,
                'pdf_url' => $content->pdf_url,
                'created_at' => $content->created_at,
                'updated_at' => $content->updated_at
            ]
        ], 200);
    }
}
