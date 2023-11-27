<?php

/**
 * Plugin Name:     Mai Portfolio
 * Plugin URI:      https://bizbudding.com/mai-design-pack/
 * Description:     A versatile and lightweight portfolio plugin for Mai Theme.
 * Version:         1.1.2
 *
 * Author:          BizBudding
 * Author URI:      https://bizbudding.com
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) exit;

// Must be at the top of the file.
use YahnisElsts\PluginUpdateChecker\v5\PucFactory;

/**
 * Main Mai_Portfolio_Plugin Class.
 *
 * @since 0.1.0
 */
final class Mai_Portfolio_Plugin {

	/**
	 * @var   Mai_Portfolio_Plugin The one true Mai_Portfolio_Plugin
	 * @since 0.1.0
	 */
	private static $instance;

	/**
	 * Main Mai_Portfolio_Plugin Instance.
	 *
	 * Insures that only one instance of Mai_Portfolio_Plugin exists in memory at any one
	 * time. Also prevents needing to define globals all over the place.
	 *
	 * @since   0.1.0
	 * @static  var array $instance
	 * @uses    Mai_Portfolio_Plugin::setup_constants() Setup the constants needed.
	 * @uses    Mai_Portfolio_Plugin::includes() Include the required files.
	 * @uses    Mai_Portfolio_Plugin::hooks() Activate, deactivate, etc.
	 * @see     Mai_Portfolio_Plugin()
	 * @return  object | Mai_Portfolio_Plugin The one true Mai_Portfolio_Plugin
	 */
	public static function instance() {
		if ( ! isset( self::$instance ) ) {
			// Setup the setup.
			self::$instance = new Mai_Portfolio_Plugin;
			// Methods.
			self::$instance->setup_constants();
			self::$instance->includes();
			self::$instance->hooks();
		}
		return self::$instance;
	}

