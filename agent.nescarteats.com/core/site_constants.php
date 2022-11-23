<?php
defined('DS') ? NULL : define('DS', DIRECTORY_SEPARATOR);

defined('SITE_NAME') ? NULL : define('SITE_NAME', 'Agent | Nescart Eats');


defined('VENDOR_URL') ? NULL : define('VENDOR_URL', 'https://vendor.nescarteats.com/');
defined('AGENT_URL') ? NULL : define('AGENT_URL', 'https://agent.nescarteats.com/');
defined('SITE_URL') ? NULL : define('SITE_URL', 'https://agent.nescarteats.com/');
defined('SITE_ADMIN_URL') ? NULL : define('SITE_ADMIN_URL', SITE_URL . 'administrator/');

defined('SITE_ROOT') ? NULL : define('MAIN_SITE_ROOT', DS . 'home' . DS . 'oniontab' . DS . 'websites' . DS . 'nescarteats.com');
defined('SITE_ADMIN_ROOT') ? NULL : define('SITE_ADMIN_ROOT', MAIN_SITE_ROOT . DS . 'administrator');

defined('SITE_ROOT') ? NULL : define('SITE_ROOT', DS . 'home' . DS . 'oniontab' . DS . 'websites' . DS . 'nescarteats.com' . DS . 'agent.nescarteats.com');

defined('LIB_PATH') ? NULL : define('LIB_PATH', MAIN_SITE_ROOT . DS . "models");
defined('CORE_PATH') ? NULL : define('CORE_PATH', SITE_ROOT . DS . "core");
defined('ASSET_PATH') ? NULL : define('ASSET_PATH', SITE_ROOT . DS . "assets");