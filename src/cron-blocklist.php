<?php
    date_default_timezone_set('Europe/Rome');
    //include('lib/utils.php');
    define('DB_NAME', 'u421231893_dispatcher');
    define('DB_USER', 'u421231893_dispatcher');
    define('DB_PASSWORD', 'Dispatcher01!');
    define('DB_HOST', '217.21.76.151');
    define('DB_CHARSET', 'utf8');
         ob_start();
         session_start();
    
   function dbconnect(){
        $conn = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
        
        // Check connection
        if (!$conn) {
           die("Connection failed: " . $conn->connect_error);
           return false;
        } 
        return $conn;
  }
    
    function insertUrl($url,$user,$app,$deviceId){
		$ret = null;
		$link = dbconnect();
        $urli = mysqli_real_escape_string($link,$url);
        $useri = mysqli_real_escape_string($link,$user);
        $appi = mysqli_real_escape_string($link,$app);
        $deviceIdi = mysqli_real_escape_string($link,$deviceId);
        $ipaddress = $_SERVER['REMOTE_ADDR'];
		$query = "INSERT INTO `blk_urlblocked` (`url`, `ts`, `ip`, `user`,`deviceId`, `app`) VALUES ('".$urli."',  '".date("Y-m-d H:i:s")."', '".$ipaddress."', '".$useri."','".$deviceIdi."', '".$appi."');";
		mysqli_query( $link , $query );	
		mysqli_close($link);
	}
    function redirect($statusCode = 303){
        $url = strtok($_SERVER["REQUEST_URI"], '?');
        header('Location: ' . $url, true, $statusCode);
        die();
    }
	 
	  function getUsers(){
       //global $RHD_redirect;
       
         $link = dbconnect();
         $ret = array();
            $reta = array();
         $query = "SELECT `id`,`username`,`RHD_url`,`RHD_resolve`,`RHD_user`,`RHD_pwd`, 1 as enabled
         FROM `blk_users` WHERE COALESCE(`RHD_cron_enabled`,0) = 1 ";
           // echo $query;  (RHD_cron_last_ts is NULL OR CURRENT_TIMESTAMP > DATE_ADD(RHD_cron_last_ts, INTERVAL RHD_cron_sec SECOND)) as enabled
        $wh = "";
        //timestamp su RHD_cron_running_ts
        $wh .= " AND (RHD_cron_running_ts is NULL OR CURRENT_TIMESTAMP > DATE_ADD(RHD_cron_running_ts, INTERVAL 3 MINUTE)) ";
        //$wh .= " AND (RHD_cron_last_ts is NULL OR CURRENT_TIMESTAMP > DATE_ADD(RHD_cron_last_ts, INTERVAL RHD_cron_sec SECOND)) ";   
           
        $query.=$wh;   
           
			if($result = mysqli_query( $link , $query )){
					while ($row = mysqli_fetch_assoc($result)) {
						$ret[] = $row;
                        
                        
					}		
			}else{
          //insert_cron_log($opid,"SQL Error",$query,$error=true);
          echo  "User : ".$query."<br />\n";
      }
			mysqli_close($link);
			return $ret;
	}
   
    function curlGet($url, $resolve = NULL, $headers = NULL,$opid=false,$debug=false) {
        $ch = curl_init($url);
        if($resolve!=null) curl_setopt($ch, CURLOPT_RESOLVE, $resolve);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if($debug) curl_setopt($ch, CURLOPT_VERBOSE, true); //debug
        
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    
        //if (!empty($headers)) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        //}
    
        $response = curl_exec($ch);
    
        if (curl_error($ch)) {
            echo curl_error($ch);
           insert_cron_log($opid,$url,curl_error($ch),$error=true);
         }else{
            insert_cron_log($opid,$url,json_encode($response),$error=false);
         }
    
        curl_close($ch);
        return $response;
    }

