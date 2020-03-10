<?php
//Creates new record as per request
    //Connect to database
    $servername = "localhost";		//example = localhost or 192.168.0.0
    $username = "root";		//example = root
    $password = "F3V3Rh4ck3d";	
    $dbname = "su_projesi";

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);
    // Check connection
    if ($conn->connect_error) {
        die("Database Connection failed: " . $conn->connect_error);
    }

    //Get current date and time
    date_default_timezone_set('Etc/GMT-3');
    $d = date("Y-m-d");
    $t = date("H:i:s");

    if(!empty($_POST['anlik']))
    {
		$akanSivi =  (float)$_POST['anlik']/1000;
		$sorgu = $conn->query("SELECT * FROM su");
		while ($sonuc = $sorgu->fetch_assoc()) {
			$gunlukDB = $sonuc['gunlukSuTuketimi'];
			$haftalikDB = $sonuc['haftalikSuTuketimi'];
			$aylikDB = $sonuc['aylikSuTuketimi'];
		}
		$gunlukDB += $akanSivi;
		$haftalikDB+=$akanSivi;
		$aylikDB+=$akanSivi;
		$sql = "INSERT INTO su (anlikSuTuketimi, gunlukSuTuketimi, haftalikSuTuketimi , aylikSuTuketimi) VALUES ('".$akanSivi."', '".$gunlukDB."', '".$haftalikDB."', '".$aylikDB."')";
		
		if ($conn->query($sql) === TRUE) {
		    echo "OK";
		} else {
		    echo "Error: " . $sql . "<br>" . $conn->error;
		}
		echo $akanSivi;
	}
	else 
	{
		$akanSivi = 0;
	}
	echo $akanSivi;
	$conn->close();
?>