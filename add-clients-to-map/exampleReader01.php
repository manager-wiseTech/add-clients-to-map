<?php
error_reporting(0);
/*
This function returns the longitude and latitude of the client address.
It recieves to parameters.
@ stateid is the current map id
@ streetid is the street address of the client

return latitude and longitude of the given client address
*/
function getlanandlogi($stateid, $streetid){
	global $wpdb;  
	global $jal_db_version;  
	$table_name = $wpdb->prefix . "maingooglemap";
	$sql = "SELECT * from " .$table_name. " where id=$stateid";
	$qry_results = $wpdb->get_results($sql);
	foreach($qry_results as $qry_result){
        $streetaddress = $streetid;
		$cityaddress = $qry_result->countyname;
		$stateaddress = $qry_result->state;
		$countryaddres = $qry_result->Country;
		$partialaddress = $streetaddress.' '.$cityaddress.' '.$stateaddress;
		$address = str_replace(" ", "+", $partialaddress);		
		$geocode =  wp_remote_request('https://maps.google.com/maps/api/geocode/json?key='.get_option('google_map_api_key').'&address='.$address.'&sensor=false');
		$geocode = $geocode['body'];
		$output = json_decode($geocode);
		if($output->status == 'OK') {
			$latitude = $output->results[0]->geometry->location->lat;
			$longitude = $output->results[0]->geometry->location->lng;
			if(!empty($latitude) && !empty($longitude)) {
				$arrayforlongandlati[0]=$latitude;
				$arrayforlongandlati[1]=$longitude;
				return $arrayforlongandlati;
			}
		}
	}			
}
?>
<form action="#" method="post" enctype="multipart/form-data">
  	<ul>
    	<li><input type="file" name="file" id="file"></li>
    	<li><input class="button button-primary" type="submit" name="importExcel" value="Submit"></li>
  	</ul>
</form>
<?php
global $wpdb;  
global $jal_db_version;  

if(isset($_POST['importExcel'])){

	//Including autoload.php file which is required to include the phpSpreadsheet classes
	require_once( plugin_dir_path(__FILE__) . 'vendor/autoload.php' );

	//now take instance of phpSpreadsheet class
	$objPHPExcel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

	
    //storing the allowed file extensions.
$file_mimes = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
 
if(isset($_FILES['file']['name']) && in_array($_FILES['file']['type'], $file_mimes)) {
    // getting file extension
    $arr_file = explode('.', $_FILES['file']['name']);
    $extension = end($arr_file);
    // comparing file extension to include corresponding file reader
        if('csv' == $extension) {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
        } else {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        }
    if ($_FILES["file"]["error"] > 0){
			echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
	    }
	else{
	    //displaying message
	    	echo "<b>Upload:</b>" . $_FILES["file"]["name"] . "<br>";
	    	echo "<b>Size: </b>" . round(($_FILES["file"]["size"] / 1024)) . " kB<br>";
	    	if (1==2 && file_exists("".dirname(__FILE__)."/upload/" . $_FILES["file"]["name"])){
	      		echo "<b>already exists.</b><br> ";
	   	}
	else{
	    $spreadsheet = $reader->load($_FILES['file']['tmp_name']);
        //getting spreadsheet data
        $sheetData = $spreadsheet->getActiveSheet()->toArray();
       // print_r($sheetData);echo"<br>";
        $table_name = $wpdb->prefix . "clinetgooglemap";
        for($i = 1; $i < count($sheetData); $i++)
        {   
            //street address
             $streetadd = $sheetData[$i][0];
            //city
             $cityadd = $sheetData[$i][1];
            //state
             $stateaddresss = $sheetData[$i][2];
            //zipcode
             $zipcode = $sheetData[$i][3];
            //calling the getlanandlogi() function and passing required parameters
            $arraylatilangi = getlanandlogi($_GET['mpidd'],$streetadd);
            //latitude
            $lat = (!empty($arraylatilangi[0])) ? $arraylatilangi[0] : '';
			//longitude
			$lang = (!empty($arraylatilangi[1])) ? $arraylatilangi[1] : '';
            // generating micro content.
            $microcontent = '<div id="" class="vcard">';
            $microcontent .= '<div class="adr">';
            $microcontent .= '<div class="street-address">'.$streetadd.'</div>';
            $microcontent .= '<span class="locality">'.$cityadd.'</span>,';
            $microcontent .= '<span class="region">'.$stateaddresss.'</span>,';
            $microcontent .= '<span class="postal-code">'.$zipcode.'</span></div></div>';
            //building database query
            $insert_user ="INSERT into $table_name set ";
            $insert_user.="mapid='".$_GET['mpidd']."',
					 	streetaddress='".$streetadd."',
					 	city='".$cityadd."',
					 	content='".$microcontent."',
					 	latitude='".$lat."',            
					 	longitude='".$lang."'";   
			//Inserting data into database
            $wpdb->query($insert_user);
            
        }
        //displaying suuccess message
				echo '<p style="color:green">INSERTED !!!</p>';
				echo '<br>';
				$name = time().$_FILES["file"]["name"];
		//Uploading the file into the upload folder of the plugin directory.
				move_uploaded_file($_FILES["file"]["tmp_name"],"".dirname(__FILE__)."/upload/".$name);
				chmod(dirname(__FILE__)."/upload/".$name, 0777);
		      	echo "Stored in: " . "".dirname(__FILE__)."/upload/" . $name."<br>";
	            }
    
            }
    
        }
        else{
            echo"Invalid File";
        }
    
}


