<?php
class put {
    static function json($array){
        echo json_encode($array);
    }
    
    static function succeed($array = array()){
        $output = array(
            "status" => "succeed"
        );
        $output = array_merge($output, $array);
        echo json_encode($output);
    }
    
    static function error($info = array()){
        $output = array(
            "status" => "error",
            "error" => $info
        );
        echo json_encode($output);
    }
}
?>