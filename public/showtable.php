<link rel="stylesheet" href="/wp-content/plugins/find-a-voice/css/pure/pure-min.css" >
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
<link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.10.1/bootstrap-table.min.css"> 
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css" integrity="sha384-mzrmE5qonljUremFsqc01SB46JvROS7bZs3IO2EmfFsd15uHvIt+Y8vEf7N7fWAU" crossorigin="anonymous">


<style>
	.talent-details {
		font-size: smaller;
	}
	.talents-list tbody tr td{
		vertical-align: middle;
	}
	.playlist_actions {
    width: 18px;
    height: 18px;
    display: inline-block;
    position: relative;
    padding-right: 2px;
    padding-bottom: 1px;
    margin: 0 5px 0 0;
	}
	
	.sml_col {
		width:30px;
	}
	
	@media only screen and (max-width: 900px) {
		.hide_on_mobile {
			display: none !important;
		}
		.big_on_mobile {
			display: none !important;
		}
	}
</style>

<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>    
<script src="//cdnjs.cloudflare.com/ajax/libs/bootstrap-table/1.10.1/bootstrap-table.min.js"></script>
        
<?php
	global $wpdb;
	$db_prefix = $wpdb->prefix;
	
	// get filters
	$results_gender = $wpdb->get_results( "SELECT id_gender, gender FROM {$wpdb->prefix}lfm_genders ORDER BY gender");
	$results_accents = $wpdb->get_results( "SELECT id_accent, accent FROM {$wpdb->prefix}lfm_accents ORDER BY accent");
	$results_language = $wpdb->get_results( "SELECT id_language, language FROM {$wpdb->prefix}lfm_languages ORDER BY language");
//	$results_platform = $wpdb->get_results( "SELECT id_platform, platform FROM {$wpdb->prefix}lfm_platforms ORDER BY platform");
	$results_style = $wpdb->get_results( "SELECT id_style, style FROM {$wpdb->prefix}lfm_styles ORDER BY style");
	$results_tone = $wpdb->get_results( "SELECT id_tone, tone FROM {$wpdb->prefix}lfm_tones ORDER BY tone");
	
?>	
<form class="pure-form">
    <fieldset>
        <legend>Find the perfect voice</legend>

		<select name="gender">
			<option value="">Gender</option>
		<?php foreach ($results_gender as $gender) {
				if ($gender->id_gender == $_REQUEST['gender']) {
					echo('<option value="'.$gender->id_gender.'" selected>'.$gender->gender.'</option>'); 
				} else {
					echo('<option value="'.$gender->id_gender.'">'.$gender->gender.'</option>'); 
				}
			} ?>		
		</select>
		
		<select name="accent">
			<option value="">Accent</option>
		<?php foreach ($results_accents as $accent) { 
				if ($accent->id_accent == $_REQUEST['accent']) {
					echo('<option value="'.$accent->id_accent.'" selected>'.$accent->accent.'</option>'); 
				} else {
					echo('<option value="'.$accent->id_accent.'">'.$accent->accent.'</option>'); 
				}
			} ?>		
		</select>

		<select name="language">
			<option value="">Language</option>
		<?php foreach ($results_language as $language) { 
				if ($language->id_language == $_REQUEST['language']) {
					echo('<option value="'.$language->id_language.'" selected>'.$language->language.'</option>'); 
				} else {
					echo('<option value="'.$language->id_language.'">'.$language->language.'</option>'); 
				}
			} ?>		
		</select>


<!--
		<select name="platform">
			<option value="">Platform</option>
		<?php foreach ($results_platform as $platform) { 
				if ($platform->id_platform == $_REQUEST['platform']) {
					echo('<option value="'.$platform->id_platform.'" selected>'.$platform->platform.'</option>'); 
				} else {
					echo('<option value="'.$platform->id_platform.'">'.$platform->platform.'</option>'); 
				}
			} ?>		
		</select>
