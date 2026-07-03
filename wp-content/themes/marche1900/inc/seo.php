<?php
/**
 * SEO, Open Graph, Twitter Card, hreflang and JSON-LD output for Marche 1900.
 *
 * Self-contained: the site runs without an SEO plugin, so the theme emits its
 * own meta. Everything hooks into wp_head. Titles and rel=canonical are already
 * handled by WordPress core (title-tag support + core canonical), so we don't
 * duplicate them here.
 *
 * @package Marche1900
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * FR page ID => NL page ID.
 *
 * Drives the reciprocal hreflang alternates. Edit this map if the bilingual
 * page pairing changes. Note: the FR "Le Marché" page (ID 5) has no distinct
 * NL page — `markt` (1585) doubles as the NL home and is therefore paired with
 * the FR home (accueil, 2125). Keep this 1:1 so each NL page maps to one FR page.
 *
 * @return array<int,int>
 */
function marche1900_lang_pairs() {
	return array(
		2125 => 1585, // accueil (home) ↔ markt (NL home)
		9    => 1588, // oiseaux ↔ vogels
		11   => 1598, // métiers-jeux ↔ ambachten-volksspelen
		17   => 1601, // accès ↔ toegang
		23   => 1605, // affiches ↔ palix-nl
		25   => 1607, // presse ↔ pers
		27   => 1611, // associations ↔ verenigingen
		29   => 1613, // médias ↔ media-nl
		31   => 1616, // contact ↔ contact-nl
	);
}

/**
 * Canonical URL for a page ID, honouring the static front page.
 *
 * @param int $id Page ID.
 * @return string
 */
function marche1900_page_url( $id ) {
	if ( (int) $id === (int) get_option( 'page_on_front' ) ) {
		return home_url( '/' );
	}
	$url = get_permalink( $id );
	return $url ? $url : home_url( '/' );
}

/**
 * Best meta description for the current request (~160 chars, single line).
 *
 * @return string
 */
function marche1900_meta_description() {
	$desc = '';

	if ( is_front_page() ) {
		$desc = get_bloginfo( 'description', 'display' );
	} elseif ( is_singular() ) {
		// get_the_excerpt() handles manual excerpts and strips block markup
		// when auto-generating, which plain strip_tags on block content cannot.
		$desc = get_the_excerpt( get_queried_object_id() );
	} elseif ( is_tax() || is_category() || is_tag() ) {
		$desc = term_description();
	} elseif ( is_archive() ) {
		$desc = get_the_archive_description();
	}

	$desc = wp_strip_all_tags( (string) $desc );
	$desc = trim( preg_replace( '/\s+/u', ' ', $desc ) );

	if ( '' === $desc ) {
		$desc = get_bloginfo( 'description', 'display' );
	}

	if ( function_exists( 'mb_strlen' ) && mb_strlen( $desc ) > 160 ) {
		$desc = mb_substr( $desc, 0, 157 );
		$desc = preg_replace( '/\s+\S*$/u', '', $desc ) . '…';
	}

	return apply_filters( 'marche1900_meta_description', $desc );
}

/**
 * Share image for the current request: the post's featured image when present,
 * otherwise the site-wide default (a landscape event photo).
 *
 * @return array{url:string,width:int,height:int,alt:string}|null
 */
function marche1900_share_image() {
	$id = 0;

	if ( is_singular() && has_post_thumbnail() ) {
		$id = get_post_thumbnail_id();
	}

	if ( ! $id ) {
		// 2416 — "Marche 1900, couple en costume d'époque" (1600×1200 photo).
		$id = (int) apply_filters( 'marche1900_default_share_image', 2416 );
	}

	$src = wp_get_attachment_image_src( $id, 'full' );
	if ( ! $src ) {
		return null;
	}

	return array(
		'url'    => $src[0],
		'width'  => (int) $src[1],
		'height' => (int) $src[2],
		'alt'    => (string) get_post_meta( $id, '_wp_attachment_image_alt', true ),
	);
}

