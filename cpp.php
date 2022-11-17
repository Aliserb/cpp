<?php
/*
Plugin Name: cpp
Description: Plugin for changed prices in woocommerce
Version: 1.0
Author: Alisher
*/

define('CPP_FOLDER', plugin_basename(dirname(__FILE__)));
define('CPP_DIR', WP_PLUGIN_DIR . '/' . CPP_FOLDER);
define('CPP_URL', plugins_url( '/', __FILE__ ));

require_once CPP_DIR . '/cpp-hooks.php';


add_action( 'admin_menu', 'cpp_page', 25 );
 
function cpp_page(){
 
	add_menu_page(
		'Price settings',
		'CPP', 
		'manage_options', 
		'cpp', 
		'cpp_page_callback', 
        'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" shape-rendering="geometricPrecision" text-rendering="geometricPrecision" image-rendering="optimizeQuality" fill-rule="evenodd" clip-rule="evenodd" viewBox="0 0 512 388.14"><path d="m360 .02 21.53 1.12c10.07.51 17.88 9.18 17.35 19.23l-1.03 19.95c9.64 3.42 18.64 8.11 26.85 13.82l15.59-14.05c7.49-6.75 19.12-6.16 25.88 1.34l14.43 16c6.76 7.49 6.15 19.13-1.33 25.88l-16.73 15.11c4.1 8.45 7.18 17.49 9.1 26.93l22.97 1.19c10.07.51 17.88 9.16 17.36 19.24l-1.11 21.52c-.51 10.07-9.18 17.88-19.24 17.36l-23.2-1.2a113.69 113.69 0 0 1-11.88 25.56l15.37 17.05c6.75 7.48 6.15 19.13-1.34 25.88l-16 14.44c-7.49 6.75-19.13 6.13-25.88-1.34l-14.53-16.1c-8.53 4.66-17.71 8.26-27.34 10.63v-56.17c20.01-8.72 34.46-28.18 35.66-51.46 1.69-32.79-23.52-60.74-56.31-62.43-19.74-1.01-37.73 7.72-49.29 21.99h-66.17c3.42-10.11 8.2-19.55 14.12-28.14l-10.52-11.66c-6.75-7.48-6.15-19.13 1.33-25.88l16.02-14.44c7.47-6.74 19.12-6.14 25.87 1.34l11.38 12.6c9.64-4.74 20.03-8.15 30.91-10.02l.93-17.92C341.28 7.32 349.93-.49 360 .02zM188.23 257.05h-25.91c-.83 0-1.47.66-1.47 1.49v54.6c0 .83.64 1.49 1.47 1.49h25.88c.83 0 1.48-.66 1.48-1.49v-54.6c0-.82-.62-1.49-1.45-1.49zM19.91 141.15h93.21v-25.76c0-7.76 6.34-14.1 14.09-14.1h96.13c7.75 0 14.09 6.34 14.09 14.1v25.76h93.21c10.9 0 19.91 9.02 19.91 19.91v48.1c-22.51 15.43-45.73 28.58-69.67 39.31-24.07 10.78-48.89 19.14-74.57 24.91v-19.23c0-8.88-7.15-16.04-16.03-16.04h-30.04c-8.88 0-16.03 7.16-16.03 16.04v18.79C119.22 267.21 95 259 71.52 248.47 46.93 237.46 23.1 223.85 0 207.87v-46.81c0-10.91 9.01-19.91 19.91-19.91zm330.64 96.54v130.54a19.7 19.7 0 0 1-5.87 14.04c-3.63 3.61-8.6 5.87-14.04 5.87H19.91c-5.5 0-10.46-2.26-14.03-5.87C2.25 378.64 0 373.68 0 368.23V236.49c19.65 12.6 39.88 23.62 60.68 32.94 26.85 12.05 54.69 21.26 83.55 27.42v20.68c0 8.88 7.16 16.04 16.04 16.04h30.04c8.87 0 16.03-7.16 16.03-16.04v-20.3c29.41-6.16 58.01-15.52 85.42-27.82 20.14-9.02 39.73-19.63 58.79-31.72zm-212.56-117.2c-.95 0-1.79.85-1.79 1.8v18.31h78.13v-18.31c0-.96-.85-1.8-1.79-1.8h-74.55z"/></svg>'),
		20 // позиция в меню
	);
}
 
