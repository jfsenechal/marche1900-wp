<?php
/**
 * Title: Album du marché (galerie)
 * Slug: marche1900/album
 * Categories: marche1900, gallery
 * Description: Grille de photos des dernières éditions du Marché 1900, avec agrandissement au clic.
 *
 * @package Marche1900
 */

// Imported event photos (media library attachment IDs), in display order.
$marche1900_album = range( 2392, 2414 );
$marche1900_total = count( $marche1900_album );
?>
<!-- wp:group {"align":"wide","style":{"spacing":{"margin":{"top":"var:preset|spacing|70"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide" style="margin-top:var(--wp--preset--spacing--70)">
	<!-- wp:heading {"textAlign":"center","level":2,"className":"is-style-engraved-sign"} -->
	<h2 class="wp-block-heading has-text-align-center is-style-engraved-sign"><?php echo esc_html__( 'Le marché en images', 'marche1900' ); ?></h2>
	<!-- /wp:heading -->

	<!-- wp:paragraph {"align":"center","fontSize":"medium"} -->
	<p class="has-text-align-center has-medium-font-size"><?php echo esc_html__( 'Quelques moments des dernières éditions : le marché aux oiseaux, les métiers et jeux d\'antan, et les costumes d\'époque.', 'marche1900' ); ?></p>
	<!-- /wp:paragraph -->

	<!-- wp:gallery {"columns":3,"imageCrop":true,"linkTo":"none","sizeSlug":"large","className":"album-gallery","style":{"spacing":{"margin":{"top":"var:preset|spacing|50"}}}} -->
	<figure class="wp-block-gallery has-nested-images columns-3 is-cropped album-gallery wp-block-gallery-is-layout-flex" style="margin-top:var(--wp--preset--spacing--50)">
		<?php
		foreach ( $marche1900_album as $marche1900_index => $marche1900_id ) :
			$marche1900_img = wp_get_attachment_image(
				$marche1900_id,
				'large',
				false,
				array(
					'class' => 'wp-image-' . $marche1900_id,
					// Unique per position so screen readers don't hear 23 identical
					// captions; a specific per-photo description isn't available here.
					'alt'   => sprintf(
						/* translators: 1: photo number, 2: total number of photos */
						__( 'Marché 1900 à Marche-en-Famenne — photo %1$d sur %2$d', 'marche1900' ),
						$marche1900_index + 1,
						$marche1900_total
					),
				)
			);
			if ( '' === $marche1900_img ) {
				continue;
			}
			?>
		<!-- wp:image {"id":<?php echo (int) $marche1900_id; ?>,"sizeSlug":"large","linkDestination":"none","lightbox":{"enabled":true}} -->
		<figure class="wp-block-image size-large"><?php echo $marche1900_img; ?></figure>
		<!-- /wp:image -->
		<?php endforeach; ?>
	</figure>
	<!-- /wp:gallery -->

	<!-- wp:paragraph {"align":"right","className":"album-credit","fontSize":"small"} -->
	<p class="has-text-align-right album-credit has-small-font-size"><?php echo esc_html__( 'Photos © DUSCH Photo-Reporter', 'marche1900' ); ?></p>
	<!-- /wp:paragraph -->
</div>
<!-- /wp:group -->
