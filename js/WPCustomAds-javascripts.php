<?php 
	//Let's set the header straight
	header("Content-type: text/javascript"); 
	
	//Get the plugin file
	$home_dir = preg_replace('^wp-content/plugins/[a-z0-9\-/]+^', '', getcwd());
	include($home_dir . 'wp-load.php');
?>
jQuery(document).ready(
	function() {
		$n=jQuery.noConflict();	
		var ADS_JSON = <?php echo json_encode(retrieveCurrentAdsFromDB()); ?>;
		if(ADS_JSON == '') return false;
		$n(ADS_JSON).each(
			function() {
				var temp_element = $n($n(this).attr('identifier'));
				if( temp_element.is(":hidden") ) temp_element.show();
				temp_element.css('border', '0');
				temp_element.html(
					'<a title="' + $n(this).attr('title') +'" href="' + $n(this).attr('link') + '">' + 
						'<img src="' + $n(this).attr('image') + '" alt="' + $n(this).attr('title') + '" />' +
					'</a>'
				);
			}
		);
	}
);
