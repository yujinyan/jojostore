<?php
/**
 * Created by PhpStorm.
 * User: yujinyan
 * Date: 2015/9/17
 * Time: 23:46
 */


/**
 * dequeue stylesheets
 */

add_action( 'wp_print_scripts', 'jo_deregister_javascript', 100 );

function jo_deregister_javascript() {
    wp_deregister_script( 'google-maps' );
}

/**
 * enqueue stylesheets
 */

add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

function theme_enqueue_styles() {
    $parent_style = 'parent-style';

 wp_enqueue_script('bootstrapjs',get_stylesheet_directory_uri().'/js/bootstrap.min.js');
    /*wp_enqueue_style('bootstrap',get_stylesheet_directory_uri().'/css/bootstrap.min.css');*/

    wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
    wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css',
        array( $parent_style )
    );

}


/**
 * modify markups for header
 */

add_action('init','jo_remove_storefront_header_search');
add_action('init','jo_remove_storefront_header_cart');
add_action('init','jo_remove_storefront_primary_navigation');
add_action( 'storefront_header', 'storefront_primary_navigation',		15 );

//add_action( 'widgets_init', 'jo_remove_fourteen_sidebars', 11 );

function jo_remove_storefront_header_search(){
    remove_action('storefront_header','storefront_product_search',40);
}
function jo_remove_storefront_header_cart(){
    remove_action('storefront_header','storefront_header_cart',60);
}
function jo_remove_storefront_primary_navigation(){
    remove_action('storefront_header','storefront_primary_navigation',50);
}

function jo_remove_fourteen_sidebars() {
    unregister_sidebar( 'sidebar-1' ); // primary on left
    unregister_sidebar( 'sidebar-2' ); // secondary on right
}



/**
 * modify wooCommerce product page
*/

//remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
//add_action( 'woocommerce_single_product_summary', 'woocommerce_template_product_description', 20 );

function woocommerce_template_product_description() {
    wc_get_template( 'single-product/tabs/additional-information.php' );
    wc_get_template( 'single-product/tabs/description.php' );
}

add_action('woocommerce_after_single_product_summary', create_function( '$args', 'call_user_func(\'comments_template\');'), 14);

/**
 * Change text strings
 *
 * @link http://codex.wordpress.org/Plugin_API/Filter_Reference/gettext
 */

add_filter( 'gettext', 'my_text_strings', 20, 3 );

function my_text_strings( $translated_text, $text, $domain ) {
    switch ( $translated_text ) {
        case '前往收银台' :
            $translated_text = __( '报名路线', 'woocommerce' );
            break;
        case '加入购物车':
            $translated_text=__('报名路线','woocommerce');
            break;
        case '其他信息':
            $translated_text = __( '基本信息','woocommerce');
            break;
        case 'Login':
            $translated_text = __( '登录','default');
            break;
    }
return $translated_text;
}


/**
 * Customize WooCommerce checkout fields
 */

// Hook in
add_filter( 'woocommerce_checkout_fields' , 'jo_custom_override_checkout_fields' );

// Our hooked in function - $fields is passed via the filter!
function jo_custom_override_checkout_fields( $fields ) {
    unset($fields['billing']['billing_company']);
    unset($fields['order']['order_comments']);
    unset($fields['billing']['billing_first_name']);
    unset($fields['billing']['billing_last_name']);
    $fields['billing']['billing_email']['class']=array('form-row-wide');
    $fields['billing']['billing_phone']['class']=array('form-row-wide');

    $fields['billing']['billing_wechat'] = array(
        'label'     => '微信号',
        'placeholder'   => '请输入微信号方便小囧或囧伴联系您',
        'required'  => false,
        'class'     => array('form-row-wide'),
        'clear'     => true
    );
    $fields['billing']['billing_comment'] = array(
        'label'     => '给囧伴留言',
        'placeholder'   => '简单介绍一下自己，以及对项目期待，让囧伴更好地为你服务！',
        'class'     => array('form-row-wide'),
        'type'      => 'textarea',
        'clear'     => true,
        'required'  => true
    );
    $fields['billing']['billing_tour_date'] = array(
        'label'     => '行程开始日期',
        'placeholder'   => '请标注行程开始日期',
        'required'  => true,
        'class'     => array('form-row-wide'),
        'type'      => 'text',
        'clear'     => true,
    );
    $fields['billing']['billing_name'] = array(
        'label'     => '姓名',
        'placeholder'   => '请输入真实姓名',
        'required'  => true,
        'class'     => array('form-row-wide'),
        'type'      => 'text',
        'clear'     => true,
    );
    $fields['billing']['billing_id_card'] = array(
        'label'     => '身份证',
        'placeholder'   => '请输入身份证',
        'required'  => true,
        'class'     => array('form-row-wide'),
        'type'      => 'text',
        'clear'     => true,
    );


    $order=array(
      'billing_name','billing_id_card','billing_email','billing_phone','billing_wechat','billing_tour_date','billing_comment'
    );

    foreach($order as $field){
        $ordered_fields[$field]=$fields['billing'][$field];
    }
    $fields['billing']=$ordered_fields;


    return $fields;
}

