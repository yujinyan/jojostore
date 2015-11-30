<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/woocommerce/content-single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

?>

<?php
	/**
	 * woocommerce_before_single_product hook
	 *
	 * @hooked wc_print_notices - 10
	 */
	 do_action( 'woocommerce_before_single_product' );

	 if ( post_password_required() ) {
	 	echo get_the_password_form();
	 	return;
	 }
?>

<aside id="product-sidebar">
	<?php
	/**
	 * woocommerce_before_single_product_summary hook
	 *
	 * @hooked woocommerce_show_product_sale_flash - 10
	 * @hooked woocommerce_show_product_images - 20
	 */
	//do_action( 'woocommerce_before_single_product_summary' );
	woocommerce_show_product_sale_flash();
	$jopals=explode(' ',get_post_meta(get_the_ID(),'jopal',true));

	foreach ($jopals as $person){
		$jopal=get_user_by('slug',$person);
/*		echo '<pre>';
		echo print_r($jopal);
		echo '</pre>';*/
		echo get_avatar($jopal->ID,300);
		echo '<div id="iam">我是囧伴 '. ucfirst($jopal->display_name).'</div>';
		echo '<a class="button ask-button" href="'. get_bloginfo("wpurl").'/pm?fepaction=newmessage&to='. $jopal->user_nicename.'&message_title=';
		echo single_post_title(). '">咨询囧伴</a>';
	}


	//woocommerce_show_product_images();

	woocommerce_template_single_add_to_cart(); // text display modified in function.php using gettext filter
	woocommerce_template_single_price();
	?>





</aside>

<div itemscope itemtype="<?php echo woocommerce_get_product_schema(); ?>" id="product-<?php the_ID(); ?>" <?php post_class(); ?>>



	<div class="summary entry-summary">

		<?php
			/**
			 * woocommerce_single_product_summary hook
			 *
			 * @hooked woocommerce_template_single_title - 5
			 * @hooked woocommerce_template_single_rating - 10
			 * @hooked woocommerce_template_single_price - 10
			 * @hooked woocommerce_template_single_excerpt - 20
			 * @hooked woocommerce_template_single_add_to_cart - 30
			 * @hooked woocommerce_template_single_meta - 40
			 * @hooked woocommerce_template_single_sharing - 50
			 */
			//do_action( 'woocommerce_single_product_summary' );
		//woocommerce_template_single_title();
		//woocommerce_template_single_meta();
		woocommerce_template_single_sharing();
		woocommerce_template_single_rating();
		woocommerce_template_single_excerpt();
		//woocommerce_button_proceed_to_checkout();
		//woocommerce_template_single_add_to_cart();




		?>

	</div><!-- .summary -->

	<?php
		/**
		 * woocommerce_after_single_product_summary hook
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_upsell_display - 15
		 * @hooked woocommerce_output_related_products - 20
		 */
		//do_action( 'woocommerce_after_single_product_summary' );
		//woocommerce_output_product_data_tabs();
		wc_get_template( 'single-product/tabs/additional-information.php' );
    	wc_get_template( 'single-product/tabs/description.php' );
		comments_template();

	?>



	<meta itemprop="url" content="<?php the_permalink(); ?>" />

</div><!-- #product-<?php the_ID(); ?> -->

<?php do_action( 'woocommerce_after_single_product' ); ?>
