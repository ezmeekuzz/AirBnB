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
                                        <h1>Edit User</h1>
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
                                                <li class="breadcrumb-item active text-primary" aria-current="page">Edit User</li>
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
                                            <h4 class="card-title"><i class="ti ti-user"></i> Users</h4>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <form id="edituser" enctype="multipart/form-data">
                                            <?php foreach($records as $row) : ?>
                                            <div class="form-group" hidden="">
                                                <label for="user_id">User Id</label>
                                                <input type="text" name="user_id" id="user_id" class="form-control" value="<?=$row['user_id'];?>" placeholder="Enter User ID">
                                            </div>
                                            <div class="form-group">
                                                <label for="firstname">First Name</label>
                                                <input type="text" name="firstname" id="firstname" class="form-control" placeholder="Enter First Name" value = "<?=$row['firstname'];?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="lastname">Last Name</label>
                                                <input type="text" name="lastname" id="lastname" class="form-control" placeholder="Enter Last Name" value = "<?=$row['lastname'];?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="emailaddress">Email Address</label>
                                                <input type="text" name="emailaddress" id="emailaddress" class="form-control" placeholder="Enter First Name" value = "<?=$row['emailaddress'];?>">
                                            </div>
                                            <div class="form-group">
                                                <label for="username">Username</label>
                                                <input type="text" name="username" id="username" class="form-control" value="<?=$row['username'];?>" placeholder="Enter Username">
                                            </div>
                                            <div class="form-group">
                                                <label for="password">Password</label>
                                                <input type="password" name="password" id="password" class="form-control" value="<?=$row['password'];?>" placeholder="Enter Password">
                                            </div>
                                            <?php endforeach; ?>
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
            <script src="<?=base_url();?>assets_admin/js/custom/admin/edituser.js"></script>
            <?=$this->include('templates/admin/footer');?>