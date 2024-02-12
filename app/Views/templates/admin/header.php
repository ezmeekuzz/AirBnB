<!DOCTYPE html>
<html lang="en">


<head>
    <title><?=$title;?></title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
    <meta name="author" content="Rustom Codilan" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <link rel="icon" href="<?=base_url();?>assets/images/logoipsum-253 (7).png" type="image/png"> 
    <!-- google fonts -->
    <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
    <!-- plugin stylesheets -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.8.7/chosen.min.css" integrity="sha512-yVvxUQV0QESBt1SyZbNJMAwyKvFTLMyXSyBHDO4BG5t7k/Lw34tyqlSDlKIrIENIzCl+RVUNjmCPG+V/GMesRw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" type="text/css" href="<?=base_url();?>assets_admin/css/vendors.css" />
    <!-- app style -->
    <link rel="stylesheet" type="text/css" href="<?=base_url();?>assets_admin/css/style.css" />
    <link rel="stylesheet" type="text/css" href="<?=base_url();?>assets_admin/css/style2.css" />
    <link rel="stylesheet" type="text/css" href="https://unpkg.com/cropperjs/dist/cropper.css">
    <style type="text/css">
        .modal-lg{
            max-width: 80% !important;
        }
        img {
            display: block;
            max-width: 100%;
        }
        #modalContents #buttonMenus,
        #modalContents #uploadImageDiv,
        #modalContents #uploadBannerDiv,
        #modalContents #uploadFADiv {
            transition: margin-left 0.5s, opacity 0.5s;
        }

        #modalContents #uploadImageDiv,
        #modalContents #uploadBannerDiv,
        #modalContents #uploadFADiv {
            opacity: 0;
            margin-left: -100%;
        }
    </style>
    <script>
        var baseUrl = '<?= base_url(); ?>';
    </script>
</head>

<body>
    <!-- begin app -->
    <div class="app">
        <!-- begin app-wrap -->
        <div class="app-wrap">
            <!-- begin pre-loader -->
            <div class="loader">
                <div class="h-100 d-flex justify-content-center">
                    <div class="align-self-center">
                        <img src="<?=base_url();?>assets_admin/img/loader/loader.svg" alt="loader">
                    </div>
                </div>
            </div>
            <!-- end pre-loader -->
            <!-- begin app-header -->