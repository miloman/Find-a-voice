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
        $tbl_age = $db_prefix . "lfm_ages";
        $tbl_media = $db_prefix . "lfm_media_files";

	    $todo = $_REQUEST['todo'];
	    switch($todo) {
		    case "insert":
		    	if ($_POST['lfm_age'] != '') {
				    $age = ucwords(strtolower($_POST['lfm_age']));
				    // check if already exists
				    $sql = "select ages from $tbl_age where age = '$age'";
				    $db_data = $wpdb->get_results($sql, 'ARRAY_A');
				    
				    if (count($db_data) == 0) {
				        $wpdb->insert(
				                $tbl_age, //table
				                array('age' => $age), //data
				                array('%s') //data format			
				        );
				        $msg .= "Age group added";
				        $msg_class = "updated";
				    } else {
					    $msg .= "Age group already exists";
					    $msg_class = "error";
				    }
		    	}
		    break;
		    case "delete":
		    	if (isset($_REQUEST['ageid']) && ($_REQUEST['ageid'] != '')) {
			    	$age_id = $_REQUEST["ageid"];
			    	// check if any media is associated with this ageid
			    	$sql = "select count(id_media) as total from $tbl_media where age = $age_id";
			    	$db_data = $wpdb->get_results($sql, 'ARRAY_A');
			    	if ($db_data[0]['total'] == 0) {
				    	$wpdb->delete(
					    	$tbl_age,
					    	array("id_age" => $age_id)
				    	);
				    	$msg .= "Age group deleted";
				    	$msg_class = "updated";
			    	} else {
				    	$msg .= "Not possible to delete as there are media files associated with this age group";
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
				'age' => 'Age Group'
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
				case 'age':
					return $item[$column_name];
				default:
					return print_r($item, true);
			}
		}
		
		
		function get_sortable_columns() {
			$sortable_columns = array(
				'age' => array('age', false)
			);
			
			return $sortable_columns;
		}
		
				
		function column_age($item) {
			$actions = array(
//				'edit' => sprintf('<a href="?page=%s&todo=%s&ageid=%s">Edit</a>', $_REQUEST['page'], 'edit', $item['id_age']),
				'delete' => sprintf('<a href="?page=%s&todo=%s&ageid=%s">Delete</a>', $_REQUEST['page'], 'delete', $item['id_age'])
			);

			return sprintf('%1$s %2$s', $item['age'], $this->row_actions($actions));
		}
		
	 
		function get_data($per_page, $current_page) {
			global $wpdb;
			$db_prefix = $wpdb->prefix;
			$offset = ($current_page - 1) * $per_page;

			// if no sort, default to id
			$orderby = (!empty($_GET['orderby'])) ? $_GET['orderby'] : 'id_age';
			
			// if no order, default to asc
			$order = (!empty($_GET['order'])) ? $_GET['order'] : 'asc';
			
			$sql = "select * from {$db_prefix}lfm_ages order by $orderby $order limit $offset, $per_page";
			$db_data = $wpdb->get_results($sql, 'ARRAY_A');

			return $db_data;
		}
		
		function get_total() {
			global $wpdb;
			$db_prefix = $wpdb->prefix;
			
			$sql = "select count(age) as total from {$db_prefix}lfm_ages";
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
			return sprintf('<input type="checkbox" name="age[]" value="%s">', $item['id_age']);
		}

	}

		
	$ageTable = new findavoice_table();		
	$ageTable->prepare_items();
	$ageTable->display();
	
	
	
?>

<h2 style="margin-top: 50px;">Add another age group</h2>
<?php if (isset($msg)): ?><div class="<?= $msg_class; ?>"><p><?= $msg; ?></p></div><?php endif; ?>
<form method="post" action="<?= $_SERVER['REQUEST_URI']; ?>">
	<input type="text" name="lfm_age" style="height: 28px;">
	<input type="hidden" name="todo" value="insert">
	<input type="submit" name="insert" value="Save" class="button">
</form>
</div>