/**
 * Emit description, Open Graph, Twitter Card and hreflang tags.
 */
function marche1900_head_meta() {
	if ( is_admin() || is_feed() || is_embed() ) {
		return;
	}

	$queried_id = (int) get_queried_object_id();
	$pairs      = marche1900_lang_pairs();
	$nl_ids     = array_values( $pairs );
	$is_nl      = in_array( $queried_id, $nl_ids, true );

	$locale     = $is_nl ? 'nl_BE' : 'fr_BE';
	$alt_locale = $is_nl ? 'fr_BE' : 'nl_BE';

	$title = wp_get_document_title();
	$desc  = marche1900_meta_description();
	$img   = marche1900_share_image();

	if ( is_front_page() ) {
		$url     = home_url( '/' );
		$og_type = 'website';
	} elseif ( is_singular() ) {
		$canonical = wp_get_canonical_url();
		$url       = $canonical ? $canonical : get_permalink();
		$og_type   = is_single() ? 'article' : 'website';
	} else {
		$url     = home_url( '/' );
		$og_type = 'website';
	}

	echo "\n<!-- Marche 1900 SEO -->\n";

	printf( '<meta name="description" content="%s" />' . "\n", esc_attr( $desc ) );

	printf( '<meta property="og:site_name" content="%s" />' . "\n", esc_attr( get_bloginfo( 'name' ) ) );
	printf( '<meta property="og:type" content="%s" />' . "\n", esc_attr( $og_type ) );
	printf( '<meta property="og:title" content="%s" />' . "\n", esc_attr( $title ) );
	printf( '<meta property="og:description" content="%s" />' . "\n", esc_attr( $desc ) );
	printf( '<meta property="og:url" content="%s" />' . "\n", esc_url( $url ) );
	printf( '<meta property="og:locale" content="%s" />' . "\n", esc_attr( $locale ) );
	printf( '<meta property="og:locale:alternate" content="%s" />' . "\n", esc_attr( $alt_locale ) );

	if ( $img ) {
		printf( '<meta property="og:image" content="%s" />' . "\n", esc_url( $img['url'] ) );
		printf( '<meta property="og:image:width" content="%d" />' . "\n", $img['width'] );
		printf( '<meta property="og:image:height" content="%d" />' . "\n", $img['height'] );
		if ( '' !== $img['alt'] ) {
			printf( '<meta property="og:image:alt" content="%s" />' . "\n", esc_attr( $img['alt'] ) );
		}
	}

	printf( '<meta name="twitter:card" content="%s" />' . "\n", $img ? 'summary_large_image' : 'summary' );
	printf( '<meta name="twitter:title" content="%s" />' . "\n", esc_attr( $title ) );
	printf( '<meta name="twitter:description" content="%s" />' . "\n", esc_attr( $desc ) );
	if ( $img ) {
		printf( '<meta name="twitter:image" content="%s" />' . "\n", esc_url( $img['url'] ) );
		if ( '' !== $img['alt'] ) {
			printf( '<meta name="twitter:image:alt" content="%s" />' . "\n", esc_attr( $img['alt'] ) );
		}
	}

	// hreflang: only for pages that belong to a known FR/NL pair.
	$fr_id = 0;
	$nl_id = 0;
	if ( isset( $pairs[ $queried_id ] ) ) {
		$fr_id = $queried_id;
		$nl_id = $pairs[ $queried_id ];
	} else {
		$flip = array_flip( $pairs );
		if ( isset( $flip[ $queried_id ] ) ) {
			$nl_id = $queried_id;
			$fr_id = $flip[ $queried_id ];
		}
	}

	if ( $fr_id && $nl_id ) {
		$fr_url = marche1900_page_url( $fr_id );
		$nl_url = marche1900_page_url( $nl_id );
		printf( '<link rel="alternate" hreflang="fr-BE" href="%s" />' . "\n", esc_url( $fr_url ) );
		printf( '<link rel="alternate" hreflang="nl-BE" href="%s" />' . "\n", esc_url( $nl_url ) );
		printf( '<link rel="alternate" hreflang="x-default" href="%s" />' . "\n", esc_url( $fr_url ) );
	}

	echo "<!-- /Marche 1900 SEO -->\n";
}
add_action( 'wp_head', 'marche1900_head_meta', 5 );

