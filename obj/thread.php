<?php
class thread {
    function create($DATA){
        // Check items
        $undefined = fm::undefined($DATA, array(
            "title", 
            "content",
            "image_url",
            "tags",
            "cookie"
        ));
        if ($undefined) {put::error("missing ".$undefined); return;}
        
        // Check Cookie
        $COOKIE = $DATA['cookie'];
        $obj_user = new user;
        if(!$obj_user -> _check($COOKIE)){
            put::error("cookie doesn't match");
            return;
        }
        
        // Process data
        $SQL_TITLE = fm::sql($DATA['title']);
        $SQL_CONTENT = fm::sql($DATA['content']);
        $SQL_IMAGE_URL = fm::sql($DATA['image_url']);
        $SQL_AUTH = fm::ungroup($COOKIE)[0];
        $SQL_TIMESTAMP = time();
        
        // Process Tags
        $TAGS = $DATA['tags'];
        foreach ($TAGS as $k => $v){
            $SQL_TAGS[$k] = fm::sql($v);
        }
        
        // Create Thread
        $THREAD_ID = db::set("
            INSERT INTO thread
            (title, content, image_url, auth, time) VALUES 
            (
                '$SQL_TITLE',
                '$SQL_CONTENT',
                '$SQL_IMAGE_URL',
                '$SQL_AUTH',
                 $SQL_TIMESTAMP
            )
        ");
        
        // Add Tags
        foreach ($SQL_TAGS as $v){
            db::set("
                INSERT INTO tags
                (thread_id, name) VALUES 
                ($THREAD_ID, $v)
            ");
        }
        
        put::succeed(array(
            "thread_id" => $THREAD_ID
        ));
    }
    
    function recently($DATA){
        // Default page num
        if(fm::undefined($DATA, array("page"))){
            $PAGE = 1; 
        } else {
            $PAGE = intval($DATA['page']);
        }
        
        // Count pages
        $rows = db::rows("SELECT * FROM thread");
        $pagesize = 10;
        $pages = intval($rows / $pagesize) + 1;
		
		if($rows % $pagesize == 0){
			$pages = $pages - 1;
		}
        
        // Set offset (use for mysql)
        $offset = $pagesize * ($PAGE - 1);
        
        // Get threads
        $result = db::get("SELECT * FROM thread ORDER BY thread_id DESC limit $offset, $pagesize");
        put::succeed(array("pagenow"=>$PAGE, "pages"=>$pages, "threads"=>$result));
    }
}
?>