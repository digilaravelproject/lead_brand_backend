<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Banner;
use Carbon\Carbon;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Exception;

class CalendarController extends Controller
{
    /**
     * Generate Month-wise Calendar PDF with custom User details and Banner.
     */
    public function generate(Request $request)
    {
        try {
            $user = $request->user();
            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthenticated'
                ], 401);
            }

            // 1. Parse Month Range
            $startMonth = null;
            $endMonth = null;
            $year = $request->input('year', date('Y'));

            if ($request->has('range')) {
                $parts = explode('-', strtolower($request->input('range')));
                if (count($parts) == 2) {
                    $startMonth = $this->parseMonthName($parts[0]);
                    $endMonth = $this->parseMonthName($parts[1]);
                }
            }

            if (!$startMonth && $request->has('from_month')) {
                $startMonth = $this->parseMonthName($request->input('from_month'));
            }
            if (!$endMonth && $request->has('to_month')) {
                $endMonth = $this->parseMonthName($request->input('to_month'));
            }

            // Default fallback
            if (!$startMonth) {
                $startMonth = intval(date('n'));
            }
            if (!$endMonth) {
                $endMonth = $startMonth;
            }

            // Normalize range direction
            if ($startMonth > $endMonth) {
                $temp = $startMonth;
                $startMonth = $endMonth;
                $endMonth = $temp;
            }

            // 2. Fetch Banner
            $banner = null;
            if ($request->has('banner_id')) {
                $banner = Banner::where('status', 1)->find($request->input('banner_id'));
            }
            if (!$banner) {
                $banner = Banner::where('status', 1)->first();
            }

            // Fallback default banner details if none found in db
            $bannerHeading = $banner ? $banner->heading : 'Doctors Save Lives, We Save Lifestyle';
            $bannerServices = $banner ? $banner->services : 'Premium Payment, Maturity Claim, Policy Revival, Policy Loan, Change in Address, Change in Nomination, Policy Branch Transfer, Change in Premium Mode';
            $servicesArray = array_map('trim', explode(',', $bannerServices));
            
            // 3. Resolve user assets local paths for Dompdf (local path avoids localhost network/permission issues in PDF rendering)
            $userPhotoLocal = $this->getLocalPath($user->profile_photo);
            $userLogoLocal = $this->getLocalPath($user->logo);

            // 4. Generate Calendar grid data
            $calendarData = [];
            for ($m = $startMonth; $m <= $endMonth; $m++) {
                $firstDay = Carbon::create($year, $m, 1);
                $daysInMonth = $firstDay->daysInMonth;
                $startDayOfWeek = $firstDay->dayOfWeek; // 0 (Sunday) to 6 (Saturday)

                $monthWeeks = [];
                $currentWeek = array_fill(0, 7, null);

                // Fill leading empty days
                for ($i = 0; $i < $startDayOfWeek; $i++) {
                    $currentWeek[$i] = '';
                }

                $dayIndex = $startDayOfWeek;
                for ($day = 1; $day <= $daysInMonth; $day++) {
                    if ($dayIndex == 7) {
                        $monthWeeks[] = $currentWeek;
                        $currentWeek = array_fill(0, 7, null);
                        $dayIndex = 0;
                    }
                    $currentWeek[$dayIndex] = $day;
                    $dayIndex++;
                }

                // Fill trailing empty days
                if ($dayIndex > 0) {
                    for ($i = $dayIndex; $i < 7; $i++) {
                        $currentWeek[$i] = '';
                    }
                    $monthWeeks[] = $currentWeek;
                }

                $calendarData[] = [
                    'month_name' => $firstDay->format('F'),
                    'year' => $year,
                    'weeks' => $monthWeeks
                ];
            }

            // 5. Render HTML
            $html = view('pdf.calendar', [
                'calendarData' => $calendarData,
                'user' => $user,
                'userPhotoLocal' => $userPhotoLocal,
                'userLogoLocal' => $userLogoLocal,
                'bannerHeading' => $bannerHeading,
                'servicesArray' => $servicesArray,
            ])->render();

            // 6. Setup Dompdf & Render
            $options = new Options();
            $options->set('isHtml5ParserEnabled', true);
            $options->set('isRemoteEnabled', true);
            
            $dompdf = new Dompdf($options);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'portrait');
            $dompdf->render();

            // 7. Save PDF to directory
            $pdfDirectory = public_path('calendars');
            if (!file_exists($pdfDirectory)) {
                mkdir($pdfDirectory, 0755, true);
            }

            $filename = 'calendar_user_' . $user->id . '_' . time() . '.pdf';
            $filepath = $pdfDirectory . DIRECTORY_SEPARATOR . $filename;
            
            file_put_contents($filepath, $dompdf->output());

            $downloadLink = url('calendars/' . $filename);

            return response()->json([
                'status' => true,
                'message' => 'Calendar generated successfully',
                'data' => [
                    'pdf_link' => $downloadLink,
                    'filename' => $filename,
                    'range' => $startMonth . ' to ' . $endMonth,
                    'year' => $year
                ]
            ], 200);

        } catch (Exception $e) {
            Log::error('generateCalendar error: ' . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to generate calendar PDF',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Map month string/number to 1-12.
     */
    private function parseMonthName($str)
    {
        $str = trim($str);
        if (is_numeric($str)) {
            return max(1, min(12, intval($str)));
        }
        $months = [
            'jan' => 1, 'january' => 1,
            'feb' => 2, 'february' => 2,
            'mar' => 3, 'march' => 3,
            'apr' => 4, 'april' => 4,
            'may' => 5,
            'jun' => 6, 'june' => 6,
            'jul' => 7, 'july' => 7,
            'aug' => 8, 'august' => 8,
            'sep' => 9, 'september' => 9,
            'oct' => 10, 'october' => 10,
            'nov' => 11, 'november' => 11,
            'dec' => 12, 'december' => 12
        ];
        return $months[$str] ?? null;
    }

    /**
     * Resolve Storage asset url to absolute local disk path.
     */
    private function getLocalPath($storageUrl)
    {
        if (!$storageUrl) {
            return null;
        }
        
        $path = parse_url($storageUrl, PHP_URL_PATH);
        if ($path) {
            // Strip storage prefix to match Laravel's storage structure
            $relativePath = str_replace('/storage/', '', $path);
            $diskPath = storage_path('app/public/' . $relativePath);
            if (file_exists($diskPath)) {
                return $diskPath;
            }
        }
        return null;
    }
}
