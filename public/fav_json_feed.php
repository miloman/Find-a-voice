<?php
	global $wpdb;
	$db_prefix = $wpdb->prefix;

	$current_page = 1;
//	$per_page = 10;
	
	$offset = ($current_page - 1) * $per_page;

	// if no sort, default to accent
	$orderby = (!empty($_GET['orderby'])) ? $_GET['orderby'] : 'talent_name';
	
	// if no order, default to asc
	$order = (!empty($_GET['order'])) ? $_GET['order'] : 'asc';

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
	$db_data = $wpdb->get_results($sql);

	echo(json_encode($db_data));

?>