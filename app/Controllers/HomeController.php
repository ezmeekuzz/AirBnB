<?php

namespace App\Controllers;
use App\Models\admin\PropertiesModel;
use App\Services\DateRangeService;

class HomeController extends BaseController
{
    public function index(): string
    {

        $data = [
            'title' => 'Home | Green Mountain Stay',
            'description' => 'Experience the epitome of mountain living at our newly remodeled 5-bedroom ski home nestled in the heart of the Green Mountains, just minutes from both Bromley and Stratton. A winter wonderland awaits with ski-in, ski-out access and daily shuttle service to the slopes, ensuring effortless enjoyment for your skiing adventures. This spacious retreat comfortably accommodates up to 12 guests, featuring two master bedrooms and three additional bedrooms with queen and double bunk beds. Whether you’re carving the slopes, snowmobiling through pristine landscapes, or exploring Manchester’s fine dining and shopping, our mountain haven provides the perfect base for all your Southern Vermont adventures. Enjoy the cozy comforts of our newly renovated property set atop the snow-laced mountains of Vermont',
            'session' => \Config\Services::session(),
        ];
        return view('pages/home', $data);
    }
    public function searchDateAvailability()
    {
        $propertiesModel = new PropertiesModel();
        $properties = $propertiesModel->findAll();
        $dateRangeService = new DateRangeService();
        
        $availableProperties = [];
    
        foreach ($properties as $property) {
            $icsUrl = $property['ics_link'];
            $formattedDateRanges = $dateRangeService->formatDateRangesBoolean($icsUrl);
    
            // Check if the provided date range is available for the property
            $isAvailable = $dateRangeService->isDateRangeAvailable($formattedDateRanges, $_GET['start_date'], $_GET['end_date']);
    
            if ($isAvailable) {
                $availableProperties[] = [
                    'property_id' => $property['property_id'],
                    'property_name' => $property['propertyname'],
                    // Add other relevant property information as needed
                ];
            }
        }
    
        // Return the list of available properties
        return redirect()->to('/search-result'.'?startDate='.$_GET['start_date'].'&endDate='.$_GET['end_date'].'&adult='.$_GET['adult'].'&children='.$_GET['children'].'&infant='.$_GET['infant'].'&pet='.$_GET['pet']);
    }
}
