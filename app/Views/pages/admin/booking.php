                <?=$this->include('templates/admin/header')?>
                <?=$this->include('templates/admin/sidebar')?>
                <!-- begin app-main -->
                <div class="app-main" id="main">
                    <!-- begin container-fluid -->
                    <div class="container-fluid">
                        <!-- begin row -->
                        <div class="row">
                            <div class="col-md-12 m-b-30">
                                <div class="d-block d-sm-flex flex-nowrap align-items-center">
                                    <div class="page-title mb-2 mb-sm-0">
                                        <h1>Booking</h1>
                                    </div>
                                    <div class="ml-auto d-flex align-items-center">
                                        <nav>
                                            <ol class="breadcrumb p-0 m-b-0">
                                                <li class="breadcrumb-item">
                                                    <a href="<?=base_url();?>admin/"><i class="ti ti-calendar"></i></a>
                                                </li>
                                                <li class="breadcrumb-item">
                                                    Booking
                                                </li>
                                                <li class="breadcrumb-item active text-primary" aria-current="page">Booking</li>
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
                                            <h4 class="card-title float-left"><i class="ti ti-user"></i> Users</h4>
                                            <div class="float-right">
                                                <div class="form-group">
                                                    <a class="btn btn-success" href="/admin/booking/exportToCsv"><i class="fa fa-file-excel-o"></i> Export</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="datatable-wrapper table-responsive">
                                            <table id="bookings" class="table table-bordered table-responsive">
                                                <thead>
                                                    <tr>
                                                        <th>Property</th>
                                                        <th>Email</th>
                                                        <th>Check In</th>
                                                        <th>Check Out</th>
                                                        <th>Adult</th>
                                                        <th>Children</th>
                                                        <th>Infant</th>
                                                        <th>Pet</th>
                                                        <th>Cleaning Fee</th>
                                                        <th>Extra Guest Fee</th>
                                                        <th>Hot Tub Fee</th>
                                                        <th>Pet Fee</th>
                                                        <th>Total Amount</th>
                                                        <th>Booking Date</th>
                                                        <th>Status</th>
                                                        <th></th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php foreach($records as $row) : ?>
                                                    <tr>
                                                        <td><?=$row['propertyname'];?></td>
                                                        <td><?=$row['emailaddress'];?></td>
                                                        <td><?=$row['checkin_date'];?></td>
                                                        <td><?=$row['checkout_date'];?></td>
                                                        <td><?=$row['adult'];?></td>
                                                        <td><?=$row['children'];?></td>
                                                        <td><?=$row['infant'];?></td>
                                                        <td><?=$row['pet'];?></td>
                                                        <td><?=$row['cleaningfee'];?></td>
                                                        <td><?=$row['extraguestfee'];?></td>
                                                        <td><?=$row['hottubfee'];?></td>
                                                        <td><?=$row['petfee'];?></td>
                                                        <td><?=$row['totalamount'];?></td>
                                                        <td><?=$row['bookingdate'];?></td>
                                                        <td><?=$row['status'];?></td>
                                                        <td>
                                                            <a href="javascript:void(0);" class = "approve-btn" data-id = "<?=$row['booking_id'];?>" style="color: blue;">
                                                                <i class="ti ti-thumb-up" style="font-size: 18px;"></i>
                                                            </a>
                                                            <a href="/admin/booking/generatePdf/<?=$row['booking_id'];?>" download target="_blank" style="color: orange;">
                                                                <i class="fa fa-file-pdf-o" style="font-size: 18px;"></i>
                                                            </a>
                                                            <a href="javascript:void(0);" class = "delete-btn" data-id = "<?=$row['booking_id'];?>" style="color: red;">
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
            <script src="<?=base_url();?>assets_admin/js/custom/admin/bookings.js"></script> 
            <?=$this->include('templates/admin/footer')?>