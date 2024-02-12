<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\admin\UsersModel;

class EdituserController extends BaseController
{
    protected $bName = 'App\Models\admin\UsersModel';

    public function index($id)
    {
        // Check if the user is logged in
        if (!session()->has('user_id')) {
            return redirect()->to('/admin/login');
        }

        $uModel = new UsersModel();
        $uresult = $uModel->where('user_id', $id)->findAll();

        $data = [
            'title' => 'Property Masterlist | Green Mountain Homes',
            'description' => 'Experience the epitome of mountain living at our newly remodeled 5-bedroom ski home nestled in the heart of the Green Mountains, just minutes from both Bromley and Stratton. A winter wonderland awaits with ski-in, ski-out access and daily shuttle service to the slopes, ensuring effortless enjoyment for your skiing adventures. This spacious retreat comfortably accommodates up to 12 guests, featuring two master bedrooms and three additional bedrooms with queen and double bunk beds. Whether you’re carving the slopes, snowmobiling through pristine landscapes, or exploring Manchester’s fine dining and shopping, our mountain haven provides the perfect base for all your Southern Vermont adventures. Enjoy the cozy comforts of our newly renovated property set atop the snow-laced mountains of Vermont',
            'session' => \Config\Services::session(),
            'activelink' => 'usermasterlist',
            'records' => $uresult,
        ];
        return view('pages/admin/edituser', $data);
    }    
    public function update()
    {
        $user_id = $this->request->getPost('user_id');
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
        $excludedIds = [$user_id];
        $userList = $userModel->where('username', $username)
                              ->whereNotIn('user_id', $excludedIds)
                              ->first();
        $userFilter = $userModel->where('user_id', $user_id)->first();
        if($userList) {
            $response = [
                'status' => 'existed',
                'message' => 'Username is not available',
            ];
        }
        else {
            $userId = $userModel->update($user_id, $data);
            $response = [
                'status' => 'success',
                'message' => 'User updated successfully!',
            ];
        }

        return $this->response->setJSON($response);
    }
}
