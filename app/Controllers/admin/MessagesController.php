<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\MessagesModel;

class MessagesController extends BaseController
{
    protected $uName = 'App\Models\MessagesModel';

    public function index()
    {
        // Check if the user is logged in
        if (!session()->has('user_id')) {
            return redirect()->to('/admin/login');
        }

        $mModel = new MessagesModel();
        $mresult = $mModel->findAll();

        $data = [
            'title' => 'Messages | Green Mountain Homes',
            'description' => 'Experience the epitome of mountain living at our newly remodeled 5-bedroom ski home nestled in the heart of the Green Mountains, just minutes from both Bromley and Stratton. A winter wonderland awaits with ski-in, ski-out access and daily shuttle service to the slopes, ensuring effortless enjoyment for your skiing adventures. This spacious retreat comfortably accommodates up to 12 guests, featuring two master bedrooms and three additional bedrooms with queen and double bunk beds. Whether you’re carving the slopes, snowmobiling through pristine landscapes, or exploring Manchester’s fine dining and shopping, our mountain haven provides the perfect base for all your Southern Vermont adventures. Enjoy the cozy comforts of our newly renovated property set atop the snow-laced mountains of Vermont',
            'session' => \Config\Services::session(),
            'records' => $mresult,
            'activelink' => 'messages'
        ];
        return view('pages/admin/messages', $data);
    }
    public function delete($id)
    {
        // Delete the data from the database
        $mModel = new MessagesModel();
        $item = $mModel->find($id);

        if ($item) {
            $mModel->delete($id);
        }
        // Return a JSON response indicating success
        return $this->response->setJSON(['status' => 'success']);
    }
}
