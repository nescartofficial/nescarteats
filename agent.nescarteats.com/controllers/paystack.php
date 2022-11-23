<?php
require_once("../core/init.php");
$user = new User();
$banks = new General('banks');
$Paystack = new Paystack();


$data = $Paystack->listBanks()->data;
if ($data) {
    foreach ($data as $k => $v) {
        if ($banks->get($v->code, 'code')) {
        } else {
            $banks->create(array(
                'code' => $v->code,
                'name' => $v->name,
                'slug' => $v->slug,
                'country' => $v->country,
                'currency' => $v->currency,
                'active' => $v->active,
            ));
        }
    }
}
print_r('done');
