<?php
$User = new User();
?>
<style>
    /* Section styling */
.subscription-plan-item {
    margin-top: 60px;
}

.subscription-plan-item .card-body {
    margin-top: 45px;
}

.subscription-plan-item .card {
    background-color: var(--color-primary);
}

.subscription-plan-item .card.active {
    background-color: white;
    color: black;
}

.subscription-plan-item .card:hover {
    transition: all .6s;
    background-color: white;
    color: black !important;
    border: 2px solid var(--color-primary) !important;
}

.subscription-plan-item .card img {
    height: 150px;
    left: 25% !important;
}

.card-b {
    border-radius: 20px;
}

.check {
    height: 20px;
    width: 20px;
    margin-right: 4px;
}
</style>

<section class="container-fluid bg-black pt-5 site-section-b">
<div class="container">
  <div class="row gx-5">
    <div class="col-lg-6 align-self-center text-white header">
      <h1 class="fs-heading--md mb-3 fw-bold text-white">Never Miss Out on <span class="text-accent"> Lunch,</span> Eat Daily,
        <span class="text-primary">Eat Healthy,</span>
      </h1>
      <p class="fs-3 mb-4">Choose from our amazing monthly <br> subscription plans and begin to enjoy daily <br>
        delicious meals delivered to you.</p>

      <div class="d-lg-flex align-items-center gap-4 mb-3 mb-lg-0">
        <a href="subscriptions#subscription-plans" class="btn bg-primary d-block mb-3">Get Started</a>
        <span class="text-accent small">Enjoy 5% discounts on your first subscription today.</span>
      </div>
    </div>

    <div class="col-lg-6">
      <img src="./assets/images/Subscription Hero Image.png" alt="" class="img-fluid hero-img ">
    </div>
  </div>
</div>
</section>

<section class="subscription-plan container-fluid bg-white header site-section-b">
<div class="container">
  <div class="row align-items-center justify-content-between">
    <div class="col-lg-5 d-none d-lg-block">
      <img src="./assets/images/Subscription Plan Features.png" alt="" class="img-fluid mb-5">
    </div>

    <div class="col-lg-6">
      <h2 class="fs-heading--sm fw-bold mb-5">With each plan, you get:</h2>
      
      <img src="./assets/images/Subscription Plan Features.png" alt="" class="img-fluid d-lg-none">

      <div class="row row-cols-1 row-cols-md-2 mt-lg-4 g-lg-5">
        <div class="col subscription-plan-item mb-4 mb-lg-5">
          <div class="card active border-0 position-relative shadow-sm card-b  h-100">
            <img src="./assets/images/Subscription Delicious Meals.svg"
              class="img-fluid position-absolute top-0 start-0 translate-middle" alt="...">
            <div class="card-body p-4">
              <p class="fs-4 mb-0 card-title fw-semibold">Delicious, nutritious and healthy meals.</p>
            </div>
          </div>
        </div>


        <div class="col subscription-plan-item mb-4 mb-lg-5">
          <div class="card border-0 position-relative shadow-sm bg-green text-white card-b h-100">
            <img src="./assets/images/Subscription 25off.svg"
              class="img-fluid position-absolute top-0 start-0 translate-middle" alt="">
            <div class="card-body p-4">
              <p class="fs-4 mb-0 card-title fw-semibold">25% off on every meal in the plan.</p>
            </div>
          </div>
        </div>

        <div class="col subscription-plan-item mb-4 mb-lg-5">
          <div class="card border-0 position-relative shadow-sm bg-green text-white card-b h-100">
            <img src="./assets/images/Subscription Free & Prompt Delivery.svg"
              class="img-fluid position-absolute top-0 start-0 translate-middle" alt="">
            <div class="card-body p-4">
              <p class="fs-4 mb-0 card-title fw-semibold">Free & prompt delivery.</p>
            </div>
          </div>
        </div>
        <div class="col subscription-plan-item mb-4 mb-lg-5">
          <div class="card border-0 position-relative shadow-sm bg-green text-white card-b h-100">
            <img src="./assets/images/Subscription Pause & Cancel.svg"
              class="img-fluid position-absolute top-0 start-0 translate-middle" alt="">
            <div class="card-body p-4">
              <p class="fs-4 mb-0 card-title fw-semibold"> Pause or cancel subscription anytime.</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</section>

