<?php
    date_default_timezone_set('Europe/Rome');
   
    define('DB_NAME', getenv('MYSQL_DATABASE'));
	define('DB_PASSWORD', getenv('MYSQL_PASSWORD'));
    
    define('DB_USER', getenv("MYSQL_USER"));
	define('DB_HOST', getenv("MYSQL_HOST"));
	define('DB_CHARSET', 'utf8'); 
    
    ob_start();
    session_start();

 function verificaFirma($signedContent, $publicKeyPEM, $buffer) {
 // Estrai la firma dalla stringa $signedContent
 preg_match('/<!-- Signature: (.+?) -->/', $signedContent, $matches);
 
 if (isset($matches[1])) {
     $base64Signature = $matches[1];
     
             //		    $message = "Il tuo messaggio da firmare";
     //openssl_sign($message, $signature, $loadedPrivateKey, OPENSSL_ALGO_SHA256);

     //if (openssl_verify($buffer, $signature, $loadedPublicKey, OPENSSL_ALGO_SHA256) === 1) {
     //    echo "Verifica della firma OK.";
     //} else {
     //    echo "Verifica della firma fallita.";
     //}
     
     // Decodifica la firma base64 in binario
     $signature = base64_decode($base64Signature);
     //echo "\n".$base64Signature."\n";
     // Estrai il contenuto originale dalla stringa $signedContent
     $signaturePosition = strpos($signedContent, "\n<!-- Signature: ");
    // if ($signaturePosition !== false) {
     // Estrai la parte del contenuto prima della firma
     $originalContent = substr($signedContent, 0, $signaturePosition);
     
     // echo confrontaCaratterePerCarattere($originalContent,$buffer);
     
     // Carica la chiave pubblica
     $loadedPublicKey = openssl_pkey_get_public($publicKeyPEM);
     
     // Verifica la firma
     $result = openssl_verify($originalContent, $signature, $loadedPublicKey, OPENSSL_ALGO_SHA256);
     
     // Libera la chiave pubblica
     openssl_free_key($loadedPublicKey);
     
     if ($result === 1) {
         // La firma è valida
         return true;
     } elseif ($result === 0) {
         // La firma non è valida
         return false;
     } else {
         // Si è verificato un errore durante la verifica
         return false;
     }
 } else {
     // La firma non è stata trovata nel contenuto
     return false;
 }
}

 function signatureHandler($buffer) {
             global $sigenabled,$privateKey;
             
             // Chiavi private e pubbliche in formato PEM come stringhe di testo
     $privateKey = "-----BEGIN PRIVATE KEY-----
MIIEvQIBADANBgkqhkiG9w0BAQEFAASCBKcwggSjAgEAAoIBAQDfEGC4ZxT2gtCA
aoHatgtD1M/dSCYq/AoPlW6WUXKD2WRh+LTKJKvHajp8Tqrvzeoaxmkzu7SBGtAR
zeOZj0vbqmlVlsV5TIjY8jvQmHnvFA1LPIBADDxK1GAVxhBrqcagOCGiY/m/Z/dp
SCBvuWp9LL6MDXvO+l1Ck4Mf9odi8EuaJIxL2zrQ8zFdXFn7vMTcsFLmIDmwNoCL
xT5k1q+TXSaxQ7iCpVSPt1oCdAWWbj2ENVGVDLoMlBLqqcdhqICcIIUUSwcu/yqP
pzxIo4skRVEvRvOAQ+9ZAK/CQpS2q41GD7xP4aSty0g66hoqnnerHtQBNVJOjLQQ
AxuoxX0PAgMBAAECggEAfwpn234x7TeRoiGGtVaK0eYJQMDPpBmJbViw9AGNE9Sj
HD02Ug5Smy9AAf4X0p3W38ryrZkQ85qeuBUntBRtorzKYwmMjEnvgodrU6ETiPtF
mvzpGhEd4YxU8PGt9nNqAWQWm9MOsVl4Ckke2CKkqIfsOf2P7tCefsVen7ybXNfn
1iPefg6FMdgYxSmGPdZbdueiMtYL5bVaoJcunU5nqgyqVlAssFoWn2rq8GDv0cBv
bDZH8szKjqk9XZ73XJmJdsptzrEOYArGj7UtnbpqQAI99ekRqQL0A8iIuM1dB5Sh
A8yz5usFKU/vnayLrcWTKuYgV5Dm9dG4URUyWAe5gQKBgQD7t0JZT8ixjIn4Vkbb
DFHE5gWOGHG0ywQSB0oENHXm6JRosgHzS2pE/1tBX+66hTN4RmjImynrtVfLdoz2
r6TUI+az+HkDaiId0/vPboo4B9i4+RWb31pPGtMQ1cwwVZXcXxdVPw1oaI4fDzUF
AYjb3wkO+ca+1BKv9iM7l59zTwKBgQDi3EfcZrmFyZH3R1WBTIp6plLqUTHyZU00
prieLaCqblRcjlpPibGuhh92jjTFYxRJBsDXBTmiZqe4VaaiLs0/7AkIqcE0Iryz
OV0MhcOzfHg9phI7+w8NtYkzgfeeFqggYVL69H8T3R3g/+SeIgSrBu4xhYprL4U3
HgN9Sy3qQQKBgCNqjH5OeHlqwbrcQMnvOM4QYzW0AMOkUOVMe0CONQyRKth0O0wF
D2W33BodqlQ6C5zfozyegE52zGyHOJw46GMkzgACdNxjIXu66NItxmrNx9N20HCH
A4jfyFS1EC64zAYepwTKg1Nuapi6JgaXSa9N+VNPF4SgCCUT568pzTiHAoGBANBL
zbdyonlS4LEZUEsOCkAfIxZ+Qsv7c24afnzODC0wbEknc4iANyK1h4IFhDKQjHmY
t8s1wvt4IhtAmWVkb5R3lrvbkcfa9UPiMatjzpKogIaqSyfDlsjRnA4tETyYNPq2
IcjGYT/N0LdcfX8sYPjfvii+Ip/T3A8FgdKzCo+BAoGADr4HVRtK1dA7KfzOar4L
VL24OZh+JV1Ftsq4a+rbd7weE9c+qVsZ5CC0IP+AhlRj0vNSokUtsqVYPLAE/Q70
gTxk7R7RoR5U2pFTauAKaEKMFdhY0csVs4GH74ve9HQX4c6Hk/e1nFzPV4ME94o+
6ZMM9wMswuDOkiVwaLykqmA=
-----END PRIVATE KEY-----";

     $publicKeyPEM = "-----BEGIN PUBLIC KEY-----
MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEA3xBguGcU9oLQgGqB2rYL
Q9TP3UgmKvwKD5VullFyg9lkYfi0yiSrx2o6fE6q783qGsZpM7u0gRrQEc3jmY9L
26ppVZbFeUyI2PI70Jh57xQNSzyAQAw8StRgFcYQa6nGoDghomP5v2f3aUggb7lq
fSy+jA17zvpdQpODH/aHYvBLmiSMS9s60PMxXVxZ+7zE3LBS5iA5sDaAi8U+ZNav
k10msUO4gqVUj7daAnQFlm49hDVRlQy6DJQS6qnHYaiAnCCFFEsHLv8qj6c8SKOL
JEVRL0bzgEPvWQCvwkKUtquNRg+8T+GkrctIOuoaKp53qx7UATVSToy0EAMbqMV9
DwIDAQAB
-----END PUBLIC KEY-----";

             if($sigenabled){
                 
                 // Firma digitale del contenuto del buffer
                 // $privateKey = openssl_pkey_get_private("private_key.pem"); // Carica la chiave privata
                 // Carica la chiave privata
                     $loadedPrivateKey = openssl_pkey_get_private($privateKey);
                     
                     // Carica la chiave pubblica
                     //$loadedPublicKey = openssl_pkey_get_public($publicKeyPEM);

                     
                     
                     
                 openssl_sign($buffer, $signature, $loadedPrivateKey, OPENSSL_ALGO_SHA256); // Firma il contenuto
                 openssl_free_key($loadedPrivateKey); // Libera la chiave privata
                     //				echo base64_encode($signature);
                 // Includi la firma nel tuo output
                 $signedContent = $buffer . "\n<!-- Signature: " . base64_encode($signature) . " -->";
                 
                 
     //		    $message = "Il tuo messaggio da firmare";
     //openssl_sign($message, $signature, $loadedPrivateKey, OPENSSL_ALGO_SHA256);

     //if (openssl_verify($buffer, $signature, $loadedPublicKey, OPENSSL_ALGO_SHA256) === 1) {
     //    echo "Verifica della firma OK.";
     //} else {
     //    echo "Verifica della firma fallita.";
     //}
                 
                 // Utilizzo della funzione per verificare una firma

                     if (verificaFirma($signedContent, $publicKeyPEM, $buffer)) {
                         $signedContent .= "\n<!-- La firma è valida. -->";
                     } else {
                         $signedContent .= "\n<!-- La firma non è valida o è mancante. -->";
                     }
                 
         //return "AA";
                 // Restituisci il contenuto firmato
             return $signedContent;
         }else{
             return $buffer;
         }
     }
 
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
 
 function autenticate($username,$password){
    global $RHD_redirect;
    
      $link = dbconnect();
      $ret = false;
      $reta = array();
      $query = "SELECT `lists`,`RHD_redirect`,`smslists` FROM `blk_users` WHERE `username`='".$username."' AND `rest_md5_key`=MD5('".$password."')";
        // echo $query;

         if($result = mysqli_query( $link , $query )){
                 if ($row = mysqli_fetch_assoc($result)) {
                     $ret = $row;//['lists'];
   //$RHD_redirect = $row["RHD_redirect"];
                    //$query = "SELECT `code` FROM `blk_lists` WHERE `id` IN ('".$lists."')";
                     if(false && $result = mysqli_query( $link , $query )){
                         while ($row = mysqli_fetch_assoc($result)) {
                             $reta[] = $row['code'];
                         }
                         $ret = implode(',',$reta);
                     }
                     
                     
                 }		
         }
         mysqli_close($link);
         return $ret;
 }
 function getListFromApp($idTrack){
     $ret = null;
     $link = dbconnect();
     $query = "SELECT `list` FROM `blk_apps` WHERE `code`='".$idTrack."'";
      if($result = mysqli_query( $link , $query )){
       if ($row = mysqli_fetch_assoc($result)) {
        $ret = $row['list'];
       }
      }
      mysqli_close($link);
     return $ret;	
}
 function getMetaFromList($idTrack){
    $ret = array();
    $link = dbconnect();
    $query = "SELECT `id`,`description`,`updated`,`mintimestamp`,`version` as `pkversion`, UNIX_TIMESTAMP(CURRENT_TIMESTAMP) as `version`, `urls`, `md5` FROM `blk_lists` WHERE id IN ( SELECT `list` FROM `blk_apps` WHERE `code`='".$idTrack."' )";
     if($result = mysqli_query( $link , $query )){
      if ($row = mysqli_fetch_assoc($result)) {
       $ret = $row;
      }
     }
     mysqli_close($link);
    return $ret;	
 }

 function getMinDateFromList($idl,$lastUpdate){
    if(trim($lastUpdate)!=""){
            if (is_numeric($lastUpdate) && (string)(int)$lastUpdate === $lastUpdate
                    && $lastUpdate <= PHP_INT_MAX
                    && $lastUpdate >= ~PHP_INT_MAX) {
                    // La variabile $lastUpdate contiene un timestamp UNIX valido
                    
                    $link = dbconnect();

                    $query = "SELECT MIN(UNIX_TIMESTAMP(`datemod`)) as `dateminmod` WHERE `list`='".$idl."' ";
                    //echo $query;
                    $c = 0;
                    if($result = mysqli_query( $link , $query )){
                        while (($row = mysqli_fetch_assoc($result) )  ) { //&& $c < 5
                            $ret = $row['dateminmod'];
                            if(((int)$lastUpdate)<((int)$ret)){
                                $lastUpdate = "";
                            }
                        }
                    }
                    mysqli_close($link);
                } 
    }
    return $lastUpdate;	
}

 function getUrlsFromList($idl,$lastUpdate){
        $ret = array();
        $link = dbconnect();
        $wh = "";
        if(trim($lastUpdate)!=""){
                if (is_numeric($lastUpdate) && (string)(int)$lastUpdate === $lastUpdate
                        && $lastUpdate <= PHP_INT_MAX
                        && $lastUpdate >= ~PHP_INT_MAX) {
                        // La variabile $lastUpdate contiene un timestamp UNIX valido
                        $wh = " AND UNIX_TIMESTAMP(`datemod`) >= $lastUpdate ";
                    } 
                    
        }
        $query = "SELECT `id`,`url`,`type`,`description`, UNIX_TIMESTAMP(`datemod`) as `datemod`,`datemod` as `verdate`, `deleted` FROM `blk_urls` WHERE `list`='".$idl."'".$wh;
        //echo $query;
        $c = 0;
        if($result = mysqli_query( $link , $query )){
            while (($row = mysqli_fetch_assoc($result) )  ) { //&& $c < 5
                $ret[] = $row;
                //print_r($row);
             $c++;
            }
        }
        mysqli_close($link);
  return $ret;	
 }
 function getSmsListFromApp($idTrack){
     $ret = null;
     $link = dbconnect();
     $query = "SELECT `smslist` FROM `blk_apps` WHERE `code`='".$idTrack."'";
      if($result = mysqli_query( $link , $query )){
       if ($row = mysqli_fetch_assoc($result)) {
        $ret = $row['smslist'];
       }
      }
      mysqli_close($link);
     return $ret;	
}
 function getMetaSmsFromList($idTrack){
    $ret = array();
    $link = dbconnect();
    $query = "SELECT `id`,`description`,`updated`,`mintimestamp`,`version` as `pkversion`, UNIX_TIMESTAMP(CURRENT_TIMESTAMP) as `version`, `nsms`, `md5` FROM `blk_smslists` WHERE id IN ( SELECT `smslist` FROM `blk_apps` WHERE `code`='".$idTrack."' )";
     if($result = mysqli_query( $link , $query )){
      if ($row = mysqli_fetch_assoc($result)) {
       $ret = $row;
      }
     }
     mysqli_close($link);
    return $ret;	
 }
 function getSmsFromList($idl,$lastUpdate){
  $ret = array();
  $link = dbconnect();
  $wh = "";
  if(trim($lastUpdate)!=""){
          if (is_numeric($lastUpdate) && (string)(int)$lastUpdate === $lastUpdate
                 && $lastUpdate <= PHP_INT_MAX
                 && $lastUpdate >= ~PHP_INT_MAX) {
                 // La variabile $lastUpdate contiene un timestamp UNIX valido
                 $wh = " AND UNIX_TIMESTAMP(`datemod`) >= $lastUpdate ";
             } 
              
  }
  $query = "SELECT `id`,`sms`,`type`,`description`, UNIX_TIMESTAMP(`datemod`) as `datemod`,`datemod` as `verdate`, `deleted` FROM `blk_sms` WHERE `list`='".$idl."'".$wh;
        $c = 0;
        if($result = mysqli_query( $link , $query )){
            while (($row = mysqli_fetch_assoc($result) )  ) { //&& $c < 5
                $ret[] = $row;
                //print_r($row);
             $c++;
         }
     }
     mysqli_close($link);
  return $ret;	
 }
 
 
 function insertReportUrl($url,$user,$app,$deviceId){
             $ret = null;
                 $link = dbconnect();
                 $urli = mysqli_real_escape_string($link,$url);
                 $useri = mysqli_real_escape_string($link,$user);
                 $appi = mysqli_real_escape_string($link,$app);
                 $deviceIdi = mysqli_real_escape_string($link,$deviceId);
                 $ipaddress = mysqli_real_escape_string($link,$_SERVER['REMOTE_ADDR']);
                 $ua = mysqli_real_escape_string($link,$_SERVER['HTTP_USER_AGENT']);
                 $query = "INSERT INTO `blk_urlreported` (`url`, `ts`, `ip`, `ua`, `user`, `deviceId`, `app`) VALUES ('".$urli."', '".date("Y-m-d H:i:s")."', '".$ipaddress."','".$ua."', '".$useri."', '".$deviceIdi."' , '".$appi."');";
             
              mysqli_query( $link , $query );	
             mysqli_close($link);
 }


