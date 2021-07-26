<?php
//This file has the code of page "view main maps" menu item page
//This page show all the ,aps that has benn added in the system.
?>
<style>
td{
	vertical-align:middle;
	text-align:center;
}
</style>
<div class="postbox " id="postexcerpt">
  	<div title="Click to toggle" class="handlediv"><br></div>
  	<h3 class="hndle" style="padding: 10px;">Setting</h3>
  	<div class="inside">
	<?php
		global $wpdb;  
	 	global $jal_db_version;  
		if(isset($_GET['mpidd'])){
			$table_name = $wpdb->prefix . "maingooglemap";
			$query = "DELETE FROM $table_name WHERE id=$_GET[mpidd]";
		    if(!$wpdb->query($query)){
		        echo "DELETE failed: $query<br />" . 
		        mysqli_error() . "<br /><br />";
		    }
		}
		?>
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

					    <a  href="/wp-admin/admin.php?page=previewpagemap&cntsid=<?=$qry_result->id?>"class="button button-primary">Preview Map</a>
			      	</td>
		  		</tr>
			<?php } ?>
    	</table>
  	</div>
</div>





