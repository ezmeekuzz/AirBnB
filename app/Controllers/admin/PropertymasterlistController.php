<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\admin\PropertiesModel;
use App\Models\admin\PropertyImagesModel;
use App\Models\admin\PropertyBannersModel;
use App\Models\admin\FeaturesModel;
use App\Models\admin\PricingModel;
use App\Models\BookingsModel;
use App\Models\ReviewsModel;

class PropertymasterlistController extends BaseController
{
    protected $uName = 'App\Models\admin\PropertiesModel';
    protected $oName = 'App\Models\admin\PropertyImagesModel';

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
            'activelink' => 'propertymasterlist'
        ];
        return view('pages/admin/propertymasterlist', $data);
    }
    // Controller method for server-side processing
    public function getData()
    {
        // Load the model
        $dModel = new PropertiesModel();

        // Get the request parameters
        $draw = $this->request->getPost('draw');
        $start = $this->request->getPost('start');
        $length = $this->request->getPost('length');
        $search = $this->request->getPost('search')['value'];

        // Get the total number of records
        $totalRecords = $dModel->countAll();

        // Get filtered records
        $filteredRecords = $dModel
            ->like('propertyname', $search)
            ->orLike('address', $search)
            // Add more columns for searching as needed
            ->limit($length, $start)
            ->findAll();

        // Prepare data for DataTables
        $data = [
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => count($filteredRecords),
            'data' => $filteredRecords,
        ];

        // Return JSON response
        return $this->response->setJSON($data);
    }
    public function delete($id)
    {
        // Delete the property and associated data from the database
        $propertyModel = new PropertiesModel();
        $imageModel = new PropertyImagesModel();
        $pricingModel = new PricingModel(); // Assuming you have a PricingModel
        $bookingModel = new BookingsModel();
        $featureModel = new FeaturesModel();
        $bannerModel = new PropertyBannersModel();
        $reviewModel = new ReviewsModel();
    
        // Find the property, its associated images, and pricing
        $property = $propertyModel->find($id);
        $images = $imageModel->where('property_id', $id)->findAll();
        $pricing = $pricingModel->where('property_id', $id)->findAll();
        $features = $featureModel->where('property_id', $id)->findAll();
        $banners = $bannerModel->where('property_id', $id)->findAll();
    
        if ($property) {
    
            // Delete the associated images from the database and actual files from the server
            foreach ($images as $image) {
                $filePath = FCPATH . $image['location'];
    
                // Check if the file exists before attempting to delete
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            $imageModel->where('property_id', $id)->delete();
    
            // Delete the associated images from the database and actual files from the server
            foreach ($features as $feature) {
                $filePath = FCPATH . $feature['icon'];
    
                // Check if the file exists before attempting to delete
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            
            $featureModel->where('property_id', $id)->delete();
    
            // Delete the associated images from the database and actual files from the server
            foreach ($banners as $banner) {
                $filePath = FCPATH . $banner['location'];
    
                // Check if the file exists before attempting to delete
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }
            
            $bannerModel->where('property_id', $id)->delete();
    
            // Delete the associated pricing from the database
            $pricingModel->where('property_id', $id)->delete();
    
            // Delete the associated reviews from the database
            $reviewModel->where('property_id', $id)->delete();
            
            // Delete the bookings from the database
            $bookingModel->where('property_id', $id)->delete();
            
            // Delete the property from the database
            $propertyModel->delete($id);

            $this->dynamicRoutes();
    
            // Return a JSON response indicating success
            return $this->response->setJSON(['status' => 'success']);
        } else {
            // Return a JSON response indicating failure (property not found)
            return $this->response->setJSON(['status' => 'error', 'message' => 'Property not found.']);
        }
    }    
    public function uploadImages()
    {
        // Ensure that this is an AJAX request
        if (!$this->request->isAJAX()) {
            // Return an error response if not an AJAX request
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request.']);
        }

        $request = $this->request;

        // Get form data
        $propertyId = $request->getPost('property_id');
        $files = $request->getFiles();

        // Specify the absolute path to your desired folder within the root directory
        $uploadPath = FCPATH . 'uploads';

        // Check if any images are uploaded
        if (empty($files['image'])) {
            // Return an error response if no images are selected
            return $this->response->setJSON(['status' => 'error', 'message' => 'No images selected.']);
        }

        // Process uploaded files
        foreach ($files['image'] as $file) {
            // Move the uploaded file to the specified folder
            $file->move($uploadPath);

            // Insert data into the database
            $data = [
                'property_id' => $propertyId,
                'location'   => 'uploads/' .$file->getName(),
                // Add other columns as needed
            ];

            // Assuming you have a model named PropertyModel
            $propertyModel = new PropertyImagesModel();

            // Insert data into the database
            if (!$propertyModel->insert($data)) {
                // Return an error response if data insertion fails
                return $this->response->setJSON(['status' => 'error', 'message' => 'Error inserting data into the database.']);
            }
        }

        // Return a success response
        return $this->response->setJSON(['status' => 'success', 'message' => 'Images uploaded successfully.']);
    }
    public function uploadBanners()
    {
        // Ensure that this is an AJAX request
        if (!$this->request->isAJAX()) {
            // Return an error response if not an AJAX request
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request.']);
        }

        $request = $this->request;

        // Get form data
        $propertyId = $request->getPost('property_id_');
        $files = $request->getFiles();

        // Specify the absolute path to your desired folder within the root directory
        $uploadPath = FCPATH . 'uploads';

        // Check if any images are uploaded
        if (empty($files['banner'])) {
            // Return an error response if no images are selected
            return $this->response->setJSON(['status' => 'error', 'message' => 'No images selected.']);
        }

        // Process uploaded files
        foreach ($files['banner'] as $file) {
            // Move the uploaded file to the specified folder
            $file->move($uploadPath);

            // Insert data into the database
            $data = [
                'property_id' => $propertyId,
                'location'   => 'uploads/' .$file->getName(),
                // Add other columns as needed
            ];

            // Assuming you have a model named PropertyModel
            $propertyModel = new PropertyBannersModel();

            // Insert data into the database
            if (!$propertyModel->insert($data)) {
                // Return an error response if data insertion fails
                return $this->response->setJSON(['status' => 'error', 'message' => 'Error inserting data into the database.']);
            }
        }

        // Return a success response
        return $this->response->setJSON(['status' => 'success', 'message' => 'Images uploaded successfully.']);
    }
    public function uploadFA()
    {
        // Ensure that this is an AJAX request
        if (!$this->request->isAJAX()) {
            // Return an error response if not an AJAX request
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request.']);
        }
    
        $request = $this->request;
    
        // Get form data
        $propertyId = $request->getPost('property_id__');
        $feature = $request->getPost('feature');
        $files = $request->getFiles();
    
        // Specify the absolute path to your desired folder within the root directory
        $uploadPath = FCPATH . 'uploads';
    
        // Check if any images are uploaded
        if (empty($files['icon'])) {
            // Return an error response if no images are selected
            return $this->response->setJSON(['status' => 'error', 'message' => 'No images selected.']);
        }
    
        // Check if feature is empty
        if (empty($feature)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Feature field is required.']);
        }
    
        // Process uploaded files
        $file = $files['icon'];
    
        // Move the uploaded file to the specified folder
        $file->move($uploadPath);
    
        // Insert data into the database
        $data = [
            'property_id' => $propertyId,
            'feature' => $feature,
            'icon'   => 'uploads/' . $file->getName(),
            // Add other columns as needed
        ];
    
        // Assuming you have a model named PropertyModel
        $propertyModel = new FeaturesModel();
    
        // Insert data into the database
        if (!$propertyModel->insert($data)) {
            $dbErrors = $propertyModel->errors(); // Get database errors
            log_message('error', 'Database Error: ' . print_r($dbErrors, true));
            // Return an error response if data insertion fails
            return $this->response->setJSON(['status' => 'error', 'message' => 'Error inserting data into the database.']);
        }
    
        // Return a success response
        return $this->response->setJSON(['status' => 'success', 'message' => 'Image uploaded successfully.']);
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
    public function propertyDetails()
    {
        // Retrieve the property ID from the GET request
        $propertyId = $this->request->getGet('propertyId');
    
        // Load the model
        $propertyModel = new PropertiesModel(); // Update namespace as needed
        $propertyImagesModel = new PropertyImagesModel(); // Update namespace as needed
        $propertyBannersModel = new PropertyBannersModel(); // Update namespace as needed
        $featuresModel = new FeaturesModel(); // Update namespace as needed
    
        // Fetch property details based on the property ID
        $propertyDetails = $propertyModel->find($propertyId);
        $propertyImages = $propertyImagesModel->where('property_id', $propertyId)->findAll();
        $propertyBanners = $propertyBannersModel->where('property_id', $propertyId)->findAll();
        $propertyFeatures = $featuresModel->where('property_id', $propertyId)->findAll();
    
        // Check if the property with the given ID exists
        if ($propertyDetails) {
            ?>
            <h1><?=$propertyDetails['propertyname'];?></h1><br/>
            <h3>Banners</h3><br/>
            <div class="row magnific-wrapper">
            <?php
            foreach($propertyBanners as $banners) {
                ?>
                <div class="col-xl-3 col-md-4 col-sm-6">
                    <div class="card card-statistics text-center">
                        <div class="card-body p-0">
                            <div class="portfolio-item">
                                <img src="<?=base_url().$banners['location'];?>" alt="gallery-img">
                                <a class="popup portfolio-img delete-banner" data-id="<?=$banners['property_banner_id'];?>" href="javascript:void(0);"><i class="fa fa-trash"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
            </div>
            <h3>Images</h3><br/>
            <div class="row magnific-wrapper">
            <?php
            foreach($propertyImages as $images) {
                ?>
                <div class="col-xl-3 col-md-4 col-sm-6">
                    <div class="card card-statistics text-center">
                        <div class="card-body p-0">
                            <div class="portfolio-item">
                                <img src="<?=base_url().$images['location'];?>" alt="gallery-img">
                                <a class="popup portfolio-img delete-image" data-id="<?=$images['property_image_id'];?>" href="javascript:void(0);"><i class="fa fa-trash"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
            </div>
            <h3>Features & Amenities</h3><br/>
            <div class="row">
            <?php
            foreach($propertyFeatures as $details) {
                ?>
                <div class="col-xl-2 col-md-3 col-sm-4">
                    <div class="card card-statistics text-center">
                        <div class="card-body p-0">
                            <div class="portfolio-item">
                                <img src="<?=base_url().$details['icon'];?>" alt="gallery-img">
                                <div class="portfolio-overlay">
                                    <h4 class="text-white"> <a href="javascript:void(0)"> <?=$details['feature'];?> </a> </h4>
                                </div>
                                <a class="popup portfolio-img delete-feature" data-id="<?=$details['feature_id'];?>" href="javascript:void(0);"><i class="fa fa-trash"></i></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
            ?>
            </div>
            <script>       
                $('.delete-banner').on('click', function() {
                    const id = $(this).data('id');
                    swal({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'No, cancel!',
                        confirmButtonClass: 'btn btn-success',
                        cancelButtonClass: 'btn btn-danger',
                        buttonsStyling: true,
                        reverseButtons: true
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                url: '/admin/propertymasterlist/deleteBanner/' + id,
                                method: 'DELETE',
                                success: function(response) {
                                    if (response.status === 'success') {
                                        // Remove the deleted row from the table
                                        $("#propertyDetails").modal("hide");
                                    }
                                }
                            });
                        }
                    });
                });  
                $('.delete-image').on('click', function() {
                    const id = $(this).data('id');
                    swal({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'No, cancel!',
                        confirmButtonClass: 'btn btn-success',
                        cancelButtonClass: 'btn btn-danger',
                        buttonsStyling: true,
                        reverseButtons: true
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                url: '/admin/propertymasterlist/deleteImage/' + id,
                                method: 'DELETE',
                                success: function(response) {
                                    if (response.status === 'success') {
                                        // Remove the deleted row from the table
                                        $("#propertyDetails").modal("hide");
                                    }
                                }
                            });
                        }
                    });
                });  
                $('.delete-feature').on('click', function() {
                    const id = $(this).data('id');
                    swal({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!',
                        cancelButtonText: 'No, cancel!',
                        confirmButtonClass: 'btn btn-success',
                        cancelButtonClass: 'btn btn-danger',
                        buttonsStyling: true,
                        reverseButtons: true
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                url: '/admin/propertymasterlist/deleteFeature/' + id,
                                method: 'DELETE',
                                success: function(response) {
                                    if (response.status === 'success') {
                                        // Remove the deleted row from the table
                                        $("#propertyDetails").modal("hide");
                                    }
                                }
                            });
                        }
                    });
                });  
            </script>
            <?php
        } else {
            // Handle the case where the property is not found
            echo 'Property not found';
        }
    }    
    public function deleteBanner($id)
    {
        // Load the model
        $propertyModel = new PropertyBannersModel(); // Update namespace as needed

        // Fetch the banner details
        $banner = $propertyModel->find($id);

        // Check if the banner exists
        if ($banner) {

            // Remove the image file from the folder
            $imagePath = FCPATH . $banner['location'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            // Delete the banner from the database
            $propertyModel->delete($id);

            // Return a success response
            return $this->response->setJSON(['status' => 'success', 'message' => 'Banner deleted successfully.']);
        } else {
            // Return an error response if the banner is not found
            return $this->response->setJSON(['status' => 'error', 'message' => 'Banner not found.']);
        }
    }
    public function deleteImage($id)
    {
        // Load the model
        $propertyModel = new PropertyImagesModel(); // Update namespace as needed

        // Fetch the banner details
        $image = $propertyModel->find($id);

        // Check if the banner exists
        if ($image) {

            // Remove the image file from the folder
            $imagePath = FCPATH . $image['location'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            // Delete the banner from the database
            $propertyModel->delete($id);

            // Return a success response
            return $this->response->setJSON(['status' => 'success', 'message' => 'Banner deleted successfully.']);
        } else {
            // Return an error response if the banner is not found
            return $this->response->setJSON(['status' => 'error', 'message' => 'Banner not found.']);
        }
    }
    public function deleteFeature($id)
    {
        // Load the model
        $featureModel = new FeaturesModel(); // Update namespace as needed

        // Fetch the banner details
        $feature = $featureModel->find($id);

        // Check if the banner exists
        if ($feature) {

            // Remove the image file from the folder
            $imagePath = FCPATH . $feature['icon'];
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }

            // Delete the banner from the database
            $featureModel->delete($id);

            // Return a success response
            return $this->response->setJSON(['status' => 'success', 'message' => 'Banner deleted successfully.']);
        } else {
            // Return an error response if the banner is not found
            return $this->response->setJSON(['status' => 'error', 'message' => 'Banner not found.']);
        }
    }
}
