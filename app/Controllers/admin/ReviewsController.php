<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ReviewsModel;

class ReviewsController extends BaseController
{
    protected $rName = 'App\Models\ReviewsModel';

    public function index()
    {
        // Check if the user is logged in
        if (!session()->has('user_id')) {
            return redirect()->to('/admin/login');
        }

        $rModel = new ReviewsModel();
        $rresult = $rModel->select('reviews.*, properties.propertyname')
        ->join('properties', 'properties.property_id = reviews.property_id')
        ->where('reviews.status', 'Approved')
        ->findAll();

        $data = [
            'title' => 'Reviews | Green Mountain Homes',
            'description' => 'Experience the epitome of mountain living at our newly remodeled 5-bedroom ski home nestled in the heart of the Green Mountains, just minutes from both Bromley and Stratton. A winter wonderland awaits with ski-in, ski-out access and daily shuttle service to the slopes, ensuring effortless enjoyment for your skiing adventures. This spacious retreat comfortably accommodates up to 12 guests, featuring two master bedrooms and three additional bedrooms with queen and double bunk beds. Whether you’re carving the slopes, snowmobiling through pristine landscapes, or exploring Manchester’s fine dining and shopping, our mountain haven provides the perfect base for all your Southern Vermont adventures. Enjoy the cozy comforts of our newly renovated property set atop the snow-laced mountains of Vermont',
            'session' => \Config\Services::session(),
            'records' => $rresult,
            'activelink' => 'reviews'
        ];
        return view('pages/admin/reviews', $data);
    }
    public function delete($id)
    {
        // Delete the data from the database
        $rModel = new ReviewsModel();
        $review = $rModel->find($id);

        if ($review) {
            $rModel->delete($id);
        }
        // Return a JSON response indicating success
        return $this->response->setJSON(['status' => 'success']);
    }
}
