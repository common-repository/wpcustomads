<?php
	/*
		Plugin name: WPCustomAds
		Version: 0.8.8
		Description: Places custom ads into predefined areas of your template
		Author: Karl Lindmark
		Author URI: http://www.ninetwozero.com
		Plugin URI: http://wordpress.org/extend/plugins/wpcustomads/
	*/
?>
<?php
	//Define the names
	define('WPCUSTOMADS_NAME', 'WPCustomAds');
	define('WPCUSTOMADS_ID', 'WPCustomAds-main');
	define('WPCUSTOMADS_TBL_AD', 'plugin_wpcustomads_ads');
	define('WPCUSTOMADS_TBL_ELEMENT', 'plugin_wpcustomads_elements');
	define('WPCUSTOMADS_HOME_URL', str_replace(getcwd(), home_url(), dirname(__FILE__)));
	define('WPCUSTOMADS_LANG_URL', basename(dirname(__FILE__)) . '/lang');
	define('WPCUSTOMADS_JS_URL', WPCUSTOMADS_HOME_URL . '/js/WPCustomAds-javascripts.php');
?>
<?php
	load_plugin_textdomain(WPCUSTOMADS_NAME, false, WPCUSTOMADS_LANG_URL);

	//Setup the hooks
	add_action('admin_menu', 'WPCustomAds_Menu');
	add_action('wp_print_scripts', 'WPCustomAds_JSLoader');
	register_activation_hook(__FILE__, 'WPCustomAds_Installer' );
	register_activation_hook(__FILE__,'WPCustomAds_InstallerCompleted');
	register_deactivation_hook(__FILE__,'WPCustomAds_Uninstaller');

	//For the results
	global $plugin_results;

	function WPCustomAds_Installer() {
		
		//This one seems important
		global $wpdb;
		
		//Construct the table name
		$plugin_table_ad = $wpdb->prefix . WPCUSTOMADS_TBL_AD;	 
		$plugin_table_element = $wpdb->prefix . WPCUSTOMADS_TBL_ELEMENT;	 
		$sql_tbl1 = $sql_tbl2 = '';
		
		//Does the table already exist?
		if($wpdb->get_var('SHOW TABLES LIKE ' . $plugin_table_ad ) != $plugin_table_ad ) {
			
			$sql_tbl1 = '
				CREATE TABLE IF NOT EXISTS ' . $plugin_table_ad . ' (
				  `ad_id` int(6) unsigned NOT NULL AUTO_INCREMENT,
				  `title` varchar(255) NOT NULL,
				  `link` varchar(255) NOT NULL,
				  `image` varchar(255) NOT NULL,
				  `date_start` date NOT NULL,
				  `date_stop` date NOT NULL,
				  `element_id` int(6) unsigned NOT NULL,
				  `active` tinyint(1) NOT NULL,
				  PRIMARY KEY (`ad_id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1;';
		}
		
		//Does the second table already exist?
		if($wpdb->get_var('SHOW TABLES LIKE ' . $plugin_table_element) != $plugin_table_element ) {
			$sql_tbl2 = '
				CREATE TABLE IF NOT EXISTS ' . $plugin_table_element . ' (
				  `element_id` int(6) unsigned NOT NULL AUTO_INCREMENT,
				  `identifier` varchar(255) NOT NULL,
				  `page` varchar(255) NOT NULL,
				  PRIMARY KEY (`element_id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8 AUTO_INCREMENT=1
			';

		}
		
		if( $sql_tbl1 != '' || $sql_tbl2 != '' ) {
		
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			if( $sql_tbl1 != '' ) dbDelta($sql_tbl1);
			if( $sql_tbl2 != '' ) dbDelta($sql_tbl2);
		
		}
	}
	
	function WPCustomAds_Uninstaller() {
		
		//Database access please
		global $wpdb;
		
		//Construct the table name
		$plugin_table_ad = $wpdb->prefix . WPCUSTOMADS_TBL_AD;	 
		$plugin_table_element = $wpdb->prefix . WPCUSTOMADS_TBL_ELEMENT;	 
		$sql_tbl1 = $sql_tbl2 = '';
		
		//Does the table already exist?
		if($wpdb->get_var('SHOW TABLES LIKE ' . $plugin_table_ad ) == $plugin_table_ad ) $sql_tbl1 = 'DROP TABLE ' . $plugin_table_ad;
		
		//Does the second table already exist?
		if($wpdb->get_var('SHOW TABLES LIKE ' . $plugin_table_element) == $plugin_table_element ) $sql_tbl2 = 'DROP TABLE ' . $plugin_table_element;

		//If we got queries to perform, we do it now
		if( $sql_tbl1 != '' || $sql_tbl2 != '' ) {
		
			require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
			if( $sql_tbl1 != '' ) dbDelta($sql_tbl1);
			if( $sql_tbl2 != '' ) dbDelta($sql_tbl2);
		
		}
	}
	
	function WPCustomAds_InstallerCompleted() {}
	
	/**
	
		JavaScript-loader for the public area
	
	*/
	
	function WPCustomAds_JSLoader() {
		
		if( !is_admin() ) {
		
			wp_enqueue_script('jquery');
			wp_register_script('wpcustomads-javascripts', WPCUSTOMADS_JS_URL, false, false);
			wp_enqueue_script('wpcustomads-javascripts', array('jquery'));
		
		}
	}
	
	/**
	
		JavaScript and CSS-loader for the upload functionality
	
	*/
	
	function WPCustomAds_GetJS() {
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
		wp_register_script('WPCustomAds_-upload', WP_PLUGIN_URL.'/wpcustomads/js/plugin-scripts.js', array('jquery','media-upload','thickbox'));
		wp_enqueue_script('WPCustomAds_-upload');
	}

	function WPCustomAds_GetCSS() {
		wp_enqueue_style('thickbox');
	}
	
	/*
		
		Functions to retrieve one, or more items from the DB.
	
	
	*/
	
	function retrieveAdFromDB($id) {
		
		global $wpdb;
		return $wpdb->get_results(
			'SELECT a.*, e.`identifier`, e.`page` FROM ' . $wpdb->prefix . WPCUSTOMADS_TBL_AD . ' a JOIN ' . $wpdb->prefix . WPCUSTOMADS_TBL_ELEMENT . ' e USING (`element_id`) WHERE `ad_id` = ' . intval($id)
		);
		
	}
	
	function retrieveAdsFromDB() {
		
		global $wpdb;
		return $wpdb->get_results('SELECT a.*, e.`identifier`, e.`page` FROM ' . $wpdb->prefix . WPCUSTOMADS_TBL_AD . ' a JOIN ' . $wpdb->prefix . WPCUSTOMADS_TBL_ELEMENT . ' e USING (`element_id`)');
		
	}
	
	function retrieveCurrentAdsFromDB() {
		
		global $wpdb;
		return $wpdb->get_results('
			SELECT a.*, e.`identifier`, e.`page`, NOW() as `foo`
			FROM ' . $wpdb->prefix . WPCUSTOMADS_TBL_AD . ' a 
			JOIN ' . $wpdb->prefix . WPCUSTOMADS_TBL_ELEMENT . ' e 
			USING (`element_id`) 
			WHERE NOW() BETWEEN a.`date_start` AND a.`date_stop`
		');
		
	}
	
	function retrieveElementFromDB($id) {
		
		global $wpdb;
		return $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . WPCUSTOMADS_TBL_ELEMENT . ' WHERE `element_id` = ' . intval($id));
	
	}
	
	function retrieveElementsFromDB() {
		
		global $wpdb;
		return $wpdb->get_results('SELECT * FROM ' . $wpdb->prefix . WPCUSTOMADS_TBL_ELEMENT);
		
	}
	
	function deleteAdFromDB($id) {
		
		global $wpdb;
		return ( $wpdb->query('DELETE FROM ' . $wpdb->prefix . WPCUSTOMADS_TBL_AD . ' WHERE `ad_id` = ' . intval($id)) )? __('Advert deleted.', WPCUSTOMADS_NAME) : __('Advert could not be deleted.', WPCUSTOMADS_NAME);
	
	}

	function deleteElementFromDB($id) {
		
		global $wpdb;
		
		//Two different queries... but only one will be used
		$sql_0 = 'DELETE e FROM ' . $wpdb->prefix . WPCUSTOMADS_TBL_ELEMENT . ' e WHERE `element_id` = ' . intval($id);
		$sql_n = 'DELETE e, a FROM ' . $wpdb->prefix . WPCUSTOMADS_TBL_ELEMENT . ' e , ' . $wpdb->prefix . WPCUSTOMADS_TBL_AD . ' a WHERE e.`element_id` = ' . intval($id) . ' AND e.`element_id` = a.`element_id`';

		//Do we have any adverts tied to the elements? If so, we remove them too
		$sql = ( count( $wpdb->get_results('SELECT `ad_id` FROM ' . $wpdb->prefix . WPCUSTOMADS_TBL_AD . ' WHERE `element_id` = ' . intval($id)) ) === 0 )? $sql_0 : $sql_n;

		return ( $wpdb->query($sql) === 1 )? __('Element deleted.', WPCUSTOMADS_NAME) : __('Element could not be deleted.', WPCUSTOMADS_NAME);

	}
	
	function insertAdToDB($data_array) {
		
		global $wpdb;
		
		//Let's loop over the $_POST (referenced) and adjust the dates
		foreach($data_array as &$item ) {
			
			if( is_array($item) ) {
				
				$item = array_reverse($item);
				$item = implode('-', $item);
				
			}
			
		}
		
		//Initialize a temporary error-array
		$errors = array();
		
		//Up next... validation!
		if( trim($_POST['input-title']) == '' ) {
		
			$errors[] = '<label for="input-title">' . __('&raquo;&nbsp;You need to enter a title', WPCUSTOMADS_NAME) . '</label>.';
			
		}
		if( trim($_POST['input-link']) == '' ) {
		
			$errors[] = '<label for="input-link">' . __('&raquo;&nbsp;You need to enter a link', WPCUSTOMADS_NAME) . '</label>.';
			
		}
		if( trim($_POST['input-image']) == '' ) {
		
			$errors[] = '<label for="input-image">' . __('&raquo;&nbsp;You need to assign an image', WPCUSTOMADS_NAME) . '</label>.';
			
		}
		
		if( count($errors) === 0 ) {
			
			$query_result = $wpdb->query( 
				$wpdb->prepare('
					INSERT INTO ' . $wpdb->prefix . WPCUSTOMADS_TBL_AD . ' 
					( `title`, `link`, `image`, `date_start`, `date_stop`, `element_id`, `active` )
					VALUES 
					( %s, %s, %s, %s, %s, %s, 1 )', 
					$data_array['input-title'], $data_array['input-link'], $data_array['input-image'], $data_array['input-date-start'], $data_array['input-date-stop'], $data_array['input-element'], 1
				) 
			);

			 return ( $query_result === 1 )? __('Database storage successful.', WPCUSTOMADS_NAME) : __('Database storage failed. Please try again.', WPCUSTOMADS_NAME);
			 
		} else {
			
			return $errors;
			
		}
		
	}
	
	function editAdInDB($data_array, $id) {
		
		global $wpdb;
		
		//Let's intval the id
		$id = intval($id);
		
		//Let's loop over the $_POST (referenced) and adjust the dates
		foreach($data_array as &$item ) {
			
			if( is_array($item) ) {
				
				$item = array_reverse($item);
				$item = implode('-', $item);
				
			}
			
		}
		
		//Initialize a temporary error-array
		$errors = array();
		
		//Up next... validation!
		if( trim($_POST['input-title']) == '' ) {
		
			$errors[] = '<label for="input-title">' . __('&raquo;&nbsp;You need to enter a title.', WPCUSTOMADS_NAME) . '</label>';
			
		}
		if( trim($_POST['input-link']) == '' ) {
		
			$errors[] = '<label for="input-link">' . __('&raquo;&nbsp;You need to enter a link.', WPCUSTOMADS_NAME) . '</label>';
			
		}
		if( trim($_POST['input-image']) == '' ) {
		
			$errors[] = '<label for="input-image">' . __('&raquo;&nbsp;You need to assign an image.', WPCUSTOMADS_NAME) . '</label>';
			
		}
		
		if( $id < 1 ) {
			
			$errors[] = __('Invalid unique id.', WPCUSTOMADS_NAME);
			
		}
		
		
		if( count($errors) === 0 ) {
			
			$query_result = $wpdb->query( 
				$wpdb->prepare('
					UPDATE ' . $wpdb->prefix . WPCUSTOMADS_TBL_AD . ' 
					SET `title` = %s, `link` = %s, `image` = %s, `date_start` = %s, `date_stop` = %s, `element_id` = %s 
					WHERE `ad_id` = %d', 
					$data_array['input-title'], $data_array['input-link'], $data_array['input-image'], $data_array['input-date-start'], $data_array['input-date-stop'], $data_array['input-element'], $id
				) 
			);

			 return ( $query_result === 1 )? __('Advert updated successfully.', WPCUSTOMADS_NAME) : __('Advert update failed. Please try again.', WPCUSTOMADS_NAME);
			 
		} else {
			
			return $errors;
			
		}
		
	}
	
	function insertElementToDB($data_array) {
		
		global $wpdb;
		
		//Initialize a temporary error-array
		$errors = array();
		
		//Up next... validation!
		if( trim($_POST['input-element']) == '' ) {
		
			$errors[] = '<label for="input-element">' . __('&raquo;&nbsp;You need to enter an element.') . '</label>';
			
		}
		if( trim($_POST['input-page']) == '' ) {
		
			$errors[] = '<label for="input-page">' . __('&raquo;&nbsp;You need to enter a page which it exists on.') . '</label>';
			
		}
		
		if( count($errors) === 0 ) {
			
			$query_result = $wpdb->query( 
				$wpdb->prepare('
					INSERT INTO ' . $wpdb->prefix . WPCUSTOMADS_TBL_ELEMENT . ' 
					( `identifier`, `page`)
					VALUES 
					( %s, %s )', 
					$data_array['input-element'], $data_array['input-page']
				) 
			);

			 return ( $query_result === 1 )? __('Database storage successful.', WPCUSTOMADS_NAME) : __('Database storage failed. Please try again.', WPCUSTOMADS_NAME);
			 
		} else {
			
			return $errors;
			
		}
		
	}
	
	function editElementInDB($data_array, $id) {
		
		global $wpdb;
		
		//Initialize a temporary error-array
		$errors = array();
		
		//Intval the $id
		$id = intval($id);
		
		//Up next... validation!
		if( trim($_POST['input-element']) == '' ) {
		
			$errors[] = '<label for="input-element">' . __('&raquo;&nbsp;You need to enter an element.', WPCUSTOMADS_NAME) . '</label>';
			
		}
		
		if( trim($_POST['input-page']) == '' ) {
		
			$errors[] = '<label for="input-page">' . __('&raquo;&nbsp;You need to enter a page which it exists on.', WPCUSTOMADS_NAME) . '</label>';
			
		}
		
		if( $id < 1 ) {
			
			$errors[] = __('Invalid unique id.', WPCUSTOMADS_NAME);
			
		}
		
		if( count($errors) === 0 ) {
			
			$query_result = $wpdb->query( 
				$wpdb->prepare('
					UPDATE ' . $wpdb->prefix . WPCUSTOMADS_TBL_ELEMENT . ' 
					SET `identifier` = %s, `page` = %s 
					WHERE `element_id` = %d',
					$data_array['input-element'], $data_array['input-page'], $id
				) 
			);

			 return ( $query_result === 1 )? __('Element updated successfully.', WPCUSTOMADS_NAME) : __('Element update failed. Please try again.', WPCUSTOMADS_NAME);
			 
		} else {
			
			return $errors;
			
		}
		
	}

	$req_page = ( isset($_GET['page']) )? $_GET['page'] : '';
	switch( $req_page ) {
		
		case 'WPCustomAds-manage-ads':			
		case 'WPCustomAds-manage-elements':
		case 'WPCustomAds-settings':
			add_action('admin_print_scripts', 'WPCustomAds_GetJS');
			add_action('admin_print_styles', 'WPCustomAds_GetCSS');
			break;
	}
	
	$req_action = ( isset($_REQUEST['action']) )? $_REQUEST['action'] : '';
	//Switch over the action to know what we're doing
	switch($req_action) {
		
		case 'newad':
			$plugin_results = insertAdToDB(&$_POST);
			break;
		
		case 'newelement':
			$plugin_results = insertElementToDB(&$_POST);
			break;
		
		case 'editad':
			$plugin_results = editAdInDB(&$_POST, $_REQUEST['id']);
			break;
			
		case 'editelement':
			$plugin_results = editElementInDB(&$_POST, $_REQUEST['id']);
			break;
		
		case 'deletead':
			$plugin_results = deleteAdFromDB($_REQUEST['id']);
			break;
			
		case 'deleteelement':
			$plugin_results = deleteElementFromDB($_REQUEST['id']);
			break;
			
		default:
			$plugin_results = '';
			break;
	}

	/**
	
		Injects the WPCustomAds-menu into the sidebar
	
	*/

	function WPCustomAds_Menu() {
	
		add_menu_page(WPCUSTOMADS_NAME, WPCUSTOMADS_NAME, 'administrator', WPCUSTOMADS_ID, 'WPCustomAds_MainScreen');
		add_submenu_page('WPCustomAds-main',  __('Managing an advert &laquo; ' . WPCUSTOMADS_NAME, WPCUSTOMADS_NAME), __('Add advert', WPCUSTOMADS_NAME), 'manage_options', 'WPCustomAds-manage-ads', 'WPCustomAds_AddScreen');
		add_submenu_page('WPCustomAds-main',  __('Viewing adverts &laquo; ' . WPCUSTOMADS_NAME, WPCUSTOMADS_NAME), __('View adverts', WPCUSTOMADS_NAME), 'manage_options', 'WPCustomAds-list-ads', 'WPCustomAds_ManageAdsScreen');
		add_submenu_page('WPCustomAds-main',  __('Registering an element &laquo; ' . WPCUSTOMADS_NAME, WPCUSTOMADS_NAME), __('Register element', WPCUSTOMADS_NAME), 'manage_options', 'WPCustomAds-manage-elements', 'WPCustomAds_AddElementScreen');
		add_submenu_page('WPCustomAds-main',  __('Viewing elements &laquo; ' . WPCUSTOMADS_NAME, WPCUSTOMADS_NAME), __('View elements', WPCUSTOMADS_NAME), 'manage_options', 'WPCustomAds-list-elements', 'WPCustomAds_ManageElementsScreen');
		//TODO: add_submenu_page('WPCustomAds-main', __('Setting it up &laquo; ' . WPCUSTOMADS_NAME, WPCUSTOMADS_NAME), __('Settings', WPCUSTOMADS_NAME), 'manage_options', 'WPCustomAds-settings', 'WPCustomAds_SettingsScreen');
	
	}


	/**
	
		Functions to include the appropriate template
	
	
	*/
	
	function WPCustomAds_MainScreen() {
		
		//Include the template
		include('templates/tpl_main.php');
		
	}
	
	function WPCustomAds_AddScreen() {
		
		//Include the template
		include('templates/tpl_manage_ad.php');
		
	}
	
	function WPCustomAds_AddElementScreen() {
		
		//Include the template
		include('templates/tpl_manage_element.php');
		
	}
	
	function WPCustomAds_ManageAdsScreen() {
		
		//Include the template
		include('templates/tpl_list_ads.php');
		
	}
	
	function WPCustomAds_ManageElementsScreen() {
				
		//Include the template
		include('templates/tpl_list_elements.php');
		
	}

	function WPCustomAds_SettingsScreen() {
		
		//Include the template
		include('templates/tpl_settings.php');
	}
	
?>
