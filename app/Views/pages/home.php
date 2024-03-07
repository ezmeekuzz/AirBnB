<?=$this->include('templates/header');?>
<div class="container-fluid">
    <div class="row">
        <div class="jumbotron custom-jumbotron">
        <div class="background-overlay"></div>    
        <h1 class="display-4 mb-2 text-center custom-heading">Discover Your Perfect Getaway</h1>
            <div class="row justify-content-center">
                <div class="col-lg-6 custom-form">
                    <div class="custom-form-container">
                        <div class="row">
                            <div class="form-group col-lg-12 mb-4">
                                <label for="daterange" style="color: #fff;">Dates</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addonDaterange"><i class="fa fa-calendar-alt"></i></span>
                                    <input type="text" name="daterange" id="daterange" class="form-control text-center" style="border-radius: 0; letter-spacing: 4px; font-style: italic;" />
                                </div>
                            </div>
                            <div class="form-group col-lg-6 mb-4">
                                <label for="adult" style="color: #fff;">Adult</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addonAdult"><i class="fa fa-user"></i></span>
                                    <input type="number" min="1" name="adult" id="adult" value="0" class="form-control text-center" style="border-radius: 0;" />
                                </div>
                            </div>
                            <div class="form-group col-lg-6 mb-4">
                                <label for="children" style="color: #fff;">Children</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addonChildren"><i class="fa fa-child"></i></span>
                                    <input type="number" min="1" name="children" id="children" value="0" class="form-control text-center" style="border-radius: 0;" />
                                </div>
                            </div>
                            <div class="form-group col-lg-6 mb-4">
                                <label for="infant" style="color: #fff;">Infant</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addonInfant"><i class="fa fa-baby"></i></span>
                                    <input type="number" min="1" name="infant" id="infant" value="0" class="form-control text-center" style="border-radius: 0;" />
                                </div>
                            </div>
                            <div class="form-group col-lg-6 mb-4">
                                <label for="pet" style="color: #fff;">Pet</label>
                                <div class="input-group mb-3">
                                    <span class="input-group-text" id="basic-addonPet"><i class="fa fa-paw"></i></span>
                                    <input type="number" min="1" name="pet" id="pet" value="0" class="form-control text-center" style="border-radius: 0;" />
                                </div>
                            </div>
                        </div>
                        <button class="btn btn-primary custom-button" id="bookNow">Book Now <i class="fa fa-edit"></i></button>
                    </div>
                </div>
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
<div id="result"></div>
<script type="text/javascript" src="<?=base_url();?>assets/js/custom/home.js"></script>
<?=$this->include('templates/footer');?>