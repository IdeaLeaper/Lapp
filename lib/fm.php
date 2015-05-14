<?php
class fm {
    static function undefined($argv, $arr){
        foreach ($arr as $v) {
            if (!isset($argv[$v])){
                return $v;
            }
        }
        return false;
    }
    
    static function sign($string){
        return md5($string.CERT);
    }
    
    static function sql($string){
        return addslashes($string);
    }
    
    static function encode($string){
        $data = base64_encode($string);
        $data = str_replace(array('+','/','='),array('-','_',''),$data);
        return $data;
    }
    
    static function decode($string){
        $data = str_replace(array('-','_'),array('+','/'),$string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }
    
    static function group($left, $right){
        $result = strlen($left)."|".$left.$right;
        return self::encode($result);
    }
    
    static function ungroup($string){
        $in = self::decode($string);
        $length = intval(substr($in, 0, strpos($in,"|")));
        $right_part = substr($in, strpos($in, "|")+1);
        $left = substr($right_part, 0, $length);
        $right = substr($right_part, $length);
        return array($left, $right);
    }
}
?>