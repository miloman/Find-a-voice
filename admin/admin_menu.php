<style>
	#wpfooter {
		position: relative !important;
	}
</style>


<?php
	$page_title = 'Dashboard'; //page title
	$menu_title = 'Find A Voice'; //menu title
	$capability = 'manage_options';
	$menu_slug = 'lfm_find-a-voice'; //menu slug
	$function = 'show_lfm_dashboard'; //function
	$menu_icon_name = 'dashicons-microphone';
	$menu_position = 20;
    add_menu_page($page_title, $menu_title, $capability, $menu_slug, $function, $menu_icon_name, $menu_position);

    $parent_slug = $menu_slug;
	$sub_page_title = 'Dashboard'; //page title
	$sub_menu_title = 'Dashboard'; //menu title
	$sub_capability = 'manage_options';
	$sub_menu_slug = 'lfm_find-a-voice'; //menu slug
	$sub_function = 'show_lfm_dashboard'; //function
	$sub_menu_icon_name = plugin_dir_url(__FILE__) . 'images/icon_wporg.png';
	$sub_menu_position = 1;
	add_submenu_page($parent_slug, $sub_page_title, $sub_menu_title, $sub_capability, $sub_menu_slug, $sub_function, $sub_menu_icon_name, $sub_menu_position);

    $parent_slug = $menu_slug;
    $sub_page_title = 'Voice Talents';
    $sub_menu_title = 'Voice Talents';
    $sub_capability = 'manage_options';
    $sub_menu_slug = 'lfm_voice_talents';
    $sub_function = 'show_lfm_voice_talents';
    $sub_menu_icon_name = plugin_dir_url(__FILE__) . 'images/icon_wporg.png';
    $sub_menu_position = 2;
    add_submenu_page($parent_slug, $sub_page_title, $sub_menu_title, $sub_capability, $sub_menu_slug, $sub_function, $sub_menu_icon_name, $sub_menu_position);

    $parent_slug = $menu_slug;
    $sub_page_title = 'Uploaded Media Files';
    $sub_menu_title = 'Media';
    $sub_capability = 'manage_options';
    $sub_menu_slug = 'lfm_media';
    $sub_function = 'show_lfm_media';
    $sub_menu_icon_name = plugin_dir_url(__FILE__) . 'images/icon_wporg.png';
    $sub_menu_position = 2;
    add_submenu_page($parent_slug, $sub_page_title, $sub_menu_title, $sub_capability, $sub_menu_slug, $sub_function, $sub_menu_icon_name, $sub_menu_position);
    
    $parent_slug = $menu_slug;
    $sub_page_title = 'Tones';
    $sub_menu_title = 'Tones';
    $sub_capability = 'manage_options';
    $sub_menu_slug = 'lfm_tones';
    $sub_function = 'show_lfm_tones';
    $sub_menu_icon_name = plugin_dir_url(__FILE__) . 'images/icon_wporg.png';
    $sub_menu_position = 2;
    add_submenu_page($parent_slug, $sub_page_title, $sub_menu_title, $sub_capability, $sub_menu_slug, $sub_function, $sub_menu_icon_name, $sub_menu_position);
    
    $parent_slug = $menu_slug;
    $sub_page_title = 'Accents';
    $sub_menu_title = 'Accents';
    $sub_capability = 'manage_options';
    $sub_menu_slug = 'lfm_accents';
    $sub_function = 'show_lfm_accents';
    $sub_menu_icon_name = plugin_dir_url(__FILE__) . 'images/icon_wporg.png';
    $sub_menu_position = 2;
    $hook = add_submenu_page($parent_slug, $sub_page_title, $sub_menu_title, $sub_capability, $sub_menu_slug, $sub_function, $sub_menu_icon_name, $sub_menu_position);
    add_action("load-$hook", 'add_accent_options');
    add_filter('set_screen_option', 'accent_table_set_option', 10, 3);

    $parent_slug = $menu_slug;
    $sub_page_title = 'Styles';
    $sub_menu_title = 'Styles';
    $sub_capability = 'manage_options';
    $sub_menu_slug = 'lfm_styles';
    $sub_function = 'show_lfm_styles';
    $sub_menu_icon_name = plugin_dir_url(__FILE__) . 'images/icon_wporg.png';
    $sub_menu_position = 2;
    add_submenu_page($parent_slug, $sub_page_title, $sub_menu_title, $sub_capability, $sub_menu_slug, $sub_function, $sub_menu_icon_name, $sub_menu_position);
    
    $parent_slug = $menu_slug;
    $sub_page_title = 'Ages';
    $sub_menu_title = 'Ages';
    $sub_capability = 'manage_options';
    $sub_menu_slug = 'lfm_ages';
    $sub_function = 'show_lfm_ages';
    $sub_menu_icon_name = plugin_dir_url(__FILE__) . 'images/icon_wporg.png';
    $sub_menu_position = 2;
    add_submenu_page($parent_slug, $sub_page_title, $sub_menu_title, $sub_capability, $sub_menu_slug, $sub_function, $sub_menu_icon_name, $sub_menu_position);
    
    $parent_slug = $menu_slug;
    $sub_page_title = 'Genders';
    $sub_menu_title = 'Genders';
    $sub_capability = 'manage_options';
    $sub_menu_slug = 'lfm_genders';
    $sub_function = 'show_lfm_genders';
    $sub_menu_icon_name = plugin_dir_url(__FILE__) . 'images/icon_wporg.png';
    $sub_menu_position = 2;
    add_submenu_page($parent_slug, $sub_page_title, $sub_menu_title, $sub_capability, $sub_menu_slug, $sub_function, $sub_menu_icon_name, $sub_menu_position);
    
    $parent_slug = $menu_slug;
    $sub_page_title = 'Languages';
    $sub_menu_title = 'Languages';
    $sub_capability = 'manage_options';
    $sub_menu_slug = 'lfm_languages';
    $sub_function = 'show_lfm_languages';
    $sub_menu_icon_name = plugin_dir_url(__FILE__) . 'images/icon_wporg.png';
    $sub_menu_position = 2;
    add_submenu_page($parent_slug, $sub_page_title, $sub_menu_title, $sub_capability, $sub_menu_slug, $sub_function, $sub_menu_icon_name, $sub_menu_position);
    
    $parent_slug = $menu_slug;
    $sub_page_title = 'Platforms';
    $sub_menu_title = 'Platforms';
    $sub_capability = 'manage_options';
    $sub_menu_slug = 'lfm_platforms';
    $sub_function = 'show_lfm_platforms';
    $sub_menu_icon_name = plugin_dir_url(__FILE__) . 'images/icon_wporg.png';
    $sub_menu_position = 2;
    add_submenu_page($parent_slug, $sub_page_title, $sub_menu_title, $sub_capability, $sub_menu_slug, $sub_function, $sub_menu_icon_name, $sub_menu_position);
?>