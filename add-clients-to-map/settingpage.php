<div class="postbox " id="postexcerpt">
  	<div title="Click to toggle" class="handlediv"><br></div>
  	<h3 class="hndle" style="padding: 10px;">Setting</h3>
  	<div class="inside">
	<?php
 		global $wpdb;  
 		global $jal_db_version; 
		if(isset($_POST[action])){
			$cityaddress=$_POST['countyname'];
			$stateaddress=$_POST['state'];
			$mapzoom=$_POST['mapzoom'];
			$mapwidth=$_POST['mapwidth'];
			$mapheight=$_POST['mapheight'];
			$countryaddress=str_replace(" ", "+", $_POST['country']);
			$partialaddress=$streetaddress.' '.$cityaddress.' '.$stateaddress;
			$address = str_replace(" ", "+", $partialaddress);
			$url = "https://maps.google.com/maps/api/geocode/json?address=$address&key=".get_option('google_map_api_key')."&region=$countryaddres";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			$response = curl_exec($ch);
			curl_close($ch);
			$response_a = json_decode($response);
			$lat = $response_a->results[0]->geometry->location->lat;
			$long = $response_a->results[0]->geometry->location->lng;
	 		$table_name = $wpdb->prefix . "maingooglemap";
			$insert_user ="INSERT into $table_name set ";
			$insert_user.="state='".$stateaddress."',
			countyname='".$cityaddress."',
			Country='".$countryaddress."',
			mapzoom='".$mapzoom."',
			mapwidth='".$mapwidth."', 
			mapheight='".$mapheight."',
			latitude='".$lat."',            
			longitude='".$long."'";
			$wpdb->query($insert_user);
			echo '<p style="color:green">successfully ADDED !!!</p>';
		}
		if(isset($_POST[updatemapid])){
			$cityaddress=$_POST['countyname'];
			$stateaddress=$_POST['state'];
			$mapzoom=$_POST['mapzoom'];
			$mapwidth=$_POST['mapwidth'];
			$mapheight=$_POST['mapheight'];
			$countryaddress=str_replace(" ", "+", $_POST['country']);
			$partialaddress=$streetaddress.' '.$cityaddress.' '.$stateaddress;
			$address = str_replace(" ", "+", $partialaddress);
			$url = "http://maps.google.com/maps/api/geocode/json?address=$address&key=".get_option('google_map_api_key')."&region=$countryaddres";
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
			curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
			$response = curl_exec($ch);
			curl_close($ch);
			$response_a = json_decode($response);
			$lat = $response_a->results[0]->geometry->location->lat;
			$long = $response_a->results[0]->geometry->location->lng;
			$table_name = $wpdb->prefix . "maingooglemap";
			$insert_user ="UPDATE $table_name SET ";
			$insert_user.="
			state='".$stateaddress."',
			countyname='".$cityaddress."',
			Country='".$countryaddress."',
			mapzoom='".$mapzoom."',
			mapwidth='".$mapwidth."', 
			mapheight='".$mapheight."',
			latitude='".$lat."',            
			longitude='".$long."'
			WHERE id= $_GET[mpide]";
			$wpdb->query($insert_user);
			// $execute_query_user_insert=mysqli_query($insert_user);
			echo '<p>successfully Update !!!</p>';
		}

		if(isset($_GET['mpide'])){
			$table_name = $wpdb->prefix . "maingooglemap";
			$sql = "SELECT * from " .$table_name. " where id=$_GET[mpide]";
			$qry_results = $wpdb->get_results($sql);
			foreach($qry_results as $qry_result){}
		}

?>
  	<form name="addmainmap" action="" method="post">
 	<?php if(isset($_GET['mpide'])){
		 echo '<input type="hidden" name="updatemapid" value="updatemapid"  />';	 
	}else{
	 	echo '<input type="hidden" name="action" value="addmainmap"  />';
	}?>
		<table>
			<tr valign="top">
				<th scope="row"><label for="posts_per_rss">County Name : </label></th>
				<td><input type="text" class="regular-text ltr" value="<?= $qry_result->countyname?>" id="countylatitude" min="1" step="1" name="countyname"></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="posts_per_rss">State : </label></th>
				<td><input type="text" class="regular-text ltr" value="<?=$qry_result->state?>" id="state" min="1" step="1" name="state"></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="posts_per_rss">Country : </label></th>
				<td><input type="text" class="regular-text ltr" value="<?=$qry_result->Country?>" id="country" min="1" step="1" name="country"></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="posts_per_rss">Map Zoom :</label></th>
				<td><input type="text" class="regular-text ltr" value="<?=$qry_result->mapzoom?>" id="mapzoom" min="1" step="1" name="mapzoom"></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="posts_per_rss">Map Width :</label></th>
				<td><input type="text" class="regular-text ltr" value="<?=$qry_result->mapwidth?>" id="mapwidth" min="1" step="1" name="mapwidth"></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="posts_per_rss">Map height :</label></th>
				<td><input type="text" class="regular-text ltr" value="<?=$qry_result->mapheight?>" id="mapheight" min="1" step="1" name="mapheight"></td>
			</tr>
			<tr valign="top">
				<td colspan="2"><p class="submit"><input type="submit" value="Save Changes" class="button button-primary" id="submit" name="submit"></p></td>
			</tr>
		</table>
    </form>
  </div>
</div>