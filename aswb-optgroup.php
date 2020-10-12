<?php
/**
 * Plugin Name:       Availability Search - Custom Optgroup Walker
 * Description:       Sets parent taxonomies to use optgroups in the select dropdown.
 * Version:           1.0.0
 * Author:            Puri.io
 * Author URI:        https://puri.io/
 * Text Domain:       availability-search-for-woocommerce-bookings
 */

/**
 * Parent level optgroup walker extension
 * For more information see https://core.trac.wordpress.org/ticket/33841
 *
 * LIMITATIONS:
 * Optgroups cannot be selected.
 * Only use this if you only want customers to be able to select the child taxnomies.
 * Limits to 1 child, per group.
 */
class ASWB_Walker_CategoryDropdown_Optgroup extends Walker_CategoryDropdown {

	var $optgroup = false;

	function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {

		$pad      = str_repeat( ' ', $depth * 3 );
		$cat_name = apply_filters( 'list_cats', $category->name, $category );

		// set parent optgroup
		if ( 0 == $depth ) {
			$this->optgroup = true;
			$output        .= '<optgroup class="level-' . $depth . '" label="' . $cat_name . '" >';
		} else {
			$this->optgroup = false;
			$output        .= '<option class="level-' . $depth . '" value="' . $category->term_id . '"';
			if ( $category->term_id == $args['selected'] ) {
				$output .= ' selected="selected"';
			}
			$output .= '>' . $pad . $cat_name;
			if ( $args['show_count'] ) {
				$output .= '  (' . $category->count . ')';
			}
			$output .= '</option>';
		}
	}

	function end_el( &$output, $object, $depth = 0, $args = array() ) {

		if ( 0 == $depth && true === $this->optgroup ) {
			$output .= '</optgroup>';
		}
	}
}

/**
 * Add the walker to our $args.
 *
 * @param array $args https://developer.wordpress.org/reference/functions/wp_dropdown_categories/
 * @return array $args.
 */
function aswb_add_custom_term_walker( $args ) {

	$args['walker'] = new ASWB_Walker_CategoryDropdown_Optgroup();

	return $args;
}

add_filter( 'aswb_filter_taxonomy_args', 'aswb_add_custom_term_walker', 1, 10 );
