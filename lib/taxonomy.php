<?php

add_action( 'init', 'mai_register_portfolio_type_taxonomy' );
/**
 * Registers "portfolio-type" taxonomy for the portfolio post type.
 *
 * @since 1.0.0
 *
 * @return void
 */
function mai_register_portfolio_type_taxonomy() {
	$labels = [
		'name'                       => _x( 'Portfolio Types', 'taxonomy general name', 'genesis-portfolio-pro' ),
		'singular_name'              => _x( 'Portfolio Type', 'taxonomy singular name', 'genesis-portfolio-pro' ),
		'search_items'               => __( 'Search Portfolio Types', 'genesis-portfolio-pro' ),
		'popular_items'              => __( 'Popular Portfolio Types', 'genesis-portfolio-pro' ),
		'all_items'                  => __( 'All Types', 'genesis-portfolio-pro' ),
		'edit_item'                  => __( 'Edit Portfolio Type', 'genesis-portfolio-pro' ),
		'update_item'                => __( 'Update Portfolio Type', 'genesis-portfolio-pro' ),
		'add_new_item'               => __( 'Add New Portfolio Type', 'genesis-portfolio-pro' ),
		'new_item_name'              => __( 'New Portfolio Type Name', 'genesis-portfolio-pro' ),
		'separate_items_with_commas' => __( 'Separate Portfolio Types with commas', 'genesis-portfolio-pro' ),
		'add_or_remove_items'        => __( 'Add or remove Portfolio Types', 'genesis-portfolio-pro' ),
		'choose_from_most_used'      => __( 'Choose from the most used Portfolio Types', 'genesis-portfolio-pro' ),
		'not_found'                  => __( 'No Portfolio Types found.', 'genesis-portfolio-pro' ),
		'menu_name'                  => __( 'Portfolio Types', 'genesis-portfolio-pro' ),
		'parent_item'                => null,
		'parent_item_colon'          => null,
	];

	$args = [
		'labels'                => $labels,
		'exclude_from_search'   => true,
		'has_archive'           => true,
		'hierarchical'          => true,
		'rewrite'               => [
			'slug'       => _x( 'portfolio-type', 'portfolio-type slug', 'genesis-portfolio-pro' ),
			'with_front' => false,
		],
		'show_ui'               => true,
		'show_tagcloud'         => false,
		'show_in_rest'          => true,
		'rest_base'             => 'portfolio-type',
		'rest_controller_class' => 'WP_REST_Terms_Controller',
	];

	register_taxonomy(
		'portfolio-type',
		'portfolio',
		$args
	);
}
