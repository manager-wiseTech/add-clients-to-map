<?php
/**
* This is the template of the shield settings page, where some settings related to the plugins are specified.
*/
?>
<div class="wrap">
<form method="post" action="options.php">
<?php wp_nonce_field('update-options'); ?>
<table class="form-table">
<tr valign="top">
<th scope="row">Place Google Map API key</th>
<td>
	<input type="text" name="google_map_api_key" value="<?php echo  get_option('google_map_api_key') ?>">
	</td>
</tr>
</table>
<input type="hidden" name="action" value="update" />
<input type="hidden" name="page_options" value="google_map_api_key" />
<p class="submit">
<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
</p>
</form>
</div>
<div class="wrap">
	<p>You can get API key from <a href="https://cloud.google.com/console/google/maps-apis/overview">Google Cloud Platform Console</a></p>
</div>