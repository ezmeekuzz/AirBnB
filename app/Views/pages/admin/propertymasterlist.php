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
                                        <h1>Property Masterlist</h1>
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
                                                <li class="breadcrumb-item active text-primary" aria-current="page">Property Masterlist</li>
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
                                        <div class="datatable-wrapper table-responsive">
                                            <table id="propertymasterlist" class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Property Name</th>
                                                        <th>Address</th>
                                                        <th>Cleaning Fee</th>
                                                        <th>Extra Guest</th>
                                                        <th>Hot Tub</th>
                                                        <th>Pet Fee</th>
                                                        <th>Basic Number of Guest</th>
                                                        <th>Guest Limit</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                            </table>
                                        </div>
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
            <div class="modal fade" id="propertyActionsModal" tabindex="-1" role="dialog" aria-labelledby="propertyActionsModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="propertyActionsModalLabel">Actions</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div id="modalContents">
                                <div id="buttonMenus">
                                    <a id="viewPageBtn" target="_blank" class="dropdown-item"><i class="ti ti-eye" style="font-size: 18px;"></i> View Page</a>
                                    <a id="pricingBtn" class="dropdown-item"><i class="ti ti-money" style="font-size: 18px;"></i> Pricing</a>
                                    <a href="javascript:void(0);" class="dropdown-item UploadImage"><i class="ti ti-image" style="font-size: 18px;"></i> Upload Image(s)</a>
                                    <a href="javascript:void(0);" class="dropdown-item UploadBanner"><i class="ti ti-image" style="font-size: 18px;"></i> Upload Banner(s)</a>
                                    <a href="javascript:void(0);" class="dropdown-item UploadFA"><i class="ti ti-home" style="font-size: 18px;"></i> Add Features & Amenities</a>
                                    <a id="editPropertyBtn" class="dropdown-item"><i class="ti ti-pencil" style="font-size: 18px;"></i> Edit Property</a>
                                    <a href="javascript:void(0);" class = "dropdown-item delete-btn"><i class="ti ti-trash" style="font-size: 18px;"></i> Delete Property</a>
                                </div>
                                <div id="uploadImageDiv">                               
                                    <form id="uploadImages" enctype="multipart/form-data">
                                        <div class="form-group" hidden>
                                            <label for="property_id">Property ID</label>
                                            <input type="text" class="form-control" name="property_id" id="property_id" />
                                        </div>
                                        <div class="form-group">
                                            <label for="image">Property Image(s)</label>
                                            <div class="custom-file">
                                                <label class="custom-file-label" for="image">Choose file</label>
                                                <input type="file" class="custom-file-input" id="image" name="image[]" accept="image/png, image/gif, image/jpeg, image/webp" multiple>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-info returnBtn" style="background: green !important;">Back</button>
                                        <button type="submit" class="btn btn-primary" id="submitBtn">Submit</button>
                                    </form>
                                </div>
                                <div id="uploadBannerDiv">
                                    <form id="uploadBanners" enctype="multipart/form-data">
                                        <div class="form-group" hidden>
                                            <label for="property_id_">Property ID</label>
                                            <input type="text" class="form-control" name="property_id_" id="property_id_" />
                                        </div>
                                        <div class="form-group">
                                            <label for="banner">Property Banner(s)</label>
                                            <div class="custom-file">
                                                <label class="custom-file-label" for="banner">Choose file</label>
                                                <input type="file" class="custom-file-input" id="banner" name="banner[]" accept="image/png, image/gif, image/jpeg, image/webp" multiple>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-info returnBtn" style="background: green !important;">Back</button>
                                        <button type="submit" class="btn btn-primary" id="submitBtn">Submit</button>
                                    </form>
                                </div>
                                <div id="uploadFADiv">
                                    <form id="uploadFA" enctype="multipart/form-data">
                                        <div class="form-group" hidden>
                                            <label for="property_id__">Property ID</label>
                                            <input type="text" class="form-control" name="property_id__" id="property_id__" />
                                        </div>
                                        <div class="form-group">
                                            <label for="feature">Features/Amenities</label>
                                            <input type="text" class="form-control" name="feature" id="feature" />
                                            <span class="text-danger" id="feature-error"></span>
                                        </div>
                                        <div class="form-group">
                                            <label for="icon">Image Icon(s)</label>
                                            <div class="custom-file">
                                                <label class="custom-file-label" for="icon">Choose file</label>
                                                <input type="file" class="custom-file-input" id="icon" name="icon" accept="image/png, image/gif, image/jpeg, image/webp">
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-info returnBtn" style="background: green !important;">Back</button>
                                        <button type="submit" class="btn btn-primary" id="submitBtn">Submit</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="propertyDetails" tabindex="-1" role="dialog" aria-labelledby="propertyDetailsModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="propertyDetailsModalLabel"><i class="fa fa-info-circle"></i> Property Details</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                              <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body" style="text-align: justify;">
                            <div id="displayDetails"></div>
                        </div>
                    </div>
                </div>
            </div>
            <script src="<?=base_url();?>assets_admin/js/custom/admin/propertymasterlist.js"></script>
            <?=$this->include('templates/admin/footer');?>