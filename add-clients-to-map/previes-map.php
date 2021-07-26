<?php
//This file has the code for the preview map page
// This code shows the corresponding map nto the dashboard.
echo '<script  src="https://maps.googleapis.com/maps/api/js?key='.get_option('google_map_api_key').'&v=3.exp"></script>';
	global $wpdb;  
	global $jal_db_version;
 	$table_name = $wpdb->prefix . "maingooglemap";
    $sql = "SELECT * from " .$table_name. " where id=$_GET[cntsid]";
	$qry_results = $wpdb->get_results($sql);
	foreach($qry_results as $qry_result){
	 
	}
?>

<script type="text/javascript">
  	var infowindow;
	(function () {
  		google.maps.Map.prototype.markers = new Array();
  		google.maps.Map.prototype.addMarker = function(marker) {
    		this.markers[this.markers.length] = marker;
	  	};
	  	google.maps.Map.prototype.getMarkers = function() {
	    	return this.markers
	  	};
	  	google.maps.Map.prototype.clearMarkers = function() {
	    	if(infowindow) {
	      		infowindow.close();
	    	}
	    	for(var i=0; i<this.markers.length; i++){
	      		this.markers[i].set_map(null);
	    	}
	  	};
	})();
  
  	function initialize() {
    	var latlng = new google.maps.LatLng(<?php echo $qry_result->latitude;?>, <?php echo $qry_result->longitude;?>);
    	var myOptions = {
      		zoom: <?php echo $qry_result->mapzoom;?>,
      		center: latlng,
      		mapTypeId: google.maps.MapTypeId.ROADMAP
      		//mapTypeId: google.maps.MapTypeId.SATELLITE
    	};
    	map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);
    	var a = new Array();
		<?php 
			$table_name = $wpdb->prefix . "clinetgooglemap";
			$sql = "SELECT * from " .$table_name. " where mapid=$_GET[cntsid]";
			$qry_resultss = $wpdb->get_results($sql);
			foreach($qry_resultss as $i=>$qry_results){ 
			 	$content=str_replace("\\", "", $qry_results->content);
			 	$content=str_replace("\"", "\\'", $content);
			 	$content=str_replace("&quot;", "", $content);
			 	$content=str_replace("&amp;", "", $content);
				// $content=str_replace("qu&lt;/p", "", $content);
			 	$content=preg_replace('/\s+/', ' ', trim($content));
	 		//Creating object for each client address
		   		echo '
		   			var t =  new Object();
    				t.name = "'.$content.'"
    				t.lat =  '.$qry_results->latitude.'
    				t.lng =  '.$qry_results->longitude.'
    				a['.$i.'] = t;';  
	  		}
		?>
    	for (var i = 0; i < a.length; i++) {
        	var latlng = new google.maps.LatLng(parseFloat(a[i].lat),parseFloat( a[i].lng));
        	map.addMarker(createMarker(a[i].name,latlng));
     	}
    	console.log(map.getMarkers());
    	console.log(map.getMarkers());    
  	}
  //This function genrate the map marker
  	function createMarker(name, latlng) {
    	var image = 'http://maps.google.com/mapfiles/ms/icons/blue-dot.png';
		var marker = new google.maps.Marker({
			position: latlng,
			icon: image,
		 	map: map
		});
    	google.maps.event.addListener(marker, "click", function() {
      		if (infowindow) infowindow.close();
      		infowindow = new google.maps.InfoWindow({content: '<div style="line-height:1.35;overflow:hidden;white-space:nowrap;">'+name+'</div>'});
      		infowindow.open(map, marker);
    	});
    	return marker;
  	}
	jQuery( document ).ready(function() {
  		initialize();
	});
</script>

<div id="map-canvas" style=" width:98%; height: 400px; position:relative;top:30px;left:0;right:0;bottom:0; overflow:hidden;"></div>
