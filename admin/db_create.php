<?php
	global $wpdb;
	
	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	$charset_collate = $wpdb->get_charset_collate();

	$table_name = $wpdb->prefix . 'lfm_voice_talents';	
	$sql = "CREATE TABLE IF NOT EXISTS  $table_name (
			id_voice_talent INT NOT NULL AUTO_INCREMENT,
			talent_name VARCHAR(255) NULL,
			talent_gender INT NULL,
			talent_age INT NULL,
			image_location VARCHAR(45) NULL,
			notes BLOB NULL,
			status VARCHAR(1) NULL,
			PRIMARY KEY  (id_voice_talent)
			) $charset_collate;";
			
	dbDelta( $sql );		
	
	

	$table_name = $wpdb->prefix . 'lfm_media_files';	
	$sql = "CREATE TABLE IF NOT EXISTS  $table_name (
			id_media INT NOT NULL AUTO_INCREMENT,
			id_voice_talent INT NOT NULL,
			description BLOB NULL,
			accent INT NULL,
			language INT NULL,
			platform INT NULL,
			tone INT NULL,
			style INT NULL,
			file_location VARCHAR(100) NULL,
			PRIMARY KEY  (id_media)
			) $charset_collate;";
			
	dbDelta( $sql );		
	
	

	$table_name = $wpdb->prefix . 'lfm_tones';
	$sql = "CREATE TABLE IF NOT EXISTS  $table_name (
			id_tone INT NOT NULL AUTO_INCREMENT,
			tone VARCHAR(45) NULL,
			PRIMARY KEY  (id_tone)
			) $charset_collate;";
			
	dbDelta( $sql );		
	
	

	$table_name = $wpdb->prefix . 'lfm_accents';
	$sql = "CREATE TABLE IF NOT EXISTS  $table_name (
			id_accent INT NOT NULL AUTO_INCREMENT,
			accent VARCHAR(45) NULL,
			PRIMARY KEY  (id_accent)
			) $charset_collate;";
			
	dbDelta( $sql );		
	
	

	$table_name = $wpdb->prefix . 'lfm_styles';
	$sql = "CREATE TABLE IF NOT EXISTS  $table_name (
			id_style INT NOT NULL AUTO_INCREMENT,
			style VARCHAR(45) NULL,
			PRIMARY KEY  (id_style)
			) $charset_collate;";
			
	dbDelta( $sql );		
	
	

	$table_name = $wpdb->prefix . 'lfm_ages';
	$sql = "CREATE TABLE IF NOT EXISTS  $table_name (
			id_age INT NOT NULL AUTO_INCREMENT,
			age VARCHAR(45) NULL,
			PRIMARY KEY  (id_age)
			) $charset_collate;";
			
	dbDelta( $sql );		
	
	

	$table_name = $wpdb->prefix . 'lfm_languages';
	$sql = "CREATE TABLE IF NOT EXISTS  $table_name (
			id_language INT NOT NULL AUTO_INCREMENT,
			language VARCHAR(45) NULL,
			PRIMARY KEY  (id_language)
			) $charset_collate;";
			
	dbDelta( $sql );		
	
	

	$table_name = $wpdb->prefix . 'lfm_genders';
	$sql = "CREATE TABLE IF NOT EXISTS  $table_name (
			id_gender INT NOT NULL AUTO_INCREMENT,
			gender VARCHAR(45) NULL,
			PRIMARY KEY  (id_gender)
			) $charset_collate;";
			
	dbDelta( $sql );		
	
	

	$table_name = $wpdb->prefix . 'lfm_platforms';
	$sql = "CREATE TABLE IF NOT EXISTS  $table_name (
			id_platform INT NOT NULL AUTO_INCREMENT,
			platform VARCHAR(45) NULL,
			PRIMARY KEY  (id_platform)
			) $charset_collate;";
			
	dbDelta( $sql );		

?>