/**
 * The event's schedule. Belle-Époque market — a single day each August.
 *
 * UPDATE THESE THREE VALUES EACH YEAR (or filter `marche1900_event_data`).
 * Times are Europe/Brussels (CEST, +02:00 in August).
 */
function marche1900_event_data() {
	$home = home_url( '/' );

	$data = array(
		'name'      => 'Marché 1900',
		'startDate' => '2026-08-15T09:00:00+02:00',
		'endDate'   => '2026-08-15T18:30:00+02:00',
	);

	$img = marche1900_share_image();

	$event = array(
		'@type'               => 'Event',
		'name'                => $data['name'],
		'startDate'           => $data['startDate'],
		'endDate'             => $data['endDate'],
		'eventStatus'         => 'https://schema.org/EventScheduled',
		'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
		'location'            => array(
			'@type'   => 'Place',
			'name'    => 'Centre-ville de Marche-en-Famenne',
			'address' => array(
				'@type'           => 'PostalAddress',
				'streetAddress'   => 'Centre-ville',
				'addressLocality' => 'Marche-en-Famenne',
				'postalCode'      => '6900',
				'addressRegion'   => 'Luxembourg',
				'addressCountry'  => 'BE',
			),
		),
		'description'         => marche1900_meta_description(),
		'organizer'           => array( '@id' => $home . '#organization' ),
		'url'                 => $home,
		'offers'              => array(
			'@type'         => 'Offer',
			'price'         => '5',
			'priceCurrency' => 'EUR',
			'description'   => "Entrée 5 € — gratuit pour les moins de 12 ans et les personnes en costume d'époque 1900",
			'availability'  => 'https://schema.org/InStock',
			'url'           => $home,
		),
	);

	if ( $img ) {
		$event['image'] = array( $img['url'] );
	}

	return apply_filters( 'marche1900_event_data', $event );
}

/**
 * Emit Organization + WebSite (site-wide) and Event (front page) JSON-LD.
 */
function marche1900_head_jsonld() {
	if ( is_admin() || is_feed() || is_embed() ) {
		return;
	}

	$home   = home_url( '/' );
	$org_id = $home . '#organization';

	$organization = array(
		'@type' => 'Organization',
		'@id'   => $org_id,
		'name'  => get_bloginfo( 'name' ),
		'url'   => $home,
	);

	$logo = wp_get_attachment_image_src( 2382, 'full' ); // deco-header masthead.
	if ( $logo ) {
		$organization['logo'] = array(
			'@type'  => 'ImageObject',
			'url'    => $logo[0],
			'width'  => (int) $logo[1],
			'height' => (int) $logo[2],
		);
	}

	$website = array(
		'@type'           => 'WebSite',
		'@id'             => $home . '#website',
		'name'            => get_bloginfo( 'name' ),
		'url'             => $home,
		'inLanguage'      => 'fr-BE',
		'publisher'       => array( '@id' => $org_id ),
		'potentialAction' => array(
			'@type'       => 'SearchAction',
			'target'      => array(
				'@type'       => 'EntryPoint',
				'urlTemplate' => home_url( '/?s={search_term_string}' ),
			),
			'query-input' => 'required name=search_term_string',
		),
	);

	$graph = array( $organization, $website );

	if ( is_front_page() ) {
		$event = marche1900_event_data();
		if ( ! empty( $event ) ) {
			$graph[] = $event;
		}
	}

	$json = wp_json_encode(
		array(
			'@context' => 'https://schema.org',
			'@graph'   => $graph,
		),
		JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE
	);

	echo '<script type="application/ld+json">' . $json . '</script>' . "\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- wp_json_encode output.
}
add_action( 'wp_head', 'marche1900_head_jsonld', 20 );
