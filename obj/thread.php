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
        if(!$obj_user->_check($COOKIE)){
            // Output Error JSON
            put::error("cookie doesn't match");
            return;
        }
        
        // Process data
        $SQL_TITLE = fm::sql($DATA['title']);
        $SQL_CONTENT = fm::sql($DATA['content']);
        $SQL_IMAGE_URL = fm::sql($DATA['image_url']);
        $UNGROUP = fm::ungroup($COOKIE);
        $SQL_AUTH = $obj_user->_get_id($COOKIE); // Uses user Object
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
                INSERT INTO tag
                (thread_id, name) VALUES 
                ($THREAD_ID, $v)
            ");
        }
        
        // Output JSON
        put::succeed(array("thread_id"=>$THREAD_ID));
    }
    
    function recently($DATA){
        // Default page num
        $PAGE = intval(fm::set_default(@$DATA['page'], 1, 1));
        
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
        
        // Generate threads
        $threads = array();
        foreach ($result as $k=>$v){
            $threads[$k] = array(
                "thread_id" => intval($v['thread_id']),
                "title" => $v['title'],
                "excerpt" => fm::truncate($v['content']),
                "image_url" => $v['image_url']
            );
        }
        
        // Output JSON
        put::succeed(array(
            "pagenow" => $PAGE, 
            "pages" => $pages, 
            "threads" => $threads
        ));
    }
    
    function search($DATA){
        // Check items
        $undefined = fm::undefined($DATA, array("search"));
        if ($undefined) {put::error("missing ".$undefined); return;}
        
        // Default page num
        $PAGE = intval(fm::set_default(@$DATA['page'], 1, 1));
        
        // Count pages
        $rows = db::rows("SELECT * FROM thread");
        $pagesize = 10;
        $pages = intval($rows / $pagesize) + 1;
		
		if($rows % $pagesize == 0){
			$pages = $pages - 1;
		}
        
        // Set offset (use for mysql)
        $offset = $pagesize * ($PAGE - 1);
        
        // Set Search Word
        $QUERY_STRING = $DATA['search'];
        $SEARCH = "";
        for($i=0; $i<=mb_strlen($QUERY_STRING,"utf-8")-1;$i++){
            $SEARCH = $SEARCH.mb_substr($QUERY_STRING, $i, 1, "utf-8")."%";
        }
        $SQL_SEARCH = fm::sql($SEARCH);
        
        // Get threads
        $result = db::get("
            SELECT * FROM thread
            WHERE CONCAT(`title`,`content`) LIKE '%$SEARCH'
            ORDER BY thread_id DESC limit $offset, $pagesize
        ");
        
        // Generate threads
        $threads = array();
        foreach ($result as $k=>$v){
            $threads[$k] = array(
                "thread_id" => intval($v['thread_id']),
                "title" => $v['title'],
                "excerpt" => fm::truncate($v['content']),
                "image_url" => $v['image_url']
            );
        }
        
        // Output JSON
        put::succeed(array(
            "pagenow" => $PAGE, 
            "pages" => $pages, 
            "threads" => $threads
        ));
    }
    
    function tag($DATA){
        // Check items
        $undefined = fm::undefined($DATA, array("tag"));
        if ($undefined) {put::error("missing ".$undefined); return;}
        
        // Default page num
        $PAGE = intval(fm::set_default(@$DATA['page'], 1, 1));
        
        // Count pages
        $rows = db::rows("SELECT * FROM thread");
        $pagesize = 10;
        $pages = intval($rows / $pagesize) + 1;
		
		if($rows % $pagesize == 0){
			$pages = $pages - 1;
		}
        
        // Set offset (use for mysql)
        $offset = $pagesize * ($PAGE - 1);
        
        // Set Search Tag
        $QUERY_STRING = $DATA['tag'];
        $SEARCH = "";
        for($i=0; $i<=mb_strlen($QUERY_STRING,"utf-8")-1;$i++){
            $SEARCH = $SEARCH.mb_substr($QUERY_STRING, $i, 1, "utf-8")."%";
        }
        $SQL_SEARCH = fm::sql($SEARCH);
        
        // Get the list of threads by tag
        $result = db::get("
            SELECT * FROM tag
            WHERE name LIKE '%$SEARCH'
            ORDER BY thread_id DESC limit $offset, $pagesize
        ");
        
        // Generate threads
        $threads = array();
        for($i=0; $i<=count($result)-1; $i++){
            $thread_id = intval($result[$i]['thread_id']);
            $result = db::get("SELECT * FROM thread WHERE thread_id = $thread_id");
            
            // Generate thread
            $get_thread = array(
                "thread_id" => intval($result[0]['thread_id']),
                "title" => $result[0]['title'],
                "excerpt" => fm::truncate($result[0]['content']),
                "image_url" => $result[0]['image_url']
            );
            $threads[] = $get_thread;
        }
        
        // Output JSON
        put::succeed(array(
            "pagenow" => $PAGE, 
            "pages" => $pages, 
            "threads" => $threads
        ));
    }
    
    function user($DATA){
        // Check items
        $undefined = fm::undefined($DATA, array("user_id"));
        if ($undefined) {put::error("missing ".$undefined); return;}
        
        // Default page num
        $PAGE = intval(fm::set_default(@$DATA['page'], 1, 1));
        
        // Count pages
        $rows = db::rows("SELECT * FROM thread");
        $pagesize = 10;
        $pages = intval($rows / $pagesize) + 1;
		
		if($rows % $pagesize == 0){
			$pages = $pages - 1;
		}
        
        // Set offset (use for mysql)
        $offset = $pagesize * ($PAGE - 1);
        
        // Set Search Word
        $QUERY_STRING = 
        $SQL_SEARCH = intval($DATA['user_id']);
        
        // Get threads
        $result = db::get("
            SELECT * FROM thread
            WHERE auth = '$SQL_SEARCH'
            ORDER BY thread_id DESC limit $offset, $pagesize
        ");
        
        // Generate threads
        $threads = array();
        foreach ($result as $k=>$v){
            $threads[$k] = array(
                "thread_id" => intval($v['thread_id']),
                "title" => $v['title'],
                "excerpt" => fm::truncate($v['content']),
                "image_url" => $v['image_url']
            );
        }
        
        // Output JSON
        put::succeed(array(
            "pagenow" => $PAGE, 
            "pages" => $pages, 
            "threads" => $threads
        ));
    }
}
?>