<?php
/**
 * Title: Infos pratiques (cartes)
 * Slug: marche1900/practical-info
 * Categories: marche1900, featured
 * Description: Three flat sage-beige cards for practical information — dates, access, contact.
 *
 * @package Marche1900
 */
?>
<!-- wp:group {"align":"wide","style":{"spacing":{"padding":{"top":"var:preset|spacing|50","bottom":"var:preset|spacing|50"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide" style="padding-top:var(--wp--preset--spacing--50);padding-bottom:var(--wp--preset--spacing--50)">
	<!-- wp:heading {"textAlign":"center","level":2,"className":"is-style-engraved-sign"} -->
	<h2 class="wp-block-heading has-text-align-center is-style-engraved-sign"><?php echo esc_html__( 'Infos pratiques', 'marche1900' ); ?></h2>
	<!-- /wp:heading -->

	<!-- wp:columns {"style":{"spacing":{"blockGap":{"top":"var:preset|spacing|40","left":"var:preset|spacing|40"},"margin":{"top":"var:preset|spacing|50"}}}} -->
	<div class="wp-block-columns" style="margin-top:var(--wp--preset--spacing--50)">
		<!-- wp:column {"className":"is-surface","style":{"spacing":{"padding":"var:preset|spacing|40"}}} -->
		<div class="wp-block-column is-surface" style="padding:var(--wp--preset--spacing--40)">
			<!-- wp:heading {"textAlign":"center","level":3,"fontSize":"large"} -->
			<h3 class="wp-block-heading has-text-align-center has-large-font-size"><?php echo esc_html__( 'Quand', 'marche1900' ); ?></h3>
			<!-- /wp:heading -->
			<!-- wp:paragraph {"align":"center"} -->
			<p class="has-text-align-center"><?php echo esc_html__( 'Le 15 août et les jours qui l\'entourent, au cœur de Marche-en-Famenne.', 'marche1900' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"className":"is-surface","style":{"spacing":{"padding":"var:preset|spacing|40"}}} -->
		<div class="wp-block-column is-surface" style="padding:var(--wp--preset--spacing--40)">
			<!-- wp:heading {"textAlign":"center","level":3,"fontSize":"large"} -->
			<h3 class="wp-block-heading has-text-align-center has-large-font-size"><?php echo esc_html__( 'Accès', 'marche1900' ); ?></h3>
			<!-- /wp:heading -->
			<!-- wp:paragraph {"align":"center"} -->
			<p class="has-text-align-center"><?php echo esc_html__( 'Grand-Place et rues du centre. Parkings et navettes fléchés les jours de fête.', 'marche1900' ); ?></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->

		<!-- wp:column {"className":"is-surface","style":{"spacing":{"padding":"var:preset|spacing|40"}}} -->
		<div class="wp-block-column is-surface" style="padding:var(--wp--preset--spacing--40)">
			<!-- wp:heading {"textAlign":"center","level":3,"fontSize":"large"} -->
			<h3 class="wp-block-heading has-text-align-center has-large-font-size"><?php echo esc_html__( 'Contact', 'marche1900' ); ?></h3>
			<!-- /wp:heading -->
			<!-- wp:paragraph {"align":"center"} -->
			<p class="has-text-align-center"><a href="mailto:info@marche1900.be">info@marche1900.be</a></p>
			<!-- /wp:paragraph -->
		</div>
		<!-- /wp:column -->
	</div>
	<!-- /wp:columns -->
</div>
<!-- /wp:group -->