function curlPost($url,$resolve, $data=NULL, $headers = NULL,$opid=false,$debug=false) {
    $ch = curl_init($url);
    if($resolve!=null) curl_setopt($ch, CURLOPT_RESOLVE, $resolve);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    if($debug) curl_setopt($ch, CURLOPT_VERBOSE, true); //debug
    
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    //if(!empty($data)){  //lo lascio sempre abilitato
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    //}

    //if (!empty($headers)) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    //}

    $response = curl_exec($ch);

    if (curl_error($ch)) {
        echo curl_error($ch);
        insert_cron_log($opid,$url,curl_error($ch),$error=true);
        trigger_error('Curl Error:' . curl_error($ch));
    }else{
       insert_cron_log($opid,$url,json_encode($response),$error=false);
    }

    curl_close($ch);
    return $response;
}

function loginRHD($username,$password,$RHDurl,$RHDResolve,$opid=false,$debug=false){
    // {"username":"d.masotti","password":"rhd"}

    //global $RHDurl,$RHDResolve;

    $url = $RHDurl.'/api/v2/app/'.'login';
    //print($url);
    $headers = array(
        "Accept: application/json" ,
        "Content-Type: application/x-www-form-urlencoded"
    
    );
    $json = array(
        "username"=>$username,
        "password"=>$password,
    );
    $data = "jsonString=".json_encode($json);

    $resolve = null;
   
    if($RHDResolve != null && $RHDResolve!=""){
       $resolve = array($RHDResolve);
    } 
    $r =  curlPost($url,$resolve, $data, $headers, $opid, $debug);
    if($r!=NULL){
        return json_decode($r,true);
    }
    return false;
}

