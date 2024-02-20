<?=$this->include('templates/header');?>
<div class="container mt-5 mb-5">
    <h1 class="title-head m-3"><i class="fas fa-book"></i> Request to Book</h1>
    <div class="row">
        <div class="col-lg-7">
            <div>
                <h2 class="title-head m-3"><i class="fas fa-car"></i> Your Trip</h2>
                <label class="m-3 mt-1">Dates: <span id="checkInDate"></span> - <span id="checkOutDate"></span></label>
                <hr class="m-3 my-1">
            </div>
            <div>
                <h2 class="title-head m-3"><i class="fas fa-shield-alt"></i> Cancellation Policy</h2>
                <label class="m-3 mt-1">Free cancellation for 48 hours. Cancel before Oct 17 for a partial refund. <a href="#">Learn more</a></label>
                <hr class="m-3 my-1">
            </div>
            <div>
                <h2 class="title-head m-3"><i class="fas fa-file-alt"></i> Ground Rules</h2>
                <label class="m-3 mt-1">We ask every guest to remember a few simple things about what makes a great guest.</label>
                <ul>
                    <li>Follow the rules</li>
                    <li>Treat your host's home like your own</li>
                </ul>
                <hr class="m-3 my-1">
            </div>
            <div>
                <label class="m-3 mt-1">Your reservation won't be confirmed until the Host accepts your request <b>(within 24 hours)</b>. You won't be charged until then.</label>
                <hr class="m-3 mb-6">
            </div>
            <!--<div>
                <h1 class="title-head m-3"><i class="fas fa-lock"></i> Login or Sign Up to Book</h1>
                <h5 class="m-3"><i class="fas fa-envelope"></i> Email Address</h5>
                <input type="email" class="form-control m-3" name="emailaddress" id="emailaddress" />
                <button class="btn btn-primary m-3 w-100"><i class="fas fa-paper-plane"></i> Submit</button>
            </div>-->
        </div>
        <div class="col-lg-5"> 
            <div class="card w-100">
                <div class="card-body">
                    <div>
                        <div class="row">
                            <div class="col-lg-6">
                                <img src="<?=base_url() . $image['location'];?>" class="mb-2" style="width: 100%; border-radius: 5px;" alt="" />
                            </div>
                            <div class="col-lg-6">
                                <h4><i class="fas fa-building"></i> <?=$properties['propertyname'];?></h4>
                                <h6 class="title-head"><i class="fas fa-map-pin"></i> <?=$properties['address'];?></h6>
                            </div>
                        </div>
                        <hr class="my-4">
                    </div>
                    <div>
                        <div class="row">
                            <div class="col-lg-12">
                                <h4 class="title-head">Price Details</h4>
                            </div>
                            <div class="col-lg-12">
                                <label class="mb-3 text-start float-start h6 fw-bold">Nights (x<span id="nightStay"></span>)</label>
                                <label class="mb-3 text-end float-end h6 fw-bold">$<span id="nightStayTotalAmountLabel"></span></label>
                            </div>
                            <div class="col-lg-12">
                                <label class="mb-3 text-start float-start h6 fw-bold">Cleaning Fee</label>
                                <label class="mb-3 text-end float-end h6 fw-bold">$<span id="cleaningFeeLabel"></span></label>
                            </div>
                            <div class="col-lg-12">
                                <label class="mb-3 text-start float-start h6 fw-bold">Extra Guest Fee</label>
                                <label class="mb-3 text-end float-end h6 fw-bold">$<span id="extraGuestFeeLabel"></span></label>
                            </div>
                            <div class="col-lg-12">
                                <label class="mb-3 text-start float-start h6 fw-bold">Hot Tub Fee</label>
                                <label class="mb-3 text-end float-end h6 fw-bold">$<span id="hotTubFeeLabel"></span></label>
                            </div>
                            <div class="col-lg-12">
                                <label class="mb-3 text-start float-start h6 fw-bold">Pet Fee</label>
                                <label class="mb-3 text-end float-end h6 fw-bold">$<span id="petFeeLabel"></span></label>
                            </div>
                        </div>
                        <hr class="my-2 mb-4 mt-4">
                    </div>
                    <div>
                        <div class="row">
                            <div class="col-lg-12">
                                <h4 class="title-head mb-3 float-start">Total Before Taxes</h4>
                                <h4 class="mb-3 float-end text-end">$<span id="totalAmountLabel"></span></h4>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div id="paypalButton" class="mb-3 form-group"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="<?=base_url();?>assets/js/custom/checkout.js"></script>
<?=$this->include('templates/footer');?>