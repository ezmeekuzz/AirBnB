<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\admin\UsersModel;

class AdduserController extends BaseController
{
    protected $gsmName = 'App\Models\admin\GeneralsettingsModel';
    protected $uName = 'App\Models\admin\UsersModel';

    public function index()
    {
        // Check if the user is logged in
        if (!session()->has('user_id')) {
            return redirect()->to('/admin/login');
        }
        
        $data = [
            'title' => 'Property Masterlist | Green Mountain Homes',
            'description' => 'Experience the epitome of mountain living at our newly remodeled 5-bedroom ski home nestled in the heart of the Green Mountains, just minutes from both Bromley and Stratton. A winter wonderland awaits with ski-in, ski-out access and daily shuttle service to the slopes, ensuring effortless enjoyment for your skiing adventures. This spacious retreat comfortably accommodates up to 12 guests, featuring two master bedrooms and three additional bedrooms with queen and double bunk beds. Whether you’re carving the slopes, snowmobiling through pristine landscapes, or exploring Manchester’s fine dining and shopping, our mountain haven provides the perfect base for all your Southern Vermont adventures. Enjoy the cozy comforts of our newly renovated property set atop the snow-laced mountains of Vermont',
            'session' => \Config\Services::session(),
            'activelink' => 'adduser'
        ];
        return view('pages/admin/adduser', $data);
    }
    public function insert()
    {
        $data = [
            'firstname' => $this->request->getPost('firstname'),
            'lastname' => $this->request->getPost('lastname'),
            'emailaddress' => $this->request->getPost('emailaddress'),
            'username' => $this->request->getPost('username'),
            'password' => $this->request->getPost('password'),
            'encryptedpass' => password_hash($this->request->getPost('password'), PASSWORD_BCRYPT),
        ];
        $username = $this->request->getPost('username');
        $userModel = new UsersModel();
        $userList = $userModel->where('username', $username)->first();
        if($userList) {
            $response = [
                'status' => 'existed',
                'message' => 'Username is not available',
            ];
        }
        else {
            $userId = $userModel->insert($data);
    
            if ($userId) {
                $response = [
                    'status' => 'success',
                    'message' => 'User added successfully!',
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Failed to add user.',
                ];
            }
        }

        return $this->response->setJSON($response);
    }
}
