<?php global $plugin_results; ?>
<div class="wrap">
	<?php if( $plugin_results != '' ): ?>
	<div class="icon32" id="icon-index"><br></div><h2><?php _e('Status', WPCUSTOMADS_NAME); ?></h2>
	<p>
		<?php
			if( is_array($plugin_results) ) {
				
				echo '<ul>';
				foreach($plugin_results as $res) {
					
					echo '<li>', $res , '</li>';
					
				}
				echo '</ul>';
				
			} else {
				
				echo $plugin_results;
				
			}
		?>
	</p>
	<?php endif; ?>
	<div class="icon32" id="icon-users"><br></div>
	<h2><?php echo WPCUSTOMADS_NAME; ?></h2>
	<p>
		<?php _e('Manage the ads using the form and table below.', WPCUSTOMADS_NAME); ?>
	</p>
	<p>
	<a class="button" href="?page=WPCustomAds-manage-ads"><?php _e('Click here to create a new advert', WPCUSTOMADS_NAME)?></a>
	</p>
	<table class="widefat ads" cellspacing="0" id="<?php echo $context ?>-ads-table">
		<thead>
			<tr>
				<th scope="col" class="manage-column check-column">&nbsp;</th>
				<th scope="col" class="manage-column"><?php _e('Title', WPCUSTOMADS_NAME); ?></th>
				<th scope="col" class="manage-column"><?php _e('Link', WPCUSTOMADS_NAME); ?></th>
				<th scope="col" class="manage-column"><?php _e('Image', WPCUSTOMADS_NAME); ?></th>
				<th scope="col" class="manage-column"><?php _e('Element', WPCUSTOMADS_NAME); ?></th>
				<th scope="col" class="manage-column" style="width: 15em"><?php _e('Period', WPCUSTOMADS_NAME); ?></th>
				<th scope="col" class="manage-column" style="width: 5em"><?php _e('Active', WPCUSTOMADS_NAME); ?></th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th scope="col" class="manage-column check-column">&nbsp;</th>
				<th scope="col" class="manage-column"><?php _e('Title', WPCUSTOMADS_NAME); ?></th>
				<th scope="col" class="manage-column"><?php _e('Link', WPCUSTOMADS_NAME); ?></th>
				<th scope="col" class="manage-column"><?php _e('Image', WPCUSTOMADS_NAME); ?></th>
				<th scope="col" class="manage-column"><?php _e('Element', WPCUSTOMADS_NAME); ?></th>
				<th scope="col" class="manage-column" style="width: 15em"><?php _e('Period', WPCUSTOMADS_NAME); ?></th>
				<th scope="col" class="manage-column" style="width: 5em"><?php _e('Active', WPCUSTOMADS_NAME); ?></th>
			</tr>
		</tfoot>
		<tbody class="ads">
			<?php foreach(retrieveAdsFromDB() as $ad): ?>
				<tr>
					<td>&nbsp;</td>
					<td class="post-title column-title">
						<strong><a title="<?php echo __('Edit', WPCUSTOMADS_NAME) , ' ”' , $ad->title, '”'; ?>" href="?page=WPCustomAds-manage-ads&screen=editad&id=<?php echo $ad->ad_id; ?>" class="row-title"><?php echo $ad->title; ?></a></strong>
						<div class="row-actions">
							<span class="edit">
								<a title="<?php _e('Edit this item', WPCUSTOMADS_NAME); ?>" href="?page=WPCustomAds-manage-ads&screen=editad&id=<?php echo $ad->ad_id; ?>"><?php _e('Edit', WPCUSTOMADS_NAME); ?></a> | 
							</span>
							<span class="delete">
								<a href="?page=WPCustomAds-list-ads&id=<?php echo $ad->ad_id; ?>&amp;action=deletead" title="<?php _e('Delete this item', WPCUSTOMADS_NAME); ?>" class="submitdelete"><?php _e('Delete', WPCUSTOMADS_NAME); ?></a>
							</span>
						</div>	
					</td>
					<td><a href="<?php echo ( strpos($ad->link, '://') === false )? 'http://' . $ad->link : $ad->link; ?>"><?php echo $ad->link; ?></a></td>
					<td>
						<a class="WPCustomAds-link" href="<?php echo ( strpos($ad->image, '://') === false )? 'http://' . $ad->image : $ad->image; ?>">
							<img style="max-height: 100px;" src="<?php echo ( strpos($ad->image, '://') === false )? 'http://' . $ad->image : $ad->image; ?>" />
						</a>
					</td>
					<td>
						<?php if( $ad->identifier[0] == '#' ): ?>
						
							<a href="../<?php echo $ad->page, $ad->identifier; ?>">
								<?php echo $ad->identifier . '@' . $ad->page; ?>
							</a>
						<?php 
							else:
								echo $ad->identifier . '@' . $ad->page;
							endif; 
						?>
					</td>
					<td><?php echo $ad->date_start , ' ' , __('to', WPCUSTOMADS_NAME) , ' ' , $ad->date_stop; ?></td>
					<td><?php echo ($ad->active)? __('Yes', WPCUSTOMADS_NAME) : __('No', WPCUSTOMADS_NAME); ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>