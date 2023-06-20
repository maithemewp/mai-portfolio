# Mai Portfolio
A versatile and lightweight portfolio plugin for Mai Theme.

Change content type labels and slugs via the available filters:
```
/**
 * Change portfolio post type args.
 *
 * @param array $args The post type args.
 *
 * @return array
 */
add_filter( 'mai_portfolio_args', function( $args ) {
	$args['rewrite']['slug'] = 'campaigns';

	foreach ( $args['labels'] as $key => $label ) {
		$args['labels'][ $key ] = str_replace( 'Portfolio', 'Campaign', $args['labels'][ $key ]  );
		$args['labels'][ $key ] = str_replace( 'portfolio', 'campaign', $args['labels'][ $key ]  );
	}

	return $args;
});
```
```
/**
 * Change portfolio type taxonomy args.
 *
 * @param array $args The taxonomy type args.
 *
 * @return array
 */
add_filter( 'mai_portfolio_type_args', function( $args ) {
	$args['rewrite']['slug'] = 'campaign-type';

	foreach ( $args['labels'] as $key => $label ) {
		$args['labels'][ $key ] = str_replace( 'Portfolio', 'Campaign', $args['labels'][ $key ]  );
		$args['labels'][ $key ] = str_replace( 'portfolio', 'campaign', $args['labels'][ $key ]  );
	}

	return $args;
});
```
```
/**
 * Change portfolio tag taxonomy args.
 *
 * @param array $args The taxonomy tag args.
 *
 * @return array
 */
add_filter( 'mai_portfolio_tag_args', function( $args ) {
	$args['rewrite']['slug'] = 'campaign-tag';

	foreach ( $args['labels'] as $key => $label ) {
		$args['labels'][ $key ] = str_replace( 'Portfolio', 'Campaign', $args['labels'][ $key ]  );
		$args['labels'][ $key ] = str_replace( 'portfolio', 'campaign', $args['labels'][ $key ]  );
	}

	return $args;
});
```
