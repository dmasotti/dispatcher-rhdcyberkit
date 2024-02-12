<?php
  session_start();
  
  define('DB_NAME', 'u421231893_dispatcher');
  define('DB_USER', 'u421231893_dispatcher');
  define('DB_PASSWORD', 'Dispatcher01!');
  define('DB_HOST', '217.21.76.151');
  define('DB_CHARSET', 'utf8');
	 
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
         // La firma  valida
         return true;
     } elseif ($result === 0) {
         // La firma non  valida
         return false;
     } else {
         // Si  verificato un errore durante la verifica
         return false;
     }
 } else {
     // La firma non  stata trovata nel contenuto
     return false;
 }
}
	 
	 function signatureHandler($buffer,$sigenabled,$encode) {
           
             
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
                         $signedContent .= "\n<!-- La firma  valida. -->";
                     } else {
                         $signedContent .= "\n<!-- La firma non  valida o  mancante. -->";
                     }
                 
         //return "AA";
                 // Restituisci il contenuto firmato
             if($encode) {
             		return str_replace( "+", " ", urlencode($signedContent));
             }else{
             		return $signedContent;
             }
                 
             
         }else{
         		if($encode) {
             		return str_replace( "+", " ", urlencode($buffer));
            }else{
             		return $buffer;
           	}
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
	
	 function getTrackidByList($list_id){
        $ret = array();
        $link = dbconnect();
        $wh = "";
        
        $query = "SELECT distinct `code`, count(*) as tot FROM `blk_pushlists` WHERE `enabled` = 1 ";
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
 	 
 	 function getListid(){
        $ret = array();
        $link = dbconnect();
        $wh = "";
        
        //$query = " SELECT `id`,`code`,`description`,`updated`,`mintimestamp` FROM `blk_lists`  where id in ( SELECT list FROM `blk_apps` where code in (SELECT code FROM `blk_pushlists` WHERE `enabled` = 1 HAVING count(*)>0) )";
        $query = " SELECT CONCAT('url-',id) as `id`, 'url' as `type` ,`code`,`description`,`updated`,`mintimestamp` FROM `blk_lists` UNION ";
        $query .= " SELECT CONCAT('sms-',id) as `id`, 'sms' as `type` ,`code`,`description`,`updated`,`mintimestamp` FROM `blk_smslists` ";
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
 	 function getUrlsByList($lid){
 	 			$ret = array();
 	 			if($lid!=""){
		        $link = dbconnect();
		        $x = explode("-",$lid);
		        switch($x[0]){
		 	 				case 'url':
		 	 						$query = " SELECT `id`, `url` as `value`,`type`,`description`,`datemod`,`deleted` FROM `blk_urls` WHERE list ='".$x[1]."' ";
		        	
		 	 				break;	
		 	 				case 'sms':
		 	 						$query = " SELECT `id`, `sms` as `value`,`type`,`description`,`datemod`,`deleted` FROM `blk_sms` WHERE list ='".$x[1]."' ";
		        	
		 	 				break;
		 	 			}
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
 	 	 		}
  			return $ret;	
 	 }

	  function restoreBaseDelete($lid){
		$ret = 0;	
		if($lid!=""){
			$query = "";
			$queryList = "";

			$vali = $val;
			$desci = $desc;
			$x = explode("-",$lid);
			switch($x[0]){
				case 'url':
					$query = "DELETE FROM `blk_urls` WHERE `deleted` = 1 AND `list` ='".$x[1]."' ";
					$queryList = "update blk_lists set mintimestamp = NOW() WHERE id='".$x[1]."' ";
			
				break;	
				case 'sms':
					$query = "DELETE FROM `blk_sms` WHERE `deleted` = 1 AND `list` ='".$x[1]."' ";
					$queryList = "update blk_smslists set mintimestamp = NOW() WHERE id='".$x[1]."' ";
				break;

			}
			//echo $query;
			//echo $queryList;
			// die();
			if($query == "" || $queryList == ""){
				return -1;
			}
			$link = dbconnect();
			if(mysqli_query(  $link ,  $query  )){
				$ret = mysqli_affected_rows($link);
				if($ret>=0){
					if(!mysqli_query(  $link ,  $queryList  )){
						$ret = -2;
					}
				}

			}		
		  	mysqli_close($link);	

		 }
		 return $ret;	
	  }

	  function deleteUrlInList($deleterows,$lid){
		$ret = 0;	
		if($lid!="" && is_array($deleterows) && count($deleterows)>0){
			$query = "";
			$vali = $val;
			$desci = $desc;
			$x = explode("-",$lid);
			switch($x[0]){
				case 'url':
					$query = "UPDATE `blk_urls` SET `deleted` = 1, `datemod` = NOW() WHERE id IN(".implode(',',array_values($deleterows)).")";
			
				break;	
				case 'sms':
					$query = "UPDATE `blk_sms` SET `deleted` = 1, `datemod` = NOW() WHERE id IN(".implode(',',array_values($deleterows)).")";
			
				break;
			}
			if($query == ""){
				return -1;
			}
			$link = dbconnect();
			if(mysqli_query(  $link ,  $query  )){
				$ret = mysqli_affected_rows($link);
			}		
		  	mysqli_close($link);	

		 }
		 return $ret;	
	  }
	  function addUrlInList($val,$typ,$desc,$lid){
		$ret = 0;	
		if($lid!="" && $typ!="" && $val!=""){
			$query = "";
			$vali = $val;
			$desci = $desc;
			$x = explode("-",$lid);
			switch($x[0]){
				case 'url':
			//			$query = " SELECT `id`, `url` as `value`,`type`,`description`,`datemod`,`deleted` FROM `blk_urls` WHERE list ='".$x[1]."' ";
				    if($typ=="0" && (substr($val,0,6)!="http//" || substr($val,0,7)!="https//" ) ){
						//normalizza l 'url lasciando la sola parte di dominio e path
						$uri = parse_url($val);
						$vali = $uri['host'].$uri['path'];

					}
					$query = "INSERT INTO `blk_urls` (`url`,`type`,`description`,`datemod`,`deleted`,`list`)  VALUES ('".$vali. "','".$typ."','".$desci. "',NOW(),0,'".$x[1]."')";
			
				break;	
				case 'sms':
					//	$query = " SELECT `id`, `sms` as `value`,`type`,`description`,`datemod`,`deleted` FROM `blk_sms` WHERE list ='".$x[1]."' ";
						$query = "INSERT INTO `blk_sms` (`sms`,`type`,`description`,`datemod`,`deleted`,`list`)  VALUES ('".$vali. "','".$typ."','".$desci. "',NOW(),0,'".$x[1]."')";
			
				break;
			}
			if($query == ""){
				return -1;
			}
			$link = dbconnect();
			if(mysqli_query(  $link ,  $query  )){
				$ret = mysqli_insert_id($link);
			}		
		  	mysqli_close($link);	

		 }
		 return $ret;
	}
 	 
 	 function sendPushFCMToDevices($link,$content,$type,$trackid,$fcm_api_key){
 	 		// see https://firebase.google.com/docs/cloud-messaging/http-server-ref?hl=it
 	 		
 	 		$fcmEndpoint = "https://fcm.googleapis.com/fcm/send";
 	 		
 	 		
 	 		
 	 		$query = " SELECT DISTINCT `fcmId` FROM `blk_pushlists` where enabled=1 AND `code`='".$trackid."'";
      $allfcmtocken = array();  
 	 		if($result = mysqli_query( $link , $query )){
          while (($u = mysqli_fetch_assoc($result) )  ) { //&& $c < 5
               $allfcmtocken[] = $u["fcmId"];
          }
      }
 	 		if(count($allfcmtocken)==0){
 	 			
 	 			return "Nessun ricevente trovato";
 	 		}
 	 		
    
 	 		/*$payload = [
			    "registration_ids" => $allfcmtocken, // Modificato per puntare al topic personalizzato  
			    "content_available" => true,
			    "priority" => "high",
			    "data" => [
			        "type" => $type,
			        "idTrack" => $trackid,
			        "content" => $content
			    ],
			    "aps" => [
			        "content-available" => 1
			    ]
			];
			
			
			$payload = [
			    "to" => $allfcmtocken[0], // Modificato per puntare al topic personalizzato  "registration_ids" => $allfcmtocken,
			    "content_available" => true,
			    "priority" => "high",
			    "data" => [
			        "type" => $type,
			        "idTrack" => $trackid,
			        "content" => $content
			    ],
			    "aps" => [
			        "content-available" => 1
			    ]
			];*/
			
			$payload = [
			    "registration_ids" => $allfcmtocken, // "to" => $allfcmtocken[0], // Modificato per puntare al topic personalizzato  "registration_ids" => $allfcmtocken,
			    "content_available" => true,
			    "priority" => "high",
			    "data" => [
			        "type" => $type,
			        "idTrack" => $trackid,
			        "content" => $content
			    ],
			    "aps" => [
				    "content-available" => 1
				  ]
			];
			
			
			
			// Converti l'array in JSON
				$jsonPayload = json_encode($payload);
				
				// Imposta le opzioni cURL
				$ch = curl_init($fcmEndpoint);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HTTPHEADER, [
				    'Authorization: key=' . $fcm_api_key,
				    'Content-Type: application/json'
				]);
				
				// Invia la richiesta e ottieni la risposta
				$response = curl_exec($ch);
				
				// Chiudi la sessione cURL
				curl_close($ch);
				
				// Visualizza la risposta (opzionale)
				return array('request' => $jsonPayload,'response' => $response);
 	 	
 	 }
 	 function sendPushFCMTopic($link,$content,$type,$trackid,$fcm_api_key){
 	 		$fcmEndpoint = "https://fcm.googleapis.com/fcm/send";
 	 		
 	 		$to = "/topics/rhdcyb/" . $trackid;
 	 		$to = "/topics/all";
    
 	 		$payload = [
			    "to" => $to, // Modificato per puntare al topic personalizzato
			    "content_available" => true,
			    "priority" => "high",
			    "data" => [
			        "type" => $type,
			        "idTrack" => $trackid,
			        "content" => $content
			    ],
			    "aps" => [
			        "content-available" => 1
			    ]
			];
			
			// Converti l'array in JSON
				$jsonPayload = json_encode($payload);
				
				// Imposta le opzioni cURL
				$ch = curl_init($fcmEndpoint);
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
				curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonPayload);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
				curl_setopt($ch, CURLOPT_HTTPHEADER, [
				    'Authorization: key=' . $fcm_api_key,
				    'Content-Type: application/json'
				]);
				
				// Invia la richiesta e ottieni la risposta
				$response = curl_exec($ch);
				
				// Chiudi la sessione cURL
				curl_close($ch);
				
				// Visualizza la risposta (opzionale)
				return array('request' => $jsonPayload,'response' => $response);
 	 	
 	 }
 	 function sendPushNotification($lid,$idurls,$idtracks){
 	 			$ret = array();
 	 			if( is_array($idurls) && count($idurls)>0 && is_array($idtracks) && count($idtracks)>0 ){
		        //var_dump($idurls);
		 	 			var_dump($idtracks);
 	 			
		        $link = dbconnect();
		        $x = explode("-",$lid);
		        //$wh = "";
		        $wh = " AND id IN(".implode(',',array_values($idurls)).")";
		        switch($x[0]){
		 	 				case 'url':
		 	 						//$query = " SELECT `id`, `url` as `value`,`type`,`description`,`datemod`,`deleted` FROM `blk_urls` WHERE list ='".$x[1]."' ";
		        			$query = "SELECT `id`,`url` as `value`,`type`,`description`, UNIX_TIMESTAMP(`datemod`) as `datemod`,`datemod` as `verdate`, `deleted` FROM `blk_urls` WHERE `list`='".$x[1]."'".$wh;
        
		 	 				break;	
		 	 				case 'sms':
		 	 						$query = "SELECT `id`,`sms` as `value`,`type`,`description`, UNIX_TIMESTAMP(`datemod`) as `datemod`,`datemod` as `verdate`, `deleted` FROM `blk_sms` WHERE `list`='".$x[1]."'".$wh;
  
		        	
		 	 				break;
		 	 			}
		 	 			
		        //echo $query."<br />\n";
		        $tmp = "# Updated: ". date('m/d/Y h-i-s')."\n";
		        if($result = mysqli_query( $link , $query )){
		            while (($u = mysqli_fetch_assoc($result) )  ) { //&& $c < 5
		                $t = $u['type'];
				             if($u["deleted"]=='1'){
				                     if($t=='1') $t = 'D';
				                     else $t = 'd';
				             }
						         $tmp .= $u['value'].",".$t." # ".$u["description"]." ".$u["datemod"]." ".$u["verdate"]." ".$u["deleted"]."\n";
		             $c++;
		            }
		        }
		        $tmp = signatureHandler($tmp,true,true);
				    // echo $tmp;
				    $wh1 = " AND id IN(".implode(',',array_values($idtracks)).")";
				    $query = " SELECT DISTINCT `code`,`fcm_api_key` FROM `blk_apps` where `list` ='".$x[1]."'".$wh1;
				    echo $query."<br />\n";
				    if($result = mysqli_query( $link , $query )){
		            while (($row = mysqli_fetch_assoc($result) )  ) { //&& $c < 5
		            
		                $ret[] = sendPushFCMToDevices($link,$tmp, $x[0] , $row['code'] ,$row['fcm_api_key']);
		            
		            }
		        }
				    
				    mysqli_close($link);
				}
  			return $ret;	
 	 }
 	 
 	 function getTracksByList($lid){
 	 			$ret = array();
 	 			if($lid!=""){
		        $link = dbconnect();
		        $x = explode("-",$lid);
		        switch($x[0]){
		 	 				case 'url':
		 	 						$query = " SELECT `id`, `code`, `description`, `fcm_api_key` FROM `blk_apps` where `list` ='".$x[1]."' ";
		        	
		 	 				break;	
		 	 				case 'sms':
		 	 						$query = " SELECT `id`, `code`, `description`, `fcm_api_key` FROM `blk_apps` where `smslist` ='".$x[1]."' ";
		        	
		 	 				break;
		 	 			}
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
 	 	 		}
  			return $ret;	
 	 }

?>
<html>
<head>

<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.13.0/css/all.min.css' type='text/css' media='all' />
<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/chosen/1.6.2/chosen.min.css' type='text/css' media='all' />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/chosen/1.6.2/chosen.jquery.min.js"></script>
<script src="/js/js-push.js"></script>
<style>
.dot {
  height: 15px;
  width: 15px;
  border-radius: 50%;
  display: inline-block;
}
.green{background-color:green;}
.red{background-color:red;}

table.url, table.track, table.add-url {
	width: 100%;	
}
table.url, table.url th, table.url td {
  border: 1px solid black;
  border-collapse: collapse;
}
input.add-rows{
	width: 100%;	
}
tr.deleted{
	text-decoration: line-through;
}
</style>
<script>
	function confirmSubmit() {
        return confirm('Sei sicuro di voler resettare gli elementi eliminati?');
    }
	$( document ).ready(function() {
			$('#list_id').change(function(){
				$(this).parents('form').submit();
			});
			
			$('#allrows').change(function(){
						var val = $(this).prop('checked');
						$('input.rows').prop('checked',val);
			});
			$('#push-allrows').change(function(){
						var val = $(this).prop('checked');
						$('input.push-rows').prop('checked',val);
			});
			$('input.push-rows,#push-allrows').prop('checked',true);
			
	});	
</script>
</head>
<body>
<h1>Aggiornamento url + push per signature anti-phishing </h1>
<br />

<?php
	 error_reporting( E_ALL );
	 global $app_id,$rest_key,$REQfields;

	 $go=false;
	 //$list_id="it.alfagroup.cybtest.cyberThreat";
	 $list_id = '';
	 
	 if( $_SESSION["list_id"]!='' ){
		$list_id=$_SESSION["list_id"];
	 }
	 if( $_POST["list_id"]!='' ){
		$list_id=$_POST["list_id"];
	 }
	 
	// $time = 52785859;

	// require_once("./lib/utils.php");

	$apps = array(
			array( "name" => "FCM - proj. cybtest (dmasotti@gmail.com)", "API_KEY" => "AAAA3MCxiiE:APA91bEK8GtA6PyyL4HiuBfc_KrxvpyFWxoi7yjJcSH3a-ZXVqVpwLgnlbUJ5cI37yiZMiRwZhEvjly4ON5Xd19mvpNQ-9-LepWFgTGq78-1kXjrlUnKDaS2Y2QaIHjy_xfOpSNz2Em0"),			  
			//array( "name" => "RHD (fcm bryansanchez.311@gmail.com)", "rest_key" => "AAAABXbDYOM:APA91bFlJGHlSh6xXgKeD7ANYHp63poH1pQJSF-FD-OgshQynnwZdQx7r8pGLV435F-pMkQ8t2BvaLA_RiLenEGfjHc_wSXHSMKtBTH2HypJZuK6ux05q4lPfPybzcTMHqRRmexokwLT", "app_id" => "f4e0997b-bca6-4cbb-be6a-f8772a868fbd" , "apptype" => "rhd" , "provider" => "FCM" ),
	);
	
	//$tracks = getTrackid();
	
	
	
	$idapp=0;
	if( $_POST["APP_ID"]!=""){
		$app = $apps[intval($_POST["APP_ID"])];
		$_SESSION["APP_ID"]=$_POST["APP_ID"];
    	$idapp=intval($_POST["APP_ID"]);
	}else{
		
		if( $_SESSION["APP_ID"]!=''){
			$app = $apps[intval($_SESSION["APP_ID"])];
      		$idapp=intval($_SESSION["APP_ID"]);
		}else{
			$app = $apps[0];
      		$idapp=0;
		}
	}
	

	$API_KEY =  $app["API_KEY"];//"M2FjODA3YTItYWRmNy00MjBiLThkOGItZTY4ZTgzMjUzYWFh";
	if($_POST["trash"]!=""){
		// resetta la base dati togliendo tutti gli eliminati e impostando il valore di basemin
		$ret = restoreBaseDelete($list_id);
		if($ret>0){
			echo "Reset OK (".$ret.")";
		}else{
			echo "Reset KO";
		}

	}
	if($_POST["add"]!=""){
			//add-value add-description add-type
			
			
			
		$val = trim($_POST["add-value"]);
		$typ = trim($_POST["add-type"]);
		$desc = trim($_POST["add-description"]);
		$deleterows = $_POST["delete-rows"];
		if(is_array($deleterows) && count($deleterows)>0){
			$ret = deleteUrlInList($deleterows,$list_id);
			if($ret>0){
				echo "Delete OK (".$ret.")";
			}else{
				echo "Delete KO";
			}
		}	
		if($val<>""){
			$ret = addUrlInList($val,$typ,$desc,$list_id);
			if($ret>0){
				echo "Aggiunta OK";
			}else{
				echo "Aggiunta KO";
			}
		}

	}
	
		if($_POST["invia"]!=""){
			$response = sendPushNotification($list_id,$_POST["rows"],$_POST["push-rows"]);
			
			/*switch($provider){
					case "FCM":
						$response = sendPushMessageFCM($_POST["tagid"],$_POST["uuid"],$_POST["tab"],$_POST["worksp"],$_POST["module"],$_POST["command"],$_POST["code"], $_POST["titolo"],$_POST["testo"],$icon,$image,$_POST["forceid"]);

						break;
					case "OS":
					default:	
						$response = sendPushMessage($_POST["tagid"],$_POST["uuid"],$_POST["tab"],$_POST["worksp"],$_POST["module"],$_POST["command"],$_POST["code"], $_POST["titolo"],$_POST["testo"],$icon,$image,$_POST["forceid"]);

						break;	
			}*/
			
//			$return["allresponses"] = $response;
//			$return = json_encode( $return);
//			print("\n\nJSON received:\n");
			
			echo "<h4>Request:</h4><pre>";
			print( json_encode($REQfields, JSON_PRETTY_PRINT) );
      		echo "</pre>";

			echo "<h4>Response:</h4><pre>";
			var_dump($response );
			//print(json_encode($response, JSON_PRETTY_PRINT) );
			echo "</pre>";			
//			print("\n");
     //  $response = json_decode($response);
     //  echo "<h4>Response2:</h4><pre>";
		//	var_dump($response);
		//	echo "</pre>";	
   //    $_SESSION["not_id"] = $response->id; 
   //    $response_id = registerPushResponse($response,$_POST["tagid"],$_POST["uuid"]);
       
			
			 $track_id=$_POST["track_id"];
			 $_SESSION["track_id"] = $track_id;
			 
       
		}

	// 
	
	$lists = getListid();
	$urls  = getUrlsByList($list_id);
	$tracks  = getTracksByList($list_id);
	
	
?>




<br />
<form method="POST">
<table style="width:100%;">
	<!--<tr>
		<td>App</td><td>
			<select name="APP_ID" >
				<?php
					for( $i=0;$i<count($apps);$i++){
						echo '<option value="'.$i.'" '.(($apps[$i]["name"]==$app["name"])?' selected="selected"':'').' > '.$apps[$i]["name"].' </option>';
					}
				?>
				<option> </option>
			</select>
		</td>
	</tr>-->
<tr>
	<td>Lista </td><td>
		<select id="list_id" name="list_id" >
				<option>  </option>
				<?php
					for( $i=0;$i<count($lists);$i++){
						echo '<option value="'.$lists[$i]["id"].'" '.(($lists[$i]["id"]==$list_id)?' selected="selected"':'').' > '.$lists[$i]["type"].' - '.$lists[$i]["code"].' - '.$lists[$i]["description"].' '.' </option>';
					}
				?>
				<option> </option>
		</select>
		
		<!--<input type="text" id="uuid" name="track_id" size="80" value="<?php echo $track_id; ?>" >
		-->
		</td>
</tr>
<tr>
	<th colspan="2"><hr /></th>
</tr>
<tr>
	<th colspan="2"><h3> Trac ids associati (<?php echo $list_id; ?>) </h3></th>
</tr>
<tr>
	<td colspan="2"> 
		<table class="track">	
			<tr>
				<th > <input type="checkbox" id="push-allrows"  name="push-allrows" value="1"   />  </th>
				<th > id </th>
				<th > track </th>
				<th > desc </th>
				<th > fcm push key </th>
			</tr>
			<?php
					for( $i=0;$i<count($tracks);$i++){
						$t = $tracks[$i];
						// `id`, `code`, `description`, `fcm_api_key` 
			?>
			
			<tr>
				<td > 
					<?php 
							if($t["fcm_api_key"]<>"") { 
					?>
						<input type="checkbox" class="push-rows"  name="push-rows[]" value="<?php echo $t["id"]; ?>"   /> 
					<?php
							} else { 
								echo '<i class="fas fa-xmark"></i>'; 
							} ; 
					?> 
				</td>
				<td > <?php echo $t["id"]; ?> </td>
				<td > <?php echo $t["code"]; ?> </td>
				<td > <?php echo $t["description"]; ?> </td>
				<td > 
					<?php 
							if($t["fcm_api_key"]<>"") { 
								echo '<i class="fas fa-check"></i>'; 
							} else { 
								echo '<i class="fas fa-xmark"></i>'; 
							} ; 
					?> 
				</td>
				<td > 
						
				</td>
			</tr>
			<?php		
					}
			?>			
	</table>	
	</td>
</tr>
<tr>
	<th colspan="2"><hr /></th>
</tr>
<tr>
	<th colspan="2"><h3> Prop. Lista </h3></th>
</tr>
<tr>
<?php
	for( $i=0;$i<count($lists);$i++){
		if($lists[$i]["id"]==$list_id) {
			echo "<td colspan=\"6\"><center><table>";
			echo "<tr><td>id</td><td>".$lists[$i]["id"]."</td><tr>";
			echo "<tr><td>code</td><td>".$lists[$i]["code"]."</td><tr>";
			echo "<tr><td>description</td><td>".$lists[$i]["description"]."</td><tr>";
			echo "<tr><td>updated</td><td>".$lists[$i]["updated"]."</td><tr>";
			echo "<tr><td>mintimestamp</td><td>".$lists[$i]["mintimestamp"]."</td><tr>";
			echo "<tr><td>md5</td><td>".$lists[$i]["md5"]."</td><tr>";
			echo "<tr><td>version</td><td>".$lists[$i]["version"]."</td><tr>";
			echo "</table></center></td>";
		}
		//echo '<option value="'.$lists[$i]["id"].'" '.(($lists[$i]["id"]==$list_id)?' selected="selected"':'').' > '.$lists[$i]["type"].' - '.$lists[$i]["code"].' - '.$lists[$i]["description"].' '.' </option>';
	}
?>
</tr>	
<tr>
	<th colspan="2"><hr /></th>
</tr>
<tr>
	<th colspan="2"><h3> Url di lista (<?php echo $list_id; ?>) </h3></th>
</tr>
<tr>
	<td colspan="2"> 
		<table class="url">	
			<tr>
				<th > <input type="checkbox" id="allrows"  name="allrows" value="1"   />  </th>
				<th > id </th>
				<th > value </th>
				<th > type </th>
				<th > desc </th>
				<th > datemod </th>
				<th > deleted </th>
			</tr>
			<?php
					for( $i=0;$i<count($urls);$i++){
						$u = $urls[$i];
						$cls = (($u["deleted"]=='1')?"deleted":"");
			?>
			
			<tr class="<?php echo $cls; ?>" >
				<td > <input type="checkbox" class="rows"  name="rows[]" value="<?php echo $u["id"]; ?>"   /> </td>
				<td > <?php echo $u["id"]; ?> </td>
				<td > <?php echo $u["value"]; ?> </td>
				<td > <?php echo $u["type"]; ?> </td>
				<td > <?php echo $u["description"]; ?> </td>
				<td > <?php echo $u["datemod"]; ?> </td>
				<td > 
						<?php 
								echo $u["deleted"]; 
								if($u["deleted"]=='0'){
						?>			
										<input type="checkbox" class="delete-rows"  name="delete-rows[]" value="<?php echo $u["id"]; ?>"   />
						<?php			
								}else{
									
									
								}
						?> 
				</td>
			</tr>
			<?php		
					}
			?>
			</table>
	</td>
</tr>
<tr>
<td colspan="2">			
			<table class="add-url">
			<tr>
				<td colspan="3" > valore </td>
				<td> tipologia</td>
				<td colspan="3" > descrizione </td>
				<td>  </td>
				<td>  </td>
			</tr>
			<tr>
				<td colspan="3" > <input type="input" class="add-rows"  name="add-value" value="" /> </td>
				<td> <input type="radio" class=""  name="add-type" value="0" /> plain <input type="radio" class=""  name="add-type" value="1" /> reg-ex </td>
				<td colspan="3" > <input type="input" class="add-rows"  name="add-description" value="" /> </td>
				<td> &nbsp; <button class="cmd" type="submit" name="add" value="add" style="border: 1px solid;" ><i class="fas fa-plus"></i> Modifica/Aggiungi </button> </td>
				<td> &nbsp; <button class="cmd" type="submit" name="trash" value="add" style="border: 1px solid;" onclick="return confirmSubmit();" ><i class="fas fa-trash"></i>Resetta Eliminati </button> </td>
			</tr>
			
	</table>	
	</td>
</tr>
<tr>
	<th colspan="2">	
	<input type="submit" style="width:150px;height:70px;color: red;" name="invia" value="invia Push Notification" />
	</th>
</tr>	
</table>

 
</form>
<div id="ajaxresult"></div>
<hr />
<br /><br /><br />


<?php
	 	if($_POST["getEnrolled"]!=""){
			$response = getEnrolledDevices();
			
			
			echo '<div id="response_table"><table border="1"  >';
			echo "<tr><td colspan='6'>Devices: " .count($response).  "</td></tr>";
     		 echo "<tr><td>#</td><td>Player (Push) ID</td><td>Device</td><td>Istanze RHD</td><td>last_active</td><td>ip</td></tr>";
			$n = 1;
			for( $i=0;$i<count($response);$i++){
				//echo $i;
				$r = $response[$i];
				//print_r($r);
				$v = '1';
				$k = $r["appId"];
				//$tg = $k;
				$tg = '<div style="display:inline;">'.(($v=='1')?'<a href="javascript:void(0);" class="select-player" data-playerid="'.$r["pushid"].'" data-tag="'.str_replace('rhd_not_','',$k).'" ><span class="dot green"></span></a>':'<span class="dot red"></span>').'&nbsp;&nbsp;'.str_replace('rhd_not_','',$k).'</div>';

				echo "<tr><td>" .$n.  "</td><td>" .$r["pushid"].  "</td><td>" .$r["deviceType"].  "</td><td>" .$tg.  "</td><td>"  . $r["last_active"] . "</td><td>" .$r["ip"].  "</td></tr>";
				
				$n++;
			}
			
			echo "</table></div>";			
		 		
		}

		if($_POST["getDevices"]!=""){

			$response = getAllDevices();
			$pushEn = getPushEnabled();
			$pushIds = getPushIds();
			
			
			echo '<div id="response_table">'.$app_id.' : '.$rest_key. print_r($_POST,true).' '.print_r($_SESSION,true) .'<table border="1"  >';
			echo "<tr><td colspan='6'>Devices: " .$response["total_count"].  "</td></tr>";
      echo "<tr><td>#</td><td>Player (Push) ID</td><td>Device</td><td>Istanze RHD</td><td>last_active</td><td>ip</td></tr>";

			$n = 1;
			usort($response["players"], function($a,$b){ return $b["last_active"]-$a["last_active"]; });
			foreach($response["players"] as $r){
				$tags = array();
				foreach($r["tags"] as $k => $v){
					//$tags[] = str_replace('rhd_not_','',$k).' : '.(($v=='1')?'<a href="#" data-playerid="" data-tag="" >Attivo</a>':'');
					$tags[] = '<div style="display:inline;">'.(($v=='1')?'<a href="javascript:void(0);" class="select-player" data-playerid="'.$r["id"].'" data-tag="'.str_replace('rhd_not_','',$k).'" ><span class="dot green"></span></a>':'<span class="dot red"></span>').'&nbsp;&nbsp;'.str_replace('rhd_not_','',$k).'</div>';

				}
				
				if(array_key_exists($r["id"],$pushIds)){
            		$k1 = $pushIds[$r["id"]];
            		if(array_key_exists($k1,$pushEn)){
                		foreach($pushEn[$k1] as  $v){
                     		$tags[] = '<div style="display:inline;"><a href="javascript:void(0);" class="select-player" data-playerid="'.$r["id"].'" data-tag="'.$v.'" ><span class="dot green"></span></a>-&nbsp;&nbsp;'.$v.'</div>';
  
                		}
            		}
            
        		}


				echo "<tr><td>" .$n.  "</td><td>" .$r["id"].  "</td><td>" .$r["device_model"].  "</td><td>" .implode('<br />',$tags).  "</td><td>" .date('Y-m-d h:i:s',$r["last_active"]).  "</td><td>" .$r["ip"].  "</td></tr>";
				$n = $n+1;
			}
			
			echo "</table></div>";			
		 		
		}

?>

<!--<form method="POST">
	<input type="submit" style="width:200px;height:100px;color: red;" name="getApps" value="getApps" />
</form>-->
<?php
	 

		if($_POST["viewPushResponse"]!=""){
			$response = viewPushResponse();
			//var_dump($response);
			echo '<div id="response_table"><table border="1" >';
			echo "<tr><td>#</td><td>ID PUSH</td><td>Istanza</td><td>date</td><td>Recipients</td><td>user_ids</td><td>app_id</td></tr>";
			$n = 1;
			//usort($response["players"], function($a,$b){ return $b["last_active"]-$a["last_active"]; });
			foreach($response as $r){
				/*$tags = array();
				foreach($r["tags"] as $k => $v){
					//$tags[] = str_replace('rhd_not_','',$k).' : '.(($v=='1')?'<a href="#" data-playerid="" data-tag="" >Attivo</a>':'');
					$tags[] = '<div style="display:inline;">'.(($v=='1')?'<a href="javascript:void(0);" class="select-player" data-playerid="'.$r["id"].'" data-tag="'.str_replace('rhd_not_','',$k).'" ><span class="dot green"></span></a>':'<span class="dot red"></span>').'&nbsp;&nbsp;'.str_replace('rhd_not_','',$k).'</div>';

				}*/

				echo "<tr><td>" .$r["id"].  "</td><td>" .$r["id_push"].  "</td><td>" .$r["id_item"].  "</td><td>" .date('Y-m-d h:i:s',$r["last_active"]).  "</td><td>" .$r["recipients"].  "</td><td>" .$r["user_ids"].  "</td><td>" .$r["app_id"].  "</td></tr>";
				$n = $n+1;
			}
			
			echo "</table></div>";			
		 		
		}

?>

<br /><br /><br />

<?php
    $not_id = $_SESSION["not_id"];
	  

		if($_POST["notification"]!=""){
      
      $not_id = $_POST["not_id"];
      
      $_SESSION["not_id"] = $not_id;
      
			$response = viewNotification($not_id);
		
      echo '<pre><div id="response_table">';			
			var_dump($response);
			echo "</pre></div>";	
		}

?>

<?php
	 

		if($_POST["outcomes"]!=""){
			$response = viewOutcomes();
		
      echo '<pre><div id="response_table">';			
			var_dump($response);
			echo "</pre></div>";	
		}

?>


<?php
	 

		if($_POST["getApps"]!=""){
			$response = getApps();
			
			//echo "<h4>Devices: " .$response["total_count"].  "</h4>";
			echo '<div id="response_table"><table >';
      echo "<tr><td colspan='6'>Devices: " .$response["total_count"].  "</td></tr>";
			echo "<tr><td>#</td><td>ID</td><td>Device</td><td>Sub</td><td>last_active</td><td>ip</td></tr>";
			$n = 1;
			foreach($response["players"] as $r){
				$tags = array();
				foreach($r["tags"] as $k => $v){
					$tags[] = str_replace('rhd_not_','',$k).' : '.(($v=='1')?'Attivo':'');


				}

				echo "<tr><td>" .$n.  "</td><td>" .$r["id"].  "</td><td>" .$r["device_model"].  "</td><td>" .implode('<br />',$tags).  "</td><td>" .date('Y-m-d h:n:s',$r["last_active"]).  "</td><td>" .$r["ip"].  "</td></tr>";
				$n = $n+1;
			}
			
			echo "</table></div>";			
			echo "<pre>";			
			var_dump($response);
			echo "</pre>";			
		 		
		}
?>

<hr />

</body>
</html>