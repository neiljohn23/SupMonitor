<?php
    
    include("DBConfig.php");



	// Create connection
	$conn = new mysqli($host, $user, $password);
	// Check connection
	if ($conn->connect_error) {
		die("Connection failed: " . $conn->connect_error);
	} 

	// Create database
	$sql = "CREATE DATABASE SupremeStock";
	
	if ($conn->query($sql) === TRUE) {
		echo "Database created successfully\n";
	} else {
		echo "Error creating database: " . $conn->error;
	}
	
	$dbconnect = mysqli_select_db($conn,"SupremeStock");
                
    if(!$dbconnect){
         die('Could not connect to new database');
      }else{
	
		// Create table
		$sql2 = "CREATE TABLE `currentstock` (
				`Name` varchar(255) NOT NULL,
				`Color` varchar(255) NOT NULL,
				`Link` varchar(255) NOT NULL,
				`Type` varchar(255) NOT NULL,
				`InStock` varchar(255) NOT NULL,
				`increment` int(4) NOT NULL AUTO_INCREMENT,
				`cop` varchar(255) NOT NULL DEFAULT '',
				PRIMARY KEY (`increment`)
				) ENGINE=InnoDB AUTO_INCREMENT=147 DEFAULT CHARSET=utf8";


		if ($conn->query($sql2) === TRUE) {
			echo "Table created successfully\n";
		} else {
			echo "Error creating table: " . $conn->error;
		}
	}
	$conn->close();
  
	
?>
