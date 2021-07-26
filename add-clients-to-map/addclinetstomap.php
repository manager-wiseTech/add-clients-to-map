<?php
/*
* This file has the code that is used to add clients data on to the particular map.
* $_GET['mpidd'] is the id of the current on which client has to be added.
*/
?>
<style>
td{
	vertical-align:middle;
	text-align:center;
}
</style>
<?php

error_reporting(1);
global $wpdb;  
global $jal_db_version;  
if(isset($_GET['mpidd'])){
	if(isset($_GET['edit'])){
 		$table_name = $wpdb->prefix . "clinetgooglemap";
	  	$sql = "SELECT * from " .$table_name. " where id=$_GET[edit]";
	  	$qry_resultss = $wpdb->get_results($sql);
	  	foreach($qry_resultss as $qry_results){ }
		//wp_editor( $qry_results->content, 'content-id' );
	}
	//--------------------------------------- ADD clients -----------------------------------------
?>
<style>
#add_jp_gallery{
	display:none;
}
</style>
<div class="postbox " id="postexcerpt">
	<div title="Click to toggle" class="handlediv"><br></div>
	<h3 class="hndle" style="padding: 10px;">Setting</h3>
  	<div class="inside">
		<?php
	 	if(isset($_POST['addclinet']) && isset($_GET['edit'])){ 
	 		$table_name = $wpdb->prefix . "maingooglemap";
		  	$sql = "SELECT * from " .$table_name. " where id=$_GET[mpidd]";
		  	$qry_results = $wpdb->get_results($sql);
		  	foreach($qry_results as $qry_result){
	            $streetaddress = $_POST['streetaddress'];
				$cityaddress=$qry_result->countyname;
				$stateaddress=$qry_result->state;
				$countryaddres=$qry_result->Country;
				$partialaddress=$streetaddress.' '.$cityaddress.' '.$stateaddress;
				$address = str_replace(" ", "+", $partialaddress);
				$url = "https://maps.google.com/maps/api/geocode/json?address=$address&key=".get_option('google_map_api_key')."&sensor=false&region=$countryaddres";
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

	 			$table_name = $wpdb->prefix . "clinetgooglemap";
	 			$insert_user ="UPDATE $table_name SET ";
	 			$insert_user.="
	 				mapid='".$_GET['mpidd']."',
					streetaddress='".$_POST['streetaddress']."',
					content='".$_POST['txtmessage']."',
					city='".$_POST['city']."',
					latitude='".$lat."',            
					longitude='".$long."'
					WHERE id= $_GET[edit]
	 			";
				$wpdb->query($insert_user);
			}
			// $execute_query_user_insert=mysqli_query($insert_user);
			echo '<p>successfully update !!!</p>';
		}else if(isset($_POST['addclinet'])){
	    	$table_name = $wpdb->prefix . "maingooglemap";
		  	$sql = "SELECT * from " .$table_name. " where id=$_GET[mpidd]";
		  	$qry_results = $wpdb->get_results($sql);
		  	foreach($qry_results as $qry_result){
	            $streetaddress=$_POST['streetaddress'];
				$cityaddress=$qry_result->countyname;
				$stateaddress=$qry_result->state;
				$countryaddres=$qry_result->Country;
				$partialaddress=$streetaddress.' '.$cityaddress.' '.$stateaddress;
				$address = str_replace(" ", "+", $partialaddress);
				$url = "https://maps.google.com/maps/api/geocode/json?address=$&key=".get_option('google_map_api_key')."&sensor=false&region=$countryaddres";
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
		 		$table_name = $wpdb->prefix . "clinetgooglemap";
		 		$insert_user ="INSERT into $table_name set ";
		 		$insert_user.="mapid='".$_GET['mpidd']."',
		 			streetaddress='".$_POST['streetaddress']."',
		 			content='".$_POST['txtmessage']."',
		 			city='".$_POST['city']."',
		 			latitude='".$lat."',            
		 			longitude='".$long."'
		 		";
		 		// echo $insert_user;
				$wpdb->query($insert_user);
				// $execute_query_user_insert=mysqli_query($insert_user);
			}
			echo '<p style="color:green">successfully ADDED !!!</p>';
		}
		?>
		<form name="addmainmap" action="" method="post">
		  	<input type="hidden" name="addclinet" value="addclinet">
		    <table>
		      	<tr valign="top">
		        	<th scope="row"><label for="posts_per_rss">Street Address : </label></th>
		        	<td><input type="text" class="regular-text ltr" value="<?=$qry_results->streetaddress?>" id="streetaddress" min="1" step="1" name="streetaddress"></td>
		      	</tr>
		      	<tr valign="top">
		        	<th scope="row"><label for="posts_per_rss">City Name : </label></th>
		        	<td><input type="text" class="regular-text ltr" value="<?=$qry_results->city?>" id="streetaddress" min="1" step="1" name="city"></td>
		      	</tr>
		      	<tr valign="top">
		        	<th scope="row"><label for="posts_per_rss">Content InfoBox : </label></th>
			        <td>
				        <?php
						if(isset($_GET['edit'])){
							$content = $qry_results->content;
						}
						wp_editor( $content, 'content-id', array( 'textarea_name' => 'txtmessage', 'media_buttons' => true, 'tinymce_adv' => array( 'width' => '300', 'theme_advanced_buttons1' => 'formatselect,forecolor,|,bold,italic,underline,|,bullist,numlist,blockquote,|,justifyleft,justifycenter,justifyright,justifyfull,|,link,unlink,|,spellchecker,wp_fullscreen,wp_adv' ) ) );
						?>
			        </td>
		      	</tr>
		      	<tr valign="top">
		        	<td colspan="2"><p class="submit"><input type="submit" value="Save Changes" class="button button-primary" id="submit" name="submit"></p></td>
		      	</tr>
		    </table>
		</form>
	</div>
