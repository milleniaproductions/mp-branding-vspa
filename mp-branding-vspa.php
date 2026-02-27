<?php
/**
 * Plugin Name: VolcanoSpa® Front-end Branding
 * Description: Front-end trademark formatting for VolcanoSpa®. Normalizes all "Volcano Spa" / "VolcanoSpa" variants (any case) using safe DOM node splitting — no appendXML(), no output buffering.
 * Version: 1.1.4
 * Author: Millenia Productions LLC
 * Author URI: https://lapalmproducts.com
 */

if ( ! defined( 'ABSPATH' ) ) exit;

define( 'MP_BRANDING_KEY', 'vspa' );
define( 'MP_VSPA_BRAND',  'VolcanoSpa' );
define( 'MP_VSPA_CLASS',  'regmark' );
define( 'MP_VSPA_SYMBOL', '®' );

/**
 * Enqueue branding stylesheet (front-end only).
 */
function mp_vspa_enqueue_branding_css() {
    if ( is_admin() ) {
        return;
    }

    wp_enqueue_style(
        'mp-branding-vspa',
        plugin_dir_url( __FILE__ ) . 'assets/css/branding.css',
        [],
        '1.1.4'
    );
}
add_action( 'wp_enqueue_scripts', 'mp_vspa_enqueue_branding_css' );

function mp_vspa_strip_leading_mark_from_node( DOMNode $node ) {
    if ( $node->nodeType === XML_TEXT_NODE ) {
        $v = $node->nodeValue;
        $new = preg_replace( '/^\s*(?:®|&reg;)\s*/u', '', $v, 1, $count );
        if ( $count > 0 ) {
            if ( $new === '' ) {
                $node->parentNode->removeChild( $node );
            } else {
                $node->nodeValue = $new;
            }
            return true;
        }
        return false;
    }

    if ( $node->nodeType === XML_ELEMENT_NODE ) {
        $tag = strtolower( $node->nodeName );

        if ( $tag === 'span' ) {
            $class = $node->attributes && $node->attributes->getNamedItem('class')
                ? $node->attributes->getNamedItem('class')->nodeValue
                : '';
            if ( preg_match( '/\b' . preg_quote( MP_VSPA_CLASS, '/' ) . '\b/', $class ) ) {
                $node->parentNode->removeChild( $node );
                return true;
            }
        }

        if ( $tag === 'sup' ) {
            $text = trim( $node->textContent );
            if ( $text === '®' || strtolower( $text ) === '&reg;' ) {
                $node->parentNode->removeChild( $node );
                return true;
            }
        }
    }

    return false;
}

function mp_vspa_strip_following_marks( DOMNode $insertedSpan ) {
    $next = $insertedSpan->nextSibling;
    while ( $next ) {
        if ( $next->nodeType === XML_TEXT_NODE && trim( $next->nodeValue ) === '' ) {
            $next = $next->nextSibling;
            continue;
        }

        $stripped = mp_vspa_strip_leading_mark_from_node( $next );
        if ( ! $stripped ) break;

        $next = $insertedSpan->nextSibling;
    }
}

function mp_vspa_process_dom( DOMDocument $dom ) {
    $xpath = new DOMXPath( $dom );

    $text_nodes = $xpath->query(
        '//text()[
            not( ancestor::script ) and
            not( ancestor::style ) and
            not( ancestor::noscript ) and
            not( ancestor::textarea )
        ]'
    );

    if ( ! $text_nodes ) return;

    $nodes = iterator_to_array( $text_nodes );
    $pattern = '/\bvolcano\s*spa\b/iu';

    foreach ( $nodes as $node ) {
        $text = $node->nodeValue;
        if ( ! is_string( $text ) || stripos( $text, 'volcano' ) === false ) continue;

        $segments = preg_split( $pattern, $text );
        if ( count( $segments ) < 2 ) continue;

        $parent = $node->parentNode;
        $ref = $node;
        $match_count = count( $segments ) - 1;

        for ( $i = 0; $i < count( $segments ); $i++ ) {
            if ( $segments[ $i ] !== '' ) {
                $parent->insertBefore( $dom->createTextNode( $segments[ $i ] ), $ref );
            }

            if ( $i < $match_count ) {
                $parent->insertBefore( $dom->createTextNode( MP_VSPA_BRAND ), $ref );

                $span = $dom->createElement( 'span' );
                $span->setAttribute( 'class', MP_VSPA_CLASS );
                $span->appendChild( $dom->createTextNode( MP_VSPA_SYMBOL ) );
                $parent->insertBefore( $span, $ref );

                mp_vspa_strip_following_marks( $span );
            }
        }

        $parent->removeChild( $ref );
    }
}

