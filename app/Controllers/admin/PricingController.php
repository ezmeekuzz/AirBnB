<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\admin\PricingModel;

class PricingController extends BaseController
{
    protected $pName = 'App\Models\admin\PricingModel';

    public function index()
    {
        // Check if the user is logged in
        if (!session()->has('user_id')) {
            return redirect()->to('/admin/login');
        }

        $data = [
            'title' => 'Pricing | Green Mountain Homes',
            'description' => 'Experience the epitome of mountain living at our newly remodeled 5-bedroom ski home nestled in the heart of the Green Mountains, just minutes from both Bromley and Stratton. A winter wonderland awaits with ski-in, ski-out access and daily shuttle service to the slopes, ensuring effortless enjoyment for your skiing adventures. This spacious retreat comfortably accommodates up to 12 guests, featuring two master bedrooms and three additional bedrooms with queen and double bunk beds. Whether you’re carving the slopes, snowmobiling through pristine landscapes, or exploring Manchester’s fine dining and shopping, our mountain haven provides the perfect base for all your Southern Vermont adventures. Enjoy the cozy comforts of our newly renovated property set atop the snow-laced mountains of Vermont',
            'session' => \Config\Services::session(),
            'activelink' => 'propertymasterlist'
        ];
        return view('pages/admin/pricing', $data);
    }
    public function lists()
    {
        // Handle fetching events for FullCalendar based on property_id
        // You might fetch events from your database or another source
        $propertyId = $this->request->getGet('property_id');
    
        // Example: Fetch events from the database
        // Assuming you have a PricingModel with appropriate methods for fetching events
        $pricingModel = new PricingModel();
    
        // Modify this based on your actual data fetching logic
        $pricingModel = new PricingModel();
        $eventsData = $pricingModel->where('property_id', $propertyId)->findAll();
    
        // Format the data for FullCalendar
        $events = [];
        foreach ($eventsData as $event) {
            $events[] = [
                'title' => 'Price : $'. $event['price'], // Replace with the actual title column from your database
                'start' => $event['date'], // Replace with the actual start_date column from your database
            ];
        }
    
        return $this->response->setJSON($events);
    }    
    public function insert()
    {
        // Handle inserting or updating pricing data into the database
        // You will receive the data via AJAX POST request
    
        $request = $this->request;
    
        $propertyId = $request->getVar('property_id');
        $dates = $request->getVar('dates'); // Change to 'dates' to handle multiple selected dates
        $price = $request->getVar('price');
    
        // Check if 'property_id' is empty
        if (empty($propertyId)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Property ID cannot be empty.']);
        }
    
        // Corrected namespace for PricingModel
        $pricingModel = new PricingModel();
    
        foreach ($dates as $date) {
            // Check if a record with the same property_id and date exists
            $existingRecord = $pricingModel
                ->where('property_id', $propertyId)
                ->where('date', $date)
                ->first();
    
            if ($existingRecord) {
                // If record exists, update it
                $result = $pricingModel
                    ->where('property_id', $propertyId)
                    ->where('date', $date)
                    ->set(['price' => $price])
                    ->update();
            } else {
                // If record doesn't exist, insert a new one
                $result = $pricingModel->insert([
                    'property_id' => $propertyId,
                    'date' => $date,
                    'price' => $price,
                ]);
            }
    
            // Check the result of each operation
            if (!$result) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Error inserting/updating data into the database.']);
            }
        }
    
        return $this->response->setJSON(['status' => 'success', 'message' => 'Data inserted/updated successfully.']);
    }    
    public function insertMultiple()
    {
        // Handle inserting or updating pricing data into the database for multiple selected dates
        // You will receive the data via AJAX POST request
    
        $request = $this->request;
    
        $propertyId = $request->getVar('property_id');
        $dates = $request->getVar('dates');
        $price = $request->getVar('price');
    
        // Check if 'property_id' is empty
        if (empty($propertyId)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Property ID cannot be empty.']);
        }
    
        // Corrected namespace for PricingModel
        $pricingModel = new PricingModel();
    
        // Check for past dates
        $today = date('Y-m-d');
        foreach ($dates as $date) {
            if ($date < $today) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Cannot insert prices for past dates.']);
            }
    
            // Adjust end date - exclude it if it's not part of the selected range
            $endDate = date('Y-m-d', strtotime($date . ' +1 day'));
    
            // Check if a record with the same property_id and date exists
            $existingRecord = $pricingModel
                ->where('property_id', $propertyId)
                ->where('date >=', $date)
                ->where('date <', $endDate)
                ->first();
    
            if ($existingRecord) {
                // If record exists, update it
                $result = $pricingModel
                    ->where('property_id', $propertyId)
                    ->where('date >=', $date)
                    ->where('date <', $endDate)
                    ->set(['price' => $price])
                    ->update();
            } else {
                // If record doesn't exist, insert a new one
                $result = $pricingModel->insert([
                    'property_id' => $propertyId,
                    'date' => $date,
                    'price' => $price,
                ]);
            }
    
            // Check the result of each operation
            if (!$result) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Error inserting/updating data into the database.']);
            }
        }
    
        return $this->response->setJSON(['status' => 'success', 'message' => 'Data inserted/updated successfully for multiple selected dates.']);
    }
    
}