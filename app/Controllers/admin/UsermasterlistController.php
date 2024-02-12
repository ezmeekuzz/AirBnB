<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\admin\UsersModel;

class UsermasterlistController extends BaseController
{
    protected $uName = 'App\Models\admin\UsersModel';

    public function index()
    {
        // Check if the user is logged in
        if (!session()->has('user_id')) {
            return redirect()->to('/admin/login');
        }

        $uModel = new UsersModel();
        $uresult = $uModel->findAll();

        $data = [
            'title' => 'Property Masterlist | Green Mountain Homes',
            'description' => 'Experience the epitome of mountain living at our newly remodeled 5-bedroom ski home nestled in the heart of the Green Mountains, just minutes from both Bromley and Stratton. A winter wonderland awaits with ski-in, ski-out access and daily shuttle service to the slopes, ensuring effortless enjoyment for your skiing adventures. This spacious retreat comfortably accommodates up to 12 guests, featuring two master bedrooms and three additional bedrooms with queen and double bunk beds. Whether you’re carving the slopes, snowmobiling through pristine landscapes, or exploring Manchester’s fine dining and shopping, our mountain haven provides the perfect base for all your Southern Vermont adventures. Enjoy the cozy comforts of our newly renovated property set atop the snow-laced mountains of Vermont',
            'session' => \Config\Services::session(),
            'records' => $uresult,
            'activelink' => 'usermasterlist'
        ];
        return view('pages/admin/usermasterlist', $data);
    }
    public function delete($id)
    {
        // Delete the data from the database
        $uModel = new UsersModel();
        $item = $uModel->find($id);

        if ($item) {
            // Delete associated image if it exists
            if (!empty($item['userimage'])) {
                unlink(ROOTPATH . 'uploads' . $item['userimage']);
            }
            $uModel->delete($id);
        }
        // Return a JSON response indicating success
        return $this->response->setJSON(['status' => 'success']);
    }
}
