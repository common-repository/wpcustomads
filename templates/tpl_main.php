<?php global $plugin_results; ?>
<div class="wrap">
	<div class="icon32" id="icon-users"><br></div>
	<h2><?php echo WPCUSTOMADS_NAME; ?></h2>
	<p>
		<?php 
			_e('Welcome to WPCustomAds. This plugin enables your to fill pre-defined elements in your templates with advertisement, offers or just link-partners. Whatever you might want to call it, this is the plugin to do it.', WPCUSTOMADS_NAME); 
		?>
	</p>
	<div class="icon32" id="icon-options-general"><br></div><h2><?php _e('Configuration', WPCUSTOMADS_NAME); ?></h2>
	<p>
		<?php _e('This plugin requires some configuration to work properly, so take a few minutes to set it up according to the list below:', WPCUSTOMADS_NAME); ?>
		<ul>
			<li>
				<?php _e('1. <a href="?page=WPCustomAds-manage-elements">Register elements that you want to display advertisement in</a>', WPCUSTOMADS_NAME); ?>
			</li>
			<li>
				<?php _e('2. <a href="?page=WPCustomAds-manage-ads">Create adverts for the registered elements</a>', WPCUSTOMADS_NAME); ?>
			</li>
			<li>
				<?php _e('3. <a href="?page=WPCustomAds-list-ads">View the adverts you just created</a>', WPCUSTOMADS_NAME); ?>
			</li>
		</ul>
	</p>
</div>