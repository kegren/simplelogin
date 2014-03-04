<?php
// composer autoloader
require __DIR__ . '/vendor/autoload.php';
// helper functions
require __DIR__ . '/helper.php';

// perform logout
(new Sody\Authenticate\Auth())->logout();

return redirectTo('index.php');
