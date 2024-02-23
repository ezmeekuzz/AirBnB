<?=$this->include('templates/header');?>
<div class="container mt-5 mb-5">
    <h1 class="title-head m-3"><i class="fas fa-house-user"></i> <?=$propertyname;?></h1>
    <div class="row">
        <div class="col-lg-6 col-md-12 mb-4 mb-lg-0">
            <a href="<?= base_url($banners[0]['location']); ?>" data-lightbox="images" data-title="Image">
                <img src="<?= base_url($banners[0]['location']); ?>" class="w-100 shadow-1-strong rounded mb-4 full-height-image" />
            </a>
        </div>
        <div class="col-lg-6 mb-4 mb-lg-0">
            <div class="row">
            <?php foreach ($banners as $index => $banner) : ?>
                <?php if ($index !== 0) : ?>
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <a href="<?= base_url($banner['location']); ?>" data-lightbox="images" data-title="Image <?= $index + 1 ?>">
                        <img src="<?= base_url($banner['location']); ?>" class="w-100 shadow-1-strong rounded mb-4" />
                    </a>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="row mt-1">
        <div class="col-lg-12 mb-4 mb-lg-0">
            <button id="openModalBtn" data-bs-toggle="modal" data-bs-target="#imagesModal" class="btn btn-primary float-end"><i class="fas fa-image"></i> See All <?= count($images); ?></button>
            <div class="modal fade" id="imagesModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div id="imagesCarousel" class="carousel slide fixed-height-carousel" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    <?php foreach ($images as $index => $lists) : ?>
                                        <div class="carousel-item<?= $index === 0 ? ' active' : ''; ?>">
                                            <img src="<?= base_url() . $lists['location']; ?>" class="d-block w-100" alt="">
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#imagesCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#imagesCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                            <div id="miniCarousel" class="carousel slide mt-3" data-bs-ride="carousel">
                                <div class="carousel-inner">
                                    <?php $chunked_images = array_chunk($images, 5); ?>
                                    <?php foreach ($chunked_images as $chunk_index => $chunk) : ?>
                                        <div class="carousel-item<?= $chunk_index === 0 ? ' active' : ''; ?>">
                                            <div class="d-flex">
                                                <?php foreach ($chunk as $index => $lists) : ?>
                                                    <div class="mini-thumbnail-container">
                                                        <img src="<?= base_url() . $lists['location']; ?>" class="d-block mini-thumbnail" alt="" data-bs-target="#imagesCarousel" data-bs-slide-to="<?= $index + $chunk_index * 5; ?>">
                                                    </div>
                                                <?php endforeach; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <button class="carousel-control-prev" type="button" data-bs-target="#miniCarousel" data-bs-slide="prev">
                                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Previous</span>
                                </button>
                                <button class="carousel-control-next" type="button" data-bs-target="#miniCarousel" data-bs-slide="next">
                                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                    <span class="visually-hidden">Next</span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-1">
        <div class="col-lg-8">
            <h1 class="title-head mt-3"><i class="fas fa-calendar-alt"></i> Select Check In Date</h1>
            <h5 class="title-head mt-3 mb-3">Add your travel dates for exact pricing</h5>
            <div id="dateRangePicker"></div>
        </div>
        <div class="col-lg-4">
            <h5 class="title-head mt-3 mb-3"><i class="fas fa-money-bill-wave-alt"></i>Total Amount</h5>
            <h1 class="title-head mt-3"><span id="totalAmount">$0.00</span></h1>
            <h6 class="mb-3 alert alert-info">Check In : <span id="checkInLabel"><?=date('m-d-Y');?></span></h6>
            <h6 class="mb-4 alert alert-info">Check Out : <span id="checkOutLabel"><?=date('m-d-Y');?></span></h6>
            <div class="dropdown guest-details">
                <button class="btn btn-secondary dropdown-toggle w-100" type="button" id="dropdownMenuButton" data-bs-display="static" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="fas fa-cogs"></i> Select Guest Option
                </button>
                <ul class="dropdown-menu w-100 p-3" aria-labelledby="dropdownMenuButton">
                    <li>
                        <div class="d-flex justify-content-between align-items-center option-row">
                            <div class="option-label">
                                <h5 class="mb-0">Adults</h5>
                                <small class="text-muted">Age 13+</small>
                            </div>
                            <div class="number-counter">
                                <button type="button" class="decrement">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" class="counter" id="adults" value="0" readonly />
                                <button type="button" class="increment">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="d-flex justify-content-between align-items-center option-row">
                            <div class="option-label">
                                <h5 class="mb-0">Children</h5>
                                <small class="text-muted">Age 2-12</small>
                            </div>
                            <div class="number-counter">
                                <button type="button" class="decrement">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" class="counter" id="childrens" value="0" readonly />
                                <button type="button" class="increment">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="d-flex justify-content-between align-items-center option-row">
                            <div class="option-label">
                                <h5 class="mb-0">Infants</h5>
                                <small class="text-muted">Under 2</small>
                            </div>
                            <div class="number-counter">
                                <button type="button" class="decrement">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" class="counter" id="infants" value="0" readonly />
                                <button type="button" class="increment">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="d-flex justify-content-between align-items-center option-row">
                            <div class="option-label">
                                <h5 class="mb-0">Pets</h5>
                                <small class="text-muted">Bringing a Service Animal?</small>
                            </div>
                            <div class="number-counter">
                                <button type="button" class="decrement">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <input type="number" class="counter" id="pets" value="0" readonly />
                                <button type="button" class="increment">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                    </li>
                    <li>
                        <label>If you're bringing more than 2 pets, please let your host know.</label>
                    </li>
                    <li>
                        <div>
                            <button type="button" class="btn btn-primary done-button" disabled>Done</button>
                        </div>
                    </li>
                </ul>
            </div>
            <button type="button" class="btn btn-primary mt-4 w-100 reservedBtn" disabled><i class="fas fa-pencil-alt"></i> Book a Reservation</button>
            <div class="totalBreakDown">
                <h4 class="title-head mt-3 text-center">You won't be charged yet</h4>

                <div class="listObtained mt-2">
                    <div class="row">
                        <div class="col-6">
                            <h6><i class="fas fa-broom"></i> Cleaning Fee</h6>
                        </div>
                        <div class="col-6 text-end">
                            <h6 id="cleaningFeeLabel">$0.00</h6>
                        </div>
                    </div>
                </div>

                <div class="listObtained mt-2">
                    <div class="row">
                        <div class="col-6">
                            <h6><i class="fas fa-users"></i> Extra Guest Fee</h6>
                        </div>
                        <div class="col-6 text-end">
                            <h6 id="extraGuestFeeLabel">$0.00</h6>
                        </div>
                    </div>
                </div>

                <div class="listObtained mt-2">
                    <div class="row">
                        <div class="col-6">
                            <h6><i class="fas fa-hot-tub"></i> Hot Tub Fee</h6>
                        </div>
                        <div class="col-6 text-end">
                            <h6 id="hotTubFeeLabel">$0.00</h6>
                        </div>
                    </div>
                </div>

                <div class="listObtained mt-2">
                    <div class="row">
                        <div class="col-6">
                            <h6><i class="fas fa-dog"></i> Pet Fee</h6>
                        </div>
                        <div class="col-6 text-end">
                            <h6 id="petFeeLabel">$0.00</h6>
                        </div>
                    </div>
                </div>

                <div class="listObtained mt-2">
                    <div class="row">
                        <div class="col-6">
                            <h6><i class="fab fa-paypal"></i> PayPal Fee (3%)</h6>
                        </div>
                        <div class="col-6 text-end">
                            <h6 id="paypalFee">$0.00</h6>
                        </div>
                    </div>
                </div>

                <div class="listObtained mt-2">
                    <div class="row">
                        <div class="col-6">
                            <h6><i class="fas fa-coins"></i> VT Room Tax Fee (9%)</h6>
                        </div>
                        <div class="col-6 text-end">
                            <h6 id="taxFee">$0.00</h6>
                        </div>
                    </div>
                </div>

                <hr class="my-4">
                <div class="totalBeforeTax mt-2">
                    <div class="row">
                        <div class="title-head col-6">
                            <h4>Total w/ Tax(es)</h4>
                        </div>
                        <div class="title-head col-6 text-end">
                            <h4><span id="overAllTotalAmount">$0.00</span></h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row mt-1">
        <div class="col-lg-8">
            <h1 class="title-head mt-5">
                <i class="fas fa-map-pin"></i> Address
            </h1>
            <h5 class="mt-3"><?=$address;?></h5>
            <div style="width: 100%; height: 400px" class="place mt-3"></div>
        </div>
    </div>
    <div class="row mt-1">
        <div class="col-lg-8">
            <h1 class="title-head mt-5">
                <i class="fas fa-house-user"></i> Other Rental Properties
            </h1>
            <div id="carouselExampleControls" class="carousel slide mt-3" data-bs-ride="carousel">
                <div class="carousel-indicators">
                    <?php foreach ($otherRentals as $key => $property): ?>
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="<?= $key ?>" class="<?= $key === 0 ? 'active' : ''; ?>" aria-label="<?= $property['propertyname']; ?>"></button>
                    <?php endforeach; ?>
                </div>
                <div class="carousel-inner">
                    <?php foreach ($otherRentals as $key => $property): ?>
                        <div class="carousel-item <?= $key === 0 ? 'active' : ''; ?>">
                            <h3 class="mb-3"><?= $property['propertyname']; ?></h3>
                            <img src="<?= $property['location']; ?>" class="d-block w-100" alt="<?= $property['propertyname']; ?>">
                            <div class="carousel-caption d-none d-md-block">
                                <h5><?= $property['propertyname']; ?></h5>
                                <a href="<?= base_url() . $property['slug']; ?>" class="btn btn-warning"><i class="fas fa-external-link-alt"></i> See Property</a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Next</span>
                </button>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid mt-5">   
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
<script>
    var address = '<?=$address;?>';
    var property_id = '<?=$property_id;?>';
    var slug = '<?=$slug;?>';
</script>
<script type="text/javascript" src="<?=base_url();?>assets/js/custom/properties.js"></script>
<?=$this->include('templates/footer');?>