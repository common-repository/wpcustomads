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
			
			$data = retrieveAdFromDB($id);
			$data = $data[0];
		
			$data->date_start = explode('-', $data->date_start);
			$data->date_stop = explode('-', $data->date_stop);

		} else {
			
			$data = '';
		
		}
	?>
	<?php if( !$editing ): ?>
		<div class="icon32" id="icon-edit"><br></div><h2><?php _e('New advert', WPCUSTOMADS_NAME); ?></h2>
		<p>
			<?php _e('Use the form below to insert a new advert into the system.', WPCUSTOMADS_NAME); ?>
		</p>
	<?php else: ?>
		<div class="icon32" id="icon-edit"><br></div><h2><?php _e('Edit advert', WPCUSTOMADS_NAME); ?></h2>
		<p>
			<?php _e('Use the form below to update an existing advert.', WPCUSTOMADS_NAME); ?>
		</p>
	<?php endif; ?>
	<form class="add:ads: validate" id="adduser" name="addad" method="post" action="?<?php echo $_SERVER['QUERY_STRING']; ?>">
		<table class="form-table">
			<tbody>
				<tr class="form-field form-required">
					<th scope="row">
						<label for="input-title">
							<?php _e('Title', WPCUSTOMADS_NAME); ?> <span class="description">(<?php _e('required', WPCUSTOMADS_NAME); ?>)</span>
						</label>
						<?php if( !$editing ) : ?>
							<input type="hidden" value="newad" id="action" name="action">
						<?php else: ?>
							<input type="hidden" value="editad" id="action" name="action">
						<?php endif; ?>
					</th>
					<td>
						<input type="text" aria-required="true" value="<?php echo ($editing)? $data->title : ''; ?>" id="input-title" name="input-title" placeholder="<?php _e('Enter a title for the ad here', WPCUSTOMADS_NAME); ?>">
					</td>
				</tr>
				<tr class="form-field form-required">
					<th scope="row"><label for="input-link"><?php _e('Website', WPCUSTOMADS_NAME); ?> <span class="description">(<?php _e('required', WPCUSTOMADS_NAME); ?>)</span></label></th>
					<td><input type="text" value="<?php echo ($editing)? $data->link : ''; ?>" placeholder="<?php _e('Enter a link for the ad', WPCUSTOMADS_NAME); ?>" id="input-link" name="input-link"></td>
				</tr>
				<tr class="form-field">
					<th scope="row"><label for="input-image"><?php _e('Image', WPCUSTOMADS_NAME); ?> <span class="description">(<?php _e('required', WPCUSTOMADS_NAME); ?>)</span></label></th>
					<td>
						<input id="input-image" type="text" size="36" name="input-image" value="<?php echo ($editing)? $data->image : ''; ?>" placeholder="<?php _e('Enter a link to an image here', WPCUSTOMADS_NAME); ?>" /> or
						<button id="input-image-button" class="button"><?php _e('Upload your own image', WPCUSTOMADS_NAME); ?></button>
					</td>
				</tr>
				<tr class="form-field">
					<th scope="row"><label for="input-date-start"><?php _e('Start date', WPCUSTOMADS_NAME); ?></label></th>
					<td>
						<div class="hide-if-js" id="startdatediv" style="display: block;">
							<div class="timestamp-wrap">
								<?php /*****/ if( $editing ) : /*****/ ?>
								
									<select tabindex="4" name="input-date-start[]" id="dd">
										<?php for($day = 1; $day <= 31; $day++): ?>
											<option<?php if( $day == $data->date_start[2] ) echo ' selected'; ?> value="<?php echo str_pad($day, 2, "0", STR_PAD_LEFT); ?>">
												<?php echo str_pad($day, 2, "0", STR_PAD_LEFT); ?>
											</option>
										<?php endfor; ?>
									</select>
									<select tabindex="4" name="input-date-start[]" id="mm">
										<option<?php if($data->date_start[1] == '01') echo ' selected="selected"'; ?> value="01">Jan</option>
										<option<?php if($data->date_start[1] == '02') echo ' selected="selected"'; ?>  value="02">Feb</option>
										<option<?php if($data->date_start[1] == '03') echo ' selected="selected"'; ?>  value="03">Mar</option>
										<option<?php if($data->date_start[1] == '04') echo ' selected="selected"'; ?>  value="04">Apr</option>
										<option<?php if($data->date_start[1] == '05') echo ' selected="selected"'; ?>  value="05">May</option>
										<option<?php if($data->date_start[1] == '06') echo ' selected="selected"'; ?>  value="06">Jun</option>
										<option<?php if($data->date_start[1] == '07') echo ' selected="selected"'; ?>  value="07">Jul</option>
										<option<?php if($data->date_start[1] == '08') echo ' selected="selected"'; ?>  value="08">Aug</option>
										<option<?php if($data->date_start[1] == '09') echo ' selected="selected"'; ?>  value="09">Sep</option>
										<option<?php if($data->date_start[1] == '10') echo ' selected="selected"'; ?>  value="10">Oct</option>
										<option<?php if($data->date_start[1] == '11') echo ' selected="selected"'; ?>  value="11">Nov</option>
										<option<?php if($data->date_start[1] == '12') echo ' selected="selected"'; ?>  value="12">Dec</option>
									</select>
									<select tabindex="4" name="input-date-start[]" id="dd">
										<?php for($year = date('Y')+9; $year >= (date('Y') - 1); $year--): ?>
											<option<?php if( $year == $data->date_start[0] ) echo ' selected'; ?> value="<?php echo $year; ?>"><?php echo $year; ?></option>
										<?php endfor; ?>
									</select>
									
								<?php /*****/ else: /*****/ ?>
									
									<select tabindex="4" name="input-date-start[]" id="dd">
										<?php for($day = 1; $day <= 31; $day++): ?>
											<option<?php if( $day == date('d') ) echo ' selected'; ?> value="<?php echo str_pad($day, 2, "0", STR_PAD_LEFT); ?>">
												<?php echo str_pad($day, 2, "0", STR_PAD_LEFT); ?>
											</option>
										<?php endfor; ?>
									</select>
									<select tabindex="4" name="input-date-start[]" id="mm">
										<option<?php if(date('m') == '01') echo ' selected="selected"'; ?> value="01">Jan</option>
										<option<?php if(date('m') == '02') echo ' selected="selected"'; ?>  value="02">Feb</option>
										<option<?php if(date('m') == '03') echo ' selected="selected"'; ?>  value="03">Mar</option>
										<option<?php if(date('m') == '04') echo ' selected="selected"'; ?>  value="04">Apr</option>
										<option<?php if(date('m') == '05') echo ' selected="selected"'; ?>  value="05">May</option>
										<option<?php if(date('m') == '06') echo ' selected="selected"'; ?>  value="06">Jun</option>
										<option<?php if(date('m') == '07') echo ' selected="selected"'; ?>  value="07">Jul</option>
										<option<?php if(date('m') == '08') echo ' selected="selected"'; ?>  value="08">Aug</option>
										<option<?php if(date('m') == '09') echo ' selected="selected"'; ?>  value="09">Sep</option>
										<option<?php if(date('m') == '10') echo ' selected="selected"'; ?>  value="10">Oct</option>
										<option<?php if(date('m') == '11') echo ' selected="selected"'; ?>  value="11">Nov</option>
										<option<?php if(date('m') == '12') echo ' selected="selected"'; ?>  value="12">Dec</option>
									</select>
									<select tabindex="4" name="input-date-start[]" id="dd">
										<?php for($year = date('Y')+9; $year >= (date('Y') - 1); $year--): ?>
											<option<?php if( $year == date('Y') ) echo ' selected'; ?> value="<?php echo $year; ?>"><?php echo $year; ?></option>
										<?php endfor; ?>
									</select>
									
								<?php /*****/ endif; /*****/?>
							</div>
						</div>	
					</td>
				</tr>
				<tr class="form-field">
					<th scope="row"><label for="input-date-stop"><?php _e('Stop date', WPCUSTOMADS_NAME); ?></label></th>
					<td>
						<div class="hide-if-js" id="stopdatediv" style="display: block;">
							<div class="timestamp-wrap">
								<?php /*****/ if( $editing ) : /*****/ ?>
								
									<select tabindex="4" name="input-date-stop[]" id="dd">
										<?php for($day = 1; $day <= 31; $day++): ?>
											<option<?php if( $day == $data->date_stop[2] ) echo ' selected'; ?> value="<?php echo str_pad($day, 2, "0", STR_PAD_LEFT); ?>">
												<?php echo str_pad($day, 2, "0", STR_PAD_LEFT); ?>
											</option>
										<?php endfor; ?>
									</select>
									<select tabindex="4" name="input-date-stop[]" id="mm">
										<option<?php if($data->date_stop[1] == '01') echo ' selected="selected"'; ?> value="01">Jan</option>
										<option<?php if($data->date_stop[1] == '02') echo ' selected="selected"'; ?>  value="02">Feb</option>
										<option<?php if($data->date_stop[1] == '03') echo ' selected="selected"'; ?>  value="03">Mar</option>
										<option<?php if($data->date_stop[1] == '04') echo ' selected="selected"'; ?>  value="04">Apr</option>
										<option<?php if($data->date_stop[1] == '05') echo ' selected="selected"'; ?>  value="05">May</option>
										<option<?php if($data->date_stop[1] == '06') echo ' selected="selected"'; ?>  value="06">Jun</option>
										<option<?php if($data->date_stop[1] == '07') echo ' selected="selected"'; ?>  value="07">Jul</option>
										<option<?php if($data->date_stop[1] == '08') echo ' selected="selected"'; ?>  value="08">Aug</option>
										<option<?php if($data->date_stop[1] == '09') echo ' selected="selected"'; ?>  value="09">Sep</option>
										<option<?php if($data->date_stop[1] == '10') echo ' selected="selected"'; ?>  value="10">Oct</option>
										<option<?php if($data->date_stop[1] == '11') echo ' selected="selected"'; ?>  value="11">Nov</option>
										<option<?php if($data->date_stop[1] == '12') echo ' selected="selected"'; ?>  value="12">Dec</option>
									</select>
									<select tabindex="4" name="input-date-stop[]" id="dd">
										<?php for($year = date('Y')+9; $year >= (date('Y') - 1); $year--): ?>
											<option<?php if( $year == $data->date_stop[0] ) echo ' selected'; ?> value="<?php echo $year; ?>"><?php echo $year; ?></option>
										<?php endfor; ?>
									</select>
									
								<?php /*****/ else: /*****/ ?>
									
									<select tabindex="4" name="input-date-stop[]" id="dd">
										<?php for($day = 1; $day <= 31; $day++): ?>
											<option<?php if( $day == date('d') ) echo ' selected'; ?> value="<?php echo str_pad($day, 2, "0", STR_PAD_LEFT); ?>">
												<?php echo str_pad($day, 2, "0", STR_PAD_LEFT); ?>
											</option>
										<?php endfor; ?>
									</select>
									<select tabindex="4" name="input-date-stop[]" id="mm">
										<option<?php if(date('m') == '01') echo ' selected="selected"'; ?> value="01">Jan</option>
										<option<?php if(date('m') == '02') echo ' selected="selected"'; ?>  value="02">Feb</option>
										<option<?php if(date('m') == '03') echo ' selected="selected"'; ?>  value="03">Mar</option>
										<option<?php if(date('m') == '04') echo ' selected="selected"'; ?>  value="04">Apr</option>
										<option<?php if(date('m') == '05') echo ' selected="selected"'; ?>  value="05">May</option>
										<option<?php if(date('m') == '06') echo ' selected="selected"'; ?>  value="06">Jun</option>
										<option<?php if(date('m') == '07') echo ' selected="selected"'; ?>  value="07">Jul</option>
										<option<?php if(date('m') == '08') echo ' selected="selected"'; ?>  value="08">Aug</option>
										<option<?php if(date('m') == '09') echo ' selected="selected"'; ?>  value="09">Sep</option>
										<option<?php if(date('m') == '10') echo ' selected="selected"'; ?>  value="10">Oct</option>
										<option<?php if(date('m') == '11') echo ' selected="selected"'; ?>  value="11">Nov</option>
										<option<?php if(date('m') == '12') echo ' selected="selected"'; ?>  value="12">Dec</option>
									</select>
									<select tabindex="4" name="input-date-stop[]" id="dd">
										<?php for($year = date('Y')+9; $year >= (date('Y') - 1); $year--): ?>
											<option<?php if( $year == date('Y') ) echo ' selected'; ?> value="<?php echo $year; ?>"><?php echo $year; ?></option>
										<?php endfor; ?>
									</select>
									
								<?php /*****/ endif; /*****/?>
							</div>
						</div>	
					</td>
				</tr>
				<tr class="form-field">
					<th scope="row"><label for="input-element"><?php _e('Element', WPCUSTOMADS_NAME); ?></label></th>
					<td>
						<select name="input-element" style="min-width: 27em">
							<?php foreach( retrieveElementsFromDB() as $element ): ?>
							
							<option <?php if( $editing && $data->element_id == $element->element_id ) echo 'selected="selected" '; ?>value="<?php echo $element->element_id; ?>"><?php echo $element->identifier . '@' . $element->page; ?></option>
							
							<?php endforeach; ?>
						</select>	
					</td>
				</tr>
				<tr>
					<th scope="row">
						<input type="submit" name="input-submit" value="<?php echo ($editing)? __('Save changes', WPCUSTOMADS_NAME) : __('Store the advert', WPCUSTOMADS_NAME); ?>" />
					</th>
				</tr>
			</tbody>
		</table>
	</form>
</div>