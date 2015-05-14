<?php
/*
    Lapp Framework V1.01
    Designed By Aego Yu (Yu, Renyou In Chinese)
*/
require_once("./lapp.php");

/* Recieve requests */
$INPUT = $_REQUEST;

if(isset($INPUT['api'])){
    $API = $INPUT['api'];
    $OBJECT_NAME = substr($API, 0, strpos($API, "."));
    $METHOD_NAME = substr($API, strlen($OBJECT_NAME)+1);
    if(@$INPUT['data']){
        $DATA = json_decode($INPUT['data'], true);
    } else {
        $DATA = array();
    }
} else {
    put::error("missing object or method");
    return;
}

/* Check Request Object */
$DENIED = array("db", "fm", "put");
foreach ($DENIED as $v) {
    if ($v == $OBJECT_NAME){
        put::error("object had been denied");
        return;
    }
}

if(substr($METHOD_NAME, 0, 1)=="_"){
    put::error("method had been denied");
    return;
}

/* Class Router */
if (class_exists($OBJECT_NAME)){
    eval('$OBJECT = new '.$OBJECT_NAME.';');
    if (method_exists($OBJECT, $METHOD_NAME)){
        eval('$OBJECT -> '.$METHOD_NAME.'($DATA);');
    } else {
        put::error("method not found");
    }
} else {
    put::error("object not found");
}

?>