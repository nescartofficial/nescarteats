<?php
include_once('core/init.php');
$user = isset($user) ? $user : new User();
!$user->isLoggedIn() ? Redirect::to('login') : null;
$world = new World();
$Menus = new Menus();
$Subscriptions = new General('subscriptions');
$SubscriptionPlans = new General('subscription_plans');

$us = $user->data();
$profile = $user->getProfile();
$countries = $world->getCountries();

$plans = $SubscriptionPlans->getAll(1, 'status', '=');

$user_subscription = $Subscriptions->getByUser('active', 'status', $user->data()->id);
$user_plan = $user_subscription ? $SubscriptionPlans->get($user_subscription->plan) : null;

$plan_meals = $Menus->getAllNearMe($profile->state, $profile->city, null, false, " menus.price <= {$user_plan->daily_amount} ");

$form_data = Session::exists('profile_fd') ? Session::get('profile_fd') : null;

Alerts::displayError();
Alerts::displaySuccess();
?>

<section class="container-fluid py-5">
    <header class="container mb-6">
        <div class="d-flex align-items-center justify-contnt-between">
            <!-- Back -->
            <a href="#" id="back_button" class="d-flex p-2 border-0 rounded shadow bg-white">
                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22.5 38.381">
                    <path id="chevron-left" d="M30.949,7.767a3.526,3.526,0,0,1,0,5.216l-12.4,12.932,12.4,12.932a3.516,3.516,0,0,1,0,5.207,3.93,3.93,0,0,1-5.344,0c-1.384-1.344-14.9-15.535-14.9-15.535a3.619,3.619,0,0,1,0-5.214S24.221,9.119,25.6,7.769a3.928,3.928,0,0,1,5.344,0Z" transform="translate(-9.6 -6.72)" />
                </svg>

            </a>
            <h4 class="mb-0 mx-auto pr-40">Subscription</h4>
        </div>
    </header>

    <div class="container">
        <div class="row">
            <div class="col-12">
                <p class="fs-16p mb-5 text-center">
                    Select a monthly meal package and begin to enjoy daily delicious meals delivered to you.</p>


                <section class="row mb-6">
                    
                    <?php if(!$user_plan && $plans){ ?>
                        <?php foreach($plans as $plan){ ?>
                            <div class="col-lg-4">
                                <div class="card <?= $plan->classes ?> shadow mb-3">
                                    <div class="card-body text-center py-5">
                                        <h2 class="mb-4 <?= $plan->text_color ?>"><?= $plan->plan; ?></h2>
            
                                        <p class="mb-4 <?= $plan->text_color ?>"><?= $plan->short_description; ?></p>
            
                                        <h1 class=" <?= $plan->text_color ?> mb-4"><?= Helpers::format_currency($plan->amount); ?></h1>
            
                                        <button class="btn <?= $plan->classes == 'bg-accent' || $plan->classes == 'bg-black' ? 'bg-primary text-white' : 'bg-accent'; ?> mt-2 pay-subscription" data-subscription="<?= $plan->id; ?>"> Select Package</button>
                                        
                                        <a href="subscriptions" class="d-block <?= $plan->classes != 'bg-accent' ? 'text-white' : null; ?> mt-3">Learn more about <?= $plan->plan; ?></a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php } ?>
                    
                </section>

                <div class="card-body bg-white shadow rounded py-3 mb-5">
                    <p class="fw-bold mb-0">Current Plan</p>
                    <?php if($user_plan){ ?>
                        <p class="mb-0 fs-16p">You are subscribed to the <b><?= $user_plan->plan; ?></b> plan and your daily spend limit is <?= Helpers::format_currency($user_plan->daily_amount) ?></p>
                    <?php }else{ ?>
                        <p class="mb-0 fs-16p">You are not on any subscription plan</p>
                    <?php } ?>
                </div>

                <?php if($plan_meals){ ?>
                    <div class="card-body bg-white shadow rounded py-3 mb-5">
                        <p class="fw-bold mb-0">Menus</p>
                        <p class="mb-0 fs-16p">Find a meal today.</p>
                    </div>
                    
                    <?php Component::render('menu', array('data' => $plan_meals, 'type' => 'list', 'title' => null)); ?>
                <?php } ?>
                
                
                
                
                <div class="card-body bg-white shadow rounded py-3 mb-5">
                    <p class="fw-bold mb-0">
                        Meals Plan History</p>
                    <p class="mb-0 fs-16p">
                        You are not on any subscription Plan</p>
                </div>

            </div>
        </div>
    </div>
</section>