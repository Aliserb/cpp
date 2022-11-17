<?php

function cpp_register() {
	
	wp_enqueue_script( 'jquery' );
 
	wp_enqueue_script( 'cpp_frontend_js', CPP_URL . 'assets/js/script.js','','',true);

	wp_localize_script( 
		'cpp_frontend_js', 
		'cpp_frontend_script', 
		array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ),
	);
}
add_action('admin_enqueue_scripts', 'cpp_register');

add_action( 'admin_enqueue_scripts', 'cpp_style_admin', 25 );
 
function cpp_style_admin() {
 	wp_enqueue_style( 'cpp_style', CPP_URL . 'assets/css/style.css' );
}
?>