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
	<div class="icon32" id="icon-page"><br ></div><h2><?php _e('Pre-defined elements', WPCUSTOMADS_NAME); ?></h2>
	<p>
		<?php 
			_e(
				'Please use classes to assign an ad to multiple containers, and an element id to assign it to specific ones. <strong>.</strong> indicates that it is a class, and # that it is an unique identifier.',
				WPCUSTOMADS_NAME
			); 
		?>
	</p>
	<p>
	<a class="button" href="?page=WPCustomAds-manage-elements"><?php _e('Click here to register a new element', WPCUSTOMADS_NAME)?></a>
	</p>
	<table class="widefat elements" cellspacing="0" id="<?php echo $context ?>-elements-table">
	<thead>
		<tr>
			<th scope="col" class="manage-column check-column">&nbsp;</th>
			<th scope="col" class="manage-column"><?php _e('Element', WPCUSTOMADS_NAME); ?></th>
			<th scope="col" class="manage-column"><?php _e('Page', WPCUSTOMADS_NAME); ?></th>
		</tr>
	</thead>
	<tfoot>
		<tr>
			<th scope="col" class="manage-column check-column">&nbsp;</th>
			<th scope="col" class="manage-column"><?php _e('Element', WPCUSTOMADS_NAME); ?></th>
			<th scope="col" class="manage-column"><?php _e('Page', WPCUSTOMADS_NAME); ?></th>
		</tr>
	</tfoot>
	<tbody class="elements">
		<?php foreach(retrieveElementsFromDB() as $element): ?>
			<tr>
				<td>&nbsp;</td>
				<td class="post-title column-title">
					<strong><a rel="external" title="<?php echo ' ”' , $element->identifier, '”'; ?>" href="../<?php echo $element->page; if( $element->identifier[0] == '#' ) echo $element->identifier; ?>" class="row-title"><?php echo $element->identifier; ?></a></strong>
					<div class="row-actions">
						<span class="edit">
							<a title="Edit this item" href="?page=WPCustomAds-manage-elements&screen=editelement&id=<?php echo $element->element_id; ?>">
								<?php _e('Edit', WPCUSTOMADS_NAME); ?>
							</a> | 
						</span>
						<span class="delete">
							<a href="?page=WPCustomAds-list-elements&id=<?php echo $element->element_id; ?>&amp;action=deleteelement" title="<?php _e('Delete this item', WPCUSTOMADS_NAME); ?>" class="submitdelete">
								<?php _e('Delete', WPCUSTOMADS_NAME); ?>
							</a>
						</span>
					</div>	
				</td>
				<td><a rel="external" href="../<?php echo $element->page; ?>"><?php echo $element->page; ?></a></td>
			</tr>
		<?php endforeach; ?>
	</table>
</div>