function callRHD($url,$RHDurl,$RHDResolve,$authToken,$sessionid,$json=NULL,$jsonvar=NULL,$heads=NULL,$opid=false,$debug=false,$md5=false){
    // {"username":"d.masotti","password":"rhd"}
    
   // global $RHDurl,$RHDResolve;

    $urlv = $RHDurl.'/api/v2/'.$url;

    $headers = array(
        "Accept: application/json" ,
        "Content-Type: application/x-www-form-urlencoded",
        "AuthToken: " . $authToken,
        "Cookie: JSESSIONID=" . $sessionid
    
    );

    if($heads!=NULL && is_array($heads)){
        $headers=array_merge($headers,$heads);
    }
    $data = NULL;
    if($json!=NULL){
         if($jsonvar==NULL) $jsonvar="jsonString";
        $data = $jsonvar."=".json_encode($json);
    }
    $resolve = null;
    
    if($RHDResolve != null && $RHDResolve!=""){
       $resolve = array($RHDResolve);
    } 

    if($json===0){
            $r =  curlGet($urlv,$resolve, $headers, $opid, $debug);
    }else{
            $r =  curlPost($urlv,$resolve, $data, $headers, $opid, $debug);
    }
    
    if($r!=NULL){
      $ret = json_decode($r,true);
      if($md5){
        $ret['md5'] = md5($r);
       // print_r($ret);
      }
      return $ret;
    }
    return false;
}
function  create_cron_ops($userid){
      $ret = false;
		$link = dbconnect();
      $ua = '';
      $ip = '';
      if(array_key_exists('REMOTE_ADDR',$_SERVER)){
         $ip = $_SERVER['REMOTE_ADDR'];
      }
      if(array_key_exists('HTTP_USER_AGENT',$_SERVER)){
         $ua = $_SERVER['HTTP_USER_AGENT'];
      }
      
      $query = "INSERT INTO `blk_cron_ops` (`startts`,`user_id`,`ip`,`ua`)  VALUES (CURRENT_TIMESTAMP,'".$userid. "','".$ip. "','".$ua. "')";
		if(mysqli_query(  $link ,  $query  )){
					$ret = mysqli_insert_id($link);
		}		
		mysqli_close($link);
		return $ret;
}
function update_list($listid,$arrfields,$opid){
      $ret = false;
		$link = dbconnect();
      $query = "UPDATE `blk_lists` set `id` = $listid ";
      foreach($arrfields as $k=>$v){
         if($v=='CURRENT_TIMESTAMP'){
            $query .=  ",`".$k."` = CURRENT_TIMESTAMP";
         }else{
             if($v=='NULL'){
               $query .=  ",`".$k."` = NULL";
             }else{
               $query .=  ",`".$k."` = '".mysqli_real_escape_string($link,$v). "'";
             }
         }
         
      }
      $query .= " WHERE id='".$listid."'";
      //echo $query;
      if(mysqli_query(  $link ,  $query  )){
         $ret = true;
      }else{
			insert_cron_log($opid,"SQL Error",$query,$error=true);
		}
		mysqli_close($link); 
		return $ret;
}
function update_user($userid,$arrfields){
      $ret = false;
		$link = dbconnect();
      $query = "UPDATE `blk_users` set `id` = $userid ";
      foreach($arrfields as $k=>$v){
         if($v=='CURRENT_TIMESTAMP'){
            $query .=  ",`".$k."` = CURRENT_TIMESTAMP";
         }else{
             if($v=='NULL'){
               $query .=  ",`".$k."` = NULL";
             }else{
               $query .=  ",`".$k."` = '".mysqli_real_escape_string($link,$v). "'";
             }
         }
         
      }
      $query .= " WHERE id='".$userid."'";
      if(mysqli_query(  $link ,  $query  )){
         $ret = true;
      }else{
			//insert_cron_log($opid,"SQL Error",$query,$error=true);
		}
		mysqli_close($link); 
		return $ret;
}
function  update_cron_ops($opid,$arrfields){
      $ret = false;
		$link = dbconnect();
      $query = "UPDATE `blk_cron_ops` set `currts` = CURRENT_TIMESTAMP ";
      foreach($arrfields as $k=>$v){
         if($v=='CURRENT_TIMESTAMP'){
            $query .=  ",`".$k."` = CURRENT_TIMESTAMP";
         }else{
             if($v=='NULL'){
               $query .=  ",`".$k."` = NULL";
             }else{
               $query .=  ",`".$k."` = '".mysqli_real_escape_string($link,$v). "'";
             }
         }
         
      }
      $query .= " WHERE id='".$opid."'";
      if(mysqli_query(  $link ,  $query  )){
         $ret = true;
      }else{
        insert_cron_log($opid,"SQL Error",$query,$error=true);
      }
		mysqli_close($link); 
		return $ret;
}

function keyEl($arr,$k){
   if(is_array($arr) && array_key_exists($k,$arr)){
      return $arr[$k];
   }
   return "";
} 

function composeUrl($u){
    $utp = parse_url($u);
    $s = keyEl($utp,'scheme');
    if($s == 'http' || $s == 'https' ){
         
    }else{
    
    }
    $h = keyEl($utp,'host');
    $p = keyEl($utp,'path');
    $q = keyEl($utp,'query');
    $o = keyEl($utp,'port');
    
    $ret = $h;
    if($p!="" && $p!="/" ){
      $ret .=$p;
    }
    if($o!=""){
      $ret .= ":".$o;
    }
    return $ret;
    
    /*
    
    [scheme] => http
    [host] => hostname
    [port] => 8080
    [user] => username
    [pass] => password
    [path] => /path
    [query] => arg=value
    [fragment] => anchor
    
    */
}


