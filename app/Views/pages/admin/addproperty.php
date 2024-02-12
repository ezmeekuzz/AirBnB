                <?=$this->include('templates/admin/header');?>
                <?=$this->include('templates/admin/sidebar');?>
                <!-- begin app-main -->
                <div class="app-main" id="main">
                    <!-- begin container-fluid -->
                    <div class="container-fluid">
                        <!-- begin row -->
                        <div class="row">
                            <div class="col-md-12 m-b-30">
                                <div class="d-block d-sm-flex flex-nowrap align-items-center">
                                    <div class="page-title mb-2 mb-sm-0">
                                        <h1>Add Property</h1>
                                    </div>
                                    <div class="ml-auto d-flex align-items-center">
                                        <nav>
                                            <ol class="breadcrumb p-0 m-b-0">
                                                <li class="breadcrumb-item">
                                                    <a href="<?=base_url();?>admin/"><i class="ti ti-home"></i></a>
                                                </li>
                                                <li class="breadcrumb-item">
                                                    Dashboard
                                                </li>
                                                <li class="breadcrumb-item active text-primary" aria-current="page">Add Property</li>
                                            </ol>
                                        </nav>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- begin row -->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card card-statistics">
                                    <div class="card-header">
                                        <div class="card-heading">
                                            <h4 class="card-title"><i class="ti ti-home"></i> Properties</h4>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <form id="addproperty" enctype="multipart/form-data">
                                            <div class="form-group">
                                                <label for="propertyname">Property Name</label>
                                                <input type="text" name="propertyname" id="propertyname" class="form-control" placeholder="Enter Property Name">
                                            </div>
                                            <div class="form-group">
                                                <label for="description">Description</label>
                                                <textarea class="form-control" name="description" id="description" placeholder="Enter description"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label for="address">Address</label>
                                                <input type="text" name="address" id="address" class="form-control" placeholder="Enter Address">
                                            </div>
                                            <div class="form-group">
                                                <label for="cleaningfee">Cleaning Fee</label>
                                                <input type="text" name="cleaningfee" id="cleaningfee" class="form-control" placeholder="Enter Cleaning Fee">
                                            </div>
                                            <div class="form-group">
                                                <label for="extraguest">Extra Guest</label>
                                                <input type="text" name="extraguest" id="extraguest" class="form-control" placeholder="Enter Extra Guest">
                                            </div>
                                            <div class="form-group">
                                                <label for="hottub">Hot Tub</label>
                                                <input type="text" name="hottub" id="hottub" class="form-control" placeholder="Enter Hot Tub">
                                            </div>
                                            <div class="form-group">
                                                <label for="petfee">Pet Fee</label>
                                                <input type="text" name="petfee" id="petfee" class="form-control" placeholder="Enter Pet Fee">
                                            </div>
                                            <div class="form-group">
                                                <label for="basic_number_of_guest">Basic Number of Guest</label>
                                                <input type="text" name="basic_number_of_guest" id="basic_number_of_guest" class="form-control" placeholder="Basic Number of Guest">
                                            </div>
                                            <div class="form-group">
                                                <label for="guest_limit">Guest Limit</label>
                                                <input type="text" name="guest_limit" id="guest_limit" class="form-control" placeholder="Enter Guest Limit">
                                            </div>
                                            <div class="form-group">
                                                <label for="ics_link">ICS Link (iCalendar)</label>
                                                <input type="text" name="ics_link" id="ics_link" class="form-control" placeholder="Enter ICS Link">
                                            </div>
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- end row -->
                    </div>
                    <!-- end container-fluid -->
                </div>
                <!-- end app-main -->
            </div>
            <!-- end app-container -->
            <script src="<?=base_url();?>assets_admin/js/custom/admin/addproperty.js"></script>
            <?=$this->include('templates/admin/footer');?>