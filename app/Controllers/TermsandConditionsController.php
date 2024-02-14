<?php

namespace App\Controllers;

class TermsandConditionsController extends BaseController
{
    public function index()
    {

        $data = [
            'title' => 'Checkout | Green Mountain Homes',
            'description' => 'Experience the epitome of mountain living at our newly remodeled 5-bedroom ski home nestled in the heart of the Green Mountains, just minutes from both Bromley and Stratton. A winter wonderland awaits with ski-in, ski-out access and daily shuttle service to the slopes, ensuring effortless enjoyment for your skiing adventures. This spacious retreat comfortably accommodates up to 12 guests, featuring two master bedrooms and three additional bedrooms with queen and double bunk beds. Whether you’re carving the slopes, snowmobiling through pristine landscapes, or exploring Manchester’s fine dining and shopping, our mountain haven provides the perfect base for all your Southern Vermont adventures. Enjoy the cozy comforts of our newly renovated property set atop the snow-laced mountains of Vermont',
            'session' => \Config\Services::session(),
        ];
        return view('pages/termsandconditions', $data);
    }
}
