<?php

namespace App\Controllers\admin;

use App\Controllers\BaseController;
use App\Models\admin\UsersModel;
use CodeIgniter\API\ResponseTrait;

class LoginController extends BaseController
{
    public function index()
    {
        // Check if the user is logged in
        if (session()->has('user_id')) {
            return redirect()->to('/admin/booking');
        }
        $data = [
            'title' => 'Login | Green Mountain Homes',
            'description' => 'Experience the epitome of mountain living at our newly remodeled 5-bedroom ski home nestled in the heart of the Green Mountains, just minutes from both Bromley and Stratton. A winter wonderland awaits with ski-in, ski-out access and daily shuttle service to the slopes, ensuring effortless enjoyment for your skiing adventures. This spacious retreat comfortably accommodates up to 12 guests, featuring two master bedrooms and three additional bedrooms with queen and double bunk beds. Whether you’re carving the slopes, snowmobiling through pristine landscapes, or exploring Manchester’s fine dining and shopping, our mountain haven provides the perfect base for all your Southern Vermont adventures. Enjoy the cozy comforts of our newly renovated property set atop the snow-laced mountains of Vermont',
        ];
        return view('pages/admin/login', $data);
    }
    public function loginfunc() 
    {
        $userModel = new UsersModel();

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $result = $userModel->where('username', $username)->first();

        if ($result && password_verify($password, $result['encryptedpass'])) {
            session()->set('user_id', $result['user_id']);
            session()->set('firstname', $result['firstname']);
            session()->set('lastname', $result['lastname']);
            session()->set('emailaddress', $result['emailaddress']);
            return redirect()->to('/admin/booking');
        } else {
            return redirect()->back()->with('error', 'Invalid login credentials');
        }
    }
}
