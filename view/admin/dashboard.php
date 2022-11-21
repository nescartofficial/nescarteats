<?php
$user = new User();
$user_list = $user->getAll()
?>
<section class="container site-section">
    <div class="row">
        <?php foreach ($user_list as $k => $v) { ?>
            <div class="col-lg-4 mx-auto text-center">
                <h5><? $v->first_name . ' ' . $v->last_name; ?></h5>
                <p><? $v->phone; ?></p>
                <p><? $v->email; ?></p>
                <p>Account Type:<? $v->vendor ? 'Vendor' : 'User'; ?></p>
            </div>
        <?php } ?>
    </div>
</section>