<?php
/**
 * Plugin Name: Pages
 * Description: Quickly and easily view your pages in the dashboard.
 * Version:     1.0.0
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

// Add new dashboard widget with list of draft posts.
add_action(
	'wp_dashboard_setup',
	function () {
		wp_add_dashboard_widget(
			'pages',
			sprintf(
				'<span><span class="dashicons dashicons-admin-page" style="padding-right: 10px"></span>%s</span>',
				esc_attr__( 'Recent Pages', 'pages' )
			),
			__NAMESPACE__ . '\\dashboard_widget'
		);
	}
);

/**
 * Add dashboard widget for draft posts.
 */
function dashboard_widget() {
	$posts = new WP_Query(
		[
			'post_type'      => 'page',
			'orderby'        => 'modified',
			'post_status'    => 'publish',
			'order'          => 'DESC',
			'posts_per_page' => 25,
			'no_found_rows'  => true,
		]
	);

	$pages = [];

	if ( $posts->have_posts() ) {
		while ( $posts->have_posts() ) {
			$posts->the_post();

			$pages[] = [
				'ID'      => get_the_ID(),
				'title'   => get_the_title(),
				'date'    => gmdate( 'F j, g:ia', get_the_time( 'U' ) ),
				'preview' => get_preview_post_link(),
			];
		}
	}

	printf(
		'<div id="pages-widget-wrapper">
			<div id="pages-widget" class="activity-block" style="padding-top: 0;">
				<ul>%s</ul>
			</div>
		</div>',
		display_pages_in_widget( $pages ) // phpcs:ignore
	);
}
/**
 * Display draft posts in widget.
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
