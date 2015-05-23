<?php
class db{
    /* get mysql data */
    static function get($sql)
    {
        $link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS);
        mysqli_select_db($link, MYSQL_DB);
        mysqli_query($link, "set names utf8");
        $result = mysqli_query($link, $sql);
        
        $gets = array();
        while ($rows = mysqli_fetch_array($result)){
            $keys = count($rows) / 2 - 1;
            for ($i = 0; $i <= $keys; $i++){
                unset($rows[$i]);
            }
            
            foreach ($rows as $key => $value){
                if(is_numeric($value)){
                    $rows[$key] = floatval($value);
                }
            }
            
            $gets[] = $rows;
        }
        
        return $gets;
    }
    
    /* get rows number */
    static function rows($sql){
        $link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS);
        mysqli_select_db($link, MYSQL_DB);
        mysqli_query($link, "set names utf8");
        $result = mysqli_query($link, $sql);
        
        return mysqli_num_rows($result);
    }
    
    /* insert or update mysql data */
    static function set($sql)
    {
        $link = mysqli_connect(MYSQL_HOST, MYSQL_USER, MYSQL_PASS);
        mysqli_select_db($link, MYSQL_DB);
        mysqli_query($link, "set names utf8");
        if(mysqli_query($link, $sql)){
            return mysqli_insert_id($link);
        } else {
            return false;
        }
    }
}
?>