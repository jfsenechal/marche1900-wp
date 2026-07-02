<?php
/**
 * Title: Sectienavigatie — Markt (menu_bis NL)
 * Slug: marche1900/section-nav-nl
 * Categories: marche1900
 * Description: Dutch secondary navigation for the "Markt" cluster — a centered flat pill linking its sub-pages, with the current page marked. NL counterpart of marche1900/section-nav.
 *
 * @package Marche1900
 */

$marche1900_nl_links = array(
	array(
		'label' => __( 'Markt', 'marche1900' ),
		'path'  => '/markt',
	),
	array(
		'label' => __( 'Vogels', 'marche1900' ),
		'path'  => '/vogels',
	),
	array(
		'label' => __( 'Ambachten & Volksspelen', 'marche1900' ),
		'path'  => '/ambachten-volksspelen',
	),
	array(
		'label' => __( 'Affiches', 'marche1900' ),
		'path'  => '/palix-nl',
	),
);

$marche1900_current = wp_parse_url( isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : '', PHP_URL_PATH );
$marche1900_current = untrailingslashit( is_string( $marche1900_current ) ? $marche1900_current : '' );

$marche1900_items = '';
foreach ( $marche1900_nl_links as $marche1900_link ) {
	$marche1900_is_current = ( $marche1900_current === untrailingslashit( $marche1900_link['path'] ) );

	$marche1900_items .= sprintf(
		'<li class="section-nav__item"><a class="section-nav__link" href="%1$s"%2$s>%3$s</a></li>',
		esc_url( home_url( $marche1900_link['path'] ) ),
		$marche1900_is_current ? ' aria-current="page"' : '',
		esc_html( $marche1900_link['label'] )
	);
}
?>
<!-- wp:html -->
<nav class="section-nav" aria-label="<?php echo esc_attr__( 'Secties van de Markt', 'marche1900' ); ?>">
	<ul class="section-nav__list">
		<?php echo $marche1900_items; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- built from escaped parts above. ?>
	</ul>
</nav>
<!-- /wp:html -->
