<?php

add_filter( 'mai_archive-settings_config', 'mai_portfolio_archive_settings_config' );
/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @param $defaults
 *
 * @return array
 */
function mai_portfolio_archive_settings_config( $defaults ) {
	$defaults[] = 'portfolio';
	$defaults[] = 'portfolio-type';

	return $defaults;
}

add_filter( 'mai_single-settings_config', 'mai_portfolio_single_settings_config' );
/**
 * Description of expected behavior.
 *
 * @since 1.0.0
 *
 * @param $defaults
 *
 * @return array
 */
function mai_portfolio_single_settings_config( $defaults ) {
	$defaults[] = 'portfolio';
	$defaults[] = 'portfolio-type';

	return $defaults;
}
