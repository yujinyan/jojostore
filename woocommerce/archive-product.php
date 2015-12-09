<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


get_header( 'shop' ); ?>



	<?php
		/**
		 * woocommerce_before_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
		 * @hooked woocommerce_breadcrumb - 20
		 */
		do_action( 'woocommerce_before_main_content' );
	?>

<!--		<?php /*if ( apply_filters( 'woocommerce_show_page_title', true ) ) : */?>

			<h1 class="page-title"><?php /*woocommerce_page_title(); */?></h1>

		--><?php /*endif; */?>



		<?php if ( have_posts() ) : ?>

			<?php
				/**
				 * woocommerce_before_shop_loop hook
				 *
				 * @hooked woocommerce_result_count - 20
				 * @hooked woocommerce_catalog_ordering - 30
				 */
				//do_action( 'woocommerce_before_shop_loop' );
			?>

			<?php
			global $wp_query;
			$current_cat_obj = $wp_query->get_queried_object();
			$current_cat_obj_parent=get_term_by('id',$current_cat_obj->parent,'product_cat');
			/*echo '<pre>';
			echo print_r($current_cat_obj);
			echo '</pre>';*/

			if($current_cat_obj->post_type=='page'){
				//echo '针对全部商品页';
				/**
				 * woocommerce_before_shop_loop hook
				 *
				 * @hooked woocommerce_result_count - 20
				 * @hooked woocommerce_catalog_ordering - 30
				 */
				//do_action( 'woocommerce_before_shop_loop' );

				/*woocommerce_catalog_ordering();
			 	woocommerce_product_loop_start();
				woocommerce_product_subcategories();
				while ( have_posts() ) : the_post();

					 wc_get_template_part( 'content', 'product' );

				endwhile; // end of the loop.

			 	woocommerce_product_loop_end();*/

				$IDbyNAME = get_term_by('slug', 'theme', 'product_cat');
				$product_cat_ID = $IDbyNAME->term_id;
				$args = array(
					'hierarchical' => 1,
					'show_option_none' => '',
					'hide_empty' => 0,
					'parent' => $product_cat_ID,
					'taxonomy' => 'product_cat',
				);
				$subcats = get_categories($args);
				//print_r($subcats);
				echo '<ul class="nav nav-tabs">';
				$counter=0;
				foreach ($subcats as $key=>$sc) {

					$productCheck = new WP_Query
					(array
						(
							'post_type' => 'product',
							'post_status' => 'publish',
							'tax_query' =>
								array(
									array(
										'taxonomy' => 'product_cat',
										'terms' => array($sc->slug),
										'field' => 'slug',
									)
								)
						)
					);
					if ($productCheck->post_count == 0){
						unset($subcats[$key]);
						continue;
					}

					if ($counter==0){
						echo '<li class="active"><a href="#'.$sc->slug.'"data-toggle="tab">';
					}else{
						echo '<li><a href="#'.$sc->slug.'"data-toggle="tab">';
					}
					echo $sc->name;
					echo '</a></li>';
					$counter++;
				};
				$counter=0;

				echo '</ul>';
				echo '<div class="tab-content">';

				foreach ($subcats as $sc) {


					if ($counter==0){
						echo '<div id="'.$sc->slug.'" class="tab-pane active">';
					} else{
						echo '<div id="'.$sc->slug.'" class="tab-pane">';
					}

					/*echo '<h3>' . $sc->name . '</h3>';*/
					$shortcode_str = '[product_category category="' . $sc->slug . '" columns="3" per_page=100]';
					//echo $shortcode_str;
					echo do_shortcode($shortcode_str);
					echo '</div>';
					$counter++;


				};

				//echo do_shortcode('[products columns=3 per_page=100]');
			}

			elseif(category_has_children($current_cat_obj->term_id,'product_cat') and $current_cat_obj->taxonomy=='product_cat'){
				//echo "针对“城市”、“特色”等一级category页";
				$shortcode_str='[product_categories parent="'. $current_cat_obj->term_id .'" columns=3 per_page=100]';
				echo do_shortcode($shortcode_str);
			}

			elseif (get_term_by('id',$current_cat_obj->parent,'product_cat')->slug=='city') {
				//echo "城市”页下根据“特色”分类显示路线";
				$IDbyNAME = get_term_by('slug', 'theme', 'product_cat');
				$product_cat_ID = $IDbyNAME->term_id;
				$args = array(
					'hierarchical' => 1,
					'show_option_none' => '',
					'hide_empty' => 0,
					'parent' => $product_cat_ID,
					'taxonomy' => 'product_cat',
				);
				$subcats = get_categories($args);
				//print_r($subcats);
				echo '<ul class="nav nav-tabs">';
				$counter=0;
				foreach ($subcats as $key=>$sc) {

					$productCheck = new WP_Query
					(array
						(
							'post_type' => 'product',
							'post_status' => 'publish',
							'tax_query' =>
								array(
									array(
										'taxonomy' => 'product_cat',
										'terms' => array($sc->slug, $current_cat_obj->slug),
										'field' => 'slug',
										'operator' => 'AND'
									)
								)
						)
					);
					if ($productCheck->post_count == 0){
						unset($subcats[$key]);
						continue;
					}

					if ($counter==0){
						echo '<li class="active"><a href="#'.$sc->slug.'"data-toggle="tab">';
					}else{
						echo '<li><a href="#'.$sc->slug.'"data-toggle="tab">';
					}
					echo $sc->name;
					echo '</a></li>';
					$counter++;
				};
				$counter=0;

				echo '</ul>';
				echo '<div class="tab-content">';

				foreach ($subcats as $sc) {


						if ($counter==0){
							echo '<div id="'.$sc->slug.'" class="tab-pane active">';
						} else{
							echo '<div id="'.$sc->slug.'" class="tab-pane">';
						}

						/*echo '<h3>' . $sc->name . '</h3>';*/
						$shortcode_str = '[product_category category="' . $current_cat_obj->slug . ',' . $sc->slug . '" operator="AND" columns="3" per_page=100]';
						//echo $shortcode_str;
						echo do_shortcode($shortcode_str);
						echo '</div>';
						$counter++;


				};
			}

			else{
				//echo "其他";
				$shortcode_str = '[product_category category="' . $current_cat_obj->slug . '" columns="3" per_page="100"]';
				//echo $shortcode_str;
				echo do_shortcode($shortcode_str);
			};




			?>

			<?php
				/**
				 * woocommerce_after_shop_loop hook
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				//do_action( 'woocommerce_after_shop_loop' );
			?>

		<?php elseif ( ! woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>

			<?php wc_get_template( 'loop/no-products-found.php' ); ?>

		<?php endif; ?>

	<?php
		/**
		 * woocommerce_after_main_content hook
		 *
		 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
		 */
		do_action( 'woocommerce_after_main_content' );
	?>

	<?php
		/**
		 * woocommerce_sidebar hook
		 *
		 * @hooked woocommerce_get_sidebar - 10
		 */
		do_action( 'woocommerce_sidebar' );
	?>

<?php get_footer( 'shop' ); ?>