function  update_urls_in_list($arrurls,$opid,$list_id,$md5){
      $ret = true;
      $link = dbconnect();
      $c = 0;
      
      
       $query = "SELECT `updated`,`version`,`md5` FROM `blk_lists` WHERE id='".$list_id."' ";
           
      if($result = mysqli_query( $link , $query )){
					if ($row = mysqli_fetch_assoc($result)) {
                   print_r($row);
                   if($md5 != $row["md5"] ){
                    
                      mysqli_begin_transaction($link ,MYSQLI_TRANS_START_READ_WRITE, $name = null);
         
                       try {
                                $query = "DELETE FROM `blk_urls` WHERE list = '".$list_id."'";
                                if(!mysqli_query(  $link ,  $query  )){
                                       throw "Error: ".$query;   
                                }
                                $go = true;
                                $gomsg = '';
                               foreach($arrurls as $u){
                                  $ut = trim($u);
                                  if($go && $ut==""){
                                     $go = false;
                                     $gomsg = 'URL Skipped: url void';
                                  }
                                  
                                  $ut = composeUrl($ut);
                                  //$utp = parse_url($ut);
                                  //print_r($utp);
                                  $utc = strlen($ut);
                                  if($go && ($utc>400)){
                                     $go = false;
                                     $gomsg = 'URL Skipped: too long '.$utc;
                                  }
                                  
                                  
                                  if($go){
                                     $query = "INSERT INTO blk_urls(url, type, list) VALUES ('".mysqli_real_escape_string($link, $ut)."','0','".$list_id."')";
                                     if(mysqli_query(  $link ,  $query  )){
                                        $c++;
                                     }
                                  }else{
                                      echo $gomsg."<br />\n".$u."<br />\n<br />\n<br />\n";
                                      insert_cron_log($opid,$gomsg,$u,$error=true);
                                  }
                                  
                               }
                               
                               update_list($list_id,array( "urls"=>$c, "md5"=> $md5 ,"updated"=>"CURRENT_TIMESTAMP" ),$opid);
                               
                               // SELECT `id`,`description`,`updated`,`version` FROM `blk_lists` WHERE
                               
                               mysqli_commit($link, $flags = 0,  $name = null);
                           } catch (mysqli_sql_exception $exception) {
                                mysqli_rollback($link, $flags = 0, $name = null);
                                insert_cron_log($opid,"Exception Error",$exception->getMessage(),$error=true);
                                $ret = false;
                                throw $exception;
                           }
                   
                   }else{
                      echo "Get URLS: Aggiornamento domini non variato<br />\n<br />\n<br />\n";
                      insert_cron_log($opid,"Get URLS","Aggiornamento domini non variato",$error=false);
                    
                   }
                        
					}		
			}else{
          //insert_cron_log($opid,"SQL Error",$query,$error=true);
          echo  "GetList : ".$query."<br />\n";
      }
      
      //$arrurls = $s_output["responseData"];
      
      
		mysqli_close($link);
      if($ret){
         return $c;
      }else{
         return null;
      }
}

function getUrlReportedNum($userid){
       
         $link = dbconnect();
         $ret = 0;
       
         $query = "SELECT count(*) as TOT FROM `blk_urlreported` WHERE user = (SELECT username FROM blk_users WHERE id='".$userid."')";
           
      if($result = mysqli_query( $link , $query )){
					if ($row = mysqli_fetch_assoc($result)) {
						$ret = $row['TOT'];
                        
                        
					}		
			}else{
          //insert_cron_log($opid,"SQL Error",$query,$error=true);
          echo  "getUrlReportedNum : ".$query."<br />\n";
      }
			mysqli_close($link);
			return $ret;
	}
  
  function getUrlBlockedNum($userid){
       
         $link = dbconnect();
         $ret = 0;
       
         $query = "SELECT count(*) as TOT FROM `blk_urlblocked` WHERE user = (SELECT username FROM blk_users WHERE id='".$userid."')";
           
      if($result = mysqli_query( $link , $query )){
					if ($row = mysqli_fetch_assoc($result)) {
						$ret = $row['TOT'];
                        
                        
					}		
			}else{
          //insert_cron_log($opid,"SQL Error",$query,$error=true);
          echo  "getUrlBlockedNum : ".$query."<br />\n";
      }
			mysqli_close($link);
			return $ret;
	}

