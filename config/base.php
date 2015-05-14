<?php
/* HTTP HEADER Settings */
header('Content-type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');

/* Define MYSQL information */
define("MYSQL_HOST", "");
define("MYSQL_USER", "root");
define("MYSQL_PASS", "");
define("MYSQL_DB", "lapp");

/* Define signatures */
define("CERT", "885eed56ec8a87f6ac13ed064c661ee7");
define("TOKEN", substr(time() * 8857, 9));
?>