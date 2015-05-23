<?php
class user {
    function _check($COOKIE){
        $UNGROUP = fm::ungroup($COOKIE);
        $USERNAME = $UNGROUP[0];
        $PASSWORD = $UNGROUP[1];
        $SQL_USERNAME = fm::sql($USERNAME);
        $SQL_PASSWORD = fm::sign($PASSWORD);
        
        // Check if the user matches
        if (count(db::get("SELECT * FROM user WHERE username = '$SQL_USERNAME' AND password = '$SQL_PASSWORD'"))){
            return true;
        } else {
            return false;
        }
    }
    
    function _get_id($COOKIE){
        $UNGROUP = fm::ungroup($COOKIE);
        $USERNAME = $UNGROUP[0];
        $PASSWORD = $UNGROUP[1];
        $SQL_USERNAME = fm::sql($USERNAME);
        $SQL_PASSWORD = fm::sign($PASSWORD);
        
        // Check if the user matches
        $user = db::get("SELECT * FROM user WHERE username = '$SQL_USERNAME' AND password = '$SQL_PASSWORD'");
        if (count($user)){
            return intval($user[0]['user_id']);
        } else {
            return false;
        }
    }
    
    function create($DATA){
        // Check items
        $undefined = fm::undefined($DATA, array("username", "password")); 
        if ($undefined) {put::error("missing ".$undefined); return;}
        
        // Process data
        $USERNAME = $DATA['username'];
        $PASSWORD = $DATA['password'];
        $SQL_USERNAME = fm::sql($USERNAME);
        $SQL_PASSWORD = fm::sign($PASSWORD);
        
        // Generate Cookie
        $COOKIE = fm::group($USERNAME, $PASSWORD);
        
        // Check if the user exists
        if (count(db::get("SELECT * FROM user WHERE username = '$SQL_USERNAME'"))){
            put::error("user exists");
        } else {
            // Create the user and its profile
            $USER_ID = db::set("
                INSERT INTO user 
                (username, password, coin) VALUES 
                ('$SQL_USERNAME','$SQL_PASSWORD', 100)
            ");
            
            put::succeed(array(
                "user_id" => intval($USER_ID),
                "cookie" => $COOKIE
            ));
        }
    }
    
    function login($DATA){
        // Check items
        $undefined = fm::undefined($DATA, array("username", "password")); 
        if ($undefined) {put::error("missing ".$undefined); return;}
        
        // Process data
        $USERNAME = $DATA['username'];
        $PASSWORD = $DATA['password'];
        $SQL_USERNAME = fm::sql($USERNAME);
        $SQL_PASSWORD = fm::sign($PASSWORD);
        
        // Generate Cookie
        $COOKIE = fm::group($USERNAME, $PASSWORD);
        
        // Check if the user matches
        if (count(db::get("SELECT * FROM user WHERE username = '$SQL_USERNAME' AND password = '$SQL_PASSWORD'"))){
            put::succeed(array("cookie"=>$COOKIE));
        } else {
            put::error("username or password doesn't match");
        }
    }
    
    function get($DATA){
        // Check items
        $undefined = fm::undefined($DATA, array("cookie")); 
        if ($undefined) {put::error("missing ".$undefined); return;}
        
        // Cookie
        $COOKIE = $DATA['cookie'];
        
        // Process data
        $UNGROUP = fm::ungroup($COOKIE);
        $USERNAME = $UNGROUP[0];
        $PASSWORD = $UNGROUP[1];
        $SQL_USERNAME = fm::sql($USERNAME);
        $SQL_PASSWORD = fm::sign($PASSWORD);
        
        // Get user information
        $result = db::get("SELECT * FROM user WHERE username = '$SQL_USERNAME' AND password = '$SQL_PASSWORD'");
        if (count($result)){
            // Unset password item
            unset($result[0]['password']);
            put::succeed($result[0]);
        } else {
            put::error("cookie doesn't match");
        }
    }
}
?>