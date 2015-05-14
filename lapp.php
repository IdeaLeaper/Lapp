<?php
/*
    LAPP AUTORUN SCRIPT    
*/

/* Require Lapp Files */
require_once("./config/base.php");

/* Require libraries automatically */
$process_dir = "./lib/";
$files = array();

$handler = opendir($process_dir);
while (($filename = readdir($handler)) !== false) {
    if ($filename != "." && $filename != "..") {
    $files[] = $filename ;
    }
}
closedir($handler);

foreach ($files as $value) {
    require_once($process_dir.$value);
}

/* Require objects automatically */
$process_dir = "./obj/";
$files = array();

$handler = opendir($process_dir);
while (($filename = readdir($handler)) !== false) {
    if ($filename != "." && $filename != "..") {
    $files[] = $filename ;
    }
}
closedir($handler);

foreach ($files as $value) {
    require_once($process_dir.$value);
}
?>