// if(isset($_POST['hello'])){
// 	$allowedExts = array("xlsx", "xlx", "jpg", "png");
// 	$temp = explode(".", $_FILES["file"]["name"]);
// 	$extension = end($temp);
// 	if ((($_FILES["file"]["type"] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet")
// 	|| ($_FILES["file"]["type"] == "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"))
// 	|| ($_FILES["file"]["size"] > 20000)
// 	|| in_array($extension, $allowedExts)){
// 	  	if ($_FILES["file"]["error"] > 0){
// 			echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
// 	    }else{
// 	    	echo "<b>Upload:</b>" . $_FILES["file"]["name"] . "<br>";
// 	    	echo "<b>Size: </b>" . ($_FILES["file"]["size"] / 1024) . " kB<br>";
// 	    	if (1==2 && file_exists("".dirname(__FILE__)."/upload/" . $_FILES["file"]["name"])){
// 	      		echo "<b>already exists.</b><br> ";
// 	    	}else{
// 				$name = time().$_FILES["file"]["name"];
// 				move_uploaded_file($_FILES["file"]["tmp_name"],"".dirname(__FILE__)."/upload/".$name);
// 				chmod(dirname(__FILE__)."/upload/".$name, 0777);
// 		      	echo "Stored in: " . "".dirname(__FILE__)."/upload/" . $name."<br>";
// 			  	/** Include path **/
// 				set_include_path(get_include_path() . PATH_SEPARATOR . 'Classes/');					
// 				/** PHPExcel_IOFactory */
// 				include dirname(__FILE__).'/Classes/PhpSpreadsheet/IOFactory.php';					
// 				$inputFileName = dirname(__FILE__).'/upload/'.$name;
// 				$inputFileType = PhpSpreadsheet/IOFactory::identify($inputFileName);
// 				$objReader = PhpSpreadsheet/IOFactory::createReader($inputFileType);
// 				$objPHPExcel = PhpSpreadsheet/IOFactory::load($inputFileName);
// 				$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);
				
// 				$j = 1;
// 				$arraylatilangi = getlanandlogi($_GET['mpidd'],'609 Evans Rd');
// 				// echo '<p style="color:red">Please wait ...</p>';die;
// 				for ($i = 2; $i <= count($sheetData); $i++) {
// 					if($j == 8){
// 						sleep(1);
// 						$j = 1;
// 					}
// 					$addresss = $sheetData[$i]['A'];
// 				if(empty($addresss)){echo"empty address";}
// 					$stateaddresss = str_replace(" ", "", $sheetData[$i]['C']);
// 					$zipcode = str_replace(" ", "", $sheetData[$i]['D']);
// 					$contentinfo='<strong>'.$sheetData[$i]['A'].'</strong><br>'.$sheetData[$i]['B'].' '.$stateaddresss.','.$zipcode.'';
// 					$arraylatilangi = getlanandlogi($_GET['mpidd'],$addresss);
// 					$lat = (!empty($arraylatilangi[0])) ? $arraylatilangi[0] : '';
// 					$lang = (!empty($arraylatilangi[1])) ? $arraylatilangi[1] : '';
// 					$table_name = $wpdb->prefix . "clinetgooglemap";
// 					$insert_user ="INSERT into $table_name set ";
// 					$insert_user.="mapid='".$_GET['mpidd']."',
// 					 	streetaddress='".$sheetData[$i]['A']."',
// 					 	city='".$sheetData[$i]['B']."',
// 					 	content='".$contentinfo."',
// 					 	latitude='".$lat."',            
// 					 	longitude='".$lang."'";
// 					$wpdb->query($insert_user);
// 					$j++;
// 				}
// 				echo '<p style="color:green">INSERTED !!!</p>';
// 				echo '<br>';
// 	    	}
// 	    }
// 	}else{
// 	  	echo "Invalid file";
// 	}
// }
?>