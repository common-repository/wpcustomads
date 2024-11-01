jQuery(document).ready(
	function() {
		jQuery('#input-image-button').click(
			function() {
				formfield = jQuery('#input-image').attr('name');
				tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
				return false;
			}
		);
		window.send_to_editor = function(html) {
			imgurl = jQuery('img',html).attr('src');
			jQuery('#input-image').val(imgurl);
			tb_remove();
		}
	}
);