function insertReportBlocked($url,$user,$app,$deviceId){
     $ret = null;
     $link = dbconnect();
     $urli = mysqli_real_escape_string($link,$url);
     $useri = mysqli_real_escape_string($link,$user);
     $appi = mysqli_real_escape_string($link,$app);
     $deviceIdi = mysqli_real_escape_string($link,$deviceId);
     $ipaddress = mysqli_real_escape_string($link,$_SERVER['REMOTE_ADDR']);
     $ua = mysqli_real_escape_string($link,$_SERVER['HTTP_USER_AGENT']);
     $query = "INSERT INTO `blk_numberblocked` (`number`, `ts`, `ip`, `ua`, `user`, `deviceId`, `app`) VALUES ('".$urli."', '".date("Y-m-d H:i:s")."', '".$ipaddress."','".$ua."', '".$useri."', '".$deviceIdi."' , '".$appi."');";
 
  mysqli_query( $link , $query );	
     mysqli_close($link);
 }

function insertSmsDetected($url,$user,$app,$deviceId){
     $ret = null;
     $link = dbconnect();
     $urli = mysqli_real_escape_string($link,$url);
     $useri = mysqli_real_escape_string($link,$user);
     $appi = mysqli_real_escape_string($link,$app);
     $deviceIdi = mysqli_real_escape_string($link,$deviceId);
     $ipaddress = mysqli_real_escape_string($link,$_SERVER['REMOTE_ADDR']);
     $ua = mysqli_real_escape_string($link,$_SERVER['HTTP_USER_AGENT']);
     $query = "INSERT INTO `blk_smsdetected` (`number`, `ts`, `ip`, `ua`, `user`, `deviceId`, `app`) VALUES ('".$urli."', '".date("Y-m-d H:i:s")."', '".$ipaddress."','".$ua."', '".$useri."', '".$deviceIdi."' , '".$appi."');";
 
  mysqli_query( $link , $query );	
     mysqli_close($link);
 }
 
