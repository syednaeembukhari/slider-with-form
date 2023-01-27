<?php

function add_adminmenu()
{
	$page_title="Slider With Form"; 
	$capability="1"; 
	$menu_title="Slider With Form"; 

	if ( current_user_can('manage_options') ) {

		$menu_title="Slider With Form"; $capability="1";  
		$menu_slug=SWF_SLUG; 
		$function="protected_files_loader"; 
		$icon_url=""; 
		$position=10;
		add_menu_page( $page_title, $menu_title, $capability, $menu_slug, $function, $icon_url, $position );
	}
}

add_action( 'admin_menu', 'add_adminmenu' );