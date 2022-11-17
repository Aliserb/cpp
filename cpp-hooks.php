<?php

function cpp_register() {
	
	wp_enqueue_script( 'jquery' );
 
	wp_enqueue_script( 'rws_frontend_js', RWS_URL . 'assets/js/script.js');

	wp_localize_script( 
		'rws_frontend_js', 
		'rws_frontend_script', 
		array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ),
	);
}
add_action('wp_enqueue_scripts', 'cpp_register');
?>