function cpp_page_callback(){
?>
	<form action="#" method="POST">
        <input type="text" id="cpp_enter_price" name="cpp_enter_price" placeholder="Enter price">
        
		<?php
			$args = array(
				'post_type' => 'product',
				'post_status' => 'publish',
				'posts_per_page' => -1,
				'orderby' => 'date',
				'order' => 'DESC',
				'meta_query' => array(
					'key' => '_stock_status',
					'value' => 'instock'
				),
			);

			$query = new WP_Query( $args );
			// echo '<pre>';
			// print_r($query);
			// echo '</pre>';
		?>
		<select name="cpp_wc_products" id="cpp_wc_products">
			<?php
				if ( $query->have_posts() ) :
					while ( $query->have_posts() ) :
						$query->the_post();
						global $post;
						$product = wc_get_product($post->ID);
			?>
						<option value="<?php echo $post->ID; ?>" id="variable_<?php echo $post->ID; ?>" data-product-type="<?php
							if( $product->is_type( 'variable' ) ){
								echo 'variable';
							} elseif( $product->is_type( 'simple' ) ){
							   echo 'simple';
							}
						?>">
						<?php the_title(); 
						?></option>
			<?php
						if (isset($_POST['cpp_enter_price']) && isset($_POST['cpp_wc_products'])) {
							$new_price = $_POST['cpp_enter_price'] * 3;
							$product_id = $_POST['cpp_wc_products'];
							$product = wc_get_product($product_id);
							
							// if (!$product) return '';
				
							$product->set_regular_price($new_price);
							$product->save();
						}
					endwhile;
				endif;
			?>
        </select>

		<?php
			$args_variable = array (
				'post_type' => 'product',
				'post_status' => 'publish',
				'posts_per_page' => -1,
				'orderby' => 'date',
				'order' => 'DESC',
				'meta_query' => array(
					'key' => '_stock_status',
					'value' => 'instock'
				),
				'tax_query' => array(
					array(
						'taxonomy' => 'product_type',
						'field'    => 'slug',
						'terms'    => 'variable', 
					),
				),
			);

			$query_variable = new WP_Query( $args_variable );
		?>

		<?php
			if ( $query_variable->have_posts() ) :
		?>
			<?php
				while ( $query_variable->have_posts() ) :
					$query_variable->the_post();
					global $post;
					$product = wc_get_product($post->ID);
					if( $product->is_type( 'variable' ) ){
						// if (isset()) {
							
						// }
					}
			?>
			<table class="cpp_wc_variable_table" data-id="variable_<?php echo $post->ID; ?>">
				<thead>
					<tr>
						<th>
							Выбрать
						</th>

						<th>
							Название вариации
						</th>

						<th>
							Цена
						</th>
					</tr>
				</thead>
				
				<tbody>
				<?php
				//$variation_price = $product_variation->get_price_html();
				// если товар вариантивный
				if ($product->is_type( 'variable' )) :
					//получаем варианты
					$variablePrice = $product->get_variation_regular_price('max', true);
					$available_variations = $product->get_available_variations();
					foreach ($available_variations as $key => $value) :
						foreach($value['attributes'] as $key => $valueKey) :
				?>
					<tr>
							<td>
								<input type="checkbox" class="variable_key" name="variableKey" id="<?php echo $key . $valueKey ?>" value="<?php echo $key . $valueKey ?>">
							</td>
							<td>
								<label for="<?php echo $key . $valueKey ?>">
									<?php echo $valueKey; ?>
								</label>
							</td>
							<td>
								<?php echo $value['display_price']; ?>
							</td>
					</tr>
				<?php
						endforeach;

						
						// echo '<pre>';
						// print_r($value);
						// echo '</pre>';
					endforeach;
				endif;
				?>
				</tbody>
			</table>
			<?php
				if (isset($_POST['variableKey'])) {
					echo $_POST['variableKey'];
					
				}

				endwhile;
			?>
		<?php
			endif;
		?>

        <button type="submit">Save</button>
    </form>
<?php
}
?>