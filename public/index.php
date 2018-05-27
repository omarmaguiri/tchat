<?php

/**
 * Dump the passed variables and end the script.
 *
 * @param  mixed
 * @return void
 */
function dd()
{
    echo "<pre><br />";
    array_map(function ($x) {
        echo print_r($x, true)."<br />";
    }, func_get_args());
    echo "</pre>";
    die;
}
define('PUBLIC_DIRECTORY', __DIR__);

require __DIR__ . '/../vendor/autoload.php';

$request = new \Core\Http\Request();
$kernel = new \Core\Kernel();
$response = $kernel->handle($request);
$response->send();
