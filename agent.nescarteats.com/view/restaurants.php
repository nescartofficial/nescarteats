<?php
$vendors = new Vendors();

$form_data = Session::exists('form_data') ? Session::get('form_data') : null;

?>

<section class="site-section">
    <div class="container spacer-lg">
        <div class="row justify-content-between mb-4">
            <div class="col-lg-6 order-1 order-lg-0">
                <h2 class="fs-title" data-aos="fade-left" data-aos-duration="1800">Find a Restaurant</h2>
            </div>
        </div>
    </div>


    <!-- Top Vendors -->
    <section class="container-fluid mb-5">
        <div class="container">
            <?php Component::render('vendor', array('data' => $vendors->getAll(1, 'status', '='), 'type' => 'single',)); ?>
        </div>
    </section>
</section>