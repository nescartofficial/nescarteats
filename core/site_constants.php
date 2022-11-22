<?php
defined('DS') ? NULL : define('DS', DIRECTORY_SEPARATOR);

defined('SITE_NAME') ? NULL : define('SITE_NAME', 'Nescart Eats');
defined('VENDOR_URL') ? NULL : define('VENDOR_URL', 'https://vendor.nescarteats.com/');
defined('AGENT_URL') ? NULL : define('AGENT_URL', 'https://agent.nescarteats.com/');
defined('SITE_URL') ? NULL : define('SITE_URL', 'https://nescarteats.azurewebsites.net/');
defined('SITE_ADMIN_URL') ? NULL : define('SITE_ADMIN_URL', SITE_URL . 'administrator/');

defined('SITE_ROOT') ? NULL : define('SITE_ROOT', DS . 'home');
defined('SITE_ADMIN_ROOT') ? NULL : define('SITE_ADMIN_ROOT', SITE_ROOT . DS . 'administrator');

defined('LIB_PATH') ? NULL : define('LIB_PATH', SITE_ROOT . DS . "models");
defined('CORE_PATH') ? NULL : define('CORE_PATH', SITE_ROOT . DS . "core");
defined('ASSET_PATH') ? NULL : define('ASSET_PATH', SITE_ROOT . DS . "assets");
