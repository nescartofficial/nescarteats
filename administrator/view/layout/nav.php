<?php
$user = isset($user) ? $user : new User();
?>
<!-- ============================================================== -->
<!-- Topbar header - style you can find in pages.scss -->
<!-- ============================================================== -->
<header class="topbar">
    <nav class="navbar top-navbar navbar-expand-md navbar-dark">
        <div class="navbar-header">
            <!-- This is for the sidebar toggle which is visible on mobile only -->
            <a class="nav-toggler waves-effect waves-light d-block d-md-none" href="javascript:void(0)"><i class="ti-menu ti-close"></i></a>
            <!-- ============================================================== -->
            <!-- Logo -->
            <!-- ============================================================== -->
            <a class="navbar-brand" href="dashboard">
                <!-- Logo icon -->
                <b class="logo-icon">
                    <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                    <!-- Dark Logo icon -->
                    <img src="../media/images/nescart-logo-main-white.png" alt="homepage" class="dark-logo img-fluid p-4 w-75" />
                    <!-- Light Logo icon -->
                    <img src="../media/images/nescart-logo-main-white.png" alt="homepage" class="light-logo img-fluid p-4 w-75" />
                </b>
                <!--End Logo icon -->
                <!-- Logo text 
                <span class="logo-text">-->
                <!-- dark Logo text 
                    <img src="media/images/logo.png" alt="homepage" class="dark-logo" />-->
                <!-- Light Logo text 
                    <img src="media/images/logo.png" class="light-logo" alt="homepage" />
                </span>-->
            </a>
            <!-- ============================================================== -->
            <!-- End Logo -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Toggle which is visible on mobile only -->
            <!-- ============================================================== -->
            <a class="topbartoggler d-block d-md-none waves-effect waves-light" href="javascript:void(0)" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><i class="ti-more"></i></a>
        </div>
        <!-- ============================================================== -->
        <!-- End Logo -->
        <!-- ============================================================== -->
        <div class="navbar-collapse collapse" id="navbarSupportedContent">
            <!-- ============================================================== -->
            <!-- toggle and nav items -->
            <!-- ============================================================== -->
            <ul class="navbar-nav float-left mr-auto">
                <li class="nav-item d-none d-md-block"><a class="nav-link sidebartoggler waves-effect waves-light" href="javascript:void(0)" data-sidebartype="mini-sidebar"><i class="mdi mdi-menu font-24"></i></a></li>

                <!-- ============================================================== -->
                <!-- Search -->
                <!-- ============================================================== -->
                <li class="nav-item search-box"> <a class="nav-link waves-effect waves-dark" href="javascript:void(0)"><i class="ti-search"></i></a>
                    <form class="app-search position-absolute">
                        <input type="text" class="form-control" placeholder="Search &amp; enter"> <a class="srh-btn"><i class="ti-close"></i></a>
                    </form>
                </li>
            </ul>
            <!-- ============================================================== -->
            <!-- Right side toggle and nav items -->
            <!-- ============================================================== -->
            <ul class="navbar-nav float-right">

                <!-- ============================================================== -->
                <!-- Comment -->
                <!-- ============================================================== -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="mdi mdi-bell font-24"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right mailbox animated bounceInDown">
                        <span class="with-arrow"><span class="bg-primary"></span></span>
                        <ul class="list-style-none">
                            <li>
                                <div class="drop-title bg-primary text-white">
                                    <h4 class="m-b-0 m-t-5">4 New</h4>
                                    <span class="font-light">Notifications</span>
                                </div>
                            </li>
                            <li>
                                <div class="message-center notifications">
                                    <!-- Message -->
                                    <a href="javascript:void(0)" class="message-item">
                                        <span class="btn btn-danger btn-circle"><i class="fa fa-link"></i></span>
                                        <div class="mail-contnet">
                                            <h5 class="message-title">Luanch Admin</h5> <span class="mail-desc">Just see the my new admin!</span> <span class="time">9:30 AM</span>
                                        </div>
                                    </a>
                                    <!-- Message -->
                                    <a href="javascript:void(0)" class="message-item">
                                        <span class="btn btn-success btn-circle"><i class="ti-calendar"></i></span>
                                        <div class="mail-contnet">
                                            <h5 class="message-title">Event today</h5> <span class="mail-desc">Just a reminder that you have event</span> <span class="time">9:10 AM</span>
                                        </div>
                                    </a>
                                    <!-- Message -->
                                    <a href="javascript:void(0)" class="message-item">
                                        <span class="btn btn-info btn-circle"><i class="ti-settings"></i></span>
                                        <div class="mail-contnet">
                                            <h5 class="message-title">Settings</h5> <span class="mail-desc">You can customize this template as you want</span> <span class="time">9:08 AM</span>
                                        </div>
                                    </a>
                                    <!-- Message -->
                                    <a href="javascript:void(0)" class="message-item">
                                        <span class="btn btn-primary btn-circle"><i class="ti-user"></i></span>
                                        <div class="mail-contnet">
                                            <h5 class="message-title">Pavan kumar</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:02 AM</span>
                                        </div>
                                    </a>
                                </div>
                            </li>
                            <li>
                                <a class="nav-link text-center m-b-5 text-dark" href="javascript:void(0);"> <strong>Check all notifications</strong> <i class="fa fa-angle-right"></i> </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <!-- ============================================================== -->
                <!-- End Comment -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Messages -->
                <!-- ============================================================== -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" id="2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="font-24 mdi mdi-comment-processing"></i>

                    </a>
                    <div class="dropdown-menu dropdown-menu-right mailbox animated bounceInDown" aria-labelledby="2">
                        <span class="with-arrow"><span class="bg-danger"></span></span>
                        <ul class="list-style-none">
                            <li>
                                <div class="drop-title text-white bg-danger">
                                    <h4 class="m-b-0 m-t-5">5 New</h4>
                                    <span class="font-light">Messages</span>
                                </div>
                            </li>
                            <li>
                                <div class="message-center message-body">
                                    <!-- Message -->
                                    <a href="javascript:void(0)" class="message-item">
                                        <span class="user-img"> <img src="includes/template/assets/images/users/1.jpg" alt="user" class="rounded-circle"> <span class="profile-status online pull-right"></span> </span>
                                        <div class="mail-contnet">
                                            <h5 class="message-title">Pavan kumar</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:30 AM</span>
                                        </div>
                                    </a>
                                    <!-- Message -->
                                    <a href="javascript:void(0)" class="message-item">
                                        <span class="user-img"> <img src="includes/template/assets/images/users/2.jpg" alt="user" class="rounded-circle"> <span class="profile-status busy pull-right"></span> </span>
                                        <div class="mail-contnet">
                                            <h5 class="message-title">Sonu Nigam</h5> <span class="mail-desc">I've sung a song! See you at</span> <span class="time">9:10 AM</span>
                                        </div>
                                    </a>
                                    <!-- Message -->
                                    <a href="javascript:void(0)" class="message-item">
                                        <span class="user-img"> <img src="includes/template/assets/images/users/3.jpg" alt="user" class="rounded-circle"> <span class="profile-status away pull-right"></span> </span>
                                        <div class="mail-contnet">
                                            <h5 class="message-title">Arijit Sinh</h5> <span class="mail-desc">I am a singer!</span> <span class="time">9:08 AM</span>
                                        </div>
                                    </a>
                                    <!-- Message -->
                                    <a href="javascript:void(0)" class="message-item">
                                        <span class="user-img"> <img src="includes/template/assets/images/users/4.jpg" alt="user" class="rounded-circle"> <span class="profile-status offline pull-right"></span> </span>
                                        <div class="mail-contnet">
                                            <h5 class="message-title">Pavan kumar</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:02 AM</span>
                                        </div>
                                    </a>
                                </div>
                            </li>
                            <li>
                                <a class="nav-link text-center link text-dark" href="javascript:void(0);"> <b>See all e-Mails</b> <i class="fa fa-angle-right"></i> </a>
                            </li>
                        </ul>
                    </div>
                </li>
                <!-- ============================================================== -->
                <!-- End Messages -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- User profile and search -->
                <!-- ============================================================== -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle text-muted waves-effect waves-dark pro-pic" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img src="includes/template/assets/images/users/1.jpg" alt="user" class="rounded-circle" width="31"></a>
                    <div class="dropdown-menu dropdown-menu-right user-dd animated flipInY">
                        <span class="with-arrow"><span class="bg-primary"></span></span>
                        <div class="d-flex no-block align-items-center p-15 bg-primary text-white m-b-10">
                            <div class=""><img src="includes/template/assets/images/users/1.jpg" alt="user" class="img-circle" width="60"></div>
                            <div class="m-l-10">
                                <h4 class="m-b-0"><?= $user->data()->username; ?></h4>
                                <p class=" m-b-0"><?= $user->data()->email; ?></p>
                            </div>
                        </div>
                        <a class="dropdown-item" href="javascript:void(0)"><i class="ti-user m-r-5 m-l-5"></i> My Profile</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="controllers/logout.php"><i class="fa fa-power-off m-r-5 m-l-5"></i> Logout</a>
                    </div>
                </li>
                <!-- ============================================================== -->
                <!-- User profile and search -->
                <!-- ============================================================== -->
            </ul>
        </div>
    </nav>
