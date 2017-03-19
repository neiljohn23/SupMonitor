<?php
    require_once('codebird.php');

   
    function sendAlert($itemsArray){
		
		global $ConsumerKey, $ConsumerSecret, $AccessToken, $AccessTokenSecret;
		
        if(count($itemsArray) != 0){ 
			
			 if($ConsumerKey == ""){
                for($y =0; $y<count($itemsArray); $y++){
                    
                    $message = $itemsArray[$y]['Name']. " has restocked in " . $itemsArray[$y]['Color'];
                    echo "\n" .$message. "";
                   
                    if($itemsArray[$y]['cop'] != 1){
                        echo "\nItem is not eligible for auto-checkout.\n";
                    } else {
                        echo "\nSending to auto-checkout with link " .$itemsArray[$y]['Link']. " and type as " .$itemsArray[$y]['Type'];
                    }
                
                }
            } else {
				  
            \Codebird\Codebird::setConsumerKey($ConsumerKey, $ConsumerSecret);
            $cb = \Codebird\Codebird::getInstance();
            $cb->setToken($AccessToken, $AccessTokenSecret);
            
                echo $ConsumerKey."\n".$ConsumerSecret."\n".$AccessToken."\n".$AccessTokenSecret;
                for($y =0; $y<count($itemsArray); $y++){
                    
                    
                
                    $message = $itemsArray[$y]['Name']. " has restocked in " . $itemsArray[$y]['Color'];
                    echo "\n" .$message. "\n\n";
                    $messageToSend = $message. ".\nwww.supremenewyork.com". $itemsArray[$y]['Link'];
                    echo "\nSending tweet...";
                    
                    $params = array(
                                    'status' => $messageToSend
                                    );
                    $reply = $cb->statuses_update($params);
                    
                    echo "Tweet sent.";
                    
                    if($itemsArray[$y]['cop'] != 1){
                        echo "\nItem is not eligible for auto-checkout.\n";
                    } else {
                        echo "\nSending to auto-checkout with link " .$itemsArray[$y]['Link']. " and type as " .$itemsArray[$y]['Type'];
                    }
                
                }
            
				
				
				
				
				
            }
            
        }
    }	
?>
