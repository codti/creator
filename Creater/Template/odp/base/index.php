<?php
try {
    if (!isset($_COOKIE['BDUSS'])) {
        //联通wap网络不能携带cookie，url中传递
        if (isset($_REQUEST['bduss']) && strlen($_REQUEST['bduss']) > 0) {
            $_COOKIE['BDUSS'] = $_REQUEST['bduss'];
        } elseif (isset($_REQUEST['BDUSS']) && strlen($_REQUEST['BDUSS']) > 0) {
            $_COOKIE['BDUSS'] = $_REQUEST['BDUSS'];
        }
    }

    $objApplication = Bd_Init::init();
    $objResponse    = $objApplication->bootstrap()->run();
} catch (Exception $e) {
    Bd_Log::warning('Caught exception: ' . $e->getMessage() . "\n");
    header("HTTP/1.0 404 Not Found");
}