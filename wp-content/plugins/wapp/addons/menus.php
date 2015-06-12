<?php
/*
Controller name: Menus
Controller description: Retrieve menus
*/

class JSON_API_Menus_Controller {

	public function get_menu() {

	    global $json_api;
	    global $titan;

 		$id = $titan->getOption( 'menu' );

	    $menu_output = wp_get_nav_menu_items($id);

		$filtered_items = array_map(function($el){
			$filter = array(
				"id"=>$el->ID,
				"parent_id"=>$el->menu_item_parent,
				"menu_order"=>$el->menu_order,
				"label"=>$el->title,
				"object_type"=>$el->object,
				"object_id"=>($el->object == "custom" ? "" : $el->object_id ),
				"url"=>($el->object == "custom" ? $el->url : "" ),
				"icon" => get_post_meta($el->ID, 'menu-icons')
			);
			return $filter;
		}, $menu_output);

		$output = ( $json_api->query->dev == "1" ? $menu_output : $filtered_items);
		$count = count($output);

		if ($count == "0") {
			$json_api->error("The menu you are looking for is empty or does not exist.");
		} else {
			return array(
				"output" => $output,
				"count" => $count
			);
		}
	}
}

?>