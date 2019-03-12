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
        $tbl_accent = $db_prefix . "lfm_accents";
        $tbl_media = $db_prefix . "lfm_media_files";

	    $todo = $_REQUEST['todo'];
	    switch($todo) {
		    case "insert":
		    	if ($_POST['lfm_accent'] != '') {
				    $accent = ucwords(strtolower($_POST['lfm_accent']));
				    // check if accent already exists
				    $sql = "select accent from $tbl_accent where accent = '$accent'";
				    $db_data = $wpdb->get_results($sql, 'ARRAY_A');
				    
				    if (count($db_data) == 0) {
				        $wpdb->insert(
				                $tbl_accent, //table
				                array('accent' => $accent), //data
				                array('%s') //data format			
				        );
				        $msg .= "Accent added";
				        $msg_class = "updated";
				    } else {
					    $msg .= "Accent already exists";
					    $msg_class = "error";
				    }
		    	}
		    break;
		    case "delete":
		    	if (isset($_REQUEST['accentid']) && ($_REQUEST['accentid'] != '')) {
			    	$accent_id = $_REQUEST["accentid"];
			    	// check if any media is associated with this accentid
			    	$sql = "select count(id_media) as total from $tbl_media where accent = $accent_id";
			    	$db_data = $wpdb->get_results($sql, 'ARRAY_A');
			    	if ($db_data[0]['total'] == 0) {
				    	$wpdb->delete(
					    	$tbl_accent,
					    	array("id_accent" => $accent_id)
				    	);
				    	$msg .= "Accent deleted";
				    	$msg_class = "updated";
			    	} else {
				    	$msg .= "Not possible to delete as there are media files associated with this accent";
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
				'accent' => 'Accent'
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
				case 'accent':
					return $item[$column_name];
				default:
					return print_r($item, true);
			}
		}
		
		
		function get_sortable_columns() {
			$sortable_columns = array(
				'accent' => array('accent', false)
			);
			
			return $sortable_columns;
		}
		
				
		function column_accent($item) {
			$actions = array(
//				'edit' => sprintf('<a href="?page=%s&todo=%s&accentid=%s">Edit</a>', $_REQUEST['page'], 'edit', $item['id_accent']),
				'delete' => sprintf('<a href="?page=%s&todo=%s&accentid=%s">Delete</a>', $_REQUEST['page'], 'delete', $item['id_accent'])
			);

			return sprintf('%1$s %2$s', $item['accent'], $this->row_actions($actions));
		}
		
	 
		function get_data($per_page, $current_page) {
			global $wpdb;
			$db_prefix = $wpdb->prefix;
			$offset = ($current_page - 1) * $per_page;

			// if no sort, default to accent
			$orderby = (!empty($_GET['orderby'])) ? $_GET['orderby'] : 'accent';
			
			// if no order, default to asc
			$order = (!empty($_GET['order'])) ? $_GET['order'] : 'asc';
			
			$sql = "select * from {$db_prefix}lfm_accents order by $orderby $order limit $offset, $per_page";
			$db_data = $wpdb->get_results($sql, 'ARRAY_A');

			return $db_data;
		}
		
		function get_total() {
			global $wpdb;
			$db_prefix = $wpdb->prefix;
			
			$sql = "select count(accent) as total from {$db_prefix}lfm_accents";
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

		
	$accentTable = new findavoice_table();		
	$accentTable->prepare_items();
	$accentTable->display();
	
	
	
?>

<h2 style="margin-top: 50px;">Add another accent</h2>
<?php if (isset($msg)): ?><div class="<?= $msg_class; ?>"><p><?= $msg; ?></p></div><?php endif; ?>
<form method="post" action="<?= $_SERVER['REQUEST_URI']; ?>">
	<input type="text" name="lfm_accent" style="height: 28px;">
	<input type="hidden" name="todo" value="insert">
	<input type="submit" name="insert" value="Save" class="button">
</form>
</div>

