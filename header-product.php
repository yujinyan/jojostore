<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package storefront
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?> <?php storefront_html_tag_schema(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div id="page" class="hfeed site">
	<?php
	do_action( 'storefront_before_header' ); ?>

	<header id="masthead" class="site-header" role="banner">

		<div class="col-full">
			<div id="hero-product"
				<?php if(get_post_meta(get_the_ID(),'hero_image_link',true))
					//{echo 'style="background-image: url(' . esc_url( get_post_meta(get_the_ID(),'hero_image_link',true) ) . ');"'; }
					{echo 'style="background-image: url(' .wp_get_attachment_url( get_post_thumbnail_id() ) . ');"'; }
				else
					{echo 'style="background-image: url(' . esc_url( "http://localhost/jojotour/wp-content/uploads/2014/11/ZLSw0SXxThSrkXRIiCdT_DSC_0345-1024x680.jpg") . ');"'; }

				?>>

		</div>


		<div class="headerwrapper">
			<?php
			/**
			 * @hooked storefront_skip_links - 0
			 * @hooked storefront_social_icons - 10
			 * @hooked storefront_site_branding - 20
			 * @hooked storefront_secondary_navigation - 30
			 * @hooked storefront_product_search - 40
			 * @hooked storefront_primary_navigation - 50
			 */
			//do_action( 'storefront_header' );
			storefront_primary_navigation();
			echo '<h1 class="page-title">';
			echo woocommerce_template_single_title();
			echo '</h1>';
			?>
		</div>


	</header><!-- #masthead -->

	<?php
	/**
	 * @hooked storefront_header_widget_region - 10
	 */
	do_action( 'storefront_before_content' ); ?>

	<div id="content" class="site-content" tabindex="-1">
		<div class="col-full">



		<?php
		/**
		 * @hooked woocommerce_breadcrumb - 10
		 */
		//do_action( 'storefront_content_top' ); ?>
