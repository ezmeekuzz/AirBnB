<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\admin\PropertiesModel;
use App\Models\ReviewsModel;
use App\Models\BookingsModel;
use App\Models\admin\PropertyImagesModel;

class CheckoutController extends BaseController
{

    public function index()
    {
        // Create an instance of the PropertiesModel
        $propertiesModel = new PropertiesModel();
        $reviewModel = new ReviewsModel();
        $imageModel = new PropertyImagesModel();

        // Retrieve propertyId from the query string
        $propertyId = $this->request->getGet('property_id');
        
        // Fetch data for the specific property using the $id parameter
        $property = $propertiesModel->find($propertyId);

        $reviews = $reviewModel->select('reviews.*, properties.propertyname')
        ->join('properties', 'properties.property_id = reviews.property_id')
        ->where('reviews.status', 'Approved')
        ->where('reviews.property_id', $propertyId)
        ->findAll();

        // Calculate the total sum of review ratings
        $totalRatings = 0;
        $totalCount = count($reviews);
        foreach ($reviews as $review) {
            $totalRatings += $review['rating'];
        }

        // Fetch data for the specific property using the $id parameter
        $image = $imageModel->where('property_id', $propertyId)->first();

        // Calculate the average rating
        $averageRating = ($totalCount > 0) ? ($totalRatings / $totalCount) : 0;

        $data = [
            'title' => 'Checkout | Green Mountain Homes',
            'slug' => $property['slug'],
            'description' => 'Experience the epitome of mountain living at our newly remodeled 5-bedroom ski home nestled in the heart of the Green Mountains, just minutes from both Bromley and Stratton. A winter wonderland awaits with ski-in, ski-out access and daily shuttle service to the slopes, ensuring effortless enjoyment for your skiing adventures. This spacious retreat comfortably accommodates up to 12 guests, featuring two master bedrooms and three additional bedrooms with queen and double bunk beds. Whether you’re carving the slopes, snowmobiling through pristine landscapes, or exploring Manchester’s fine dining and shopping, our mountain haven provides the perfect base for all your Southern Vermont adventures. Enjoy the cozy comforts of our newly renovated property set atop the snow-laced mountains of Vermont',
            'session' => \Config\Services::session(),
            'properties' => $property,
            'reviews' => $reviews,
            'averageRating' => $averageRating,
            'image' => $image,
        ];
        return view('pages/checkout', $data);
    }
    public function submitBooking()
    {
        // Load the email service
        $this->email = \Config\Services::email();
    
        // Retrieve data from the AJAX request
        $propertyId = $this->request->getPost('property_id');
        $adult = $this->request->getPost('adults');
        $children = $this->request->getPost('childrens');
        $infant = $this->request->getPost('infants');
        $pet = $this->request->getPost('pets');
        $checkinDate = $this->request->getPost('checkIn');
        $checkoutDate = $this->request->getPost('checkOut');
        $totalAmount = $this->request->getPost('totalAmount');
        $extraGuestFee = $this->request->getPost('extraGuestFee');
        $cleaningFee = $this->request->getPost('cleaningFee');
        $hotTubFee = $this->request->getPost('hotTubFee');
        $petFee = $this->request->getPost('petFee');
        $emailaddress = $this->request->getPost('email');
    
        // Insert data into the booking table using a model
        $bookingModel = new BookingsModel();
        
        $propertyModel = new PropertiesModel();

        $property = $propertyModel->find($propertyId);

        // Get the property name
        $propertyName = $property ? $property['propertyname'] : null;

        $data = [
            'property_id' => $propertyId,
            'cleaningfee' => $cleaningFee,
            'extraguestfee' => $extraGuestFee,
            'hottubfee' => $hotTubFee,
            'petfee' => $petFee,
            'adult' => $adult,
            'children' => $children,
            'infant' => $infant,
            'pet' => $pet,
            'checkin_date' => $checkinDate,
            'checkout_date' => $checkoutDate,
            'totalamount' => $totalAmount,
            'emailaddress' => $emailaddress,
            'bookingdate' => date('Y-m-d'),
            'status' => 'Pending',
        ];

        // Create content for the email
        $content = 'Property: ' . $propertyName . '<br/>' .
                   'Adults: ' . $adult . '<br/>' .
                   'Children: ' . $children . '<br/>' .
                   'Infants: ' . $infant . '<br/>' .
                   'Pet: ' . $pet . '<br/>' .
                   'Check-in Date: ' . $checkinDate . '<br/>' .
                   'Check-out Date: ' . $checkoutDate . '<br/>' .
                   'Email Address: ' . $emailaddress . '<br/>' .
                   'Total Amount: $' . $totalAmount;
    
        $bookingModel->insert($data);
    
        // Send email to your Gmail account
        $this->email->setTo('yourgreenmountainstay@gmail.com');
        $this->email->setSubject('New Booking Submission');
        $this->email->setMessage('A new booking has been submitted. <br/><br/>'. $content);
    
        if ($this->email->send()) {
            // Email to your Gmail account sent successfully
        } else {
            // Email to your Gmail account failed
            $this->logger->error('Email to your Gmail account failed: ' . $this->email->printDebugger(['headers']));
        }
    
        // Send confirmation email to the customer
        $this->email->clear();
    
        $this->email->setTo($emailaddress);
        $this->email->setSubject('Booking Confirmation');
        $this->email->setMessage('Thank you for your booking. We have received your reservation. <br/>'. $content);
    
        if ($this->email->send()) {
            // Confirmation email sent successfully
            return $this->response->setJSON(['message' => 'We received your booking. Please check your email!']);
        } else {
            // Confirmation email failed
            $this->logger->error('Confirmation email failed: ' . $this->email->printDebugger(['headers']));
            return $this->response->setJSON(['message' => 'Error sending confirmation email.']);
        }
    }    
}