//global $RHD_redirect;
 $type = trim($_GET["type"]);
 
 $idTrack = $_POST["idTrack"];
 $user = trim($_POST["user"]);
 $rest_key = trim($_POST["rest_key"]);
 $reportUrl = trim($_POST["reportUrl"]);
 $deviceId = trim($_POST["deviceid"]);
 $blockedNumber = trim($_POST["blockedNumber"]);
 $reportSms = trim($_POST["reportSms"]);
 $lastUpdate = trim($_POST["lastUpdate"]);
 $lastUpdateBase = "";
 
 $sigenabled = true;
 
if($_GET["debug"]=="1"){
    // call https://labs.dmasotti.space/blocklist.php
    $type = "url";
 //$type = "sms";
 //$type = "call"; 
 $idTrack = "it.alfagroup.rhdcyberth.cyberThreat";
 //$idTrack = "it.alfagroup.rhdcyberth.cyberThreat";
 //$idTrack = "it.alfagroup.rhdCyberthExample.BlockExt";
// $idTrack = "it.alfagroup.rhdCyberthExample.BlockSmsExt";
//$idTrack = "it.alfagroup.rhdCyberth.BlockCall";
 $user = "dmasotti";
 $rest_key = "rhd";
 $deviceId = 'idxx';
 // $reportUrl = "xxx";
 
 $lastUpdate = "";
 
}
 if(trim($lastUpdate)!=""){
          if (is_numeric($lastUpdate) && (string)(int)$lastUpdate === $lastUpdate
                 && $lastUpdate <= PHP_INT_MAX
                 && $lastUpdate >= ~PHP_INT_MAX) {
                 // La variabile $lastUpdate contiene un timestamp UNIX valido
                 
                 //TODO verificare che il timestamp sia maggiore del minimo gestino, se no fai aggiornamento full
                 // serve per quando elimino i dati e non ho più record di eliminazione
                 if($lastUpdate > 0){
                     $lastUpdateBase = $lastUpdate;
                 }
             } 
              
  }
 if($reportSms!=""){
     insertSmsDetected($reportSms,$user,$idTrack,$deviceId);
      operationsLog('insertSmsDetected',$user,$app,$deviceId);
            echo "1:<H1>URL Inserito nel nostro db :".$reportSms." </H1>";
     die();
 }
 if($blockedNumber!=""){
     insertReportBlocked($blockedNumber,$user,$idTrack,$deviceId);
      operationsLog('insertReportBlocked',$user,$app,$deviceId);
            echo "1:<H1>URL Inserito nel nostro db :".$blockedNumber." </H1>";
     die();
 }
 
 if($reportUrl!=""){
     insertReportUrl($reportUrl,$user,$idTrack,$deviceId);
      operationsLog('reportUrl',$user,$app,$deviceId);
            echo "1:<H1>URL Inserito nel nostro db :".$reportUrl." </H1>";
     die();
 }
 
 //generachiavi();
 //testChiavi();
 $auth = autenticate($user,$rest_key);
 

    //$privateKey;
 
 
 
 if($auth===false){
     header("HTTP/1.1 401 Unauthorized", true, 401);
     echo "Unauthorized ($user : $rest_key ).\n";
     //echo print_r($_POST);
     die();
 }
 //metto ob
 while (ob_get_level() > 0) {
         ob_end_clean();
     }
  ob_start();
  
 
  switch($type){
    case 'sms':
    case 'call': 
 $lists = $auth["smslists"];
 $list = getSmsListFromApp($idTrack);
 if(trim($list)==""){
     header("HTTP/1.1 401 Unauthorized", true, 401);
     echo "Unauthorized (sms list $idTrack not found ).\n";
     //echo print_r($_POST);
     die();
 }
 if(strpos(trim(",".$lists.","), trim(",".$list.","))===false){
     header("HTTP/1.1 401 Unauthorized", true, 401);
     echo "Unauthorized (sms list $idTrack not allowed ).\n";
     //echo print_r($_POST);
     die();
 }

 $meta = getMetaSmsFromList($idTrack);

 $lastUpdate = getMinDateFromList($meta['id'],$lastUpdate);

 if($lastUpdate != "" && trim($meta['mintimestamp'])!="" ){
    $mintimestamp = $meta['mintimestamp'];
    if (is_numeric($mintimestamp) && (string)(int)$mintimestamp === $mintimestamp
               && $mintimestamp <= PHP_INT_MAX
               && $mintimestamp >= ~PHP_INT_MAX) {
               // La variabile $mintimestamp contiene un timestamp UNIX valido
               if(((int)$lastUpdateBase)<((int)$mintimestamp)){ // se il timestamp è minore del minimo gestito, forzo aggiornamento completo
                   $lastUpdateBase = "";
               }
    } 
 }
 
 $urls = getSmsFromList($meta['id'],$lastUpdateBase);


 //print_r($urls);
 
 if(trim($RHD_redirect)==""){
       $RHD_redirect =''.$_SERVER['SERVER_NAME'].'/inxx.php';
 }
 operationsLog('getSms',$user,$idTrack,$deviceId);

?>
# Title: Blocklist <?php echo $idTrack; ?> 
# Description: <?php echo $meta["description"]." MD5 ".$meta["md5"]." PK ".$meta["pkversion"]; ?> 
# Updated: <?php echo date('m/d/Y h-i-s', strtotime($meta["updated"])); ?> 
# Version: <?php echo $meta["version"]; ?> 
# Domain Count: <?php echo count($urls)." ".$meta["nsms"]; ?> 
# Redir: <?php echo  urlencode($RHD_redirect); ?> 
# User: <?php echo str_replace(":","-",$user); ?> 
# DeviceId: <?php echo str_replace(":","-",$deviceId); ?>
<?php
 if($lastUpdateBase!="") echo "# PartialFrom:".$lastUpdateBase."\n";
?> 
#===============================================================

<?php
     foreach($urls as $u){
             $t = $u['type'];
             if($u["deleted"]=='1'){
                     if($t=='1') $t = 'D';
                     else $t = 'd';
             }
         echo $u['sms'].",".$t." # ".$u["description"]." ".$u["datemod"]." ".$u["verdate"]." ".$u["deleted"]."\n";
     }     
     
      break;
    case 'url': 
    default:
 $RHD_redirect = $auth["RHD_redirect"];
 $lists = $auth["lists"];
 
 $list = getListFromApp($idTrack);
 if(trim($list)==""){
     header("HTTP/1.1 401 Unauthorized", true, 401);
     echo "Unauthorized (list $idTrack not found ).\n";
     //echo print_r($_POST);
     die();
 }
 if(strpos(trim(",".$lists.","), trim(",".$list.","))===false){
     header("HTTP/1.1 401 Unauthorized", true, 401);
     echo "Unauthorized (list $idTrack not allowed ).\n";
     //echo print_r($_POST);
     die();
 }
 
 $meta = getMetaFromList($idTrack);

 $lastUpdate = getMinDateFromList($meta['id'],$lastUpdate);

 if($lastUpdate != "" && trim($meta['mintimestamp'])!="" ){
    $mintimestamp = $meta['mintimestamp'];
    if (is_numeric($mintimestamp) && (string)(int)$mintimestamp === $mintimestamp
               && $mintimestamp <= PHP_INT_MAX
               && $mintimestamp >= ~PHP_INT_MAX) {
               // La variabile $mintimestamp contiene un timestamp UNIX valido
               if(((int)$lastUpdateBase)<((int)$mintimestamp)){ // se il timestamp è minore del minimo gestito, forzo aggiornamento completo
                   $lastUpdateBase = "";
               }
    } 
 }

 $urls = getUrlsFromList($meta['id'],$lastUpdateBase);
 //print_r($urls);
 
 if(trim($RHD_redirect)==""){
       $RHD_redirect =''.$_SERVER['SERVER_NAME'].'/inxx.php';
 }
 operationsLog('getUrls',$user,$idTrack,$deviceId);

?>
# Title: Blocklist <?php echo $idTrack; ?> 
# Description: <?php echo $meta["description"]." MD5 ".$meta["md5"]." PK ".$meta["pkversion"]; ?> 
# Updated: <?php echo date('m/d/Y h-i-s', strtotime($meta["updated"])); ?> 
# Version: <?php echo $meta["version"]; ?> 
# Domain Count: <?php echo count($urls)." ".$meta["urls"]; ?> 
# Redir: <?php echo  urlencode($RHD_redirect); ?> 
# User: <?php echo str_replace(":","-",$user); ?> 
# DeviceId: <?php echo str_replace(":","-",$deviceId); ?> 
<?php
 if($lastUpdateBase!="") echo "# PartialFrom:".$lastUpdateBase."\n";
?>
#===============================================================

<?php
             // $u['type']  se = D o = d da eliminare, se = 1 blocchi il percorso completo (e solo il percorso completo e si elimina con D) , altrimenti blocchi su regex String(format: "https?://(www.)?%@.*", tr) (e si elimina con d) 
     foreach($urls as $u){
             $t = $u['type'];
             if($u["deleted"]=='1'){
                     if($t=='1') $t = 'D';
                     else $t = 'd';
             }
         echo $u['url'].",".$t." # ".$u["description"]." ".$u["datemod"]." ".$u["verdate"]." ".$u["deleted"]."\n";
     }
  }
  $outputContent = ob_get_clean();
  echo signatureHandler($outputContent);
?>