<?php

namespace App\Services;

use CodeIgniter\HTTP\Response;

class DateRangeService
{
    public static function formatDateRanges($icsUrl)
    {
        // Fetch the data from the provided URL
        $icsData = file_get_contents($icsUrl);

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
        // Fetch the data from the provided URL
        $icsData = file_get_contents($icsUrl);

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
