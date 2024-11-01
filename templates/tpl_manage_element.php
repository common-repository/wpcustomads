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
	<?php 
		$screen = ( isset($_GET['screen']) ) ? $_GET['screen'] : '';
		$editing = ( $screen == 'editad' ) ? true : false;
		$id = ( isset($_GET['id']) )? intval($_GET['id']) : 0; 
		
		//Are we editing?
		if( $editing ) {
			
			$data = retrieveElementFromDB($id);
			$data = $data[0];
		} else {
			
			$data = '';
		
		}
	?>
	<?php if( !$editing ): ?>
	<div class="icon32" id="icon-edit"><br></div>
	<h2><?php _e('Add new elements to the listing', WPCUSTOMADS_NAME); ?></h2>
	<?php else: ?>
	<div class="icon32" id="icon-edit"><br></div>
	<h2><?php _e('Edit element', WPCUSTOMADS_NAME); ?></h2>
	<?php endif; ?>
	<p>
		<form class="add:elements: validate" id="adduser" name="newelement" method="post" action="?<?php echo $_SERVER['QUERY_STRING']; ?>">
			<table class="form-table">
				<tbody>
					<tr class="form-field form-required">
						<th scope="row">
							<label for="input-identifier">
								<?php _e('Identifier', WPCUSTOMADS_NAME); ?> <span class="description">(<?php _e('required', WPCUSTOMADS_NAME); ?>)</span>
							</label>
							<?php if( !$editing ) : ?>
								<input type="hidden" value="newelement" id="action" name="action">
							<?php else: ?>
								<input type="hidden" value="editelement" id="action" name="action">
							<?php endif; ?>
						</th>
						<td><input type="text" value="<?php echo ($editing)? $data->identifier : ''; ?>" placeholder="<?php _e('HTML-selector', WPCUSTOMADS_NAME); ?>" name="input-element" id="input-element" /></td>
					</tr>
					<tr class="form-field form-required">
						<th scope="row"><label for="input-page"><?php _e('Page', WPCUSTOMADS_NAME); ?> <span class="description">(<?php echo __('required', WPCUSTOMADS_NAME) , ' &raquo; ' , __('slug1;slug2;', WPCUSTOMADS_NAME); ?>)</span></label></th>
						<td><input type="text" value="<?php echo ($editing)? $data->page : ''; ?>" placeholder="<?php _e('On which pages do you want it to appear?', WPCUSTOMADS_NAME); ?>" name="input-page" id="input-page" /></td>
					</tr>
					<tr>
						<th scope="row">
							<input type="submit" name="input-submit" value="<?php echo ($editing)? __('Update the element', WPCUSTOMADS_NAME) : __('Add the element to the list', WPCUSTOMADS_NAME); ?>" />
						</th>
					</tr>
				</tbody>
			</table>
		</form>
	</p>
</div>