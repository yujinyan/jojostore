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
<link rel="shortcut icon" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.png" />

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>


<div id="page" class="hfeed site">
	<?php
	do_action( 'storefront_before_header' );
	global $wp_query;
	// get the query object
	$cat_obj = $wp_query->get_queried_object();
	if (get_class($cat_obj)=='stdClass'){
		$thumbnail_id=get_woocommerce_term_meta($cat_obj->term_id,'thumbnail_id',true);
		$image=wp_get_attachment_url($thumbnail_id);
	} else{
		$image=wp_get_attachment_image_src( get_post_thumbnail_id( $cat_obj->ID ), 'single-post-thumbnail' );
		$image=$image[0];
	}

	?>

	<header id="masthead" class="site-header" role="banner">
		<div class="col-full">
			<div class="shop-hero" style="background: url(<?php echo $image ?>) center center;background-size:cover;height: 500px;">

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
