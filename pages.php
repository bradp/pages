<?php
/**
 * Plugin Name: Pages
 * Description: Quickly and easily view your recently modified pages in the dashboard.
 * Version:     1.1.0
 * Author:      Brad Parbs
 * Author URI:  https://bradparbs.com/
 * License:     GPLv2
 * Text Domain: pages
 * Domain Path: /lang/
 *
 * @package pages
 */

namespace Pages;

use WP_Query;

add_action( 'wp_dashboard_setup', __NAMESPACE__ . '\\add_dashboard_widget' );

/**
 * Add new dashboard widget with list of recently modified pages.
 */
function add_dashboard_widget() {
	$name = sprintf(
		'<span><span class="dashicons %s" style="padding-right: 10px"></span>%s</span>',
		apply_filters( 'pages_widget_icon', 'dashicons-admin-page' ),
		apply_filters( 'pages_widget_title', esc_attr__( 'Recent Pages', 'pages' ) )
	);

	wp_add_dashboard_widget( 'pages', $name, __NAMESPACE__ . '\\dashboard_widget' );
}

/**
 * Add dashboard widget for recently modified pages.
 */
function dashboard_widget() {
	$query_args = apply_filters( 'pages_widget_query_args', [
		'post_type'      => 'page',
		'orderby'        => 'modified',
		'post_status'    => 'publish',
		'order'          => 'DESC',
		'posts_per_page' => 25,
		'no_found_rows'  => true,
	] );

	$posts     = new WP_Query( $query_args );
	$pages = get_pages_posts( $posts );

	printf(
		'<div id="pages-posts-widget-wrapper">
			<div id="pages-posts-widget" class="activity-block" style="padding-top: 0;">
				<ul>%s</ul>
			</div>
		</div>',
		display_pages_in_widget( $pages ) // phpcs:ignore
	);
}

/**
 * Get the pages to display in the dashboard widget.
 *
 * @param WP_Query $posts WP_Query object.
 *
 * @return array Array of pages.
 */
function get_pages_posts( $posts ) {
	$pages = [];

	if ( $posts->have_posts() ) {
		while ( $posts->have_posts() ) {
			$posts->the_post();

			$add_to_pages = apply_filters( 'pages_show_in_widget', [
				'ID'      => get_the_ID(),
				'title'   => get_the_title(),
				'date'    => gmdate( 'F j, g:ia', get_the_time( 'U' ) ),
				'preview' => get_preview_post_link(),
			] );

			if ( isset( $add_to_pages ) ) {
				$pages[] = $add_to_pages;
			}
		}
	}

	return $pages;
}

/**
 * Display pages in widget.
 *
 * @param array $posts Post data.
 *
 * @return string Output of post data.
 */
function display_pages_in_widget( $posts ) {
	$output = '';

	foreach ( $posts as $post ) {
		$output .= sprintf(
			'<li><em style="%4$s">%1$s</em> <a href="%2$s">%3$s</a></li>',
			isset( $post['date'] ) ? $post['date'] : '',
			isset( $post['preview'] ) ? $post['preview'] : '',
			isset( $post['title'] ) ? $post['title'] : '',
			'display: inline-block; margin-right: 5px; min-width: 125px; color: #646970;'
		);
	}

	return $output;
}
