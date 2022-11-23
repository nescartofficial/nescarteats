<?php
$User = new User();
?>
<header class="container-fluid hero">
    <div class="container">
        <div class="row min-vh-100 align-items-center">
            <div class="col-lg-4">
                <h1 class="fs-heading"><span class="text-accent">Delicious Meals</span> from Your <span class="text-accent">Favorite</span> Restaurants:</h1>

                <img src="assets/images/Hero Image.png" alt="Nescart Eat" class="img-fluid my-4 d-lg-none">

                <div class="hstack gap-4 align-items-center mb-5 my-lg-4">
                    <img src="assets/images/element/Nescart Eats Topper.png" alt="Nescart Eat" class="img-fluid icon">
                    <p class="mb-0 fs-5 fw-bold">When you are too busy to cook, we are just a click away!</p>
                </div>
                
                    <div class="text-center text-lg-start">
                        <a href="find-vendors" class="btn d-block d-lg-inline-block me-md-5 mb-4 mb-lg-0">
                            Find Food Vendors</a>
                            
                        <?php if(!$User->isLoggedIn()){ ?>
                            <a href="<?= VENDOR_URL ?>">Become a Vendor</a>
                        <?php } ?>
                    </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block">
                <img src="assets/images/Hero Image.png" alt="Nescart Eat" class="img-fluid">
            </div>
            <div class="col-lg-3 hero-features">
                <div class="hstack align-items-center gap-4 mb-6">
                    <img src="assets/icons/Top Restaurants.svg" alt="Nescart Eat" class="img-fluid icon">
                    <div class="">
                        <h4 class="fw-bold">Top Restaurants</h4>
                        <p class="mb-0">All your favourite restaurants in one place</p>
                    </div>
                </div>
                <div class="hstack align-items-center gap-4 mb-6">
                    <img src="assets/icons/Fast Delivery.svg" alt="Nescart Eat" class="img-fluid icon">
                    <div class="">
                        <h4 class="fw-bold">Fast Delivery</h4>
                        <p class="mb-0">Your food arrives within 30 mins.</p>
                    </div>
                </div>
                <div class="hstack align-items-center gap-4">
                    <img src="assets/icons/Delicious.svg" alt="Nescart Eat" class="img-fluid icon">
                    <div class="">
                        <h4 class="fw-bold">Fresh Always</h4>
                        <p class="mb-0">Enjoy your meals fresh, crispy & hot</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>


<section class="container-fluid download site-section-b">
    <div class="container">
        <div class="card br-lg border-0">
            <div class="d-none d-lg-flex row align-items-end justify-content-end card-body position-relative">
                <img src="assets/images/Mobile App.png" alt="Nescart Eat" class="img-fluid download-mobile">
                <img src="assets/images/Pizza.png" alt="Nescart Eat" class="img-fluid download-pizza">
                <div class="col-lg-4">
                    <h3 class="mb-3 mb-3">Download Our Mobile App</h3>
                    <img src="assets/images/App users.png" alt="Nescart Eat" class="img-fluid">
                </div>
                <div class="col-lg-3 offset-lg-1">
                    <a href="" class="d-block mb-3"><img src="assets/images/Google Play.png" alt="" class="img-fluid"></a>
                    <a href="" class="d-block"><img src="assets/images/App Store.png" alt="" class="img-fluid"></a>
                </div>
            </div>

            <div class="card-body d-lg-none">
                <img src="assets/images/Pizza.png" alt="Nescart Eat" class="img-fluid mb-4">

                <div class="mb-4">
                    <h2 class="mb-4 fs-secondary">Download Our Mobile App</h2>
                    <img src="assets/images/App users.png" alt="Nescart Eat" class="img-fluid">
                </div>
                <img src="assets/images/Mobile App.png" alt="Nescart Eat" class="img-fluid mb-4" style="
                    height: 600px;
                    object-fit: cover;
                ">
                <div class="">
                    <a href="javascript:;" class="d-block mb-3"><img src="assets/images/Google Play.png" alt="" class="img-fluid"></a>
                    <a href="javascript:;" class="d-block"><img src="assets/images/App Store.png" alt="" class="img-fluid"></a>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="container-fluid fast-delivery site-section-b">
    <div class="container">
        <div class="row align-items-center justify-content-between">
            <div class="col-lg-5">
                <h2 class="fs-heading"><span class="text-accent">Fastest</span> Food <span class="text-accent">Delivery</span> in Your City.</h2>

                <img src="assets/images/Enjoy meal.png" alt="Nescart Eat" class="img-fluid my-4 d-lg-none">

                <p>We satisfy your cravings within 30 minutes. Get tasty and affordable meals in zero waiting time!</p>

                <a href="find-vendors" class="btn bg-primary mt-4">Find Food Vendors</a>
            </div>
            <div class="col-lg-6 d-none d-lg-block">
                <img src="assets/images/Enjoy meal.png" alt="Nescart Eat" class="img-fluid">
            </div>
        </div>
    </div>