function sendUrlReported($RHD_url,$RHD_resolve,$authToken,$sessionId,$opid,$userid){
      $ret = true;
		$link = dbconnect();
      $c = 0;
      
      //$arrurls = $s_output["responseData"];
      mysqli_begin_transaction($link ,MYSQLI_TRANS_START_READ_WRITE, $name = null);
      
      try {
         
          $arrUrls = array(); 
          $query = "SELECT url,deviceId FROM `blk_urlreported` WHERE user = (SELECT username FROM blk_users WHERE id='".$userid."')";  
          //$query = "DELETE FROM `blk_urls` WHERE list = '".$list_id."'";
          
          if($result = mysqli_query( $link , $query )){
                while ($row = mysqli_fetch_assoc($result)) {
                    $arrUrls[] = array(
                          'url' => urlencode($row['url']),
                          'deviceid'=>urlencode($row['deviceId']),
                    );
                }		
          }else{
            
                throw "Error: ".$query;   
          }
         $c = count($arrUrls);
         if($c>0){
               $jdata = array(
                     "urls" => $arrUrls
               );
               //var_dump($jdata);
               //  {"urls": [	{"url": "value"}]}
               $s_output = callRHD("fm360/phishing/urls",$RHD_url,$RHD_resolve,$authToken,$sessionId,$json=$jdata,$jsonvar="phishingUrl",$heads=NULL,$opid,$debug=false); //$json=0 indica get , $json=NULL implica post
               //var_dump($s_output);
               if(!array_key_exists( "responseData",$s_output ) || trim($s_output["responseCode"])!="OK" ){
                  if(array_key_exists( "responseMsg",$s_output ) ){
                     echo  "URL Reported ERRORE : ".$s_output["responseMsg"]."<br />\n";
                     insert_cron_log($opid,"URL Reported Error",$s_output["responseMsg"],$error=true);
                  }else{
                      echo "URL Reported ERRORE"."<br />\n";
                      insert_cron_log($opid,"URL Reported Error","Server Error",$error=true);
                  }
               }else{
                  
                     echo "URL Reported: user $userid segnalati $c urls <br>\n<br>\n<br>\n";
                     update_cron_ops($opid,array( "url_reported"=>$c,"currts"=>"CURRENT_TIMESTAMP" ));
                     
                     $query = "DELETE FROM `blk_urlreported` WHERE user = (SELECT username FROM blk_users WHERE id='".$userid."')";
                     
                     if(!mysqli_query(  $link ,  $query  )){
                            throw "URL Reported Error: ".$query;   
                     }
                     echo "URL Reported user $userid azzerati url reported <br>\n<br>\n<br>\n";
                     
               }           
         }else{
             echo "URL Reported: Nessun url riportato<br />\n<br />\n<br />\n";
            insert_cron_log($opid,"URL Reported","Nessun url riportato",$error=false);
         }
      
         
         mysqli_commit($link, $flags = 0,  $name = null);
     } catch (mysqli_sql_exception $exception) {
          mysqli_rollback($link, $flags = 0, $name = null);
          echo  "ERRORE URL Reported : ".$exception->getMessage();
          insert_cron_log($opid,"Exception Error",$exception->getMessage(),$error=true);
          $ret = false;
          //throw $exception;
     }
      
		mysqli_close($link);
      if($ret){
         return $c;
      }else{
         return null;
      }
   
}


