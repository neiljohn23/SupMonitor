<?php
    
    

    
    function changeStock($itemArray){
        if(count($itemArray) != 0){
			
			global $host, $user, $password;

            $connection = mysql_connect($host,$user,$password);
            
            if(!$connection){
                die('Connection Failed');
            } else{
                $dbconnect = @mysql_select_db('SupremeStock', $connection);
                
                if(!$dbconnect){
                    die('Could not connect to Database');
                } else{
                    
                    $qCount = 0;
                    for($w=0; $w<count($itemArray);$w++){
                        
                        $link = $itemArray[$w]['Link'];
                        $stockVal = $itemArray[$w]['In Stock'];
                        
                        if($stockVal == "true"){
                            $newSv = "false";
                        } else {
                            $newSv = "true";
                        }
                        
                        $query = "UPDATE currentstock SET InStock = '$newSv' WHERE Link = '$link';";
                        mysql_query($query, $connection) or die(mysql_error());
                        $qCount++;
                    }
                    echo "Updated stock for " .$qCount. " items.\n";
                }
            }
        } else {
            echo "No changes found.\n";
        }
    }
    
    function purgeDb(){
        echo "Purging database...";
        $pages_array = array(
                             "jackets",
                             "shirts",
                             "tops_sweaters",
                             "sweatshirts",
                             "pants",
                             "t-shirts",
                             "hats",
                             "bags",
                             "shoes",
                             "accessories",
                             "skate",
                             "shorts"
                             );
        $arrindex = 0;
        echo "\nGetting new items.";
        foreach($pages_array as $link) {
            
            $target = "http://www.supremenewyork.com/shop/all/" .$link;
            $web_page = http_get($target, "");
            $page = $web_page["FILE"];
            $html = str_get_html($page);
            
            $divTags = $html->find("article");
            
            foreach($divTags as $x) {
                $sib = $x->first_child()->first_child();
                
                if(strpos($sib, "sold out")){
                    //out of stock
                    $IIS = 'false';
                } else {
                    $IIS = 'true';
                }
                
                $Ilink = $sib->href;
                $Iname = $sib->next_sibling()->plaintext;
                $Icolor = $sib->next_sibling()->next_sibling()->plaintext;
                
                $all_items[$arrindex] = array("name"=>$Iname,
                                              "color"=>$Icolor,
                                              "link"=>$Ilink,
                                              "type"=>$link,
                                              "inStock"=>$IIS
                                              );
                
                
                //echo "<br>" .$Ilink. "<br>" .$Iname. "<br>" .$Icolor. "<br>";
                $arrindex++;
            }
            
            sleep(1);
            
            
            
        }
        
		global $host, $user, $password;

        $connection = mysql_connect($host,$user,$password);
        
        $itemNumbers =0;
        if(!$connection){
            die('Connection Failed');
        }
        else{
            $dbconnect = @mysql_select_db('SupremeStock', $connection);
            
            if(!$dbconnect){
                die('Could not connect to Database');
            }
            else{
                
                $query2 = "TRUNCATE currentstock";
                mysql_query($query2, $connection) or die(mysql_error());
                echo "\nDatabase purged.";
                
                foreach($all_items as $item){
                    
                    $itname = $item['name'];
                    
                    
                    $itname = str_replace(chr(34),  '', $itname); // replace double vertical
                    $itname = str_replace(chr(39),  '', $itname); // replace single vertical
                    $itname = str_replace(chr(145), '',     $itname); // replace single left
                    $itname = str_replace(chr(146), '',    $itname); // replace single right
                    $itname = str_replace(chr(147), '',     $itname); // replace double left
                    $itname = str_replace(chr(148), '',    $itname); // replace double right
                    
                    
                    $itcolor = $item['color'];
                    $itlink = $item['link'];
                    $ittype = $item['type'];
                    $itinstock = $item['inStock'];
                    
                    
                    
                    $query = "INSERT INTO `SupremeStock`.`currentstock` (`Name`, `Color`, `Link`, `Type`, `InStock`, `increment`) VALUES ('$itname', '$itcolor', '$itlink', '$ittype', '$itinstock', NULL);";
                    mysql_query($query, $connection) or die(mysql_error());
                    $itemNumbers++;
                    

                   
                }
                echo "\nSuccessfully purged and added " .$itemNumbers. " items.\nWaiting 30 seconds...";
            }
        }

        
    }

	
?>
