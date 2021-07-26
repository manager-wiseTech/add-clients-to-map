<?php



  /*



  Plugin Name: Google Map Adding Clinets



  Description: Manage user and plugin



  Author:  HowtoDominateLocalMarkets.com



  Version: 2.0



  */

	require 'plugin-update-checker-master/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/manager-wiseTech/add-clients-to-map/',
	__FILE__,
	'add-clients-to-map'
);

//Set the branch that contains the stable release.
$myUpdateChecker->setBranch('main');

//Optional: If you're using a private repository, specify the access token like this:
$myUpdateChecker->setAuthentication('your-token-here');




  // Hook for adding admin menus



  //add_action('admin_menu', 'mt_add_pages');



  add_action('admin_menu', 'googlemapx');



  // action function for above hook



  function googlemapx() {

	    $icon_url=dirname(__FILE__).'/upload/';

        add_menu_page(__('Add Maps','googlemapx'), __('Clients Maps','googlemapx'), 'manage_options', 'googlemapx', 'googlemapsetting',$icon_url );

        add_submenu_page('googlemapx', __('addingelement','googlemapx'), __('View Main Maps','googlemapx'), 'manage_options', 'addingelement', 'adingelement');

		add_submenu_page('googlemapx', __('addclints','googlemapx'), __('Add Clients To Map','googlemapx'), 'manage_options', 'addclints', 'addclints');
    add_submenu_page('googlemapx', __('settings','googlemapx'), __('Settings','googlemapx'), 'manage_options', 'settings', 'googlemapx_settings');
		add_submenu_page('googlemapx', __('previewmap','googlemapx'), __('','googlemapx'), 'manage_options', 'previewpagemap', 'previewpagemap');
		add_submenu_page('googlemapx', __('shortcodemap','googlemapx'), __('','googlemapx'), 'manage_options', 'shortcodemap', 'shortcodemap');
		add_submenu_page('googlemapx', __('importdb','googlemapx'), __('','googlemapx'), 'manage_options', 'importdb', 'importdb');
 }
  function shortcodemap(){
    include('shortcodemap.php');
}
function importdb(){
    include('exampleReader01.php');
}
 function adingelement(){
    include('adingelement.php');
}
  function previewpagemap(){
   include('previes-map.php');
}
function googlemapsetting() {
  echo '<h1 style="margin-top:35px; margin-top:25px">Google Map Setting !! </h1>';
  include('settingpage.php');
}
function addclints() {
   echo '<h1 style="margin-top:35px; margin-top:25px">Add clients into map !! </h1>';
   include('addclinetstomap.php');
}
function googlemapx_settings(){
  echo'<h1 style="margin-top:35px; margin-top:25px">Settings </h1>';
  include('googlemapx_settings.php');
}







//-----------------------------------------------------------------------------------



  function activation_settings_map() {  



	 global $wpdb;  



	 global $jal_db_version;  



	 $table_name = $wpdb->prefix . "maingooglemap";  



	 $sql = "CREATE TABLE IF NOT EXISTS $table_name (  



	`id` int(11) NOT NULL AUTO_INCREMENT,



	`countyname` varchar(255) NOT NULL,



	`state` varchar(255) NOT NULL,



	`Country` varchar(255) NOT NULL,



	`mapzoom` varchar(255) NOT NULL,



	`mapwidth` varchar(255) NOT NULL,



	`mapheight` varchar(255) NOT NULL,



	`latitude` varchar(255) NOT NULL,



	`longitude` varchar(255) NOT NULL,



		PRIMARY KEY (`id`)



  ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";



  



	  require_once(ABSPATH . 'wp-admin/includes/upgrade.php');



	 dbDelta($sql);



$table_name = $wpdb->prefix . "clinetgooglemap";  



	 $sql = "CREATE TABLE IF NOT EXISTS $table_name (  



	`id` int(11) NOT NULL AUTO_INCREMENT,



	`mapid` varchar(255) NOT NULL,



	`streetaddress` varchar(255) NOT NULL,



	`city` varchar(255) NOT NULL,



	`content` varchar(255) NOT NULL,



	`latitude` varchar(255) NOT NULL,



	`longitude` varchar(255) NOT NULL,



		PRIMARY KEY (`id`)



  ) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1;";

  dbDelta($sql);


}

register_activation_hook(__FILE__,'activation_settings_map');


// This function generating the map which is dispalys using the shortcode [countymap id=Your_map_id]
function bartag_func( $atts, $content = null ) {

ob_start();

	extract( shortcode_atts( array(

		'id' => 'caption',

	), $atts ) );

//include('shortcodemap.php');

?>

<?php

	 global $wpdb;  
	 global $jal_db_version;  
      $mapid=esc_attr($id);
    $table_name = $wpdb->prefix . "maingooglemap";
	  $sql = "SELECT * from " .$table_name. " where id=".$mapid;
	  $qry_results = $wpdb->get_results($sql);
	  foreach($qry_results as $qry_result)
	  { 

	  }
?>
<!--map generating script-->
<meta name="viewport" content="initial-scale=1.0, user-scalable=no">
<?php
echo '<script src="https://maps.googleapis.com/maps/api/js?key='.get_option('google_map_api_key').'&v=3.exp&markers=color:blue"></script>';
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
    var latlng = new google.maps.LatLng(<?php echo $qry_result->latitude; ?>,<?php echo $qry_result->longitude; ?>);
    var myOptions = {
      zoom:5 <?php //echo $qry_result->mapzoom?>,
      center: latlng,
      mapTypeId: google.maps.MapTypeId.ROADMAP
      //mapTypeId: google.maps.MapTypeId.SATELLITE
    };
    map = new google.maps.Map(document.getElementById("map-canvas"), myOptions);
    var a = new Array();
<?php $table_name = $wpdb->prefix . "clinetgooglemap";
	  $sql = "SELECT * from " .$table_name. " where mapid=$mapid";
	  $qry_resultss = $wpdb->get_results($sql);
	  $i=0;
	  foreach($qry_resultss as $qry_results)
	  { 
	 $content=str_replace("\\", "", $qry_results->content);
	 $content=str_replace("\"", "\\'", $content);
	 $content=str_replace("&quot;", "", $content);
	 $content=str_replace("&amp;", "", $content);
	// $content=str_replace("qu&lt;/p", "", $content);
	 $content=preg_replace('/\s+/', ' ', trim($content));
	 //<img src=\'http://ckgcontractors.com/wp-content/uploads/2013/12/IMG_2366-150x150.jpg\' />
	 
	 //creating address object  
  echo '
		   var t =  new Object();
    t.name = "'.$content.'"
    t.lat =  '.$qry_results->latitude.'
    t.lng =  '.$qry_results->longitude.'
    a['.$i.'] = t;
		   ';  
   $i++; 
	  }
?>
    for (var i = 0; i < a.length; i++) {
        var latlng = new google.maps.LatLng(a[i].lat, a[i].lng);
        map.addMarker(createMarker(a[i].name,latlng));
     }
    //console.log(map.getMarkers());    
    //console.log(map.getMarkers());    
  }
//   This functions create marker on the map
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
</script>
<script>
jQuery( document ).ready(function() {
  initialize();
});
</script>

 <div id="map-canvas" style="width:100%; height: 500px; position:relative;top:0;left:0;right:0;bottom:0; overflow:hidden;"></div>	 

<?php //return '<span class="' . esc_attr($id) . '">' . $content . '</span>';
return ob_get_clean();
}
//adding short code
add_shortcode( 'countymap', 'bartag_func' );