function mp_vspa_replace_html( $html ) {
    if ( ! is_string( $html ) || $html === '' ) return $html;
    if ( stripos( $html, 'volcano' ) === false ) return $html;

    $wrapped = '<div id="mp-vspa-wrap">' . $html . '</div>';

    if ( function_exists( 'mb_convert_encoding' ) ) {
        $wrapped = mb_convert_encoding( $wrapped, 'HTML-ENTITIES', 'UTF-8' );
    }

    libxml_use_internal_errors( true );
    $dom = new DOMDocument( '1.0', 'UTF-8' );
    $dom->loadHTML(
        '<?xml encoding="UTF-8">' . $wrapped,
        LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | ( defined('LIBXML_BIGLINES') ? LIBXML_BIGLINES : 0 )
    );

    mp_vspa_process_dom( $dom );
    libxml_clear_errors();

    $wrapper = $dom->getElementById( 'mp-vspa-wrap' );
    if ( ! $wrapper ) return $html;

    $output = '';
    foreach ( $wrapper->childNodes as $child ) {
        $output .= $dom->saveHTML( $child );
    }

    return $output;
}

function mp_vspa_replace_text( $text ) {
    if ( ! is_string( $text ) || $text === '' ) return $text;
    if ( stripos( $text, 'volcano' ) === false ) return $text;

    $canon = MP_VSPA_BRAND . '<span class="' . MP_VSPA_CLASS . '">' . MP_VSPA_SYMBOL . '</span>';

    return preg_replace(
        '/\bvolcano\s*spa\b(?:\s*(?:®|&reg;))*/iu',
        $canon,
        $text
    );
}

function mp_vspa_filter( $value ) {
    if ( is_admin() ) return $value;
    if ( ! is_string( $value ) || $value === '' ) return $value;
    if ( stripos( $value, 'volcano' ) === false ) return $value;

    if ( preg_match( '/<[a-z][^>]*>/i', $value ) ) {
        return mp_vspa_replace_html( $value );
    }

    return mp_vspa_replace_text( $value );
}

add_filter( 'the_title',               'mp_vspa_filter', 20 );
add_filter( 'the_content',             'mp_vspa_filter', 20 );
add_filter( 'the_excerpt',             'mp_vspa_filter', 20 );
add_filter( 'widget_title',            'mp_vspa_filter', 20 );
add_filter( 'widget_text',             'mp_vspa_filter', 20 );
add_filter( 'widget_text_content',     'mp_vspa_filter', 20 );
add_filter( 'get_the_archive_title',   'mp_vspa_filter', 20 );
add_filter( 'nav_menu_item_title',     'mp_vspa_filter', 20 );
add_filter( 'term_description',        'mp_vspa_filter', 20 );
add_filter( 'woocommerce_short_description',           'mp_vspa_filter', 20 );
add_filter( 'woocommerce_product_get_name',            'mp_vspa_filter', 20 );
add_filter( 'woocommerce_product_variation_get_name',  'mp_vspa_filter', 20 );
add_filter( 'woocommerce_cart_item_name',              'mp_vspa_filter', 20 );
add_filter( 'woocommerce_order_item_name',             'mp_vspa_filter', 20 );
add_filter( 'woocommerce_product_title',               'mp_vspa_filter', 20 );
add_filter( 'woocommerce_checkout_product_title',      'mp_vspa_filter', 20 );