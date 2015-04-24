<?php

/*
Plugin Name: About Settings & Widget
Description: About&hellip;
Version: 1.2
Author: Crowd Favorite
Author URI: http://crowdfavorite.com
*/

define('CFCP_ABOUT_VERSION', '1.2');
define('CFCP_ABOUT_SETTINGS', 'cfcp_about_settings');

// Init
include_once('widget/about.php');

function cfcp_about_admin_init() {
	global $pagenow, $plugin_page;
	if ($pagenow == 'themes.php' && $plugin_page == 'about.php') {
		wp_register_script(
			'jquery-popover', 
			get_template_directory_uri().'/assets/js/cf-popover/jquery.cf.popover.min.js',
			array('jquery', 'jquery-ui-position'),
			CFCP_ABOUT_VERSION
		);
		wp_enqueue_script(
			'o-type-ahead',
			get_template_directory_uri().'/assets/js/o-type-ahead.js',
			array('jquery'),
			CFCP_ABOUT_VERSION
		);
		wp_enqueue_script(
			'cfcp-about-admin-js',
			get_template_directory_uri().'/functions/about/js/about-admin.js',
			array('jquery', 'jquery-ui-sortable', 'jquery-ui-position', 'jquery-popover'),
			CFCP_ABOUT_VERSION
		);
		wp_localize_script(
			'cfcp-about-admin-js', 
			'cfcp_about_settings', 
			array(
				'image_del_confirm' => __('Remove this image from the carousel?', 'favepersonal'),
				'favicon_fetch_error' => __('Could not fetch the favicon for: ', 'favepersonal'),
				'add' => __('Add', 'favepersonal'),
				'loading' => __('Loading...', 'favepersonal'),
				'err_link_title' => __('Please enter a title.', 'favepersonal'),
				'err_link_url' => __('Please enter a valid URL (http://...).', 'favepersonal'),
			)
		);
	}
	register_setting(CFCP_ABOUT_SETTINGS, CFCP_ABOUT_SETTINGS, 'cfcp_about_settings_validate');
}
add_action('admin_init', 'cfcp_about_admin_init');

/**
 * Conditionally enqueue the cycle script only if the about carousel is being used
 *
 * @return void
 */
function cfcp_about_module_carousel_enqueue() {
	$settings = cfcp_about_get_settings();
	$images_count = 0;
	if (isset($settings['images'])) {
		$images_count = count($settings['images']);
	}
	
	if (
		!is_admin()
		&& $images_count > 1
		&& is_active_widget(null, null, 'cfcp-about')
	) {
		wp_enqueue_script('jquery-cycle'); // registered in the theme's functions.php file
	}
}
add_action('wp', 'cfcp_about_module_carousel_enqueue', 11);

// Ajax

/**
 * Generic Ajax handler
 *
 * @return void
 */
function cfcp_about_admin_ajax() {
	if (!empty($_POST['cfcp_about_action'])) {
		switch($_POST['cfcp_about_action']) {
			case 'cfcp_image_search':
				$results = cfcp_about_image_search(array(
					'key' => $_POST['key'],
					'term' => $_POST['cfp-img-search-term'],
					'exclude' => (!empty($_POST['cfcp_search_exclude']) ? array_map('intval', $_POST['cfcp_search_exclude']) : array())
				));

				$ret = array(
					'success' => (!empty($results) ? true : false),
					'key' => $_POST['key'],
					'html' => (!empty($results) ? $results : '<div class="cfp-img-search-no-results">'.__('No results found.', 'favepersonal').'</div>')
				);
				
				break;
		}
		header('Content-Type: application/json');
		echo json_encode($ret);
		exit;
	}
}
add_action('wp_ajax_cfcp_about', 'cfcp_about_admin_ajax');

/**
 * Perform image search
 *
 * @param array $params 
 * @return array
 */
function cfcp_about_image_search($params) {
	$imgs = new WP_Query(array(
		's' => trim(stripslashes($params['term'])),
		'posts_per_page' => 12,
		'post_type' => 'attachment',
		'post_mime_type' => 'image',
		'post_status' => 'inherit',
		'post__not_in' => (!empty($params['exclude']) ? (array) $params['exclude'] : array()),
		'order' => 'ASC',
		'fields' => 'ids'
	));

	$ret = '';
	if (!empty($imgs->posts)) {
		$post_type_object = get_post_type_object('attachment');
		$img_size = 'tiny-img';
		foreach ($imgs->posts as $img_id) {
			$ret .= '<li class="cfp-search-result">'.cfct_template_content(
				'functions/about/views',
				'image-item',
				compact('img_id', 'post_type_object', 'img_size')
			).'</li>';
		}
	}
	
	if (!empty($ret)) {
		$ret = '<ul>'.$ret.'</ul>';
	}

	return $ret;
}

// Admin Page

function cfcp_about_admin_menu() {
	add_theme_page(
		__('Bio Widget', 'favepersonal'),
		__('Bio Widget', 'favepersonal'),
		'edit_theme_options',
		basename(__FILE__),
		'cfcp_about_admin_form'
	);
}
add_action('admin_menu', 'cfcp_about_admin_menu');

// Add link to the admin menu bar
function cfcp_about_admin_bar() {
	global $wp_admin_bar;
	if (current_user_can('edit_theme_options')) {
		$wp_admin_bar->add_menu(array(
			'id' => 'cfcp-about',
			'title' => __('Bio Widget', 'favepersonal'),
			'href' => admin_url('themes.php?page='.basename(__FILE__)),
			'parent' => 'appearance'
		));
	}
}
add_action('wp_before_admin_bar_render', 'cfcp_about_admin_bar');

function cfcp_about_admin_form() {
	$settings = cfcp_about_get_settings();
	include('views/admin-view.php');
}

// Settings

function cfcp_about_settings_validate($settings) {
	// this is an array of attachment post-ids
	if (!empty($settings['images'])) {
		$settings['images'] = array_map('intval', $settings['images']);
	}
	
	// links processing - for consistency of editing and to make 
	// sortables easy each link is json_encoded in to a single
	// hidden element
	if (!empty($settings['links'])) {
		foreach ($settings['links'] as &$link) {
			if (!is_array($link)) {
				$link = json_decode($link, true);
			}
		}
	}
	
	return $settings;
}

// Utility
function cfcp_about_get_settings() {
	return get_option(CFCP_ABOUT_SETTINGS, array(
		'title' => null,
		'description' => null,
		'images' => array(),
		'links' => array()
	));
}
