<?php
/**
 * Bootstrap 5 WordPress Walker Navigation Class
 * Kompatibilna sa ACF ikonama i multi-level dropdown menijima
 */
class Bootstrap_Walker_Nav_Menu extends Walker_Nav_Menu {

	/**
	 * Start Level - wrapper za submenu
	 */
	public function start_lvl( &$output, $depth = 0, $args = null ) {
		$indent = str_repeat( "\t", $depth );

		if ( $depth === 0 ) {
			// Prvi nivo dropdown
			$output .= "\n$indent<ul class=\"dropdown-menu\">\n";
		} else {
			// Multi-level submenu
			$output .= "\n$indent<ul class=\"dropdown-menu dropdown-submenu\">\n";
		}
	}

	/**
	 * End Level - zatvaranje wrapper-a za submenu
	 */
	public function end_lvl( &$output, $depth = 0, $args = null ) {
		$indent = str_repeat( "\t", $depth );
		$output .= "$indent</ul>\n";
	}

	/**
	 * Start Element - pojedinačni menu item
	 */
	public function start_el( &$output, $item, $depth = 0, $args = null, $id = 0 ) {
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';

		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		// Proveri da li item ima decu (submenu)
		$has_children = in_array( 'menu-item-has-children', $classes );

		// Li element classes
		$li_classes = array();
		if ( $depth === 0 ) {
			$li_classes[] = 'nav-item';
			if ( $has_children ) {
				$li_classes[] = 'dropdown';
			}
		} elseif ( $has_children ) {
			$li_classes[] = 'dropdown-submenu';
		}

		// Link classes
		$link_classes = array();
		if ( $depth === 0 ) {
			$link_classes[] = 'nav-link';
		} else {
			$link_classes[] = 'dropdown-item';
			if ( $has_children ) {
				$link_classes[] = 'dropdown-submenu-toggle';
				$link_classes[] = 'icon-left';
			}
		}

		// Attributes za link
		$attributes = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';

		// Dropdown attributes
		if ( $has_children && $depth === 0 ) {
			$attributes .= ' data-bs-toggle="dropdown" aria-expanded="false"';
		}

		// Dobij ACF ikonu ako postoji
		$icon = get_field( 'menu_icon', $item->ID );
		$icon_html = '';
		if ( $icon ) {
			$icon_html = '<i class="' . esc_attr( $icon ) . '"></i> ';
		}

		// Dropdown strelica
		$arrow_html = '';
		if ( $has_children ) {
			if ( $depth === 0 ) {
				$arrow_html = ' <i class="icofont-rounded-down"></i>';
			} else {
				$arrow_html = ' <i class="icofont-rounded-right"></i>';
			}
		}

		// Generiši output
		$item_output = $args->before ?? '' ?? '';
		$item_output .= '<a class="' . implode( ' ', $link_classes ) . '"' . $attributes . '>';
		$item_output .= $icon_html;
		$item_output .= $args->link_before ?? '' !== null ? $args->link_before ?? '' : '';
		$item_output .= apply_filters( 'the_title', $item->title, $item->ID );
		$item_output .= $args->link_after ?? '' !== null ? $args->link_after ?? '' : '';
		$item_output .= $arrow_html;
		$item_output .= '</a>';
		$item_output .= $args->after ?? '' !== null ? $args->after ?? '' : '';

		$output .= $indent . '<li class="' . implode( ' ', $li_classes ) . '">';
		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}

	/**
	 * End Element - zatvaranje li elementa
	 */
	public function end_el( &$output, $item, $depth = 0, $args = null ) {
		$output .= "</li>\n";
	}
}