<?php
require_once("../core/init.php");
$user = new User();
$orders = new General('orders');
$menus = new Menus();
$vendors = new Vendors();

print_r($vendors->getTops(2650, null, 2));

// if(Messages::verifyEmail("123456766", "Chris Asek", "chrisasek@gmail.com")){
//     echo 'message sent successfully';
// }
// if(Messages::send("Test Message", "the subject of the mail", 'chrisasek@gmail.com', 'Chris Asek', true)){
//     echo 'message sent successfully';
// }