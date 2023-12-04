<?php
    date_default_timezone_set('Europe/Rome');
    //include('lib/utils.php');
    define('DB_NAME', 'kquptvmy_labs');
	define('DB_USER', 'kquptvmy_labs');
	define('DB_PASSWORD', 'Pisello72!');
	define('DB_HOST', 'localhost');
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
    function operationsLog($op,$user,$app,$deviceId){
         $ret = null;
         $link = dbconnect();
          $urli = mysqli_real_escape_string($link,$op);
          $useri = mysqli_real_escape_string($link,$user);
          $appi = mysqli_real_escape_string($link,$app);
          $deviceIdi = mysqli_real_escape_string($link,$deviceId);
          $ipaddress = $_SERVER['REMOTE_ADDR'];
        
       //$query = "INSERT INTO `blk_urlblocked` (`op`, `ts`, `ip`, `user`, `app`) VALUES ('".$urli."', '0000-00-00 00:00:00', '".$ipaddress."', '".$useri."', '".$appi."');";
       $query = "INSERT INTO `blk_operations_log` (`op`, `ts`, `ip`, `user`, `deviceId`, `app`) VALUES ('".$urli."', '".date("Y-m-d H:i:s")."', '".$ipaddress."', '".$useri."', '".$deviceIdi."' , '".$appi."');";
       mysqli_query( $link , $query );	
       mysqli_close($link);
	}
    function insertUrl($url,$user,$app,$deviceId){
		$ret = null;
		$link = dbconnect();
        $urli = mysqli_real_escape_string($link,$url);
        $useri = mysqli_real_escape_string($link,$user);
        $appi = mysqli_real_escape_string($link,$app);
        $deviceIdi = mysqli_real_escape_string($link,$deviceId);
        $ipaddress = mysqli_real_escape_string($link,$_SERVER['REMOTE_ADDR']);
        $ua = mysqli_real_escape_string($link,$_SERVER['HTTP_USER_AGENT']);
		
      $query = "INSERT INTO `blk_urlblocked` (`url`, `ts`, `ip`,`ua`, `user`,`deviceId`, `app`) VALUES ('".$urli."',  '".date("Y-m-d H:i:s")."', '".$ipaddress."', '".$ua."', '".$useri."','".$deviceIdi."', '".$appi."');";
		mysqli_query( $link , $query );	
		mysqli_close($link);
	}
    function redirect($statusCode = 303){
        $url = strtok($_SERVER["REQUEST_URI"], '?');
        header('Location: ' . $url, true, $statusCode);
        die();
    }
    
    $url = $_GET["u"];
    $user = $_GET["i"];
    $app = $_GET["a"];
    $deviceId = $_GET["d"];
    
    //$url = 'https://www.camper.com';
    //$user = "u";
    //$app = "a";
    //$deviceId = 'idxx';
   
   if($url!=""){
   
    insertUrl($url,$user,$app,$deviceId);
    operationsLog("blockUrl",$user,$app,$deviceId);
    redirect();
   }
    
?>

<H1>URL Bloccato</H1>
