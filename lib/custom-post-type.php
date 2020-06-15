<?php

add_action( 'init', 'mai_register_portfolio_cpt' );
/**
 * Registers the "portfolio" post type.
 *
 * @since 1.0.0
 *
 * @return void
 */
function mai_register_portfolio_cpt() {
	$labels = [
		'name'               => _x( 'Portfolio Items', 'post type general name', 'genesis-portfolio-pro' ),
		'singular_name'      => _x( 'Portfolio Item', 'post type singular name', 'genesis-portfolio-pro' ),
		'menu_name'          => _x( 'Portfolio Items', 'admin menu', 'genesis-portfolio-pro' ),
		'name_admin_bar'     => _x( 'Portfolio Item', 'add new on admin bar', 'genesis-portfolio-pro' ),
		'add_new'            => _x( 'Add New', 'Portfolio Item', 'genesis-portfolio-pro' ),
		'add_new_item'       => __( 'Add New Portfolio Item', 'genesis-portfolio-pro' ),
		'new_item'           => __( 'New Portfolio Item', 'genesis-portfolio-pro' ),
		'edit_item'          => __( 'Edit Portfolio Item', 'genesis-portfolio-pro' ),
		'view_item'          => __( 'View Portfolio Item', 'genesis-portfolio-pro' ),
		'all_items'          => __( 'All Portfolio Items', 'genesis-portfolio-pro' ),
		'search_items'       => __( 'Search Portfolio Items', 'genesis-portfolio-pro' ),
		'parent_item_colon'  => __( 'Parent Portfolio Items:', 'genesis-portfolio-pro' ),
		'not_found'          => __( 'No Portfolio Items found.', 'genesis-portfolio-pro' ),
		'not_found_in_trash' => __( 'No Portfolio Items found in Trash.', 'genesis-portfolio-pro' ),
	];

	$args = [
		'labels'       => $labels,
		'has_archive'  => true,
		'hierarchical' => true,
		'menu_icon'    => 'dashicons-format-gallery',
		'public'       => true,
		'show_in_rest' => true,
		'supports'     => [
			'title',
			'editor',
			'author',
			'thumbnail',
			'excerpt',
			'trackbacks',
			'custom-fields',
			'revisions',
			'page-attributes',
			'genesis-seo',
			'genesis-cpt-archives-settings',
		],
		'taxonomies'   => [ 'portfolio-type' ],
	];

	register_post_type(
		'portfolio',
		$args
	);
}
