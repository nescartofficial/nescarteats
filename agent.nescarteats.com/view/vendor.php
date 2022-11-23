<?php
$vendors = new Vendors();

$vendor = null;
if (Input::get('action')) {
    $vendor = $vendors->get(Input::get('action'), 'slug');
    !$vendor ? Redirect::to_js('find-vendors') : null;
}
?>

<?php if ($vendor) {
    Template::render('vendor-view', 'view');
} else { ?>
    <header class="container-fluid bg-black vendor">
        <div class="container">
            <div class="row min-vh-100 justify-content-between align-items-center">
                <div class="col-lg-6">
                    <h1 class="text-white fs-heading--md mb-4">Join <span class="text-primary">Nescart Eats,</span>
                        Acquire <span class="text-accent">More Customers, Boost Sales</span> and <span class="text-accent">Grow</span> your Restaurant.</h1>

                    <img src="assets/images/Become a Vendor Hero Image.png" alt="Nescart Eat" class="img-fluid d-lg-none mt-3 mb-4">

                    <p class="text-white mb-4 fs-3">Nescart Eats gives you visibility and connects you to more hungry
                        customers who
                        are ready to order online.</p>

                    <a href="sign-up/vendor" class="btn bg-primary me-5">Partner with us today!</a>
                </div>
                <div class="col-lg-5 d-none d-lg-block">
                    <img src="assets/images/Become a Vendor Hero Image.png" alt="Nescart Eat" class="img-fluid">
                </div>
            </div>
        </div>
    </header>

    <section class="container-fluid  site-section">
        <div class="container">
            <div class="row mb-5">
                <div class="col-md-12 text-center">
                    <h3 class="text-muted">Why Nescart Eats Is</h3>
                    <h2 class="fs-heading--sm">The best <span class="text-primary">partner</span> for your <span class="text-accent">business</span></h2>
                </div>
            </div>

            <div class="row gy-5">

                <div class="col-lg-3 col-md-6">
                    <div class="card h-100 bg-primary--light">
                        <div class="card-body sm-padding text-center">
                            <img src="assets/icons/Promote Your Business.svg" alt="Nescart eat" class="img-fluid icon mb-4">

                            <h3 class="mb-3"><span class="text-accent">Promote</span> your Business</h3>
                            <p class="mb-0">Get more visibility and increase sales through Nescart Eats promotions and
                                advertisements.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="card bg-primary--light">
                        <div class="card-body sm-padding text-center">
                            <img src="assets/icons/Reach New Customers.svg" alt="Nescart eat" class="img-fluid icon mb-4">

                            <h3 class="mb-3">Reach New <span class="text-accent">Customers</span></h3>
                            <p class="mb-0">Attract more customers to your restaurant through online orders, especially
                                the people who are busy.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="card bg-primary--light">
                        <div class="card-body sm-padding text-center">
                            <img src="assets/icons/Express Delivery.svg" alt="Nescart eat" class="img-fluid icon mb-4">

                            <h3 class="mb-3">Express <br><span class="text-accent">Delivery</span></h3>
                            <p class="mb-0">Orders from your restaurants are delivered safely to customers on time
                                through our delivery partners.</p>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <div class="card bg-primary--light">
                        <div class="card-body sm-padding text-center">
                            <img src="assets/icons/Expert Support.svg" alt="Nescart eat" class="img-fluid icon mb-4">

                            <h3 class="mb-3">Expert <span class="text-accent">Support</span> Services</h3>
                            <p class="mb-0">24/7 Customer Care Support System to give you the support you need to grow
                                your business hassle-free.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <section class="container-fluid site-section">
        <div class="container">
            <div class="row mb-5">
                <div class="col-md-12 text-center">
                    <h3 class="text-muted">How it Works</h3>
                    <h2 class="fs-heading--sm">Our <span class="text-primary">platform</span> is simple and <span class="text-accent">straightforward</span></h2>
                </div>
            </div>

            <div class="row gy-5 justify-content-betwee align-items-center">
                <div class="col-lg-6">
                    <img src="assets/images/Our Platform.png" alt="Nescart Eat" class="img-fluid">
                </div>

                <div class="col-lg-5 offset-lg-1">
                    <div class="mb-5">
                        <h2>Customer Orders</h2>
                        <p class="fs-5">Customers search and order food from your restaurant on Nescart Eats App.</p>
                    </div>
                    <div class="mb-5">
                        <h2>Prepare Food</h2>
                        <p class="fs-5">You accept the customer’s order, prepare it and notify delivery agents when
                            ready.</p>
                    </div>
                    <div class="">
                        <h2>Delivery fulfills order</h2>
                        <p class="fs-5">Delivery services pick up the order from your location and deliver it to your
                            customers.</p>
                    </div>

                </div>
            </div>
        </div>
    </section>

    <section class="container-fluid fast-delivery site-section-b">
        <div class="container">
            <div class="card bg-primary border-0">
                <div class="card-body">
                    <div class="row mb-5">
                        <div class="col-md-7 mx-auto text-center">
                            <h2 class="fs-heading--sm text-white">Start your Journey with Nescart Eats with 3 Steps</h2>
                        </div>
                    </div>

                    <div class="row align-items-center justify-content-between">
                        <div class="col-lg-3 text-center">
                            <span class="bg-white icon-text mx-auto mb-4">
                                <h1 class="mb-0">1</h1>
                            </span>
                            <h3 class=""><span class="text-accent">Sign up</span> and Register</h3>
                            <p class="text-white fs-5">Fill out the registration form to register your restaurant on the
                                platform.</p>
                        </div>
                        <div class="col-lg-3 text-center">
                            <span class="bg-white icon-text mx-auto mb-4">
                                <h1 class="mb-0">2</h1>
                            </span>
                            <h3 class="">Activate your <span class="text-accent">account</span></h3>
                            <p class="text-white fs-5">Our agent will call you to verify and activate your account.</p>
                        </div>
                        <div class="col-lg-3 text-center">
                            <span class="bg-white icon-text mx-auto mb-4">
                                <h1 class="mb-0">3</h1>
                            </span>
                            <h3 class="">Upload your <span class="text-accent">menu</span></h3>
                            <p class="text-white fs-5">Get access to the restaurant dashboard and start receiving
                                orders.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="container-fluid site-section">
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col-lg-5">
                    <h2 class="fs-heading--sm mb-lg-5">Ready to join Nescart Eats?</h2>

                    <img src="assets/images/Ready Nestcart Eats to Join.png" alt="Nescart Eat" class="img-fluid mt-4 mb-5 d-lg-none">

                    <div class="mb-4 d-flex">
                        <img src="assets/icons/Background Dots.svg" alt="Nescart Eats" class="icon-sm me-3">
                        <p class="fs-4 mb-0">Up to 30% on sales increase thanks to new online orders.</p>
                    </div>
                    <div class="mb-4 d-flex">
                        <img src="assets/icons/Background Dots.svg" alt="Nescart Eats" class="icon-sm me-3">
                        <p class="fs-4 mb-0">1000+ online partners and growing.</p>
                    </div>
                    <div class="mb-4 d-flex">
                        <img src="assets/icons/Background Dots.svg" alt="Nescart Eats" class="icon-sm me-3">
                        <p class="fs-4 mb-0">30 min our average delivery time to complete your delivery orders.</p>
                    </div>
                    <div class="mb-5 d-flex">
                        <img src="assets/icons/Background Dots.svg" alt="Nescart Eats" class="icon-sm me-3">
                        <p class="fs-4 mb-0">24/7 support system to grow your Business</p>
                    </div>

                    <a href="sign-up/vendor" class="btn bg-primary">Join us today!</a>
                </div>

                <div class="col-lg-5 d-none d-lg-block">
                    <img src="assets/images/Ready Nestcart Eats to Join.png" alt="Nescart Eat" class="img-fluid">
                </div>
            </div>
        </div>
    </section>

    <section class="site-section">
        <div class="container">
            <div class="row mb-5" id="faqs">
                <div class="col-md-12 text-center">
                    <h3 class="text-muted">FAQs</h3>
                    <h2 class="fs-heading--sm">Frequently Asked Questions</h2>
                </div>
            </div>

            <div class="row justify-content-between align-items-center">
                <div class="col-lg-12 mx-auto">
                    <div class="accordion" id="accordionExample">
                        <div class="accordion-item mb-4 shadow-sm border-0">
                            <h2 class="accordion-header" id="headingOne">
                                <button class="accordion-button py-4 px-lg-5 px-md-4" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    <p class="mb-0 fw-bold fs-5">What is Nescart Eats?</p>
                                </button>
                            </h2>
                            <div id="collapseOne" class="px-lg-5 px-md-4 accordion-collapse collapse show" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <p>Nescart Eats is an online food subscription and delivery platform (app and web) that connects customers with food businesses on our platform. Nescart Eats is a unique concept for any vendor, it allows you to create a virtual restaurant inside our system and reach as many customers per month as you want.</p>

                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-4 shadow-sm border-0">
                            <h2 class="accordion-header" id="headingTwo">
                                <button class="accordion-button py-4 px-lg-5 px-md-4 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                    <p class="mb-0 fw-bold fs-5">Can I Sell At Nescart If I Don’t Own a Physical Restaurant?</p>
                                </button>
                            </h2>
                            <div id="collapseTwo" class="px-lg-5 px-md-4 accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <p>Definitely! With Nescart Eats, you can sell food directly from your kitchen or restaurant to anyone and everyone! We help you get online in 3 working days </p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-4 shadow-sm border-0">
                            <h2 class="accordion-header" id="headingThree">
                                <button class="accordion-button py-4 px-lg-5 px-md-4 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                    <p class="mb-0 fw-bold fs-5">How Will I Receive the Orders as a Vendor?</p>
                                </button>
                            </h2>
                            <div id="collapseThree" class="px-lg-5 px-md-4 accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <p>As soon as your store is set up, you have access to a dashboard where you will manage your orders and sales. Once you confirm the order, you will have around 15 minutes until the courier arrives to pick it up.</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-4 shadow-sm border-0">
                            <h2 class="accordion-header" id="headingFour">
                                <button class="accordion-button py-4 px-lg-5 px-md-4 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                    <p class="mb-0 fw-bold fs-5">Can I Be a Vendor on Nescart Eats If I Have My Own Delivery service?</p>
                                </button>
                            </h2>
                            <div id="collapseFour" class="px-lg-5 px-md-4 accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <p>Yes! The only difference is we help you to sell to thousands, while you do the rest. </p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-4 shadow-sm border-0">
                            <h2 class="accordion-header" id="headingFive">
                                <button class="accordion-button py-4 px-lg-5 px-md-4 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                    <p class="mb-0 fw-bold fs-5">How do the Food Subscription and Meal Plans work?</p>
                                </button>
                            </h2>
                            <div id="collapseFive" class="px-lg-5 px-md-4 accordion-collapse collapse" aria-labelledby="headingFive" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <p>The Food subscription option enables you to buy a meal plan that offers you your choice of meals for 30 days at an affordable price. See Our Meal Plans for more information!</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-4 shadow-sm border-0">
                            <h2 class="accordion-header" id="headingSix">
                                <button class="accordion-button py-4 px-lg-5 px-md-4 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                    <p class="mb-0 fw-bold fs-5">Can I buy more food asides from the meal plan I subscribed to?</p>
                                </button>
                            </h2>
                            <div id="collapseSix" class="px-lg-5 px-md-4 accordion-collapse collapse" aria-labelledby="headingSix" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <p>Definitely! Subscription does not affect normal purchases. You can also buy more food and pay with your preferred payment option.</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-4 shadow-sm border-0">
                            <h2 class="accordion-header" id="headingSeven">
                                <button class="accordion-button py-4 px-lg-5 px-md-4 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                                    <p class="mb-0 fw-bold fs-5">Can I get More than 1 meal per day in my Food subscription plan?</p>
                                </button>
                            </h2>
                            <div id="collapseSeven" class="px-lg-5 px-md-4 accordion-collapse collapse" aria-labelledby="headingSeven" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <p>To have more than a meal per day plan, select the number of plans that correspond with your needs. If you want a basic plan that caters to your breakfast and lunch, simply select two basic plans in your subscription cart and you get access to 2 meals per day at ₦30,000 only!</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-4 shadow-sm border-0">
                            <h2 class="accordion-header" id="headingEight">
                                <button class="accordion-button py-4 px-lg-5 px-md-4 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseEight" aria-expanded="false" aria-controls="collapseEight">
                                    <p class="mb-0 fw-bold fs-5">What is the “Chop now, Pay Later” option about?</p>
                                </button>
                            </h2>
                            <div id="collapseEight" class="px-lg-5 px-md-4 accordion-collapse collapse" aria-labelledby="headingEight" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <p>“Chop Now, Pay Later” is an option that allows you to eat and pay later. To enjoy this benefit, you must have ordered food at least four times every week in a month using the Nescart Eats App with a spend score of at least ₦15,000. More information on “Chop Now Pay Later”</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-4 shadow-sm border-0">
                            <h2 class="accordion-header" id="headingNine">
                                <button class="accordion-button py-4 px-lg-5 px-md-4 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNine" aria-expanded="false" aria-controls="collapseNine">
                                    <p class="mb-0 fw-bold fs-5">How Many Times Can I Use the “Chop Now, Pay Later” option?</p>
                                </button>
                            </h2>
                            <div id="collapseNine" class="px-lg-5 px-md-4 accordion-collapse collapse" aria-labelledby="headingNine" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <p>You can only use this option once. You automatically get access to the Chop Now Pay Later option once you have cleared existing debts.</p>
                                </div>
                            </div>
                        </div>
                        <div class="accordion-item mb-4 shadow-sm border-0">
                            <h2 class="accordion-header" id="headingTen">
                                <button class="accordion-button py-4 px-lg-5 px-md-4 collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTen" aria-expanded="false" aria-controls="collapseTen">
                                    <p class="mb-0 fw-bold fs-5">Can I still Buy Food If I Haven’t Cleared My “Chop Now Pay Later” Debt?</p>
                                </button>
                            </h2>
                            <div id="collapseTen" class="px-lg-5 px-md-4 accordion-collapse collapse" aria-labelledby="headingTen" data-bs-parent="#accordionExample">
                                <div class="accordion-body">
                                    <p>Definitely! You can still buy and pay for your food through your preferred payment options. </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php } ?>