<section class="container-fluid header site-section-b" id="subscription-plans">
<div class="container">
  <h2 class="fs-heading--sm text-center fw-bold">Choose from our amazing monthly <br> subscription plans</h2>

  <div class="row row-cols-1 row-cols-md-3 g-4 mt-5">
    <div class="col">
      <div class="card card-b shadow-sm border-0">
        <div class="card-body px-4 py-5">
          <h3 class="card-title text-primary fw-bold mb-1">Basic Plan </h3>
          <p class="fs-4 mb-0">
            <span class="fw-bold text-accent">40k/</span><span class="fs-6">30Days</span>
          </p>

          <hr class="mb-4">

          <p class="fw-bold small card-text"> <img src="./assets/images/check.png" alt=""
              class="img-fluid check mr-2"> A delicious
            meal per day</p>
          <p class="fw-bold small card-text"> <img src="./assets/images/check.png" alt=""
              class="img-fluid check mr-2"> Meals worth
            up to N1500</p>
          <p class="fw-bold small card-text"> <img src="./assets/images/check.png" alt=""
              class="img-fluid check mr-2"> Complimentary
            Drink every Monday</p>
          <p class="fw-bold small card-text"> <img src="./assets/images/check.png" alt=""
              class="img-fluid check mr-2"> Exclusive
            Free Delivery</p>
          <p class="fw-bold small card-text"> <img src="./assets/images/check.png" alt=""
              class="img-fluid check mr-2"> Membership to
            NESCLUB</p>

          <a href="<?= $User->isLoggedIn() ? 'dashboard/subscriptions' : 'sign-up' ?>" class="btn bg-primary text-white w-100 mt-4">Choose Plan</a>
        </div>
      </div>
    </div>

    <div class="col">
      <div class="card card-b border-0 bg-primary shadow">
        <div class="card-body px-4 py-5">
          <p class="fw-semibold text-dark small mb-0"> Recommended </P>
          <h3 class="card-title text-white fw-bold mb-1">Premium Plan </h3>
          <p class="fs-4 mb-0">
            <span class="fw-bold text-white">70k/</span><span class="fs-6 text-dark">30Days</span>
          </p>

          <hr class="text-white mb-4">

          <p class="fw-bold small card-text text-white"> <img src="./assets/images/check.png" alt=""
              class="img-fluid check"> A
            delicious
            meal per day</p>
          <p class="fw-bold small card-text text-white"> <img src="./assets/images/check.png" alt=""
              class="img-fluid check">
            Meals worth
            up t0 N2500</p>
          <p class="fw-bold small card-text text-white"> <img src="./assets/images/check.png" alt=""
              class="img-fluid check">
            Complimentary
            drink 3 times/week</p>
          <p class="fw-bold small card-text text-white"> <img src="./assets/images/check.png" alt=""
              class="img-fluid check">
            Exclusive
            Free Delivery</p>
          <p class="fw-bold small card-text text-white"> <img src="./assets/images/check.png" alt=""
              class="img-fluid check"> 3 Months Membership to NESCLUB</p>

          <a href="<?= $User->isLoggedIn() ? 'dashboard/subscriptions' : 'sign-up' ?>" class="btn bg-white w-100 text-primary mt-4">Choose Plan</a>

        </div>
      </div>
    </div>

    <div class="col">
      <div class="card card-b border-0 shadow-sm">
        <div class="card-body px-4 py-5">
          <h3 class="card-title text-primary fw-bold mb-1">Exclusive Plan </h3>
          <p class="fs-4 mb-0">
            <span class="fw-bold text-accent">100k/</span><span class="fs-6">30Days</span>
          </p>

          <hr class="mb-4">

          <p class="fw-bold small card-text"> <img src="./assets/images/check.png" alt="" class="img-fluid check"> A
            delicious meal per day</p>
          <p class="fw-bold small card-text"> <img src="./assets/images/check.png" alt="" class="img-fluid check">
            Meal worth up to N4000</p>
          <p class="fw-bold small card-text"> <img src="./assets/images/check.png" alt="" class="img-fluid check">
            Complimentary drink on each order</p>
          <p class="fw-bold small card-text"> <img src="./assets/images/check.png" alt="" class="img-fluid check">
            Exclusive Free Delivery</p>
          <p class="fw-bold small card-text"> <img src="./assets/images/check.png" alt="" class="img-fluid check"> 6
            months membership to NESCLUB</p>

          <a href="<?= $User->isLoggedIn() ? 'dashboard/subscriptions' : 'sign-up' ?>" class="btn bg-primary text-white w-100 mt-4">Choose Plan</a>
        </div>
      </div>
    </div>
  </div>
</div>
</section>

<section class="container-fluid header site-section-b">
<div class="container">
  <div class="row align-items-center justify-content-between g-5">
    <div class="col-lg-6">
      <h2 class="fs-heading mb-3 fw-bold">Designed to Give You the Best Value </h2>
      
      <img src="./assets/images/Subscription Give Value.png" alt="" class="img-fluid d-lg-none mb-4 mt-1">
      
      <p class="sub-text ">Whether you are on a budget, on a quest
        for productivity, on a health journey or
        want to treat yourself to a sumptuous meal,
        our subscription plans will help you eat in a 
        balanced and more joyful way while getting 
        the best value for your money. </p>
      <button class="btn bg-primary mt-3">Get Started</button>
    </div>
    
    <div class="col-lg-5 d-none d-lg-block">
      <img src="./assets/images/Subscription Give Value.png" alt="" class="img-fluid">
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