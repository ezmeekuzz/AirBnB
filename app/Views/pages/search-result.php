<?=$this->include('templates/header');?>
<div class="container-fluid">
    <div class="row">
        <div class="jumbotron custom-jumbotron">
        <div class="background-overlay"></div>    
        <h1 class="display-4 mb-2 text-center custom-heading">Discover Your Perfect Getaway</h1>
            <div class="row mt-5 justify-content-center" id="result">
                
            </div>
        </div>
    </div>
</div>
<div class="container-fluid">   
    <div class="row mt-1 contactForm">
        <div class="col-lg-6 p-5 d-flex flex-column align-items-center">
            <div class="contactWelcomeMessage">
                <div class="mb-2">
                    <img src="assets/images/rating-svgrepo-com (9).png" alt="" />
                </div>
                <h1><span>Book Your</span> <span class="title-head">GreenMountain Adventure</span></h1>
                <h5 class="mt-3 lh-base">
                    Unlock the wonders of Southern Vermont by reserving your stay at Green Mountain Homes today. Embrace the beauty of all four seasons, from thrilling winter escapades to serene summer explorations.
                </h5>
            </div>
        </div>
        <div class="col-lg-6 p-5 d-flex flex-column align-items-center">
            <div class="card w-100">
                <div class="card-body">
                    <h5 class="card-title">
                        <i class="fas fa-comments"></i>
                        Have a Question?
                    </h5>
                    <h2 class="card-subtitle mt-2 fw-bold title-head">Get in Touch</h2>
                    <hr class="my-3">
                    <form id="sendMessage">
                        <div class="row">
                            <div class="mb-3">
                                <label for="fullname" class="form-label"><i class="fas fa-user"></i> Full Name</label>
                                <input type="text" class="form-control" name="fullname" id="fullname">
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label"><i class="fas fa-envelope"></i> Email</label>
                                <input type="email" class="form-control" name="email" id="email">
                            </div>
                            <div class="mb-3">
                                <label for="phone" class="form-label"><i class="fas fa-phone"></i> Phone</label>
                                <input type="text" class="form-control" name="phone" id="phone">
                            </div>
                            <div class="mb-3">
                                <label for="messageContent" class="form-label"><i class="fas fa-edit"></i> Message</label>
                                <textarea class="form-control" name="messageContent" id="messageContent" style="height: 150px; resize: none;"></textarea>
                            </div>
                            <div class="mb-3">
                                <button type="submit" id="messageBtn" class="btn btn-primary btn-lg w-100 rounded-0"><i class="fas fa-paper-plane"></i> Submit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="changeSearch" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-end">
                <button type="button" class="btnClose" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="custom-form">
                    <div class="custom-form-container-change-search">
                        <div class="row">
                            <div class="form-group col-lg-12 mb-4">
                                <label for="daterange">Dates</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addonDaterange"><i class="fa fa-calendar-alt"></i></span>
                                    <input type="text" name="daterange" id="daterange" class="form-control text-center" style="border-radius: 0; letter-spacing: 4px; font-style: italic;" />
                                </div>
                            </div>
                            <div class="form-group col-lg-6 mb-4">
                                <label for="adult">Adult</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addonAdult"><i class="fa fa-user"></i></span>
                                    <input type="number" min="1" value="<?=$adult;?>" name="adult" id="adult" value="0" class="form-control text-center" style="border-radius: 0;" />
                                </div>
                            </div>
                            <div class="form-group col-lg-6 mb-4">
                                <label for="children">Children</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addonChildren"><i class="fa fa-child"></i></span>
                                    <input type="number" min="1" value="<?=$children;?>" name="children" id="children" value="0" class="form-control text-center" style="border-radius: 0;" />
                                </div>
                            </div>
                            <div class="form-group col-lg-6 mb-4">
                                <label for="infant">Infant</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addonInfant"><i class="fa fa-baby"></i></span>
                                    <input type="number" min="1" value="<?=$infant;?>" name="infant" id="infant" value="0" class="form-control text-center" style="border-radius: 0;" />
                                </div>
                            </div>
                            <div class="form-group col-lg-6 mb-4">
                                <label for="pet">Pet</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addonPet"><i class="fa fa-paw"></i></span>
                                    <input type="number" min="1" value="<?=$pet;?>" name="pet" id="pet" value="0" class="form-control text-center" style="border-radius: 0;" />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary custom-button" id="bookNow">Search Availability</button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="gallery" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-end">
                <button type="button" class="btnClose" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div id="propertyImagesCarousel" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        <!-- Indicators will be dynamically added here -->
                    </div>
                    <div class="carousel-inner">
                        <!-- Images will be dynamically added here -->
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#propertyImagesCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#propertyImagesCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="priceBreakdown" data-bs-backdrop="static" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header d-flex justify-content-end">
                <button type="button" class="btnClose" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <table id="propertyPriceBreakdown" class="table table-bordered">
                    <thead>
                        <tr class="table-info">
                            <th>Item</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<script>
    var startDate = "<?= $startDate; ?>";
    var endDate = "<?= $endDate; ?>";
    var adult = "<?= $adult; ?>";
    var children = "<?= $children; ?>";
    var infant = "<?= $infant; ?>";
    var pet = "<?= $pet; ?>";
</script>
<script type="text/javascript" src="<?=base_url();?>assets/js/custom/search-result.js"></script>
<?=$this->include('templates/footer');?>