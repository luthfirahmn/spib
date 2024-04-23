<?php


if (!function_exists("pre")) {
    function pre($param = array())
    {
        echo "<PRE>";
        print_r($param);
        exit;
    }
}
