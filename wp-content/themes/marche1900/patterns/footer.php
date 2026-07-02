<?php
/**
 * Title: Footer
 * Slug: marche1900/footer
 * Categories: marche1900, footer
 * Block Types: core/template-part/footer
 * Inserter: no
 *
 * @package Marche1900
 */

// Partner logos: media-library attachment IDs, with the external site each links to.
$marche1900_partners = array(
	array( 'id' => 2486, 'alt' => __( 'asbl XV — Marche-en-Famenne', 'marche1900' ), 'url' => '' ),
	array( 'id' => 2384, 'alt' => __( 'Château de Marche', 'marche1900' ), 'url' => '' ),
	array( 'id' => 2385, 'alt' => __( 'Patrimoine vivant Wallonie-Bruxelles', 'marche1900' ), 'url' => 'https://www.patrimoinevivantwalloniebruxelles.be' ),
	array( 'id' => 2386, 'alt' => __( 'Vivacité', 'marche1900' ), 'url' => 'https://www.rtbf.be/vivacite/' ),
	array( 'id' => 2387, 'alt' => __( 'TV Lux', 'marche1900' ), 'url' => 'https://www.tvlux.be/' ),
	array( 'id' => 2388, 'alt' => __( 'Marche Motors', 'marche1900' ), 'url' => 'https://www.marchemotors.be/' ),
	array( 'id' => 2389, 'alt' => __( 'Province du Luxembourg', 'marche1900' ), 'url' => 'https://www.province.luxembourg.be' ),
	array( 'id' => 2390, 'alt' => __( 'Ville de Marche-en-Famenne', 'marche1900' ), 'url' => 'https://www.marche.be' ),
	array( 'id' => 2506, 'alt' => __( 'Visit Marche-en-Famenne', 'marche1900' ), 'url' => 'https://visitmarche.be' ),
	array( 'id' => 2488, 'alt' => __( 'VISITWallonia.be', 'marche1900' ), 'url' => 'https://www.visitwallonia.be' ),
);
?>
<!-- wp:group {"tagName":"footer","className":"site-footer","align":"full","style":{"spacing":{"blockGap":"0"}},"layout":{"type":"default"}} -->
<footer class="wp-block-group alignfull site-footer">
	<!-- wp:group {"align":"full","backgroundColor":"beige","style":{"spacing":{"padding":{"top":"var:preset|spacing|60","bottom":"var:preset|spacing|60","left":"var:preset|spacing|40","right":"var:preset|spacing|40"}}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignfull has-beige-background-color has-background" style="padding-top:var(--wp--preset--spacing--60);padding-bottom:var(--wp--preset--spacing--60);padding-left:var(--wp--preset--spacing--40);padding-right:var(--wp--preset--spacing--40)">
		<!-- wp:heading {"textAlign":"center","level":2,"className":"is-style-engraved-sign","fontSize":"large"} -->
		<h2 class="wp-block-heading has-text-align-center is-style-engraved-sign has-large-font-size"><?php echo esc_html__( 'Avec le soutien de', 'marche1900' ); ?></h2>
		<!-- /wp:heading -->

		<!-- wp:group {"style":{"spacing":{"blockGap":"var:preset|spacing|50"}},"layout":{"type":"flex","flexWrap":"wrap","justifyContent":"center","verticalAlignment":"center"}} -->
		<div class="wp-block-group">
			<?php
			foreach ( $marche1900_partners as $marche1900_meta ) :
				$marche1900_img = wp_get_attachment_image(
					$marche1900_meta['id'],
					'medium',
					false,
					array(
						'alt'     => $marche1900_meta['alt'],
						'class'   => 'footer-partner',
						'loading' => 'lazy',
					)
				);
				if ( '' === $marche1900_img ) {
					continue;
				}
				?>
			<!-- wp:html -->
				<?php if ( ! empty( $marche1900_meta['url'] ) ) : ?>
			<a class="footer-partner-link" href="<?php echo esc_url( $marche1900_meta['url'] ); ?>" target="_blank" rel="noopener noreferrer"><?php echo $marche1900_img; ?></a>
				<?php else : ?>
			<?php echo $marche1900_img; ?>
				<?php endif; ?>
			<!-- /wp:html -->
			<?php endforeach; ?>
		</div>
		<!-- /wp:group -->
	</div>
	<!-- /wp:group -->

	<!-- wp:group {"align":"full","backgroundColor":"pine","textColor":"cream","style":{"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30"}}},"layout":{"type":"constrained"}} -->
	<div class="wp-block-group alignfull has-cream-color has-pine-background-color has-text-color has-background" style="padding-top:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30)">
		<!-- wp:paragraph {"align":"center","fontSize":"small"} -->
		<p class="has-text-align-center has-small-font-size">&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php echo esc_html__( 'Marche 1900 — asbl XV de Marche-en-Famenne. Tous droits réservés.', 'marche1900' ); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->
</footer>
<!-- /wp:group -->
