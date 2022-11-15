<?php
/*
Plugin Name: cpp
Description: Plugin for changed prices in woocommerce
Version: 1.0
Author: Alisher
*/

add_action( 'admin_menu', 'cpp_page', 25 );
 
function cpp_page(){
 
	add_menu_page(
		'Price settings', // тайтл страницы
		'CPP', // текст ссылки в меню
		'manage_options', // права пользователя, необходимые для доступа к странице
		'cpp', // ярлык страницы
		'cpp_page_callback', // функция, которая выводит содержимое страницы
		// 'data:image/svg+xml;base64,' . base64_encode( '<svg><use xlink:href="./assets/svg/sprites.svg#menu_icon"></use></svg> '),
        'data:image/svg+xml;base64,' . base64_encode('<svg width="20" height="20" viewBox="0 0 2048 1792" xmlns="http://www.w3.org/2000/svg"><path fill="black" d="M704 576q0 80-56 136t-136 56-136-56-56-136 56-136 136-56 136 56 56 136zm1024 384v448h-1408v-192l320-320 160 160 512-512zm96-704h-1600q-13 0-22.5 9.5t-9.5 22.5v1216q0 13 9.5 22.5t22.5 9.5h1600q13 0 22.5-9.5t9.5-22.5v-1216q0-13-9.5-22.5t-22.5-9.5zm160 32v1216q0 66-47 113t-113 47h-1600q-66 0-113-47t-47-113v-1216q0-66 47-113t113-47h1600q66 0 113 47t47 113z"/></svg>'),
		20 // позиция в меню
	);
}
 
function cpp_page_callback(){
	echo 'привет';
}

?>