</header>
<!-- ============================================================== -->
<!-- End Topbar header -->
<!-- ============================================================== -->
<!-- ============================================================== -->
<!-- Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->
<aside class="left-sidebar">
    <!-- Sidebar scroll-->
    <div class="scroll-sidebar">
        <!-- Sidebar navigation-->
        <nav class="sidebar-nav">
            <ul id="sidebarnav">
                <li class="nav-small-cap"><i class="mdi mdi-dots-horizontal"></i> <span class="hide-menu">Personal</span></li>

                <!-- Start -->

                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="dashboard" aria-expanded="false"><i class="mdi mdi-view-dashboard"></i><span class="hide-menu">Dashboard</span></a></li>

                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="orders" aria-expanded="false"><i class="mdi mdi-cart"></i><span class="hide-menu">Enquiries</span></a></li>

                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="suppliers" aria-expanded="false"><i class="mdi mdi-account-multiple"></i><span class="hide-menu">Suppliers</span></a></li>

                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="buyers" aria-expanded="false"><i class="mdi mdi-account-multiple"></i><span class="hide-menu">Buyers</span></a></li>

                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="notifications" aria-expanded="false"><i class="mdi mdi-account-multiple"></i><span class="hide-menu">Notifications</span></a></li>

                <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-food"></i><span class="hide-menu">Products </span></a>
                    <ul aria-expanded="false" class="collapse  first-level">
                        <li class="sidebar-item"><a href="products/add" class="sidebar-link"><i class="mdi mdi-email-alert"></i><span class="hide-menu"> Add </span></a></li>
                        <li class="sidebar-item"><a href="products" class="sidebar-link"><i class="mdi mdi-email"></i><span class="hide-menu"> List </span></a></li>
                    </ul>
                </li>

                <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-food"></i><span class="hide-menu">Category </span></a>
                    <ul aria-expanded="false" class="collapse  first-level">
                        <li class="sidebar-item"><a href="categories/add" class="sidebar-link"><i class="mdi mdi-email-alert"></i><span class="hide-menu"> Add </span></a></li>
                        <li class="sidebar-item"><a href="categories" class="sidebar-link"><i class="mdi mdi-email"></i><span class="hide-menu"> List </span></a></li>
                        <li class="sidebar-item"><a href="specialcats" class="sidebar-link"><i class="mdi mdi-email"></i><span class="hide-menu"> Special </span></a></li>
                    </ul>
                </li>

                <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-food"></i><span class="hide-menu">Settings </span></a>
                    <ul aria-expanded="false" class="collapse  first-level">
                        <li class="sidebar-item"><a href="ads" class="sidebar-link"><i class="mdi mdi-email-alert"></i><span class="hide-menu"> ADS </span></a></li>
                        <li class="sidebar-item"><a href="showcases" class="sidebar-link"><i class="mdi mdi-email-alert"></i><span class="hide-menu"> Showcase </span></a></li>
                        <li class="sidebar-item"><a href="slideshows" class="sidebar-link"><i class="mdi mdi-email"></i><span class="hide-menu"> Slideshow </span></a></li>
                    </ul>
                </li>

                <li class="sidebar-item"> <a class="sidebar-link has-arrow waves-effect waves-dark" href="javascript:void(0)" aria-expanded="false"><i class="mdi mdi-food"></i><span class="hide-menu">Pages </span></a>
                    <ul aria-expanded="false" class="collapse  first-level">
                        <li class="sidebar-item"><a href="pages" class="sidebar-link"><i class="mdi mdi-email-alert"></i><span class="hide-menu"> List </span></a></li>
                        <li class="sidebar-item"><a href="faqs" class="sidebar-link"><i class="mdi mdi-email-alert"></i><span class="hide-menu"> FAQS </span></a></li>
                    </ul>
                </li>

                <!-- End  -->

                <li class="sidebar-item"> <a class="sidebar-link waves-effect waves-dark sidebar-link" href="controllers/logout.php" aria-expanded="false"><i class="mdi mdi-directions"></i><span class="hide-menu">Log Out</span></a></li>
            </ul>
        </nav>
        <!-- End Sidebar navigation -->
    </div>
    <!-- End Sidebar scroll-->
</aside>
<!-- ============================================================== -->
<!-- End Left Sidebar - style you can find in sidebar.scss  -->
<!-- ============================================================== -->

<!-- ============================================================== -->
<!-- Page wrapper  -->
<!-- ============================================================== -->
<div class="page-wrapper">
    <!-- ============================================================== -->
    <!-- Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->
    <div class="page-breadcrumb">
        <div class="row">
            <div class="col-5 align-self-center">
                <h4 class="page-title">Dashboard</h4>
                <div class="d-flex align-items-center">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <?php if (Input::get('page')) { ?>
                                <li class="breadcrumb-item active" aria-current="page"><a href="<?= Input::get('page') ?>"><?= ucfirst(Input::get('page')) ?></a></li>
                            <?php } ?>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- End Bread crumb and right sidebar toggle -->
    <!-- ============================================================== -->

    <?php
    Alerts::displayError();
    Alerts::displaySuccess();
    ?>