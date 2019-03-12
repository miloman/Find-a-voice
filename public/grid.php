<?php
	global $wpdb;
	$db_prefix = $wpdb->prefix;

	$results_accents = $wpdb->get_results( "SELECT id_accent, accent FROM {$wpdb->prefix}lfm_accents ORDER BY accent");
	$results_language = $wpdb->get_results( "SELECT id_language, language FROM {$wpdb->prefix}lfm_languages ORDER BY language");
	$results_platform = $wpdb->get_results( "SELECT id_platform, platform FROM {$wpdb->prefix}lfm_platforms ORDER BY platform");
	$results_style = $wpdb->get_results( "SELECT id_style, style FROM {$wpdb->prefix}lfm_styles ORDER BY style");
	$results_tone = $wpdb->get_results( "SELECT id_tone, tone FROM {$wpdb->prefix}lfm_tones ORDER BY tone");

?>
<!--
<link href="/wp-content/plugins/find-a-voice/public/css/simpleGridTemplate.css" rel="stylesheet" type="text/css">
-->

<!-- Main Container -->
<div class="fav_container"> 
  <!-- Hero Section -->
  <div class="intro select animated zoomIn">
  	<form>
  		<select class="sel">
  			<option class="favoption">Gender</option>
  		</select>
  		<span class="pure-control-group">
  		<select name="language" class="sel">
  		  <option value="">Language</option>
  		  <?php foreach ($results_language as $language) { echo('<option value="'.$language->id_language.'">'.$language->language.'</option>'); } ?>
	    </select>
  		</span><span class="pure-control-group">
  		<select name="accent" class="sel">
  		  <option value="">Accent</option>
  		  <?php foreach ($results_accents as $accent) { echo('<option value="'.$accent->id_accent.'">'.$accent->accent.'</option>'); } ?>
	    </select>
  		</span><span class="pure-control-group">
  		<select name="style" class="sel">
  		  <option value="">Style</option>
  		  <?php foreach ($results_style as $style) { echo('<option value="'.$style->id_style.'">'.$style->style.'</option>'); } ?>
		  </select>
  		</span><span class="pure-control-group">
  		<select name="tone" class="sel">
  		  <option value="">Tone</option>
  		  <?php foreach ($results_tone as $tone) { echo('<option value="'.$tone->id_tone.'">'.$tone->tone.'</option>'); } ?>
		  </select>
  		</span>
  	</form>  
  </div>
  <!-- Stats Gallery Section -->
  <div class="gallery">
  
<?php
// for ($i = 0; $i < 10; $i++) {	
?>
   
 <?php 
	$current_page = 1;
//	$per_page = 10;
	
	$offset = ($current_page - 1) * $per_page;

	// if no sort, default to accent
	$orderby = (!empty($_GET['orderby'])) ? $_GET['orderby'] : 'talent_name';
	
	// if no order, default to asc
	$order = (!empty($_GET['order'])) ? $_GET['order'] : 'asc';

	$num_of_filters = 0;
	$filter = '';
	if (isset($_REQUEST['gender']) && ($_REQUEST['gender'] != '')) { 
		++$num_of_filters;
		$filter_prefix = $num_of_filters < 2 ? ' where ' : ' and ';
		$filter .= $filter_prefix . ' g.talent_gender = ' . $_REQUEST['gender'];
	}

	if (isset($_REQUEST['accent']) && ($_REQUEST['accent'] != '')) { 
		++$num_of_filters;
		$filter_prefix = $num_of_filters < 2 ? ' where ' : ' and ';
		$filter .= $filter_prefix . ' a.accent = ' . $_REQUEST['accent'];
	}

	if (isset($_REQUEST['language']) && ($_REQUEST['language'] != '')) { 
		++$num_of_filters;
		$filter_prefix = $num_of_filters < 2 ? ' where ' : ' and ';
		$filter .= $filter_prefix . ' a.language = ' . $_REQUEST['language'];
	}

	if (isset($_REQUEST['platform']) && ($_REQUEST['platform'] != '')) { 
		++$num_of_filters;
		$filter_prefix = $num_of_filters < 2 ? ' where ' : ' and ';
		$filter .= $filter_prefix . ' a.platform = ' . $_REQUEST['platform'];
	}

	if (isset($_REQUEST['style']) && ($_REQUEST['style'] != '')) { 
		++$num_of_filters;
		$filter_prefix = $num_of_filters < 2 ? ' where ' : ' and ';
		$filter .= $filter_prefix . ' a.style = ' . $_REQUEST['style'];
	}

	if (isset($_REQUEST['tone']) && ($_REQUEST['tone'] != '')) { 
		++$num_of_filters;
		$filter_prefix = $num_of_filters < 2 ? ' where ' : ' and ';
		$filter .= $filter_prefix . ' a.tone = ' . $_REQUEST['tone'];
	}

	// get voice talents
	$sql = "SELECT a.id_media, a.id_voice_talent, a.description, b.accent, c.language, d.platform, e.tone, f.style, g.talent_name, g.image_location, h.gender, i.age, j.guid, k.guid as talent_image
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
			left join {$db_prefix}posts k on g.image_location = k.ID
			$filter
			order by $orderby $order";
//			order by $orderby $order limit $offset, $per_page";
//	$db_data = $wpdb->get_results($sql);
	  $remote_response = wp_remote_get('https://voice.livingformusicgroup.com/?fav=123');
	  $remote_json = $remote_response['body'];
	  $db_data = json_decode($remote_json);
//echo $sql;

?>
<?php foreach ($db_data as $talent) { ?>   
    <div class="thumbnail hvr-grow">
		<div class="fav_image_container">
			<img src="<?php echo $talent->talent_image; ?>" class="fav_talent_image" width="100%">
		</div>
		<div class="fav_talent_name"><?php echo $talent->talent_name; ?></div>
		<div class="fav_tag">
			<div style="position: relative"><?php echo $talent->language . ' - ' .$talent->accent; ?></div>
		</div>
		<div class="btn_container">
			<?php echo do_shortcode( '[sc_embed_player fileurl="'.$talent->guid.'"]' ); ?>
			<a href="<?php echo $talent->guid; ?>" download>
				<input type="button" class="fav_download_btn">
			</a>
			<a href="#">
				<input type="button" class="fav_shoppingcart_btn">
			</a>
		</div>
    </div>
<?php } ?>    

  </div>
</div>