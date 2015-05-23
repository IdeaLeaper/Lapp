<?php
class fm {
    /* Return undefined item */
    static function undefined($argv, $arr){
        foreach ($arr as $v) {
            if (!isset($argv[$v]) || trim($argv[$v]) == ""){
                return $v;
            }
        }
        return false;
    }
    
    /* Set default for the numbers (sometimes use for text)*/
    static function set_default($var, $default, $start = false){
        if(empty($var)){
            return $default;
        } else {
            if($start === false){
                return $var;
            } else {
                if(intval($var)<$start){
                    return $default;
                } else {
                    return $var;
                }
            }
        }
    }
    
    /* Both English and Chinese substr */
    static function truncate($string, $len = 60, $cnCharWidth = 2) {  
      
        $len = $len * $cnCharWidth;  
        $suffix = "...";  
        $newStr = "";  
      
        for ($i = 0, $j = 0; $i < $len; $i++, $j++) {  
      
            if (!isset($string[$j])) {  
                $suffix = "";  
                break;  
            }  
      
            $start = $j;  
            while ($j < ($start +3) && !(ord($string[$j]) < 0x80)) {  
                $j++;  
            }  
            if ($start == $j) {  
                $charLen = 1;  
            }  
            else {  
                $i = $i + 1;  
                $j--;  
                $charLen = 3;  
            }  
      
            $newStr .= substr($string, $start, $charLen);  
        }  
      
        return $newStr . $suffix;  
    }
    
    /* MD5 with salt*/
    static function sign($string){
        return md5($string.CERT);
    }
    
    /* addslashes */
    static function sql($string){
        return addslashes(trim($string));
    }
    
    /* URLsafe base64 */
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
    
    /* Lapp groupper */
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