	/**
	 * Throw error on object clone.
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since   0.1.0
	 * @access  protected
	 * @return  void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'mai-portfolio' ), '1.0' );
	}

	/**
	 * Disable unserializing of the class.
	 *
	 * @since   0.1.0
	 * @access  protected
	 * @return  void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden.
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'mai-portfolio' ), '1.0' );
	}

	/**
	 * Setup plugin constants.
	 *
	 * @access  private
	 * @since   0.1.0
	 * @return  void
	 */
	private function setup_constants() {
		// Plugin version.
		if ( ! defined( 'MAI_PORTFOLIO_VERSION' ) ) {
			define( 'MAI_PORTFOLIO_VERSION', '1.1.2' );
		}

		// Plugin Folder Path.
		if ( ! defined( 'MAI_PORTFOLIO_PLUGIN_DIR' ) ) {
			define( 'MAI_PORTFOLIO_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
		}

		// Plugin Includes Path.
		// if ( ! defined( 'MAI_PORTFOLIO_INCLUDES_DIR' ) ) {
			// define( 'MAI_PORTFOLIO_INCLUDES_DIR', MAI_PORTFOLIO_PLUGIN_DIR . 'includes/' );
		// }

		// Plugin Folder URL.
		if ( ! defined( 'MAI_PORTFOLIO_PLUGIN_URL' ) ) {
			define( 'MAI_PORTFOLIO_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
		}

		// Plugin Root File.
		if ( ! defined( 'MAI_PORTFOLIO_PLUGIN_FILE' ) ) {
			define( 'MAI_PORTFOLIO_PLUGIN_FILE', __FILE__ );
		}

		// Plugin Base Name
		if ( ! defined( 'MAI_PORTFOLIO_BASENAME' ) ) {
			define( 'MAI_PORTFOLIO_BASENAME', dirname( plugin_basename( __FILE__ ) ) );
		}
	}

	/**
	 * Include required files.
	 *
	 * @access  private
	 * @since   0.1.0
	 * @return  void
	 */
	private function includes() {
		// Include vendor libraries.
		require_once __DIR__ . '/vendor/autoload.php';
		// Includes.
		// foreach ( glob( MAI_PORTFOLIO_INCLUDES_DIR . '*.php' ) as $file ) { include $file; }
	}

	/**
	 * Run the hooks.
	 *
	 * @since   0.1.0
	 *
	 * @return  void
	 */
	public function hooks() {
		add_action( 'plugins_loaded',                  [ $this, 'updater' ], 12 );
		add_action( 'init',                            [ $this, 'register_content_types' ] );
		add_filter( 'mai_get_option_archive-settings', [ $this, 'add_setting' ] );
		add_filter( 'mai_get_option_single-settings',  [ $this, 'add_setting' ] );

		register_activation_hook( __FILE__, [ $this, 'activate' ] );
		register_deactivation_hook( __FILE__, 'flush_rewrite_rules' );
	}

	/**
	 * Setup the updater.
	 *
	 * composer require yahnis-elsts/plugin-update-checker
	 *
	 * @since 0.1.0
	 *
	 * @uses https://github.com/YahnisElsts/plugin-update-checker/
	 *
	 * @return void
	 */
	public function updater() {
		// Bail if plugin updater is not loaded.
		if ( ! class_exists( 'YahnisElsts\PluginUpdateChecker\v5\PucFactory' ) ) {
			return;
		}

		$updater = PucFactory::buildUpdateChecker( 'https://github.com/maithemewp/mai-portfolio/', __FILE__, 'mai-portfolio' );

		// Maybe set github api token.
		if ( defined( 'MAI_GITHUB_API_TOKEN' ) ) {
			$updater->setAuthentication( MAI_GITHUB_API_TOKEN );
		}

		// Add icons for Dashboard > Updates screen.
		if ( function_exists( 'mai_get_updater_icons' ) && $icons = mai_get_updater_icons() ) {
			$updater->addResultFilter(
				function ( $info ) use ( $icons ) {
					$info->icons = $icons;
					return $info;
				}
			);
		}
	}

	/**
	 * Registers content types.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function register_content_types() {
		register_post_type( 'portfolio', $this->get_post_type_args() );
		register_taxonomy( 'portfolio_type', [ 'portfolio' ], $this->get_type_args() );
		register_taxonomy( 'portfolio_tag', [ 'portfolio' ], $this->get_tag_args() );
	}

	/**
	 * Gets portfolio post type args.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function get_post_type_args() {
		return apply_filters( 'mai_portfolio_args',
			[
				'capability_type'     => 'post',
				'exclude_from_search' => false,
				'has_archive'         => true,
				'hierarchical'        => true,
				'labels'              => [
					'name'                  => __( 'Portfolio', 'mai-portfolio' ),
					'singular_name'         => __( 'Portfolio Item', 'mai-portfolio' ),
					'menu_name'             => _x( 'Portfolio', 'admin menu', 'mai-portfolio' ),
					'name_admin_bar'        => _x( 'Portfolio Item', 'add new on admin bar', 'mai-portfolio' ),
					'add_new'               => __( 'Add New Item', 'mai-portfolio' ),
					'add_new_item'          => __( 'Add New Portfolio Item', 'mai-portfolio' ),
					'new_item'              => __( 'Add New Portfolio Item', 'mai-portfolio' ),
					'edit_item'             => __( 'Edit Portfolio Item', 'mai-portfolio' ),
					'view_item'             => __( 'View Item', 'mai-portfolio' ),
					'all_items'             => __( 'All Portfolio Items', 'mai-portfolio' ),
					'search_items'          => __( 'Search Portfolio', 'mai-portfolio' ),
					'parent_item_colon'     => __( 'Parent Portfolio Item:', 'mai-portfolio' ),
					'not_found'             => __( 'No portfolio items found', 'mai-portfolio' ),
					'not_found_in_trash'    => __( 'No portfolio items found in trash', 'mai-portfolio' ),
					'filter_items_list'     => __( 'Filter portfolio items list', 'mai-portfolio' ),
					'items_list_navigation' => __( 'Portfolio items list navigation', 'mai-portfolio' ),
					'items_list'            => __( 'Portfolio items list', 'mai-portfolio' ),
				],
				'menu_icon'          => 'dashicons-format-gallery',
				'public'             => true,
				'show_in_rest'       => true,
				'rewrite'            => [ 'slug' => 'portfolio', 'with_front' => false ],
				'supports'           => [ 'title', 'editor', 'excerpt', 'thumbnail', 'author', 'page-attributes', 'genesis-seo', 'genesis-cpt-archives-settings', 'mai-archive-settings', 'mai-single-settings' ],
				'taxonomies'         => [ 'portfolio_type', 'portfolio_tag' ],
			]
		);
	}

	/**
	 * Gets portfolio category args.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	public function get_type_args() {
		return apply_filters( 'mai_portfolio_type_args',
			[
				'hierarchical'      => true,
				'labels'            => [
					'name'                       => __( 'Portfolio Types', 'mai-portfolio' ),
					'singular_name'              => __( 'Portfolio Type', 'mai-portfolio' ),
					'menu_name'                  => __( 'Portfolio Types', 'mai-portfolio' ),
					'edit_item'                  => __( 'Edit Portfolio Type', 'mai-portfolio' ),
					'update_item'                => __( 'Update Portfolio Type', 'mai-portfolio' ),
					'add_new_item'               => __( 'Add New Portfolio Type', 'mai-portfolio' ),
					'new_item_name'              => __( 'New Portfolio Type Name', 'mai-portfolio' ),
					'parent_item'                => __( 'Parent Portfolio Type', 'mai-portfolio' ),
					'parent_item_colon'          => __( 'Parent Portfolio Type:', 'mai-portfolio' ),
					'all_items'                  => __( 'All Portfolio Types', 'mai-portfolio' ),
					'search_items'               => __( 'Search Portfolio Types', 'mai-portfolio' ),
					'popular_items'              => __( 'Popular Portfolio Types', 'mai-portfolio' ),
					'separate_items_with_commas' => __( 'Separate portfolio categories with commas', 'mai-portfolio' ),
					'add_or_remove_items'        => __( 'Add or remove portfolio categories', 'mai-portfolio' ),
					'choose_from_most_used'      => __( 'Choose from the most used portfolio categories', 'mai-portfolio' ),
					'not_found'                  => __( 'No portfolio categories found.', 'mai-portfolio' ),
					'items_list_navigation'      => __( 'Portfolio categories list navigation', 'mai-portfolio' ),
					'items_list'                 => __( 'Portfolio categories list', 'mai-portfolio' ),
				],
				'public'            => true,
				'query_var'         => true,
				'rewrite'           => [ 'slug' => 'portfolio-type' ],
				'show_admin_column' => true,
				'show_in_nav_menus' => true,
				'show_in_rest'      => true,
				'show_tagcloud'     => true,
				'show_ui'           => true,
			]
		);
	}

	/**
	 * Gets portfolio tag args.
	 *
	 * @since 0.1.0
	 *
	 * @return void
	 */
	function get_tag_args() {
		return apply_filters( 'mai_portfolio_tag_args',
			[
				'hierarchical'      => false,
				'labels'            => [
					'name'                       => __( 'Portfolio Tags', 'mai-portfolio' ),
					'singular_name'              => __( 'Portfolio Tag', 'mai-portfolio' ),
					'menu_name'                  => __( 'Portfolio Tags', 'mai-portfolio' ),
					'edit_item'                  => __( 'Edit Portfolio Tag', 'mai-portfolio' ),
					'update_item'                => __( 'Update Portfolio Tag', 'mai-portfolio' ),
					'add_new_item'               => __( 'Add New Portfolio Tag', 'mai-portfolio' ),
					'new_item_name'              => __( 'New Portfolio Tag Name', 'mai-portfolio' ),
					'parent_item'                => __( 'Parent Portfolio Tag', 'mai-portfolio' ),
					'parent_item_colon'          => __( 'Parent Portfolio Tag:', 'mai-portfolio' ),
					'all_items'                  => __( 'All Portfolio Tags', 'mai-portfolio' ),
					'search_items'               => __( 'Search Portfolio Tags', 'mai-portfolio' ),
					'popular_items'              => __( 'Popular Portfolio Tags', 'mai-portfolio' ),
					'separate_items_with_commas' => __( 'Separate portfolio tags with commas', 'mai-portfolio' ),
					'add_or_remove_items'        => __( 'Add or remove portfolio tags', 'mai-portfolio' ),
					'choose_from_most_used'      => __( 'Choose from the most used portfolio tags', 'mai-portfolio' ),
					'not_found'                  => __( 'No portfolio tags found.', 'mai-portfolio' ),
					'items_list_navigation'      => __( 'Portfolio tags list navigation', 'mai-portfolio' ),
					'items_list'                 => __( 'Portfolio tags list', 'mai-portfolio' ),
				],
				'public'            => true,
				'query_var'         => true,
				'rewrite'           => [ 'slug' => 'portfolio-tag' ],
				'show_admin_column' => true,
				'show_in_nav_menus' => true,
				'show_in_rest'      => true,
				'show_tagcloud'     => true,
				'show_ui'           => true,
			]
		);
	}

	/**
	 * Forces portfolio post type to use archive/single settings.
	 *
	 * @since 0.1.0
	 *
	 * @param array $post_type The post types to for loop settings.
	 *
	 * @return array
	 */
	function add_setting( $post_types ) {
		$post_types[] = 'portfolio';
		return $post_types;
	}

	/**
	 * Plugin activation.
	 *
	 * @since 0.1.0
	 *
	 * @return  void
	 */
	public function activate() {
		$this->register_content_types();
		flush_rewrite_rules();
	}
}

/**
 * The main function for that returns Mai_Portfolio_Plugin
 *
 * The main function responsible for returning the one true Mai_Portfolio_Plugin
 * Instance to functions everywhere.
 *
 * Use this function like you would a global variable, except without needing
 * to declare the global.
 *
 * Example: <?php $plugin = Mai_Portfolio_Plugin(); ?>
 *
 * @since 0.1.0
 *
 * @return object|Mai_Portfolio_Plugin The one true Mai_Portfolio_Plugin Instance.
 */
function mai_portfolio() {
	return Mai_Portfolio_Plugin::instance();
}

// Get Mai_Portfolio_Plugin Running.
mai_portfolio();