-->
		<select name="tone">
			<option value="">Tone</option>
		<?php foreach ($results_tone as $tone) { 
				if ($tone->id_tone == $_REQUEST['tone']) {
					echo('<option value="'.$tone->id_tone.'" selected>'.$tone->tone.'</option>'); 
				} else {
					echo('<option value="'.$tone->id_tone.'">'.$tone->tone.'</option>'); 
				}
			} ?>		
		</select>

		<select name="style">
			<option value="">Style</option>
		<?php foreach ($results_style as $style) { 
				if ($style->id_style == $_REQUEST['style']) {
					echo('<option value="'.$style->id_style.'" selected>'.$style->style.'</option>'); 
				} else {
					echo('<option value="'.$style->id_style.'">'.$style->style.'</option>'); 
				}
			} ?>		
		</select>

        <button type="submit" class="pure-button pure-button-primary">Filter</button>
    </fieldset>
</form>

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
			$filter
			order by $orderby $order";
//			order by $orderby $order limit $offset, $per_page";
	$db_data = $wpdb->get_results($sql);
//echo $sql;

?>
<table class="talents-list" 
		data-toggle="table" 
		data-classes="table table-hover table-condensed table-borderless"
		data-striped="true"
		data-sort-name="Quality"
		data-sort-order="desc"
		data-pagination="true"
		data-card-view="false"
		data-click-to-select="false"
		data-select-item-name="myRadioName">
    <thead>
        <tr>
<!--            <th data-field="state" data-radio="true">&nbsp;</th> -->
            <th class="col-xs-0.5 ">&nbsp;</th>
            <th class="col-xs-auto col-md-auto col-lg-auto col-xl-auto">Name</th>
            <th class="col-xs-1 hide_on_mobile" data-field="age" data-sortable="true">Age</th>
            <th class="col-xs-1 hide_on_mobile" data-field="gender" data-sortable="true">Gender</th>
            <th class="col-xs-1 hide_on_mobile" data-field="accent" data-sortable="true">Accent</th>
            <th class="col-xs-1 hide_on_mobile" data-field="language" data-sortable="true">Language</th>
<!--            <th class="col-xs-1" data-field="platform" data-sortable="true">Platform</th> -->
            <th class="col-xs-1 hide_on_mobile" data-field="tone" data-sortable="true">Tone</th>
            <th class="col-xs-1 hide_on_mobile" data-field="style" data-sortable="true">Style</th>
            <th >&nbsp;</th>
        </tr>
    </thead>

    <tbody>
<?php foreach ($db_data as $talent) { ?>
        <tr id="tr-id-2" class="tr-class-2 talent-details">
<!--	        <td>&nbsp;</td> -->
	        <td><?php echo do_shortcode( '[sc_embed_player fileurl="'.$talent->guid.'"]' ); ?></td>
            <td><strong><?php echo $talent->talent_name; ?></strong><br><font size="10"><?php echo $talent->description; ?></font>
            </td>
            <td class="hide_on_mobile"><?php echo $talent->age; ?></td>
            <td class="hide_on_mobile"><?php echo $talent->gender; ?></td>
            <td class="hide_on_mobile"><?php echo $talent->accent; ?></td>
            <td class="hide_on_mobile"><?php echo $talent->language; ?></td>
<!--            <td><?php echo $talent->platform; ?></td> -->
            <td class="hide_on_mobile"><?php echo $talent->tone; ?></td>
            <td class="hide_on_mobile"><?php echo $talent->style; ?></td>
            <td nowrap><a class="playlist_actions" title="Download" href="<?php echo $talent->guid; ?> " download><span style="font-size: 15px; color: Dodgerblue;"><i class="fas fa-download"></i></span></a>
	            		  <a class="playlist_actions" title="Get a quote" href="?talent_name=<?php echo $talent->talent_name; ?>&media_file=<?php echo $talent->guid; ?>"><span style="font-size: 15px; color: Dodgerblue;"><i class="fas fa-shopping-cart"></i></span></a></td>
        </tr>
<?php } ?>
    </tbody>
</table>

<script>
function queryParams() {
    return {
        type: 'owner',
        sort: 'updated',
        direction: 'asc',
        per_page: 100,
        page: 1
    };
}
</script>

