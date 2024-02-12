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
                                        <h1>Messages</h1>
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
                                                <li class="breadcrumb-item active text-primary" aria-current="page">Messages</li>
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
                                            <h4 class="card-title"><i class="ti ti-comment-alt"></i> Messages</h4>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="datatable-wrapper table-responsive">
                                            <table id="messages" class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th>Full Name</th>
                                                        <th>Email</th>
                                                        <th>Phone</th>
                                                        <th>Message</th>
                                                        <th>Date</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($records as $row) : ?>
                                                    <tr>
                                                        <td><?=$row['fullname'];?></td>
                                                        <td><?=$row['email'];?></td>
                                                        <td><?=$row['phone'];?></td>
                                                        <td><?=$row['messageContent'];?></td>
                                                        <td><?=$row['messagedate'];?></td>
                                                        <td>
                                                            <a href="javascript:void(0);" class = "delete-btn" data-id = "<?=$row['message_id'];?>" style="color: red;">
                                                                <i class="ti ti-trash" style="font-size: 18px;"></i>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                    <?php endforeach;?>
                                                </tbody>
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
            <script src="<?=base_url();?>assets_admin/js/custom/admin/messages.js"></script>
            <?=$this->include('templates/admin/footer');?>