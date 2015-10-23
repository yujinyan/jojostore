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
	do_action( 'storefront_before_header' );
	?>

	<header id="masthead" class="site-header" role="banner">
		<div class="col-full">
			<?php
/*				/*echo do_shortcode('[pjc_slideshow slide_type="home-header-slide"]');*/
				$recent_posts=wp_get_recent_posts(array('numberposts'=>1,'post_type'=>'product','post_status'=>'publish'));
				/*print_r($recent_posts[0]);*/
				/*echo($recent_posts[0]['ID']);*/
				$image=wp_get_attachment_url(get_post_thumbnail_id($recent_posts[0]['ID']));
/*				echo '<div class="headerslider"';
				echo ' style="background-image:url("'. $img .'"); background-size:"cover";>';*/

			?>
			<div class="shop-hero" style="background: url(<?php echo $image ?>) center center;background-size:cover;height: 750px;">



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
				storefront_skip_links();
				storefront_social_icons();
				storefront_primary_navigation();

				echo '<h1 class="page-title">';
				echo woocommerce_page_title();
				echo '</h1>';
				do_action( 'woocommerce_archive_description' );

				?>
			</div>

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
		do_action( 'storefront_content_top' ); ?>
