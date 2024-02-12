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
}
