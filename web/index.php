<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

defined('_DEV_MODE_') or define('_DEV_MODE_', true);

require __DIR__ . '/../vendor/defines_core.php';
require __DIR__ . '/../vendor/functions.php';

/*
define('_rootDIR_', $_SERVER['DOCUMENT_ROOT'].'/');  // подключить скрипты
define('_webDIR_', $_SERVER['DOCUMENT_ROOT'].'/web/');  // подключить скрипты
define('_inclDIR_', $_SERVER['DOCUMENT_ROOT'].'/web/includes/');

define('_rootDIR_HTTP_', 'http://'.$_SERVER['HTTP_HOST'].'/'); // для ссылок
define('_web_HTTP_', _rootDIR_HTTP_.'web/'); // для ссылок
*/

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

$config = require __DIR__ . '/../config/web.php';

(new yii\web\Application($config))->run();
