<?php
defined('DS') ? NULL : define('DS', DIRECTORY_SEPARATOR);

defined('SITE_NAME') ? NULL : define('SITE_NAME', 'Nescart Eats');
defined('SITE_URL') ? NULL : define('SITE_URL', 'https://nescarteats.com/');
defined('SITE_ADMIN_URL') ? NULL : define('SITE_ADMIN_URL', SITE_URL . 'administrator/');

defined('SITE_ROOT') ? NULL : define('SITE_ROOT', DS . 'home' . DS . 'oniontab' . DS . 'websites' . DS . 'nescarteats.com');
defined('SITE_ADMIN_ROOT') ? NULL : define('SITE_ADMIN_ROOT', SITE_ROOT . DS . 'administrator');
defined('LIB_PATH') ? NULL : define('LIB_PATH', SITE_ROOT . DS . "models");
defined('CORE_PATH') ? NULL : define('CORE_PATH', SITE_ROOT . DS . "core");