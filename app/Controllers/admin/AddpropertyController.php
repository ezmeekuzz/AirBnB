<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\admin\PropertiesModel;

class AddpropertyController extends BaseController
{
    protected $gsmName = 'App\Models\admin\GeneralsettingsModel';
    protected $uName = 'App\Models\admin\PropertiesModel';

    public function index()
    {
        // Check if the user is logged in
        if (!session()->has('user_id')) {
            return redirect()->to('/admin/login');
        }
        
        $data = [
            'title' => 'Add Property | Green Mountain Homes',
            'description' => 'Experience the epitome of mountain living at our newly remodeled 5-bedroom ski home nestled in the heart of the Green Mountains, just minutes from both Bromley and Stratton. A winter wonderland awaits with ski-in, ski-out access and daily shuttle service to the slopes, ensuring effortless enjoyment for your skiing adventures. This spacious retreat comfortably accommodates up to 12 guests, featuring two master bedrooms and three additional bedrooms with queen and double bunk beds. Whether you’re carving the slopes, snowmobiling through pristine landscapes, or exploring Manchester’s fine dining and shopping, our mountain haven provides the perfect base for all your Southern Vermont adventures. Enjoy the cozy comforts of our newly renovated property set atop the snow-laced mountains of Vermont',
            'session' => \Config\Services::session(),
            'activelink' => 'addproperty'
        ];
        return view('pages/admin/addproperty', $data);
    }
    public function insert()
    {
        $slug = strtolower(htmlentities($this->request->getPost('propertyname')));
        $rep_this = [" ", "&", "!", ",", "?", ":", ";", "/", "&#039", "and#039"];
        $rep_by = ["-", "and", "", "", "", "", "", "-", "", ""];
        $slug = str_replace($rep_this, $rep_by, $slug);
        $data = [
            'propertyname' => $this->request->getPost('propertyname'),
            'description' => $this->request->getPost('description'),
            'address' => $this->request->getPost('address'),
            'cleaningfee' => $this->request->getPost('cleaningfee'),
            'extraguest' => $this->request->getPost('extraguest'),
            'hottub' => $this->request->getPost('hottub'),
            'petfee' => $this->request->getPost('petfee'),
            'basic_number_of_guest' => $this->request->getPost('basic_number_of_guest'),
            'guest_limit' => $this->request->getPost('guest_limit'),
            'slug' => $slug,
            'ics_link' => $this->request->getPost('ics_link'),
        ];
        $propertyModel = new PropertiesModel();
        $propertyId = $propertyModel->insert($data);
    
        if ($propertyId) {
            $response = [
                'status' => 'success',
                'message' => 'Property added successfully!',
            ];
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Failed to add property.',
            ];
        }

        $this->dynamicRoutes();

        return $this->response->setJSON($response);
    }
    private function dynamicRoutes() {
        $model = new PropertiesModel();
        $result = $model->findAll();
        $data = [];

        if (count($result)) {
            foreach ($result as $route) {
                $data[$route['slug']] = 'PropertiesController::index/' . $route['property_id'];
            }
        }

        $output = '<?php' . PHP_EOL;
        foreach ($data as $slug => $controllerMethod) {
            $output .= '$routes->get(\'' . $slug . '\', \'' . $controllerMethod . '\');' . PHP_EOL;
        }

        $filePath = ROOTPATH . 'app/Config/Propertiesroutes.php';

        file_put_contents($filePath, $output);
    } 
}
