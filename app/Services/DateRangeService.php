<?php

namespace App\Services;

use CodeIgniter\HTTP\Response;

class DateRangeService
{
    public static function formatDateRanges($icsUrl)
    {
        $context = stream_context_create([
            'http' => [
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            ],
        ]);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $icsUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // Enable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // Strict SSL verification
        
        // Error logging settings
        ini_set('display_errors', 0);
        ini_set('log_errors', 1);
        ini_set('error_log', '/path/to/error.log'); // Replace with the actual path
        
        $icsData = curl_exec($ch);
        
        if (curl_errno($ch)) {
            error_log('Curl error: ' . curl_error($ch));
        }
        
        curl_close($ch);

        // Regular expression to match both DTSTART and DTEND lines
        $pattern = '/(DTSTART|DTEND);VALUE=DATE:(\d{8})/';

        // Match both DTSTART and DTEND lines
        preg_match_all($pattern, $icsData, $matches);

        // Extract the matched values into separate arrays
        $startDates = [];
        $endDates = [];

        foreach ($matches[1] as $index => $type) {
            if ($type === 'DTSTART') {
                $startDates[] = $matches[2][$index];
            } elseif ($type === 'DTEND') {
                $endDates[] = $matches[2][$index];
            }
        }

        // Combine start and end dates into pairs with 1 day deducted from the end date
        $dateRanges = array_map(function ($start, $end) {
            return [
                'start' => date('Y-m-d', strtotime($start)),
                'end' => date('Y-m-d', strtotime($end . ' - 1 day')),
            ];
        }, $startDates, $endDates);

        // Convert the result to JSON and create a response object
        $response = service('response');
        $json = json_encode($dateRanges);

        return $response->setJSON($json);
    }
    public static function formatDateRangesBoolean($icsUrl)
    {
        $context = stream_context_create([
            'http' => [
                'user_agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            ],
        ]);
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $icsUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // Follow redirects
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // Enable SSL verification
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2); // Strict SSL verification
        
        // Error logging settings
        ini_set('display_errors', 0);
        ini_set('log_errors', 1);
        ini_set('error_log', '/path/to/error.log'); // Replace with the actual path
        
        $icsData = curl_exec($ch);
        
        if (curl_errno($ch)) {
            error_log('Curl error: ' . curl_error($ch));
        }
        
        curl_close($ch);

        // Regular expression to match both DTSTART and DTEND lines
        $pattern = '/(DTSTART|DTEND);VALUE=DATE:(\d{8})/';

        // Match both DTSTART and DTEND lines
        preg_match_all($pattern, $icsData, $matches);

        // Extract the matched values into separate arrays
        $startDates = [];
        $endDates = [];

        foreach ($matches[1] as $index => $type) {
            if ($type === 'DTSTART') {
                $startDates[] = $matches[2][$index];
            } elseif ($type === 'DTEND') {
                $endDates[] = $matches[2][$index];
            }
        }

        // Combine start and end dates into pairs with 1 day deducted from the end date
        $dateRanges = array_map(function ($start, $end) {
            return [
                'start' => date('Y-m-d', strtotime($start . ' + 1 day')),
                'end' => date('Y-m-d', strtotime($end . ' - 1 day')),
            ];
        }, $startDates, $endDates);

        // Return the array of date ranges directly
        return $dateRanges;
    }
    public static function isDateRangeAvailable($formattedDateRanges, $startDate, $endDate)
    {
        // Check if the given date range overlaps with any booked date range
        foreach ($formattedDateRanges as $bookedRange) {
            $bookedStartDate = strtotime($bookedRange['start']);
            $bookedEndDate = strtotime($bookedRange['end']);
            $inputStartDate = strtotime($startDate);
            $inputEndDate = strtotime($endDate); // Add 1 day to the end date
    
            // Check for date range overlap
            if (
                ($inputStartDate >= $bookedStartDate && $inputStartDate <= $bookedEndDate) ||
                ($inputEndDate >= $bookedStartDate && $inputEndDate <= $bookedEndDate) ||
                ($inputStartDate <= $bookedStartDate && $inputEndDate >= $bookedEndDate)
            ) {
                // Date range overlaps with a booked date range, it's not available
                return false;
            }
        }
    
        // Date range does not overlap with any booked date range, it's available
        return true;
    }
}