function sendUrlBlocked($RHD_url,$RHD_resolve,$authToken,$sessionId,$opid,$userid){
      $ret = true;
		$link = dbconnect();
      $c = 0;
      
      //$arrurls = $s_output["responseData"];
      mysqli_begin_transaction($link ,MYSQLI_TRANS_START_READ_WRITE, $name = null);
      
      try {
         
          $arrUrls = array(); 
          $query = "SELECT url, deviceId, ua, ip, ts FROM `blk_urlblocked` WHERE user = (SELECT username FROM blk_users WHERE id='".$userid."')";  
          //$query = "DELETE FROM `blk_urls` WHERE list = '".$list_id."'";
          // {"victims": [{"clickedurl": "[value]", "useragent": "[value]","deviceid": "[value]","clickedts": "[value]"}]}
          
          if($result = mysqli_query( $link , $query )){
					while ($row = mysqli_fetch_assoc($result)) {
						$arrUrls[] = array(
                       'clickedurl'=>urlencode($row['url']),
                       'useragent'=>urlencode($row['ua']),
                       'deviceid'=>urlencode($row['deviceId']),
                       'clickedts'=>urlencode($row['ts']),
                  );
               }		
			}else{
            
                 throw "Error: ".$query;   
         }
         $c = count($arrUrls);
         if($c>0){
               $jdata = array(
                     "victims" => $arrUrls
               );
               //var_dump($jdata);
               //  {"urls": [	{"url": "value"}]}
               $s_output = callRHD("fm360/phishing/victims",$RHD_url,$RHD_resolve,$authToken,$sessionId,$json=$jdata,$jsonvar="phishingVictim",$heads=NULL,$opid,$debug=false); //$json=0 indica get , $json=NULL implica post
               //var_dump($s_output);
               if(!array_key_exists( "responseData",$s_output ) || trim($s_output["responseCode"])!="OK" ){
                  if(array_key_exists( "responseMsg",$s_output ) ){
                     echo  "VICTIMS ERRORE : ".$s_output["responseMsg"]."<br />\n";
                     insert_cron_log($opid,"VICTIMS Error",$s_output["responseMsg"],$error=true);
                  }else{
                      echo "VICTIMS ERRORE"."<br />\n";
                      insert_cron_log($opid,"VICTIMS Error","Server Error",$error=true);
                  }
               }else{
                  
                     echo "VICTIMS user $userid segnalati $c victims <br>\n<br>\n<br>\n";
                     update_cron_ops($opid,array("url_victims"=>$c,"currts"=>"CURRENT_TIMESTAMP" ));
                     
                     $query = "DELETE FROM `blk_urlblocked` WHERE user = (SELECT username FROM blk_users WHERE id='".$userid."')";
                     if(!mysqli_query(  $link ,  $query  )){
                            throw "VICTIMS Error: ".$query;   
                     }
                     echo "VICTIMS user $userid azzerate victims <br>\n<br>\n<br>\n";
                  
               }           
         }else{
             echo "VICTIMS: Nessuna victims da segnalare<br />\n<br />\n<br />\n";
             insert_cron_log($opid,"URL Victims","Nessuna victims da segnalare",$error=false);
         }
      
         
         mysqli_commit($link, $flags = 0,  $name = null);
     } catch (mysqli_sql_exception $exception) {
          mysqli_rollback($link, $flags = 0, $name = null);
          echo  "VICTIMS ERROR : ".$exception->getMessage()."<br />\n";
          insert_cron_log($opid,"VICTIMS Exception Error",$exception->getMessage(),$error=true);
          $ret = false;
          //throw $exception;
     }
      
		mysqli_close($link);
      if($ret){
         return $c;
      }else{
         return null;
      }
   
}

function insert_cron_log($opid,$oper,$msg,$error=false){
      $ret = false;
		$link = dbconnect();
      $oper = mysqli_real_escape_string($link, $oper);
      $msg = mysqli_real_escape_string($link, $msg);
      $query = "INSERT INTO `blk_cron_log` (`ts`,`op_id`,`error`,`operation`,`msg`)  VALUES (CURRENT_TIMESTAMP,'".$opid. "','".($error?1:0). "','".$oper. "','".$msg ."')";
		if(mysqli_query(  $link ,  $query  )){
					$ret = mysqli_insert_id($link);
		}		
		mysqli_close($link);
		return $ret;
}

