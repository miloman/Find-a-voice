<div class="wrap">
	<h1 class="wp-heading-inline"><?= esc_html(get_admin_page_title()); ?></h1>
<!--
		<a href="%1$s" class="add-new-h2">Add new</a>
-->
<?php
/*
	if ( current_user_can( 'manage_options' ) ) {
		echo sprintf( '<a href="%1$s" class="add-new-h2">%2$s</a>',
			esc_url( menu_page_url( $this->edit_slug, false ) ),
			esc_html( __( 'Add New', 'lfm_accent' ) ) );
	}
*/


    
    if (isset($_REQUEST['todo'])) {
        global $wpdb;
        $db_prefix = $wpdb->prefix;
        $tbl_tone = $db_prefix . "lfm_tones";
        $tbl_media = $db_prefix . "lfm_media_files";

	    $todo = $_REQUEST['todo'];
	    switch($todo) {
		    case "insert":
		    	if ($_POST['lfm_tone'] != '') {
				    $tone = ucwords(strtolower($_POST['lfm_tone']));
				    // check if already exists
				    $sql = "select tone from $tbl_tone where tone = '$tone'";
				    $db_data = $wpdb->get_results($sql, 'ARRAY_A');
				    
				    if (count($db_data) == 0) {
				        $wpdb->insert(
				                $tbl_tone, //table
				                array('tone' => $tone), //data
				                array('%s') //data format			
				        );
				        $msg .= "Tone added";
				        $msg_class = "updated";
				    } else {
					    $msg .= "Tone already exists";
					    $msg_class = "error";
				    }
		    	}
		    break;
		    case "delete":
		    	if (isset($_REQUEST['toneid']) && ($_REQUEST['toneid'] != '')) {
			    	$tone_id = $_REQUEST["toneid"];
			    	// check if any media is associated with this toneid
			    	$sql = "select count(id_media) as total from $tbl_media where tone = $tone_id";
			    	$db_data = $wpdb->get_results($sql, 'ARRAY_A');
			    	if ($db_data[0]['total'] == 0) {
				    	$wpdb->delete(
					    	$tbl_tone,
					    	array("id_tone" => $tone_id)
				    	);
				    	$msg .= "Tone deleted";
				    	$msg_class = "updated";
			    	} else {
				    	$msg .= "Not possible to delete as there are media files associated with this tone";
				    	$msg_class = "error";
			    	}
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
				'tone' => 'Tone'
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
			$tbl_data = $this->get_data($per_page, $current_page);
			$this->items = $tbl_data;
			
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
				case 'tone':
					return $item[$column_name];
				default:
					return print_r($item, true);
			}
		}
		
		
		function get_sortable_columns() {
			$sortable_columns = array(
				'tone' => array('tone', false)
			);
			
			return $sortable_columns;
		}
		
				
		function column_tone($item) {
			$actions = array(
//				'edit' => sprintf('<a href="?page=%s&todo=%s&toneid=%s">Edit</a>', $_REQUEST['page'], 'edit', $item['id_tone']),
				'delete' => sprintf('<a href="?page=%s&todo=%s&toneid=%s">Delete</a>', $_REQUEST['page'], 'delete', $item['id_tone'])
			);

			return sprintf('%1$s %2$s', $item['tone'], $this->row_actions($actions));
		}
		
	 
		function get_data($per_page, $current_page) {
			global $wpdb;
			$db_prefix = $wpdb->prefix;
			$offset = ($current_page - 1) * $per_page;

			// if no sort, default to accent
			$orderby = (!empty($_GET['orderby'])) ? $_GET['orderby'] : 'tone';
			
			// if no order, default to asc
			$order = (!empty($_GET['order'])) ? $_GET['order'] : 'asc';
			
			$sql = "select * from {$db_prefix}lfm_tones order by $orderby $order limit $offset, $per_page";
			$db_data = $wpdb->get_results($sql, 'ARRAY_A');

			return $db_data;
		}
		
		function get_total() {
			global $wpdb;
			$db_prefix = $wpdb->prefix;
			
			$sql = "select count(tone) as total from {$db_prefix}lfm_tones";
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
			return sprintf('<input type="checkbox" name="tone[]" value="%s">', $item['id_tone']);
		}

	}

		
	$toneTable = new findavoice_table();		
	$toneTable->prepare_items();
	$toneTable->display();
	
	
	
?>

<h2 style="margin-top: 50px;">Add another tone</h2>
<?php if (isset($msg)): ?><div class="<?= $msg_class; ?>"><p><?= $msg; ?></p></div><?php endif; ?>
<form method="post" action="<?= $_SERVER['REQUEST_URI']; ?>">
	<input type="text" name="lfm_tone" style="height: 28px;">
	<input type="hidden" name="todo" value="insert">
	<input type="submit" name="insert" value="Save" class="button">
</form>
</div>

