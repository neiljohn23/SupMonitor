<?php
    
    include("LIB_http.php");
    include("simple_html_dom.php");
    include("sendRestockAlert.php");
    include("supDbUtil.php");
    include("DBConfig.php");
    
    echo "\nBot created by Neil Johnson. Twitter: @JohnsonNeil\nThis monitor is configured to check every 30-45 seconds for supreme restocks.\nRestock tweets can be configured in the DBConfig.php file.";
    
    monitor();
    
    function monitor(){
        $records = array();
		global $host, $user, $password;
        
        $connection = mysql_connect($host,$user,$password);
        
        date_default_timezone_set('EST');
        
        echo "\n" .date("g:i:s"). ": ";
        
        
        if(!$connection){
            echo "Connection failed.";
        }
        else{
            $dbconnect = @mysql_select_db('SupremeStock', $connection);
            
            if(!$dbconnect){
                echo 'Could not connect to Database.';
            }
            else{
                $query = 'SELECT * FROM currentstock';
                
                $resultset = mysql_query($query, $connection);
                
                $records= array();
                
                while($r = mysql_fetch_assoc($resultset)){
                    $records[] = $r;
                    
                    
                }//above means cxn worked
                
                
                gotDB($records);
                
                
                
            }
        }
        unset($records, $host, $user, $password, $connection, $dbconnect, $query, $resultset, $records, $r, $dbItems, $web_page, $page, $html ,$divtags, $arrindex, $chIndex, $rskindex, $itemsArray, $changed_stock, $currentItemNumber, $dbNumber, $sib, $IIS, $Ilink, $rightIndexdb, $dbStock, $numbersArray);
        sleep(rand(30, 50));
        monitor();
        
    }
    
    
    function gotDB($dbItems) {
        
        
        //get current items($ctItems) from shop all
        
        $target = "http://www.supremenewyork.com/shop/all/";
        $web_page = http_get($target, "");
        $page = $web_page["FILE"];
        $html = str_get_html($page);
        
        $divTags = $html->find("article");
        
        $arrindex = 0;
        $chIndex = 0;
        
        $rskindex = 0;
        $itemsArray = array();
        $changed_stock = array();
        
        
        $currentItemNumber = count($divTags);
        $dbNumber = count($dbItems);
        
        if($currentItemNumber == $dbNumber){
            
            foreach($divTags as $x) {
                
                
                //echo $x;
                $sib = $x->first_child()->first_child();
                
                
                if(strpos($sib, "sold out")){
                    //out of stock
                    $IIS = 'false';
                } else {
                    $IIS = 'true';
                }
                
                $Ilink = $sib->href;

                $rightIndexdb = -1;
                
                for($i=0;$i<count($dbItems);$i++){
                    $dbLink = $dbItems[$i]["Link"];
                    
                    
                    if($dbLink == $Ilink){
                        $rightIndexdb = $i;
                        break;
                    }
                    
                    
                }
                
                if($rightIndexdb != -1){
                    //if it found corresponding item
                    $dbStock = $dbItems[$rightIndexdb]['InStock'];
                    if($IIS != $dbStock){
                        if($dbStock == "false"){
                            //RESTOCK
                            
                            $itemsArray[$rskindex]['Name'] = $dbItems[$rightIndexdb]['Name'];
                            $itemsArray[$rskindex]['Color'] = $dbItems[$rightIndexdb]['Color'];
                            $itemsArray[$rskindex]['Link'] = $dbItems[$rightIndexdb]['Link'];
                            $itemsArray[$rskindex]['cop'] = $dbItems[$rightIndexdb]['cop'];
                            $itemsArray[$rskindex]['Type'] = $dbItems[$rightIndexdb]['Type'];
                            $rskindex++;
                            
                        }
                        
                        $changed_stock[$chIndex]['Link'] = $dbItems[$rightIndexdb]['Link'];
                        $changed_stock[$chIndex]['In Stock'] = $dbItems[$rightIndexdb]['InStock'];
                        $chIndex++;
                        
                    }
                }
        }
        
        //notify users
        
        sendAlert($itemsArray);
        changeStock($changed_stock);
        } else {
            purgeDb();
        }
        
        
        
    }
    
    
    ?>
