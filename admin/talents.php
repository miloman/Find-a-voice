<style>
	#guid {
		width: 70px;
	}
</style>
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
	
	$todo = '';
	
	if (isset($_REQUEST['todo'])) {
        $tbl = $db_prefix . "lfm_voice_talents";
        $tbl_media = $db_prefix . "lfm_media_files";

	    $todo = $_REQUEST['todo'];
	    switch($todo) {
		    case "insert":
		    	if ($_POST['lfm_talent_name'] != '') {
				    $talent_name = ucwords(strtolower($_POST['lfm_talent_name']));
				    // check if already exists
				    $sql = "select talent_name from $tbl where talent_name = '$talent_name'";
				    $db_data = $wpdb->get_results($sql, 'ARRAY_A');
				    
				    if (count($db_data) == 0) {
						$uploaded = 0;
						if(isset($_FILES['avatar'])){
							$avatar = $_FILES['avatar'];
							add_filter('upload_dir', 'find_a_voice_media_dir');
							$uploaded=media_handle_upload('avatar', 0);
							remove_filter('upload_dir', 'find_a_voice_media_dir');
						}
				        $wpdb->insert(
				                $tbl, //table
				                array('talent_name' => $talent_name,
				                	'talent_gender' => $_REQUEST['lfm_talent_gender'],
									'talent_age' => $_REQUEST['lfm_talent_age'],
									'image_location' => $uploaded,
									 'status' => $_REQUEST['status']),
				                array('%s') //data format			
				        );
				        $msg .= "Voice talent added";
				        $msg_class = "updated";
				    } else {
					    $msg .= "Voice talent already exists";
					    $msg_class = "error";
				    }
		    	}
		    break;
		    case "delete":
		    	if (isset($_REQUEST['talentid']) && ($_REQUEST['talentid'] != '')) {
			    	$talent_id = $_REQUEST["talentid"];
			    	// check if any media is associated with this talentid
			    	$sql = "select count(id_media) as total from $tbl_media where id_voice_talent = $talent_id";
			    	$db_data = $wpdb->get_results($sql, 'ARRAY_A');
			    	if ($db_data[0]['total'] == 0) {
				    	$wpdb->delete(
					    	$tbl,
					    	array("id_voice_talent" => $talent_id)
				    	);
				    	$msg .= "Voice talent deleted";
				    	$msg_class = "updated";
			    	} else {
				    	$msg .= "Not possible to delete as there are media files associated with this voice talent";
				    	$msg_class = "error";
			    	}
		    	}
		    break;
			case "update":
				if (isset($_REQUEST['talentid']) && ($_REQUEST['talentid'] != '')) {
					$talent_id = $_REQUEST['talentid'];
					$wpdb->update(
						$tbl,
						array('talent_name' => $_REQUEST['lfm_talent_name'],
							'talent_gender' => $_REQUEST['lfm_talent_gender'],
							'talent_age' => $_REQUEST['lfm_talent_age'],
							 'status' => $_REQUEST['status']), // data
						array('id_voice_talent' => $talent_id) // where
					);
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
				'guid' => 'Avatar',
				'talent_name' => 'Name',
				'gender' => 'Gender',
				'age' => 'Age',
				'status' => 'Status'
			);
			
			return $columns;
		}
		
		
		function prepare_items() {
			$columns = $this->get_columns();
			$hidden = array();
			$sortable = $this->get_sortable_columns();
			$this->_column_headers = array($columns, $hidden, $sortable);
			
			$per_page = 20;
			$current_page = $this->get_pagenum();
			$accent_data = $this->get_data($per_page, $current_page);
			$this->items = $accent_data;
			
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
				'status' => array('status', false),
			);
			
			return $sortable_columns;
		}
		
				
		function column_talent_name($item) {
			$actions = array(
				'edit' => sprintf('<a href="?page=%s&todo=%s&talentid=%s">Edit</a>', $_REQUEST['page'], 'edit', $item['id_voice_talent']),
				'delete' => sprintf('<a href="?page=%s&todo=%s&talentid=%s">Delete</a>', $_REQUEST['page'], 'delete', $item['id_voice_talent'])
			);

			return sprintf('%1$s %2$s', $item['talent_name'], $this->row_actions($actions));
		}
		
	 
		function get_data($per_page, $current_page) {
			global $wpdb;
			$db_prefix = $wpdb->prefix;
			$offset = ($current_page - 1) * $per_page;

			// if no sort, default to accent
			$orderby = (!empty($_GET['orderby'])) ? $_GET['orderby'] : 'talent_name';
			
			// if no order, default to asc
			$order = (!empty($_GET['order'])) ? $_GET['order'] : 'asc';
			
			$sql = "select a.id_voice_talent, a.talent_name, a.status, b.gender, c.age, j.guid 
					from {$db_prefix}lfm_voice_talents a
					left join {$db_prefix}lfm_genders b on a.talent_gender = b.id_gender
					left join {$db_prefix}lfm_ages c on a.talent_age = c.id_age
					left join {$db_prefix}posts j on a.image_location = j.ID
					order by $orderby $order limit $offset, $per_page";
			$db_data = $wpdb->get_results($sql, 'ARRAY_A');

			return $db_data;
		}
		
		function get_total() {
			global $wpdb;
			$db_prefix = $wpdb->prefix;
			
			$sql = "select count(id_voice_talent) as total from {$db_prefix}lfm_voice_talents";
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
		
		function column_guid($item) {
			return sprintf('<img src="%s" height=50>', $item['guid']);
		}

	}

		
	$voiceTalentTable = new findavoice_table();		
	$voiceTalentTable->prepare_items();
	$voiceTalentTable->display();
	
	