</section>


<?php if(!$User->isLoggedIn()){ ?>
    <section class="container-fluid fast-delivery site-section-b">
        <div class="container">
            <div class="card br-lg bg-black border-0">
                <div class="card-body">
                    <div class="row align-items-center justify-content-between">
                        <div class="col-lg-5">
                            <h2 class="fs-heading text-white">Partner with Nescart Eats Today!</h2>
    
                            <img src="assets/images/Partner  with Nescart Eat.png" alt="Nescart Eat" class="img-fluid my-4 d-lg-none">
    
                            <p class="text-white">Take your restaurant business online and acquire more customers.</p>
    
                            <a href="<?= VENDOR_URL ?>" class="btn bg-primary mt-4">Become a Vendor</a>
                        </div>
                        <div class="col-lg-5 d-none d-lg-block">
                            <img src="assets/images/Partner  with Nescart Eat.png" alt="Nescart Eat" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php } ?>


<section class="container-fluid features site-section-b">
    <div class="container">
        <div class="row gy-5 g-lg-5">
            <div class="col-lg-6">
                <!--<div class="card br-lg h-100 bg-accent">-->
                <!--    <div class="card-body sm-padding">-->
                <!--        <div class="">-->
                <!--            <img src="assets/icons/Delicious.svg" alt="Nescart Eat" class="img-fluid icon mb-3">-->
                <!--            <h2 class="fs-secondary">Delicious<br />Meals</h2>-->
                <!--        </div>-->
                <!--        <div class="col-lg-10 ms-auto">-->
                <!--            <img src="assets/images/Delicious Meals.png" alt="Nescart Eat" class="img-fluid">-->
                <!--        </div>-->
                <!--    </div>-->
                <!--</div>-->
                <div class="card br-lg bg-accent mb-5">
                    <div class="row align-items-end">
                        <div class="col-lg-6">
                            <div class="card-body sm-padding">
                                <img src="assets/icons/Delicious.svg" alt="Nescart Eat" class="img-fluid icon mb-3">
                                <h2 class="fs-secondary">Delicious<br />Meals</h2>
                            </div>
                        </div>
                        <div class="col-lg">
                            <img src="assets/images/Delicious Meals.png" alt="Nescart Eat" class="img-fluid">
                        </div>
                    </div>
                </div>
                <div class="card br-lg bg-primary mb-5">
                    <div class="row align-items-end">
                        <div class="col-lg-6">
                            <div class="card-body sm-padding">
                                <img src="assets/icons/Easy Payment.svg" alt="Nescart Eat" class="img-fluid icon mb-3">
                                <h2 class="fs-secondary text-white">Easy<br />Payment</h2>
                            </div>
                        </div>
                        <div class="col-lg">
                            <img src="assets/images/Easy Payment.png" alt="Nescart Eat" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="card br-lg bg-primary mb-5">
                    <div class="row align-items-end">
                        <div class="col-lg-7">
                            <div class="card-body sm-padding">
                                <img src="assets/icons/Easy Payment.svg" alt="Nescart Eat" class="img-fluid icon mb-3">
                                <h2 class="fs-secondary text-white">Food<br />Subscription</h2>
                            </div>
                        </div>
                        <div class="col-lg">
                            <img src="assets/images/Easy Payment.png" alt="Nescart Eat" class="img-fluid">
                        </div>
                    </div>
                </div>

                <div class="card br-lg bg-black">
                    <div class="row align-items-end">
                        <div class="col-lg-6">
                            <div class="card-body sm-padding">
                                <img src="assets/icons/Fast Delivery.svg" alt="Nescart Eat" class="img-fluid icon mb-3">
                                <h2 class="fs-secondary text-white">Fast<br />Delivery</h2>
                            </div>
                        </div>
                        <div class="col-lg">
                            <img src="assets/images/Fast Delivery.png" alt="Nescart Eat" class="img-fluid">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="container-fluid subscription site-section-b">
    <div class="container">
        <div class="card br-lg bg-accent border-0">
            <div class="card-body">
                <div class="row align-items-center justify-content-between">
                    <div class="col-lg-5">
                        <h2 class="fs-heading mb-3">More Food, Less Money! Subscribe to Our Meal Plans.</h2>

                        <img src="assets/images/subscriptions.png" alt="subscription" class="img-fluid mt-1 mb-4 d-lg-none">
                        
                        <p class="text-dark">Nescart Eats offers you a choice of 1 meal every day for a month when you Subscribe to any of our Meal Plans!</p>

                        <a href="subscriptions" class="btn bg-primary mt-4">Get Started</a>
                    </div>
                    <div class="col-lg-5 d-none d-lg-block">
                        <img src="assets/images/subscriptions.png" alt="subscription" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>