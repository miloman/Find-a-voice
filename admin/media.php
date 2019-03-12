<div class="wrap">
	<h1><?= esc_html(get_admin_page_title()); ?></h1>
<?php
	function find_a_voice_media_dir( $param ){
		$mydir = '/fav';

		$param['path'] = $param['path'] . $mydir;
		$param['url'] = $param['url'] . $mydir;

		return $param;
	}
	
	global $wpdb;
	$db_prefix = $wpdb->prefix;
	
	if (isset($_REQUEST['todo'])) {
        $tbl_media = $db_prefix . "lfm_media_files";

	    $todo = $_REQUEST['todo'];
	    switch($todo) {
		    case "insert":
		    	if(isset($_FILES['media_file'])){
					$media_file = $_FILES['media_file'];
					add_filter('upload_dir', 'find_a_voice_media_dir');
			    	$uploaded=media_handle_upload('media_file', 0);
					remove_filter('upload_dir', 'find_a_voice_media_dir');
	                // Error checking using WP functions
	                if(is_wp_error($uploaded)){
                        echo "Error uploading file: " . $uploaded->get_error_message();
	                }else{
					    $talent_id = ucwords(strtolower($_POST['talent_name']));
				        $wpdb->insert(
				                $tbl_media, //table
				                array('id_voice_talent' => $talent_id,
				                	'id_media' => $uploaded,
				                	'accent' => $_REQUEST['accent'],
				                	'language' => $_REQUEST['language'],
				                	'platform' => $_REQUEST['platform'],
				                	'style' => $_REQUEST['style'],
				                	'tone' => $_REQUEST['tone'],
									'description' => $_REQUEST['description']),
				                array('%s') //data format			
				        );
				        $msg .= "Voice talents media file added";
				        $msg_class = "updated";
	                }
			    	
		    	}
		    break;
		    case "delete":
		    	if (isset($_REQUEST['mediaid']) && ($_REQUEST['mediaid'] != '')) {
			    	$mediaid = $_REQUEST["mediaid"];
					$wpdb->delete(
						$tbl_media,
						array("id_media" => $mediaid)
					);
					wp_delete_attachment($mediaid, true);
					$msg .= "Media deleted";
					$msg_class = "updated";
		    	}
		    break;
	    }
		
	}
	

	if (!class_exists('WP_List_Table')) {
		require_once (ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
	}
	
	
	class findavoice_table extends WP_List_Table {
		
		function get_columns() {
			$columns = array(
				'cb' => '<input type="checkbox">',
				'talent_name' => 'Name',
				'age' => 'Age',
				'gender' => 'Gender',
				'accent' => 'Accent',
				'language' => 'Language',
				'platform' => 'Platform',
				'tone' => 'Tone',
				'style' => 'Style',
//				'description' => 'Description',
//				'guid' => 'File location',
				'status' => 'Status'
			);
			
			return $columns;
		}
		
		
		function prepare_items() {
			$columns = $this->get_columns();
			$hidden = array();
			$sortable = $this->get_sortable_columns();
			$this->_column_headers = array($columns, $hidden, $sortable);
			
			$per_page = 10;
			$current_page = $this->get_pagenum();
			$media_data = $this->get_data($per_page, $current_page);
			$this->items = $media_data;
			
			$total_items = $this->get_total();
			
			$this->set_pagination_args(
				array(
					'total_items' => $total_items,
					'per_page' => $per_page
				)
			);

		}
		
		
		function column_default($item, $column_name) {
			switch($column_name) {
				case 'talent_name':
					return $item[$column_name];
				case 'gender':
					return $item[$column_name];
				case 'age':
					return $item[$column_name];
				case 'accent':
					return $item[$column_name];
				case 'language':
					return $item[$column_name];
				case 'platform':
					return $item[$column_name];
				case 'tone':
					return $item[$column_name];
				case 'style':
					return $item[$column_name];
				case 'description':
					return $item[$column_name];
				case 'guid':
					return $item[$column_name];
				case 'status':
					return $item[$column_name];
				default:
					return print_r($item, true);
			}
		}
		
		
		function get_sortable_columns() {
			$sortable_columns = array(
				'talent_name' => array('talent_name', false),
				'gender' => array('gender', false),
				'age' => array('age', false),
				'accent' => array('accent', false),
				'language' => array('language', false),
				'platform' => array('platform', false),
				'tone' => array('tone', false),
				'style' => array('style', false),
				'status' => array('status', false)
			);
			
			return $sortable_columns;
		}
		
				
		function column_talent_name($item) {
			$actions = array(
//				'edit' => sprintf('<a href="?page=%s&todo=%s&talentid=%s">Edit</a>', $_REQUEST['page'], 'edit', $item['id_voice_talent']),
				'delete' => sprintf('<a href="?page=%s&todo=%s&mediaid=%s">Delete</a>', $_REQUEST['page'], 'delete', $item['id_media'])
			);

			return sprintf('%1$s %2$s', $item['talent_name'], $this->row_actions($actions));
		}
		
	 
		function get_data($per_page, $current_page) {
			global $wpdb;
			$db_prefix = $wpdb->prefix;
			$offset = ($current_page - 1) * $per_page;

			// if no sort, default to talent name
			$orderby = (!empty($_GET['orderby'])) ? $_GET['orderby'] : 'talent_name';
			
			// if no order, default to asc
			$order = (!empty($_GET['order'])) ? $_GET['order'] : 'asc';
			
			$sql = "SELECT a.id_media, a.id_voice_talent, a.description, b.accent, c.language, d.platform, e.tone, f.style, g.talent_name, h.gender, i.age, j.guid
					FROM {$db_prefix}lfm_media_files a 
					left join {$db_prefix}lfm_accents b on a.accent = b.id_accent
					left join {$db_prefix}lfm_languages c on a.language = c.id_language 
					left join {$db_prefix}lfm_platforms d on a.platform = d.id_platform
					left join {$db_prefix}lfm_tones e on a.tone = e.id_tone
					left join {$db_prefix}lfm_styles f on a.style = f.id_style
					left join {$db_prefix}lfm_voice_talents g on a.id_voice_talent = g.id_voice_talent
					left join {$db_prefix}lfm_genders h on g.talent_gender = h.id_gender
					left join {$db_prefix}lfm_ages i on g.talent_age = i.id_age
					left join {$db_prefix}posts j on a.id_media = j.ID
					order by $orderby $order limit $offset, $per_page";
			$db_data = $wpdb->get_results($sql, 'ARRAY_A');

			return $db_data;
		}
		
		function get_total() {
			global $wpdb;
			$db_prefix = $wpdb->prefix;
			
			$sql = "select count(id_media) as total from {$db_prefix}lfm_media_files";
			$db_data = $wpdb->get_results($sql, 'ARRAY_A');
			
			return $db_data[0]['total'];
		}
		
		
		function get_bulk_actions() {
			$actions = array(
				'delete' => 'Delete'
			);
			
			return $actions;
		}		
		
		
		function column_cb($item) {
			return sprintf('<input type="checkbox" name="accent[]" value="%s">', $item['id_accent']);
		}

	}

		
	$mediaTable = new findavoice_table();		
	$mediaTable->prepare_items();
	$mediaTable->display();
	
	
?>	

<?php 
	$results_voice_talents = $wpdb->get_results( "SELECT id_voice_talent, talent_name FROM {$wpdb->prefix}lfm_voice_talents ORDER BY talent_name");
	$results_accents = $wpdb->get_results( "SELECT id_accent, accent FROM {$wpdb->prefix}lfm_accents ORDER BY accent");
	$results_language = $wpdb->get_results( "SELECT id_language, language FROM {$wpdb->prefix}lfm_languages ORDER BY language");
	$results_platform = $wpdb->get_results( "SELECT id_platform, platform FROM {$wpdb->prefix}lfm_platforms ORDER BY platform");
	$results_style = $wpdb->get_results( "SELECT id_style, style FROM {$wpdb->prefix}lfm_styles ORDER BY style");
	$results_tone = $wpdb->get_results( "SELECT id_tone, tone FROM {$wpdb->prefix}lfm_tones ORDER BY tone");
?>
<h2 style="margin-top: 50px;">Add a media file</h2>
<?php if (isset($msg)): ?><div class="<?= $msg_class; ?>"><p><?= $msg; ?></p></div><?php endif; ?>
<link rel="stylesheet" href="/wp-content/plugins/find-a-voice/css/pure/pure-min.css" >
<style>
	.pure-control-group select {
		 min-width: 300px;
	}
</style>
<form method="post" action="<?= $_SERVER['REQUEST_URI']; ?>" class="pure-form pure-form-aligned"  enctype="multipart/form-data">
	<fieldset>
		<div class="pure-control-group">
			<label for="talent_name" class="form_label">Name:</label>
			<select name="talent_name">
				<option value="">Please select a voice talent</option>
			<?php foreach ($results_voice_talents as $talent) { echo('<option value="'.$talent->id_voice_talent.'">'.$talent->talent_name.'</option>'); } ?>		
			</select>
		</div>
		
		<div class="pure-control-group">
			<label for="accent" class="form_label">Accent:</label>
			<select name="accent">
				<option value="">Please select an accent</option>
			<?php foreach ($results_accents as $accent) { echo('<option value="'.$accent->id_accent.'">'.$accent->accent.'</option>'); } ?>		
			</select>
		</div>
				
		<div class="pure-control-group">
			<label for="language" class="form_label">Language:</label>
			<select name="language">
				<option value="">Please select a language</option>
			<?php foreach ($results_language as $language) { echo('<option value="'.$language->id_language.'">'.$language->language.'</option>'); } ?>		
			</select>
		</div>
				
		<div class="pure-control-group">
			<label for="platform" class="form_label">Platform:</label>
			<select name="platform">
				<option value="">Please select a platform</option>
			<?php foreach ($results_platform as $platform) { echo('<option value="'.$platform->id_platform.'">'.$platform->platform.'</option>'); } ?>		
			</select>
		</div>
				
		<div class="pure-control-group">
			<label for="style" class="form_label">Style:</label>
			<select name="style">
				<option value="">Please select a style</option>
			<?php foreach ($results_style as $style) { echo('<option value="'.$style->id_style.'">'.$style->style.'</option>'); } ?>		
			</select>
		</div>
				
		<div class="pure-control-group">
			<label for="tone" class="form_label">Tone:</label>
			<select name="tone">
				<option value="">Please select a tone</option>
			<?php foreach ($results_tone as $tone) { echo('<option value="'.$tone->id_tone.'">'.$tone->tone.'</option>'); } ?>		
			</select>
		</div>
				
		<div class="pure-control-group">
			<label for="description" class="form_label">Description:</label>
			<textarea name="description" style="width: 300px;"></textarea>
		</div>
		
		<div class="pure-control-group">
			<label for="media_file" class="form_label">MP3 Media File</label>
			<input type='file' id='media_file' name='media_file'></input>
		</div>
				
		<div class="pure-controls">
			<input type="hidden" name="todo" value="insert">
			<input type="submit" name="insert" value="Save" class="pure-button pure-button-primary">
		</div>
		
	</fieldset>
</form>
</div>


</div>