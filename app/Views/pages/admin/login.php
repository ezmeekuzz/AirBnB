            <?=$this->include('templates/admin/header');?>
            <!--start login contant-->
            <div class="app-contant">
                <div class="bg-white">
                    <div class="container-fluid p-0">
                        <div class="row no-gutters">
                            <div class="col-sm-6 col-lg-5 col-xxl-3  align-self-center order-2 order-sm-1">
                                <div class="d-flex align-items-center h-100-vh">
                                    <div class="login p-50">
                                        <p>Welcome back, please login to your account.</p>
                                        <form method="POST" action="/admin/loginfunc" class="mt-3 mt-sm-5">
                                            <?php if (session()->has('error')) : ?>
                                                <center>
                                                    <div class="alert alert-danger">
                                                        <?= session('error') ?>
                                                    </div>
                                                </center><br>
                                            <?php endif; ?>
                                            <div class="row">
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label class="control-label">User Name*</label>
                                                        <input type="text" name="username" id="username" class="form-control" placeholder="Username" />
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label class="control-label">Password*</label>
                                                        <input type="password" name="password" id="password" class="form-control" placeholder="Password" />
                                                    </div>
                                                </div>
                                                <div class="col-12">
                                                    <div class="d-block d-sm-flex  align-items-center">
                                                        <a href="<?=base_url();?>admin/forgot-password" class="ml-auto">Forgot Password ?</a>
                                                    </div>
                                                </div>
                                                <div class="col-12 mt-3" hidden>
                                                    <button class="btn btn-primary" type="submit">Sign In</button>
                                                </div>
                                                <div class="col-12 mt-3">
                                                    <button type="submit" class="btn btn-primary text-uppercase">Sign In</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6 col-xxl-9 col-lg-7 bg-gradient o-hidden order-1 order-sm-2">
                                <div class="row align-items-center h-100">
                                    <div class="col-7 mx-auto ">
                                        <img class="img-fluid" src="<?=base_url();?>assets_admin/img/bg/login.svg" alt="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end login contant-->
            <?=$this->include('templates/admin/footer');?>
