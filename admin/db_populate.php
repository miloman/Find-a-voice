<?php
	global $wpdb;
	
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	$table_name = $wpdb->prefix . 'lfm_tones';
	$wpdb->replace($table_name, array('tone' => 'Mature'));
	$wpdb->replace($table_name, array('tone' => 'Upbeat'));

	$table_name = $wpdb->prefix . 'lfm_accents';
	$wpdb->replace($table_name, array('accent' => 'British'));
	$wpdb->replace($table_name, array('accent' => 'Australian'));
	$wpdb->replace($table_name, array('accent' => 'New Zealand'));

	$table_name = $wpdb->prefix . 'lfm_styles';
	$wpdb->replace($table_name, array('style' => 'Normal'));
	$wpdb->replace($table_name, array('style' => 'Santa'));
	$wpdb->replace($table_name, array('style' => 'Character'));

	$table_name = $wpdb->prefix . 'lfm_ages';
	$wpdb->replace($table_name, array('age' => 'Young Adult'));
	$wpdb->replace($table_name, array('age' => 'Adult'));
	$wpdb->replace($table_name, array('age' => 'Middle Age'));
	$wpdb->replace($table_name, array('age' => 'Senior'));

	$table_name = $wpdb->prefix . 'lfm_languages';
	$wpdb->replace($table_name, array('language' => 'English'));
	$wpdb->replace($table_name, array('language' => 'Spanish'));
	$wpdb->replace($table_name, array('language' => 'Italian'));
	$wpdb->replace($table_name, array('language' => 'Chinese'));

	$table_name = $wpdb->prefix . 'lfm_platforms';
	$wpdb->replace($table_name, array('platform' => 'Radio'));
	$wpdb->replace($table_name, array('platform' => 'Phone'));
	$wpdb->replace($table_name, array('platform' => 'Video'));

	$table_name = $wpdb->prefix . 'lfm_genders';
	$wpdb->replace($table_name, array('gender' => 'Male'));
	$wpdb->replace($table_name, array('gender' => 'Female'));
?>