// Hook in
add_filter( 'woocommerce_default_address_fields' , 'custom_override_default_address_fields' );

// Our hooked in function - $address_fields is passed via the filter!
function custom_override_default_address_fields( $address_fields ) {
    unset($address_fields['address_1']);
    unset($address_fields['country']);
    unset($address_fields['address_2']);
    unset($address_fields['city']);
    unset($address_fields['state']);
    unset($address_fields['postcode']);
    return $address_fields;
}

// Remove Additional Information section

add_filter('woocommerce_enable_order_notes_field', '__return_false');


/**
 *  Add a custom field (in an order) to the emails
 */

add_action('woocommerce_email_customer_details', 'jo_woocommerce_email_order',20,1);
add_action('woocommerce_admin_order_data_after_billing_address','jo_woocommerce_email_order',10,1);

function jo_woocommerce_email_order( $order ) {
    echo '<p><strong>微信号: </strong>'. '<br>'.get_post_meta($order->id,'_billing_wechat',true).'</p>';
    echo '<p><strong>行程开始日期: </strong>'. '<br>'.get_post_meta($order->id,'_billing_tour_date',true).'</p>';
    echo '<p><strong>留言: </strong>'. '<br>'.get_post_meta($order->id,'_billing_comment',true).'</p>';
}


/**
 *  Customize footer
 */

add_filter('storefront_copyright_text','jo_customize_copyright_text');
function jo_customize_copyright_text() {
    echo esc_html($content = '&copy; ' . get_bloginfo( 'name' ) . ' ' . date( 'Y' ) );
    echo '<br>'.'<p>沪ICP备15043437号</p>';
}

add_filter('storefront_credit_link','jo_off_storefront_credit');
function jo_off_storefront_credit(){
    return false;
}


/**
 *  Enable SVG
 */

function cc_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
//add_filter('upload_mimes', 'cc_mime_types');


/**
 *  Force archive pages to use WooCommerce full width template
 */

add_filter( 'body_class','jo_body_classes' );
function jo_body_classes( $classes ) {
    if(is_archive()){$classes[] = 'page-template-template-fullwidth-php';}

    return $classes;

}


/**
 *  Custom utility functions
 */

// Check if current category has subcategories

function category_has_children($term_id=0, $taxonomy='category'){
    $term_children=get_term_children($term_id,$taxonomy);
    if ( empty( $term_children ) || is_wp_error( $term_children ) )
        {return false;}
    else
        {return true;}
};

// Add to cart button redirect to checkout

add_filter('add_to_cart_redirect','redirect_to_checkout');

function redirect_to_checkout(){
    return WC()->cart->get_checkout_url();
};

/**
 *  Hover effect
 */

// Add Markup

add_action('woocommerce_before_shop_loop_item','jo_add_hover_markup');

function jo_add_hover_markup(){
    echo '<div class="hover-overlay"><a href="';
    echo the_permalink();
    echo '"></a></div>';
};

add_action('woocommerce_before_subcategory','jo_add_hover_markup_cat',10,1);

function jo_add_hover_markup_cat($category){
    echo '<div class="hover-overlay"><a href="';
    echo get_term_link( $category->slug, 'product_cat' );
    echo '"></a></div>';
};




/**
 *  Ultimate Member Custom Validation for id card
 */

add_action('um_custom_field_validation_id','jo_um_my_custom_id_validation', 10,2);
function jo_um_my_custom_id_validation( $key, $array ) {
    echo 'testing';
    global $ultimatemember;
    //if(isset($array['user_email'])){
        $ultimatemember->form->add_error($key, '请使用学邮验证学生身份~' );
//    };

}

/**
 *  Add Analytics
 */

// Baidu

add_action('wp_head','jo_add_baidu_analytics');

function jo_add_baidu_analytics(){
    echo '<script>
var _hmt = _hmt || [];
(function() {
  var hm = document.createElement("script");
  hm.src = "//hm.baidu.com/hm.js?4966dced961d6456a172c5c688c7edd9";
  var s = document.getElementsByTagName("script")[0];
  s.parentNode.insertBefore(hm, s);
})();
</script>';
}

/**
 * WooCommerce Emails
 */

// kf to receive new order notification mails

function jo_new_order_mail_recipient($recipient,$order){



    $jopals='';
    $items=$order->get_items();

    foreach ($items as $item){
        $product_id=$item['product_id'];
        global $jopals;
        $jopals=explode(' ',get_post_meta($product_id,'jopal',true));
    }

    foreach ($jopals as $person){
        $jopal=get_user_by('slug',$person);
        $recipient .= ', '. $jopal->user_email;
    }

    $kf=get_users(array('role'=>'kf'));
    foreach ($kf as $usr){
        $recipient .=', ' . $usr->user_email .', ';
    }

    //$recipient = $recipient . ', ' . $email;
    return $recipient;
}

add_filter('woocommerce_email_recipient_new_order','jo_new_order_mail_recipient',1,2);