</div>
<div class="postbox " id="postexcerpt">
  	<div title="Click to toggle" class="handlediv"><br></div>
  	<h3 class="hndle" style="padding: 10px;">Import xlxs</h3>
    <div class="inside">
      	<?php include('exampleReader01.php'); ?>
    </div>
</div>
<?php 
}else if(isset($_GET['cntsid'])) {
	//--------------------------------------- View Clinets ----------------------------
	if(isset($_GET['delete'])){
		$table_name = $wpdb->prefix . "clinetgooglemap";
		$query = "DELETE FROM $table_name WHERE id=$_GET[delete]";
    	if(!$wpdb->query($query))
        	echo "DELETE failed: $query<br />" . 
        	mysqli_error() . "<br /><br />";
		else 
			echo 'Delete SuccessFully!';
	}
?>
<div class="postbox" id="postexcerpt">
  	<div title="Click to toggle" class="handlediv"><br></div>
  	<h3 class="hndle" style="padding: 10px;">View Clients</h3>
  	<div class="inside">
    	<table width="100%" border="1" align="center">
      		<tr valign="top">
        		<th scope="row">Map ID</th>
        		<th scope="row">County</th>
        		<th scope="row">State</th>
        		<th scope="row">Street Address</th>
        		<th scope="row">InfoBox Content</th>
        		<th scope="row">Action</th>
      		</tr>
			<?php
	  		$table_name = $wpdb->prefix . "maingooglemap";
	  		$sql = "SELECT * from " .$table_name. " where id=$_GET[cntsid]";
	  		$qry_results = $wpdb->get_results($sql);
	  		foreach($qry_results as $qry_result){}
	  		$table_name = $wpdb->prefix . "clinetgooglemap";
	  		$sql = "SELECT * from " .$table_name. " where mapid=$_GET[cntsid]";
	  		$qry_results = $wpdb->get_results($sql);
	  		foreach($qry_results as $qry_resultsx){?> 
	  			<tr valign="top">
		        	<td scope="row"><?=$qry_result->id?></td>
		        	<td scope="row"><?=$qry_result->countyname?></td>
		        	<td scope="row"><?=$qry_result->state?></td>
		        	<td scope="row"><?=$qry_resultsx->streetaddress?></td>
		        	<td scope="row"><?=$qry_resultsx->content?></td>
		        	<td scope="row">
		        		<a class="button button-primary" href="/wp-admin/admin.php?page=addclints&edit=<?=$qry_resultsx->id?>&mpidd=<?=$qry_result->id?>">Edit</a>
		        		<a  href="/wp-admin/admin.php?page=addclints&delete=<?=$qry_resultsx->id?>&cntsid=<?=$qry_result->id?>"class="button button-primary">Delete</a>
		        	</td>
		      	</tr>
 			<?php } ?>
    	</table>
  	</div>
</div>
<?php }else{?>
<!-- //------------------------------------ Comming From Direct ------------------------- -->
	<div class="postbox " id="postexcerpt">
  		<div title="Click to toggle" class="handlediv"><br></div>
  		<h3 class="hndle" style="padding: 10px;">Setting</h3>
  		<div class="inside">
    		<table width="100%" border="1" align="center">
      			<tr valign="top">
        			<th scope="row">Map ID</th>
        			<th scope="row">County</th>
			        <th scope="row">State</th>
			        <th scope="row">Map ZooM</th>
			        <th scope="row">Map width</th>
			        <th scope="row">Map height</th>
			        <th scope="row">Action</th>
      			</tr>
				<?php
	  			$table_name = $wpdb->prefix . "maingooglemap";
	  			$sql = "SELECT * from " .$table_name. "";
	  			$qry_results = $wpdb->get_results($sql);
	  			foreach($qry_results as $qry_result){?> 
	  				<tr valign="top">
			        	<td scope="row"><?=$qry_result->id?></td>
			        	<td scope="row"><?=$qry_result->countyname?></td>
			        	<td scope="row"><?=$qry_result->state?></td>
			        	<td scope="row"><?=$qry_result->mapzoom?></td>
			        	<td scope="row"><?=$qry_result->mapwidth?></td>
			        	<td scope="row"><?=$qry_result->mapheight?></td>
			        	<td scope="row">
			        		<a class="button button-primary" href="/wp-admin/admin.php?page=googlemap&mpide=<?=$qry_result->id?>">Edit</a>
			        		<a  href="/wp-admin/admin.php?page=addingelement&mpidd=<?=$qry_result->id?>"class="button button-primary">Delete</a>
			        		<a  href="/wp-admin/admin.php?page=addclints&mpidd=<?=$qry_result->id?>"class="button button-primary">ADD Clients</a>
			        		<a  href="/wp-admin/admin.php?page=addclints&cntsid=<?=$qry_result->id?>"class="button button-primary">View Clients</a>
			        	</td>
			      </tr>
 				<?php } ?>
    		</table>
  		</div>
	</div>
<?php }  ?>