?>	

<?php 
	$results_ages = $wpdb->get_results( "SELECT id_age, age FROM {$wpdb->prefix}lfm_ages ORDER BY id_age");
	$results_genders = $wpdb->get_results( "SELECT id_gender, gender FROM {$wpdb->prefix}lfm_genders ORDER BY id_gender");
	
	switch($todo){
		case "edit":
			$sql = "SELECT * from {$wpdb->prefix}lfm_voice_talents where id_voice_talent = " . $_REQUEST['talentid'];
			$results_talent = $wpdb->get_results($sql);
			?>
			<h2 style="margin-top: 50px;">Edit voice talent - <?php echo $results_talent[0]->talent_name; ?></h2>
			<?php if (isset($msg)): ?><div class="<?= $msg_class; ?>"><p><?= $msg; ?></p></div><?php endif; ?>
			<link rel="stylesheet" href="/wp-content/plugins/find-a-voice/css/pure/pure-min.css" >
			<form method="post" action="<?= $_SERVER['REQUEST_URI']; ?>" class="pure-form pure-form-aligned" enctype="multipart/form-data">
				<fieldset>
					<div class="pure-control-group">
						<label for="name" class="form_label">Name:</label>
						<input type="text" name="lfm_talent_name" style="height: 28px;" value="<?php echo $results_talent[0]->talent_name; ?>">
					</div>

					<div class="pure-control-group">
						<label for="gender" class="form_label">Gender:</label>
						<select name="lfm_talent_gender">
						<?php foreach ($results_genders as $gender) { 
								$selected = ($results_talent[0]->talent_gender == $gender->id_gender) ? ' selected' : '';
								echo('<option value="'.$gender->id_gender.'" '.$selected.'>'.$gender->gender.'</option>'); 
							} ?>		
						</select>
					</div>

					<div class="pure-control-group">
						<label for="age" class="form_label">Age:</label>
						<select name="lfm_talent_age">
						<?php foreach ($results_ages as $age) { 
								$selected = ($results_talent[0]->talent_age == $age->id_age) ? ' selected' : '';
								echo('<option value="'.$age->id_age.'" '.$selected.'>'.$age->age.'</option>'); 
							} ?>		
						</select>
					</div>
					
					<div class="pure-control-group">
						<label for="status" class="form_label">Status:</label>
						<select name="status">
							<option value="P" <?php if ($results_talent[0]->status == 'P') echo ' selected'; ?>>Pending/Paused</option>
							<option value="L" <?php if ($results_talent[0]->status == 'L') echo ' selected'; ?>>Live</option>
							<option value="D" <?php if ($results_talent[0]->status == 'D') echo ' selected'; ?>>Dead</option>
						</select>
					</div>

					<div class="pure-controls">
						<input type="hidden" name="todo" value="update">
						<input type="submit" name="insert" value="Update" class="pure-button pure-button-primary">
					</div>

				</fieldset>
			</form>
			<?php		break;
		default:
			?>
			<h2 style="margin-top: 50px;">Add a voice talent</h2>
			<?php if (isset($msg)): ?><div class="<?= $msg_class; ?>"><p><?= $msg; ?></p></div><?php endif; ?>
			<link rel="stylesheet" href="/wp-content/plugins/find-a-voice/css/pure/pure-min.css" >
			<form method="post" action="<?= $_SERVER['REQUEST_URI']; ?>" class="pure-form pure-form-aligned" enctype="multipart/form-data">
				<fieldset>
					<div class="pure-control-group">
						<label for="name" class="form_label">Name:</label>
						<input type="text" name="lfm_talent_name" style="height: 28px;">
					</div>

					<div class="pure-control-group">
						<label for="gender" class="form_label">Gender:</label>
						<select name="lfm_talent_gender">
						<?php foreach ($results_genders as $gender) { echo('<option value="'.$gender->id_gender.'">'.$gender->gender.'</option>'); } ?>		
						</select>
					</div>

					<div class="pure-control-group">
						<label for="age" class="form_label">Age:</label>
						<select name="lfm_talent_age">
						<?php foreach ($results_ages as $age) { echo('<option value="'.$age->id_age.'">'.$age->age.'</option>'); } ?>		
						</select>
					</div>
					
					<div class="pure-control-group">
						<label for="status" class="form_label">Status:</label>
						<select name="status">
							<option value="P">Pending/Paused</option>
							<option value="L">Live</option>
							<option value="D">Dead</option>
						</select>
					</div>

					<div class="pure-control-group">
						<label for="avatar" class="form_label">Avatar</label>
						<input type='file' id='avatar' name='avatar'></input>
					</div>

					<div class="pure-controls">
						<input type="hidden" name="todo" value="insert">
						<input type="submit" name="insert" value="Save" class="pure-button pure-button-primary">
					</div>

				</fieldset>
			</form>
			<?php
		break;
	}
?>
</div>

