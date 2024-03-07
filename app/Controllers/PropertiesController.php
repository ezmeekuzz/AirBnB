<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\admin\PropertiesModel;
use App\Models\admin\PropertyBannersModel;
use App\Models\admin\PropertyImagesModel;
use App\Models\admin\FeaturesModel;
use App\Models\admin\PricingModel;
use App\Models\ReviewsModel;
use App\Models\MessagesModel;
use App\Services\DateRangeService;

class PropertiesController extends BaseController
{

    public function index($id)
    {
        // Create an instance of the PropertiesModel
        $propertiesModel = new PropertiesModel();
        $bannerModel = new PropertyBannersModel();
        $imageModel = new PropertyImagesModel();
        $featureModel = new FeaturesModel();
        $reviewModel = new ReviewsModel();
        $pricingModel = new PricingModel();

        // Fetch data for the specific property using the $id parameter
        $property = $propertiesModel->find($id);

        // Fetch data for the specific property using the $id parameter
        $banners = $bannerModel->where('property_id', $id)->findAll();

        // Fetch data for the specific property using the $id parameter
        $image = $imageModel->where('property_id', $id)->findAll();

        // Fetch data for the specific property using the $id parameter
        $features = $featureModel->where('property_id', $id)->findAll();

        $reviews = $reviewModel->select('reviews.*, properties.propertyname')
        ->join('properties', 'properties.property_id = reviews.property_id')
        ->where('reviews.status', 'Approved')
        ->where('reviews.property_id', $id)
        ->findAll();

        $otherRentals = $this->otherRentals($id);
        // Calculate the total sum of review ratings
        $totalRatings = 0;
        $totalCount = count($reviews);
        foreach ($reviews as $review) {
            $totalRatings += $review['rating'];
        }

        // Calculate the average rating
        $averageRating = ($totalCount > 0) ? ($totalRatings / $totalCount) : 0;

        // Check if the property exists
        if ($property) {
            // Data for the specific property is available
            $data = [
                'title' => $property['propertyname'].' | Green Mountain Stay',
                'propertyname' => $property['propertyname'],
                'slug' => $property['slug'],
                'address' => $property['address'],
                'propertydescription' => $property['description'],
                'description' => 'Experience the epitome of mountain living at our newly remodeled 5-bedroom ski home nestled in the heart of the Green Mountains, just minutes from both Bromley and Stratton. A winter wonderland awaits with ski-in, ski-out access and daily shuttle service to the slopes, ensuring effortless enjoyment for your skiing adventures. This spacious retreat comfortably accommodates up to 12 guests, featuring two master bedrooms and three additional bedrooms with queen and double bunk beds. Whether you’re carving the slopes, snowmobiling through pristine landscapes, or exploring Manchester’s fine dining and shopping, our mountain haven provides the perfect base for all your Southern Vermont adventures. Enjoy the cozy comforts of our newly renovated property set atop the snow-laced mountains of Vermont',
                'session' => \Config\Services::session(),
                'banners' => $banners,
                'images' => $image,
                'features' => $features,
                'reviews' => $reviews,
                'properties' => $property,
                'averageRating' => $averageRating, // Add total ratings to the data array
                'property_id' => $id,
                'otherRentals' => $otherRentals,
            ];

            return view('pages/properties', $data);
        } else {
            // Property not found, handle accordingly (e.g., show an error message)
            return view('errors/html/error_404');
        }
    }
    public function submitReview()
    {
        // Validate the form data (you can add more validation rules as needed)
        $validationRules = [
            'emailaddress' => 'required|valid_email',
            'name' => 'required',
            'rating' => 'required|integer|greater_than[0]|less_than[6]',
            'message' => 'required',
        ];

        if (!$this->validate($validationRules)) {
            // If validation fails, return an error response
            return $this->response->setJSON(['status' => 'error', 'message' => validation_errors()]);
        }

        // If validation passes, save the data into the database
        $reviewModel = new ReviewsModel(); // Replace with your actual Review model class
        $data = [
            'property_id' => $this->request->getPost('property_id'),
            'emailaddress' => $this->request->getPost('emailaddress'),
            'name' => $this->request->getPost('name'),
            'rating' => $this->request->getPost('rating'),
            'message' => $this->request->getPost('message'),
            'reviewdate' => date('Y-m-d'), // Add the current date and time
            'status' => 'Pending',
        ];

        $reviewModel->insert($data);

        // Return a success response
        return $this->response->setJSON(['status' => 'success', 'message' => 'Review submitted successfully.']);
    }
    public function sendMessage()
    {
        // Load the email service
        $this->email = \Config\Services::email();
        // Validate the form data (you can add more validation rules as needed)
        $validationRules = [
            'fullname' => 'required',
            'email' => 'required|valid_email',
            'phone' => 'required|numeric',
            'messageContent' => 'required',
        ];
    
        $validator = \Config\Services::validation(); // Get the validation service
    
        if (!$this->validate($validationRules)) {
            // If validation fails, return an error response
            return $this->response->setJSON(['status' => 'error', 'message' => $validator->getErrors()]);
        }
    
        $messageModel = new MessagesModel(); // Replace with your actual Review model class
        $data = [
            'fullname' => $this->request->getPost('fullname'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'messageContent' => $this->request->getPost('messageContent'),
            'messagedate' => date('Y-m-d H:i:s'), // Add the current date and time
        ];
    
        $messageModel->insert($data);
    
        // Send email to your personal email address
        $this->email->clear();
    
        $this->email->setTo('yourgreenmountainstay@gmail.com'); // Replace with your actual email address
        $this->email->setSubject('You\'ve got a new message');
        
        // Create content for the email
        $emailContent = 'Full Name: ' . $data['fullname'] . '<br/>' .
                        'Email: ' . $data['email'] . '<br/>' .
                        'Phone: ' . $data['phone'] . '<br/>' .
                        'Message Content: ' . $data['messageContent'] . '<br/>' .
                        'Date: ' . $data['messagedate'];
    
        $this->email->setMessage($emailContent);
    
        if ($this->email->send()) {
            // If email is sent successfully, return a success message
            return $this->response->setJSON(['status' => 'success', 'message' => 'Message submitted successfully.']);
        } else {
            // If email fails to send, return an error message
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error sending message.']);
        }
    }       
    public function icsData($id)
    {   
        // Create an instance of the PropertiesModel
        $propertiesModel = new PropertiesModel();
            
        // Fetch data for the specific property using the $id parameter
        $property = $propertiesModel->find($id);
        
        $pModel = new PricingModel();

        $pricingData = $pModel
                    ->where('property_id', $id)
                    ->findAll();

        $icsUrl = $property['ics_link'];
        $dateRangeService = new DateRangeservice();

        // Format date ranges using the service
        $bookDates = $formattedDateRanges = $dateRangeService->formatDateRanges($icsUrl);
        return $bookDates;
    }
    public function calculateTotalPrice($property_id, $startDate, $endDate)
    {
        // Query the database to get all dates within the specified date range
        $datesInRange = $this->getDatesInRange($startDate, $endDate);
    
        // Query the database to get pricing information for the specified property and date range
        $pModel = new PricingModel();
        $pricingData = $pModel->where('property_id', $property_id)
            ->whereIn('date', $datesInRange)
            ->findAll();
    
        // Check if all dates are available in the database
        if (count($datesInRange) !== count($pricingData)) {
            // Not all dates are available
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Not all dates are available in the database.']);
        }
    
        // Continue with the rest of the logic...
    
        // Calculate the total price
        $totalPrice = $this->calculateTotalPriceFromPricingData($pricingData);
    
        // Fetch additional fees from the properties table
        $propertyModel = new PropertiesModel();
        $propertyData = $propertyModel->find($property_id);
    
        // Validate property data
        if (!$propertyData) {
            // Property not found
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Property not found.']);
        }
    
        // Extract additional fees from the property data
        $cleaningFee = $propertyData['cleaningfee'] ?? 0;
        $extraGuestFee = $propertyData['extraguest'] ?? 0;
        $hotTubFee = $propertyData['hottub'] ?? 0;
        $petFee = $propertyData['petfee'] ?? 0;
        $basicNumberofGuest = $propertyData['basic_number_of_guest'] ?? 0;
        $guestLimit = $propertyData['guest_limit'] ?? 0;
    
        // Return the total price and additional fees as a JSON response
        return $this->response->setJSON([
            'total_price' => $totalPrice,
            'cleaning_fee' => $cleaningFee,
            'extra_guest_fee' => $extraGuestFee,
            'hot_tub_fee' => $hotTubFee,
            'pet_fee' => $petFee,
            'guest_limit' => $guestLimit,
            'basic_number_of_guest' => $basicNumberofGuest,
        ]);
    }

    protected function getDatesInRange($startDate, $endDate)
    {
        // Assuming $startDate and $endDate are in 'YYYY-MM-DD' format
        $startTimestamp = strtotime($startDate);
        $endTimestamp = strtotime($endDate);

        $datesInRange = [];

        // Loop through each day in the date range
        for ($currentTimestamp = $startTimestamp; $currentTimestamp <= $endTimestamp; $currentTimestamp += 86400) {
            $datesInRange[] = date('Y-m-d', $currentTimestamp);
        }

        return $datesInRange;
    }
    
    // Helper function to calculate the total price from pricing data
    protected function calculateTotalPriceFromPricingData($pricingData)
    {
        // Initialize total price
        $totalPrice = 0;
    
        // Sum up the prices for each day in the pricing data
        foreach ($pricingData as $priceEntry) {
            $totalPrice += $priceEntry['price'];
        }
    
        return $totalPrice;
    }    
    public function getGuestLimit($id)
    {
        // Assuming you have a model named PropertiesModel to handle database interactions
        $guestLimitModel = new PropertiesModel(); // Replace with your actual model
    
        // Assuming the guest_limit field is in the properties table
        $property = $guestLimitModel->find($id);
    
        if ($property) {
            $guestLimit = $property['guest_limit'];
            $basicNumberofGuest = $property['basic_number_of_guest'];
            $extraGuest = $property['extraguest'];
            return $this->response->setJSON([
                'guest_limit' => $guestLimit,
                'basic_number_of_guest' => $basicNumberofGuest,
                'extraguest' => $extraGuest,
            ]);
        } else {
            // You can customize the error response as needed
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Property not found']);
        }
    }    
    private function otherRentals($id)
    {
        $bannerModel = new PropertyBannersModel();
    
        $properties = $bannerModel
            ->select('property_banners.*, properties.*')
            ->join('properties', 'properties.property_id = property_banners.property_id')
            ->where('property_banners.property_id !=', $id)
            ->groupBy('property_banners.property_id')
            ->findAll();
    
        return $properties;
    }
    public function datePrices($propertyId)
    {
        
        $priceModel = new PricingModel();
        
        $prices = $priceModel
            ->where('property_id', $propertyId)
            ->findAll();
        return $this->response->setJSON($prices);
    }
}
