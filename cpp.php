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
							$new_price = $_POST['cpp_enter_price'];
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
			?>
			<table class="cpp_wc_variable_table" data-id="variable_<?php echo $post->ID; ?>">
				<?php
				// если товар вариантивный
				if ($product->is_type( 'variable' )) :
					//получаем варианты
					$available_variations = $product->get_available_variations();
					foreach ($available_variations as $key => $value) :
						foreach($value['attributes'] as $key => $valueKey) :
				?>
					<tr>
							<td>
								<input type="checkbox" name="<?php echo $valueKey ?>" id="<?php echo $key . $valueKey ?>" value="<?php echo $key . $valueKey ?>">
							</td>
							<td>
								<label for="<?php echo $key . $valueKey ?>">
									<?php echo $valueKey; ?>
								</label>
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
			</table>
			<?php
				endwhile;
			?>
		<?php
			endif;
		?>

		<script>
			let selectProduct = document.querySelector('#cpp_wc_products');

			selectProduct.onchange = function(){   
				let selectOption = this.selectedOptions[0].getAttribute('data-product-type');
				let selectOptionId = this.selectedOptions[0].id;

				let variableTable = document.querySelectorAll('.cpp_wc_variable_table');
				variableTable.forEach(table => {
					if (selectOption == 'simple' && table.classList.contains('active')) {
						table.classList.remove('active')
					}

					let variableTableId = table.getAttribute('data-id');

					if (variableTableId == selectOptionId) {
						table.classList.add('active');
					} else {
						table.classList.remove('active');
					}
				})
			};
		</script>

		<style>
			.cpp_wc_variable_table {
				display: none;
			}

			.cpp_wc_variable_table.active {
				display: block;
			}
		</style>

        <button type="submit">Save</button>
    </form>

	<?php
		// do_action( 'woocommerce_init', 'update_prices' );
	
		// function update_prices() {
		// 	if (isset($_POST['cpp_enter_price']) && isset($_POST['cpp_wc_products'])) {
		// 		$new_price = $_POST['cpp_enter_price'];
		// 		$product_id = $_POST['cpp_wc_products'];
		// 		$product = wc_get_product($product_id);

		// 		// if (!$product) return '';
	
		// 		$product->set_regular_price($new_price);
		// 		$product->save();
		// 	}
		// }
	?>

<?php
}
?>