function log_prune(){
   // sfoltimento log
   $link = dbconnect();
   $query = "DELETE FROM `blk_cron_log` WHERE DATE_ADD(ts, INTERVAL 3 DAY) IS NULL OR DATE_ADD(ts, INTERVAL 3 DAY) < CURRENT_TIMESTAMP";
   if(!mysqli_query(  $link ,  $query  )){
                     //       throw "VICTIMS Error: ".$query;   
   }
   $query = "DELETE FROM `blk_cron_ops` WHERE DATE_ADD(startts, INTERVAL 3 DAY) IS NULL OR DATE_ADD(startts, INTERVAL 3 DAY) < CURRENT_TIMESTAMP";
   if(!mysqli_query(  $link ,  $query  )){
                     //       throw "VICTIMS Error: ".$query;   
   }
   mysqli_close($link);

}


function doLoop(){
    
   $usrs = getUsers();
   //print_r($usrs);
   echo "Users num : ".count($usrs) ." <br />\n<br />\n<br />\n";
   insert_cron_log(0,"Cron loop","Users num : ".count($usrs),$error=false);
   foreach($usrs as $u){
       
       $RHD_url = trim($u['RHD_url']);// => https://demo.rhd.it:8080/rhd-fm360
       $RHD_resolve = (trim($u['RHD_resolve'])!="")?trim($u['RHD_resolve']):null;// => demo.rhd.it:8080:34.68.26.211
       $RHD_user = trim($u['RHD_user']);// => superadmin
       $RHD_pwd = $u['RHD_pwd'];// => 4LF4.superadmin
       $enabled = $u['enabled'];// => 4LF4.superadmin
     //  $RHD_user = "superadmin";
     //  $RHD_pwd = "4LF4.superadmin!";
       
       $userid = intval($u['id']);
       echo "Loop User : ".$u['username']." ($userid) <br />\n<br />\n<br />\n";
       if($userid > 0 && $RHD_url != "" && $RHD_user != "" ){
            update_user($userid,array("RHD_cron_running_ts"=>"CURRENT_TIMESTAMP" ));
            $opid = create_cron_ops($userid);
            if($opid===false){
               insert_cron_log(0,"Login Error","Op not created",$error=true);
            }else{
              $urlRepNum = getUrlReportedNum($userid);
              $urlBlockNum = getUrlBlockedNum($userid);
              
              if($enabled==1 || $urlRepNum>0 || $urlBlockNum>0 ){
                  $server_output = loginRHD($RHD_user,$RHD_pwd,$RHD_url,$RHD_resolve,$opid);
                
                  if ($server_output !== false) { 
                 
                        if(
                                array_key_exists( "responseCode",$server_output ) &&  $server_output["responseCode"]=="OK" &&
                                array_key_exists( "responseData",$server_output ) 
                        ){
                              $authToken = $server_output["responseData"]["authToken"];
                              $sessionId = $server_output["responseData"]["sessionId"];
                              echo "Logged in RHD with authToken $authToken sessionId $sessionId  \n";
                    
                              try {
                                 
                                  //$s_output = callRHD("companies",$authToken,$sessionId,$json=0,$heads=NULL,$debug=false); //$json=0 indica get , $json=NULL implica post
                                  
                                  //CARICA URL
                                  if($enabled==1){
                                       $s_output = callRHD("fm360/phishing/urls",$RHD_url,$RHD_resolve,$authToken,$sessionId,$json=0,$jsonvar=NULL,$heads=NULL,$opid,$debug=false,$md5=true); //$json=0 indica get , $json=NULL implica post
                                        if(!array_key_exists( "responseData",$s_output ) || trim($s_output["responseCode"])!="OK" ){
                                           if(array_key_exists( "responseMsg",$s_output ) ){
                                              echo  "ERRORE : ".$s_output["responseMsg"];
                                              insert_cron_log($opid,"Get URLS Error",$s_output["responseMsg"],$error=true);
                                           }else{
                                               echo "ERRORE";
                                               insert_cron_log($opid,"Get URLS Error","Server Error",$error=true);
                                           }
                                        }else{
                                           $arrurls = $s_output["responseData"];
                                           $md5 = $s_output["md5"];
                                           $co_urls = count($arrurls);
                                           $c_urls = update_urls_in_list($arrurls,$opid,$list_id=1,$md5);
                                           if($c_urls===false){
                                              insert_cron_log($opid,"Get URLS Error","Error in count urls",$error=true);
                                           }else{
                                              echo "Aggiornata lista $list_id uploadati $c_urls urls su  $co_urls <br>\n<br>\n<br>\n";
                                              update_cron_ops($opid,array("list_id"=>$list_id, "url_read"=>$co_urls, "url_uploaded"=>$c_urls,"currts"=>"CURRENT_TIMESTAMP" ));
                                           }
                                        }
                                   }else{
                                      echo "Get URLS: Aggiornamento domini skipped<br />\n<br />\n<br />\n";
                                      insert_cron_log($opid,"Get URLS","Aggiornamento domini skipped",$error=false);
                                    
                                   }
                                    
                                   //SEGNALA URL da parte di clienti 
                                    
                                  if($urlRepNum>0) sendUrlReported($RHD_url,$RHD_resolve,$authToken,$sessionId,$opid,$userid);
                                  
                                  if($urlBlockNum>0) {
                                    sendUrlBlocked($RHD_url,$RHD_resolve,$authToken,$sessionId,$opid,$userid);
                                  }else{
                                      echo "VICTIMS: Nessuna victims da segnalare<br />\n<br />\n<br />\n";
                                      insert_cron_log($opid,"URL Victims","Nessuna victims da segnalare",$error=false);
                                  }  
                       //            responseCode\":\"OK\",\"responseMsg\":\"DataRetrieved\",\"responseData\":
                            
                                 //  var_dump($s_output);
                                 
                                 
                              } catch (Exception $e) {
                                  echo  "ERRORE : ".$e->getMessage();
                                  insert_cron_log($opid,"Exception Error",$e->getMessage(),$error=true);
                                  update_cron_ops($opid,array("error"=>1, "msg"=>$e->getMessage() ));
                              //    echo 'Caught exception: ',  $e->getMessage(), "\n";
                              } 
                           
                           $s_output = callRHD("app/logout",$RHD_url,$RHD_resolve,$authToken,$sessionId,$json=NULL,$jsonvar=NULL,$heads=NULL,$opid,$debug=false);
                           if(array_key_exists( "responseCode",$s_output ) && $s_output["responseCode"]=="OK" ){
                               
                            }else{
                                if(array_key_exists( "responseMsg",$s_output ) ){
                                    echo  "ERRORE : ".$s_output["responseMsg"];
                                    insert_cron_log($opid,"Logout Error",$s_output["responseMsg"],$error=true);
                                }else{
                                     echo "ERRORE";
                                     insert_cron_log($opid,"Logout Error","Server Error",$error=true);
                                }
                            }
                            update_cron_ops($opid,array("endts"=>"CURRENT_TIMESTAMP","currts"=>"NULL" ));
                          // var_dump($s_output);
                    
                        }else{
                            if(array_key_exists( "responseMsg",$server_output ) ){
                               echo  "ERRORE : ".$server_output["responseMsg"];
                               insert_cron_log($opid,"Login Error",$server_output["responseMsg"],$error=true);
                            }else{
                                echo "ERRORE";
                                insert_cron_log($opid,"Login Error","Server Error",$error=true);
                            }
                        }
                  }
            }     
            
         } 
          update_user($userid,array("RHD_cron_running_ts"=>"NULL","RHD_cron_last_ts"=>"CURRENT_TIMESTAMP" ));
       }else{
          insert_cron_log(0,"User Error","User setting non valid (user: $userid ) ",$error=true);
       }
   }

   log_prune();
   
}
//echo "aaa";

doLoop();
/*sleep(5);
doLoop();
sleep(5);
doLoop();
sleep(5);
doLoop();
sleep(5);
doLoop();
sleep(5);
doLoop();
sleep(5);
doLoop();
sleep(5);
doLoop();
sleep(5);
doLoop();
sleep(5);
doLoop();*/
?>


