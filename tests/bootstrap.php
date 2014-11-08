<?php

error_reporting(E_ALL ^ E_NOTICE);

// Load additional dependencies provided by composer.
if (!file_exists($file = __DIR__.'/../vendor/autoload.php')) {
    throw new RuntimeException('Install dependencies to run test suite.');
}

$loader = require $file;

$loader->add('Octopus', __DIR__ . '/../lib/BlockJon');
$loader->add('BlockJon\Tests', __DIR__ . '/../tests');
$loader->add('Models', __DIR__);
$loader->add('Daos', __DIR__);
$loader->add('Repositories', __DIR__);

// This list of error codes are considered fatal.
$fatalErrorCodeArray = array(E_USER_ERROR, E_COMPILE_ERROR, E_PARSE, E_ERROR);

/**
 * Given a PHP error int, return the string representation of the error.
 *
 * @param $intval
 * @param string $separator
 * @return string
 */
$describeErrorReporting = function($intval, $separator = ',') {
    $errorlevels = array(
        8192 => 'E_DEPRECATED',
        2048 => 'E_STRICT',
        2047 => 'E_ALL',
        1024 => 'E_USER_NOTICE',
        512 => 'E_USER_WARNING',
        256 => 'E_USER_ERROR', // This is a show stopper.
        128 => 'E_COMPILE_WARNING',
        64 => 'E_COMPILE_ERROR', // This is a show stopper.
        32 => 'E_CORE_WARNING',
        16 => 'E_CORE_ERROR',
        8 => 'E_NOTICE',
        4 => 'E_PARSE', // This is a show stopper.
        2 => 'E_WARNING',
        1 => 'E_ERROR' // This is a show stopper.
    );
    $result = '';
    foreach ($errorlevels as $number => $name) {
        if (($intval & $number) == $number) {
            $result .= ($result != '' ? $separator : '') . $name;
        }
    }
    return $result;
};

/**
 * Logs fatal errors and returns an HTTP 500 response code.
 */
register_shutdown_function(function() use ($describeErrorReporting, $fatalErrorCodeArray) {
    $last_error = error_get_last();
    // If the type was a FATAL-ish error, handle it elegantly.
    if (is_array($last_error) && in_array($last_error['type'], $fatalErrorCodeArray)) {
        ob_end_clean();
        echo "\nFatal PHP error encountered: \n";
        print_r($last_error);
    }
});
