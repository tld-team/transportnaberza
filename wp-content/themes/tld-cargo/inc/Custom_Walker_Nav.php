<?php
// functions.php - Dodaj u functions.php fajl
class Bootstrap_Walker_Nav_Menu extends Walker_Nav_Menu {

	public function start_lvl(&$output, $depth = 0, $args = null) {
		$indent = str_repeat("\t", $depth);
		$output .= "\n$indent<ul class=\"dropdown-menu\">\n";
	}

	public function end_lvl(&$output, $depth = 0, $args = null) {
		$indent = str_repeat("\t", $depth);
		$output .= "$indent</ul>\n";
	}

	public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
		$indent = ($depth) ? str_repeat("\t", $depth) : '';

		$classes = empty($item->classes) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;

		// Proverava da li item ima decu (sub-menu)
		$has_children = in_array('menu-item-has-children', $classes);

		// Dodaje klase na osnovu dubine
		if ($depth === 0) {
			$class_names = $has_children ? 'nav-item dropdown' : 'nav-item';
		} else {
			$class_names = $has_children ? 'dropdown-submenu' : '';
		}

		$class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

		$id = apply_filters('nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args);
		$id = $id ? ' id="' . esc_attr($id) . '"' : '';

		$output .= $indent . '<li' . $id . $class_names .'>';

		// Link attributes
		$attributes = ! empty($item->attr_title) ? ' title="'  . esc_attr($item->attr_title) .'"' : '';
		$attributes .= ! empty($item->target)     ? ' target="' . esc_attr($item->target     ) .'"' : '';
		$attributes .= ! empty($item->xfn)        ? ' rel="'    . esc_attr($item->xfn        ) .'"' : '';
		$attributes .= ! empty($item->url)        ? ' href="'   . esc_attr($item->url        ) .'"' : '';

		// Dodaje klase za linkove
		if ($depth === 0) {
			$link_class = $has_children ? 'nav-link dropdown-toggle' : 'nav-link';
			$attributes .= ' class="' . $link_class . '"';

			if ($has_children) {
				$attributes .= ' data-bs-toggle="dropdown" aria-expanded="false"';
			}
		} else {
			$link_class = $has_children ? 'dropdown-item dropdown-submenu-toggle icon-left' : 'dropdown-item';
			$attributes .= ' class="' . $link_class . '"';
		}

		// Uzima ikonicu iz ACF polja
		$icon = get_field('menu_icon', $item->ID);
		$icon_html = '';

		if ($icon && $depth > 0) {
			// Za sub-menu stavke
			$icon_html = '<i class="' . esc_attr($icon) . '"></i> ';
		}

		$item_output = isset($args->before) ? $args->before : '';
		$item_output .= '<a' . $attributes .'>';
		$item_output .= (isset($args->link_before) ? $args->link_before : '') . $icon_html . apply_filters('the_title', $item->title, $item->ID) . (isset($args->link_after) ? $args->link_after : '');

		// Dodaje dropdown ikonu
//		if ($has_children) {
//			if ($depth === 0) {
//				$item_output .= ' <i class="icofont-rounded-down"></i>';
//			} else {
//				$item_output .= ' <i class="icofont-rounded-right"></i>';
//			}
//		}

		$item_output .= '</a>';
		$item_output .= isset($args->after) ? $args->after : '';

		$output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
	}

	public function end_el(&$output, $item, $depth = 0, $args = null) {
		$output .= "</li>\n";
	}
}