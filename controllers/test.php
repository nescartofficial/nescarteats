<?php
require_once("../core/init.php");
$user = new User();
$orders = new General('orders');

if(Messages::send("Test Message", "the subject of the mail", 'chrisasek@gmail.com')){
    echo 'message sent successfully';
}