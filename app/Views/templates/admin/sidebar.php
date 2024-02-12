
            <header class="app-header top-bar">
                <!-- begin navbar -->
                <nav class="navbar navbar-expand-md">         
                    <!-- begin navbar-header -->
                    <div class="navbar-header d-flex align-items-center">
                        <a href="javascript:void:(0)" class="mobile-toggle">
                            <i class="ti ti-align-right"></i>
                        </a>
                        <a class="navbar-brand" href="<?=base_url();?>admin/">
                            <img src="<?=base_url();?>assets_admin/img/logo.png" class="img-fluid logo-desktop" alt="logo" />
                            <img src="<?=base_url();?>assets_admin/img/logo.png" class="img-fluid logo-mobile" alt="logo" />
                        </a>
                    </div>
                    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                        <i class="ti ti-align-left"></i>
                    </button>
                    <!-- end navbar-header -->
                    <!-- begin navigation -->
                    <!-- end navigation -->
                </nav>
                <!-- end navbar -->
            </header>
            <!-- end app-header -->
            <!-- begin app-container -->
            <div class="app-container">
                <!-- begin app-nabar -->
                <aside class="app-navbar">
                    <!-- begin sidebar-nav -->
                    <div class="sidebar-nav scrollbar scroll_light">
                        <ul class="metismenu " id="sidebarNav">
                            <li class="nav-static-title">Personal</li>
                            <li <?php if($activelink == 'booking') { echo 'class="active"'; } ?>>
                                <a href="<?=base_url()?>admin/booking" aria-expanded="false">
                                    <i class="nav-icon ti ti-calendar"></i>
                                    <span class="nav-title">Bookings</span>
                                </a>
                            </li>
                            <li class="nav-static-title">Users</li>
                            <li <?php if($activelink == 'adduser' || $activelink == 'usermasterlist' || $activelink == 'registeredcandidatelist') { echo 'class="active"'; } ?>>
                                <a class="has-arrow" href="javascript:void(0)" aria-expanded="false">
                                    <i class="nav-icon ti ti-user"></i>
                                    <span class="nav-title">Users</span>
                                </a>
                                <ul aria-expanded="false" class="custom-style">
                                    <li <?php if($activelink == 'adduser' || $activelink == 'usermasterlist') { echo 'class="active"'; } ?>>
                                        <a class="has-arrow" href="javascript: void(0);">Administrator</a>
                                        <ul aria-expanded="false" class="custom-style">
                                            <li <?php if($activelink == 'adduser') { echo 'class="active"'; } ?>> <a href='<?=base_url();?>admin/add-user'>Add User</a> </li>
                                            <li <?php if($activelink == 'usermasterlist') { echo 'class="active"'; } ?>> <a href='<?=base_url();?>admin/user-masterlist'>User Masterlist</a> </li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <li class="nav-static-title">Properties</li>
                            <li <?php if($activelink == 'addproperty' || $activelink == 'propertymasterlist') { echo 'class="active"'; } ?>>
                                <a class="has-arrow" href="javascript:void(0)" aria-expanded="false">
                                    <i class="nav-icon ti ti-home"></i>
                                    <span class="nav-title">Properties</span>
                                </a>
                                <ul aria-expanded="false" class="custom-style">
                                    <li <?php if($activelink == 'addproperty') { echo 'class="active"'; } ?>> <a href='<?=base_url();?>admin/add-property'>Add Property</a> </li>
                                    <li <?php if($activelink == 'propertymasterlist') { echo 'class="active"'; } ?>> <a href='<?=base_url();?>admin/property-masterlist'>Property Masterlist</a> </li>
                                </ul>
                            </li>
                            <li class="nav-static-title">Reviews</li>
                            <li <?php if($activelink == 'reviews' || $activelink == 'reviewsforapproval') { echo 'class="active"'; } ?>>
                                <a class="has-arrow" href="javascript:void(0)" aria-expanded="false">
                                    <i class="nav-icon ti ti-star"></i>
                                    <span class="nav-title">Reviews</span>
                                </a>
                                <ul aria-expanded="false" class="custom-style">
                                    <li <?php if($activelink == 'reviews') { echo 'class="active"'; } ?>> <a href='<?=base_url();?>admin/reviews'>Reviews</a> </li>
                                    <li <?php if($activelink == 'reviewsforapproval') { echo 'class="active"'; } ?>> <a href='<?=base_url();?>admin/reviews-for-approval'>Reviews For Approval</a> </li>
                                </ul>
                            </li>
                            <li class="nav-static-title">Messages</li>
                            <li <?php if($activelink == 'messages') { echo 'class="active"'; } ?>>
                                <a href="<?=base_url()?>admin/messages" aria-expanded="false">
                                    <i class="nav-icon ti ti-comment-alt"></i>
                                    <span class="nav-title">Messages</span>
                                </a>
                            </li>
                            <li class="nav-static-title">Logout</li>
                            <li>
                                <a href="<?=base_url()?>admin/logout" aria-expanded="false">
                                    <i class="nav-icon ti ti-power-off"></i>
                                    <span class="nav-title">Logout</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <!-- end sidebar-nav -->
                </aside>
                <!-- end app-navbar -->
                <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js" integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>