<?php
require_once("../core/init.php");

if ($_FILES && $_FILES['image']['name']) {
    if (!$_FILES['image']['error']) {
        $name = Helpers::getUnique(5, 'a');
        $ext = explode('.', $_FILES['image']['name']);
        $filename = $name . '.' . $ext[1];
        $destination = SITE_ROOT . '/media/images/editor/' . $filename; //change this directory
        $location = $_FILES["image"]["tmp_name"];
        move_uploaded_file($location, $destination);
        $path = '/website/erojul.com/media/images/editor/';
        echo 'http://' . $_SERVER['SERVER_NAME'] . $path . $filename; //change this URL
    } else {
        echo  $message = 'Ooops!  Your upload triggered the following error:  ' . $_FILES['image']['error'];
    }
}
