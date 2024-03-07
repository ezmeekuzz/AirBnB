<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\admin\PropertiesModel;
use App\Models\admin\PricingModel;
use App\Models\admin\FeaturesModel;
use App\Models\admin\PropertyImagesModel;
use App\Services\DateRangeService;
use DateTime;

class SearchResultController extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Search Result | Green Mountain Stay',
            'description' => 'Experience the epitome of mountain living at our newly remodeled 5-bedroom ski home nestled in the heart of the Green Mountains, just minutes from both Bromley and Stratton. A winter wonderland awaits with ski-in, ski-out access and daily shuttle service to the slopes, ensuring effortless enjoyment for your skiing adventures. This spacious retreat comfortably accommodates up to 12 guests, featuring two master bedrooms and three additional bedrooms with queen and double bunk beds. Whether you’re carving the slopes, snowmobiling through pristine landscapes, or exploring Manchester’s fine dining and shopping, our mountain haven provides the perfect base for all your Southern Vermont adventures. Enjoy the cozy comforts of our newly renovated property set atop the snow-laced mountains of Vermont',
            'session' => \Config\Services::session(),
            'startDate' => $_GET['startDate'],
            'endDate' => $_GET['endDate'],
            'adult' => $_GET['adult'],
            'children' => $_GET['children'],
            'infant' => $_GET['infant'],
            'pet' => $_GET['pet'],
        ];
        return view('pages/search-result', $data);
    }
    public function displayResult()
    {
        ini_set('max_execution_time', 120);
        $propertiesModel = new PropertiesModel();
        $pricingModel = new PricingModel();
        $featuresModel = new FeaturesModel();
        $properties = $propertiesModel->select('properties.*, property_banners.location')
                ->join('property_banners', 'property_banners.property_id = properties.property_id', 'left')
                ->groupBy('properties.property_id') // Group by property_id to get only one image per property
                ->findAll();
        $dateRangeService = new DateRangeService();

        $availableProperties = [];      

        $startDateObj = new DateTime($_GET['startDate']);
        $endDateObj = new DateTime($_GET['endDate']);  

        // Calculate the number of nights between the start and end dates
        $numberOfNights = $startDateObj->diff($endDateObj)->format('%a') + 1;
        
        $adult = $this->request->getGet('adult');
        $children = $this->request->getGet('children');
        $infant = $this->request->getGet('infant');
        $pet = $this->request->getGet('pet');
        $payableNumberOfGuest = $adult + $children;
    
        $extraguestFee = 0;
        $totalExtraGuestFee = 0;
        
        foreach ($properties as $property) {
            $icsUrl = $property['ics_link'];
            $formattedDateRanges = $dateRangeService->formatDateRangesBoolean($icsUrl);
    
            // Check if the provided date range is available for the property
            $isAvailable = $dateRangeService->isDateRangeAvailable($formattedDateRanges, $_GET['startDate'], $_GET['endDate']);
    
            if ($isAvailable) {
    
                $pricingDetails = $pricingModel
                    ->where('property_id', $property['property_id'])
                    ->where("date >= '{$_GET['startDate']}' AND date <= '{$_GET['endDate']}'")
                    ->findAll();

                $cleaningFee = $property['cleaningfee'];
                $hottubFee = $property['hottub'];
                $petFee = $property['petfee'] * $pet;
    
                if ($payableNumberOfGuest > $property['basic_number_of_guest']) {
                    $extraguestFee = $property['extraguest'];
                    $exceedingNumberofGuest = $payableNumberOfGuest - $property['basic_number_of_guest'];
                    $totalExtraGuestFee = $extraguestFee * $exceedingNumberofGuest * $numberOfNights;
                }
            
                $itemTotalPrice = array_sum(array_column($pricingDetails, 'price'));
            
                $exclTaxTotal = $itemTotalPrice + $totalExtraGuestFee + $cleaningFee + $hottubFee + $petFee;
                $paypalFee = $exclTaxTotal * 0.03;
                $subTotal = $exclTaxTotal + $paypalFee;
                $taxFee = $subTotal * 0.09;
                $totalAmount = str_replace(',', '', number_format($subTotal + $taxFee, 2));

                $availableProperties[] = [
                    'property_id' => $property['property_id'],
                    'guest_limit' => $property['guest_limit'],
                    'propertyname' => $property['propertyname'],
                    'address' => $property['address'],
                    'description' => $property['description'],
                    'location' => $property['location'],
                    'slug' => $property['slug'],
                    'checkOutLink' => 'check-out?checkIn=' . $_GET['startDate'] . '&checkOut=' . $_GET['endDate'] . '&slug=' . $property['slug'] 
                    . '&adults=' . $adult . '&childrens=' . $children . '&infants=' . $infant . '&pets=' . $pet . '&cleaningFee=' . $cleaningFee
                    . '&extraGuestFee=' . $totalExtraGuestFee . '&hotTubFee=' . $hottubFee . '&petFee=' . $petFee . '&property_id=' . $property['property_id']
                    . '&nightStayTotalAmount=' . $itemTotalPrice . '&paypalFee=' . $paypalFee . '&taxFee=' . $taxFee . '&totalAmount=' . $totalAmount
                ];
            }
            
        }
        
        $dateRange = date('M j, Y', strtotime($_GET['startDate'])) . ' <i class="fa fa-arrow-right"></i> ' . date('M j, Y', strtotime($_GET['endDate']));
        $dateRangeBottom = date('M j', strtotime($_GET['startDate'])) . ' &ndash; ' . date('M j', strtotime($_GET['endDate']));
        $searchCount = count($availableProperties);
        $adult = $_GET['adult'];
        $children = $_GET['children'];
        $infant = $_GET['infant'];
        $pet = $_GET['pet'];
        if($searchCount === 0) {
            ?>
            <div class="col-lg-12">
                <div class="card resultNotFound">
                    <div class="card-body">
                        <div class="col-lg-12 d-flex justify-content-between align-items-center">
                            <h5 class="resultHeader">Available Rooms</h5>
                            <div>
                                <a href="#" style="color: #1593D0; text-decoration: none;" data-bs-toggle="modal" data-bs-target="#changeSearch">
                                    <i class="fa fa-search"></i> Change Search
                                </a>
                            </div>
                        </div>
                        <label class="dateRangeLabel"><?=$dateRange;?></label>
                        <div class="numberIndicator mt-2">
                            <span class="numberCount" data-bs-toggle="tooltip" title="<?=$numberOfNights;?> Nights"> <?=$numberOfNights;?> <i class="fa fa-moon"></i></span>
                            <span class="numberCount" data-bs-toggle="tooltip" title="<?=$adult;?> Adult"> <?=$adult;?> <i class="fa fa-user"></i></span>
                            <span class="numberCount" data-bs-toggle="tooltip" title="<?=$children;?> Children"> <?=$children;?> <i class="fa fa-child"></i></span>
                            <span class="numberCount" data-bs-toggle="tooltip" title="<?=$infant;?> Infant"> <?=$infant;?> <i class="fa fa-baby"></i></span>
                            <span class="numberCount" data-bs-toggle="tooltip" title="<?=$pet;?> Pet"> <?=$pet;?> <i class="fa fa-paw"></i></span>
                        </div>
                        <div class="contentResult mt-3">
                            <h5 class="contentResultHeader">No Rooms Found...</h5>
                            <label>We don't have any rooms matching those parameters.</label>
                            <div class="filterConditionContent mt-4">
                                <h6>Let's try That Again.</h6>
                                <ul>
                                    <li>Did you select Friday and Saturday? We have 2-3 nights minimum on some weekends & require friday and saturday to be booked together, so try your search again and let's see what happens. (Email yourgreenmountainstay@gmail.com for possible exceptions).</li>
                                    <li>Did you use a discount or promo code? Sometimes those only apply to specific dates. Try taking that off your search this time.</li>
                                </ul>
                                <button data-bs-toggle="modal" data-bs-target="#changeSearch" class="btn btn-primary custom-button">Change Search</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
        else {
            ?>
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="col-lg-12 d-flex justify-content-between align-items-center">
                                    <h5 class="resultHeader">Available Rooms</h5>
                                    <div>
                                        <a href="#" style="color: #1593D0; text-decoration: none;" data-bs-toggle="modal" data-bs-target="#changeSearch">
                                            <i class="fa fa-search"></i> Change Search
                                        </a>
                                    </div>
                                </div>
                                <label class="dateRangeLabel"><?=$dateRange;?></label>
                                <div class="numberIndicator mt-2">
                                    <span class="numberCount" data-bs-toggle="tooltip" title="<?=$numberOfNights;?> Nights"> <?=$numberOfNights;?> <i class="fa fa-moon"></i></span>
                                    <span class="numberCount" data-bs-toggle="tooltip" title="<?=$adult;?> Adult"> <?=$adult;?> <i class="fa fa-user"></i></span>
                                    <span class="numberCount" data-bs-toggle="tooltip" title="<?=$children;?> Children"> <?=$children;?> <i class="fa fa-child"></i></span>
                                    <span class="numberCount" data-bs-toggle="tooltip" title="<?=$infant;?> Infant"> <?=$infant;?> <i class="fa fa-baby"></i></span>
                                    <span class="numberCount" data-bs-toggle="tooltip" title="<?=$pet;?> Pet"> <?=$pet;?> <i class="fa fa-paw"></i></span>
                                </div>
                            </div>
                            <?php
                                foreach ($availableProperties as $index => $availableProperty) {
                                    $sumOfPrices = $pricingModel->selectSum('price')
                                        ->where('date >=', $_GET['startDate'])
                                        ->where('date <=', $_GET['endDate'])
                                        ->where('property_id', $availableProperty['property_id'])
                                        ->findAll();
                                    $collapseId = 'collapseExample' . $index;
                                    $offset = 0; // Set your offset value
                                    $features = $featuresModel
                                                    ->where('property_id', $availableProperty['property_id'])
                                                    ->limit(10)
                                                    ->find();
                                ?>
                                    <div class="col-lg-6 mt-3">
                                        <div class="availableResult">
                                            <div class="mb-3">
                                                <div class="col-lg-12 d-flex justify-content-between align-items-center">
                                                    <h5 class="mt-4"><?= $availableProperty['propertyname']; ?></h5>
                                                    <div>
                                                        <a href="<?= $availableProperty['slug']; ?>" style="color: #1593D0; text-decoration: none;" data-bs-toggle="tooltip" title="More Information">
                                                            <i class="fa fa-info-circle"></i>
                                                        </a>
                                                    </div>
                                                </div>
                                                <span style="font-size: 14px;" data-bs-toggle="tooltip" title="Max: <?= $availableProperty['guest_limit']; ?> People"><?= $availableProperty['guest_limit']; ?> <i class="fa fa-user"></i></span>
                                                <?php
                                                    foreach($features as $feature) {
                                                        ?>
                                                <span style="font-size: 14px;"><img src="<?=$feature['icon'];?>" width="13" alt="<?=$feature['feature'];?>"> <?=$feature['feature'];?></span>
                                                        <?php
                                                    }
                                                ?>
                                            </div>
                                            <div class="mt-3 position-relative">
                                                <img src="<?= $availableProperty['location']; ?>" style="width: 100%;" alt="">
                                                <a href="javscript:" data-bs-toggle="modal" data-bs-target="#gallery" data-property-id="<?= $availableProperty['property_id']; ?>">
                                                    <i class="fa fa-camera position-absolute bottom-0 end-0 m-3" style="font-size: 14px; color: #000; background: #ffc107; padding: 7px;"></i>
                                                </a>
                                            </div>
                                            <div class="mt-3">
                                                <a href = "<?= $availableProperty['checkOutLink']; ?>" class="btn btn-primary custom-button" style="font-size: 12px;">Reserve</a>
                                                <span><?=$dateRangeBottom;?></span>
                                                <span class="numberCountResult" data-bs-toggle="tooltip" title="<?=$numberOfNights;?> Nights"> <?=$numberOfNights;?> <i class="fa fa-moon"></i></span>
                                                <span style="font-weight: bold;">$<?=number_format($sumOfPrices[0]['price']);?> <span style="font-size: 10px;">USD/NIGHT (Incl Tax)</span></span>
                                                <span data-bs-toggle="tooltip" title="Price Breakdown"><a href="#" data-bs-toggle="modal" data-bs-target="#priceBreakdown" data-property-id="<?= $availableProperty['property_id']; ?>"><i class="fa fa-info-circle"></i></a></span>
                                                <div class="d-flex align-items-center">
                                                    <span>&ndash; Green Mountain Stay Nightly Rate</span>
                                                    <a href="#" class="text-decoration-none" data-bs-toggle="collapse" data-bs-target="#<?= $collapseId; ?>" aria-expanded="false" aria-controls="<?= $collapseId; ?>" style="text-decoration: none; background: #fff; display: flex; align-items: center; justify-content: center; width: 24px; height: 24px; border-radius: 50%; border: 1px solid #ccc; margin-left: 5px; font-size: 12px;">
                                                        <i class="fa fa-chevron-down"></i>
                                                    </a>
                                                </div>
                                                <div class="collapse" id="<?= $collapseId; ?>">
                                                    <!-- Content to be collapsed goes here -->
                                                    <b>Description:</b> Thank you for choosing Green Mountain Stay for your stay! We give away a generous percentage of each night's stay to local nonprofits who are helping those experiencing homelessness here in Nashville.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                }
                            ?>
                        </div>
                     </div>
                </div>
            </div>
            <?php
        }
    }
    public function propertyGallery($propetyId)
    {
        $propertyImage = new PropertyImagesModel();
        $images = $propertyImage
                ->where('property_id', $propetyId)
                ->findAll();
        
        return $this->response->setJSON($images);
    }
    public function propertyPriceBreakdown($propertyId)
    {
    
        $pricingModel = new PricingModel();
        $propertiesModel = new PropertiesModel();

        // Retrieve data from the request
        $startDate = $this->request->getGet('startDate');
        $endDate = $this->request->getGet('endDate');
        $adult = $this->request->getGet('adult');
        $children = $this->request->getGet('children');
        $infant = $this->request->getGet('infant');
        $pet = $this->request->getGet('pet');
        $startDateObj = new DateTime($startDate);
        $endDateObj = new DateTime($endDate);
        $numberOfNights = $startDateObj->diff($endDateObj)->format('%a') + 1;
        $payableNumberOfGuest = $adult + $children;
    
        $pricingDetails = $pricingModel
            ->where('property_id', $propertyId)
            ->where("date >= '{$startDate}' AND date <= '{$endDate}'")
            ->findAll();
    
        $propertyDetails = $propertiesModel
            ->where('property_id', $propertyId)
            ->first(); // Use first() instead of find()
    
        $cleaningFee = $propertyDetails['cleaningfee'];
        $hottubFee = $propertyDetails['hottub'];
        $petFee = $propertyDetails['petfee'] * $pet;
    
        $extraguestFee = 0;
        $totalExtraGuestFee = 0;
    
        if ($payableNumberOfGuest > $propertyDetails['basic_number_of_guest']) {
            $extraguestFee = $propertyDetails['extraguest'];
            $exceedingNumberofGuest = $payableNumberOfGuest - $propertyDetails['basic_number_of_guest'];
            $totalExtraGuestFee = $extraguestFee * $exceedingNumberofGuest * $numberOfNights;
        }
    
        $itemTotalPrice = array_sum(array_column($pricingDetails, 'price'));
    
        $exclTaxTotal = $itemTotalPrice + $totalExtraGuestFee + $cleaningFee + $hottubFee + $petFee;
        $paypalFee = $exclTaxTotal * 0.03;
        $subTotal = $exclTaxTotal + $paypalFee;
        $taxFee = $subTotal * 0.09;
        $totalAmount = $subTotal + $taxFee;
    
        $fees = [
            'Cleaning Fee' => '$' . number_format($cleaningFee, 2),
            'Extra Guest Fee' => '$' . number_format($totalExtraGuestFee, 2),
            'Hot Tub Fee' => '$' . number_format($hottubFee, 2),
            'Pet Fee' => '$' . number_format($petFee, 2),
            'Paypal Fee' => '$' . number_format($paypalFee, 2),
            'Tax Fee' => '$' . number_format($taxFee, 2),
            'Total' => '$' . number_format($totalAmount, 2),
        ];
    
        return $this->response->setJSON(['pricing' => $pricingDetails, 'fees' => $fees]);
    }
}
