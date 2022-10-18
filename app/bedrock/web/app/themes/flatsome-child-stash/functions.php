<?php
// Add custom Theme Functions here
// BEGIN ENQUEUE PARENT ACTION
// AUTO GENERATED - Do not modify or remove comment markers above or below:

if ( !function_exists( 'chld_thm_cfg_locale_css' ) ):
    function chld_thm_cfg_locale_css( $uri ){
        if ( empty( $uri ) && is_rtl() && file_exists( get_template_directory() . '/rtl.css' ) )
            $uri = get_template_directory_uri() . '/rtl.css';
        return $uri;
    }
endif;
add_filter( 'locale_stylesheet_uri', 'chld_thm_cfg_locale_css' );

// END ENQUEUE PARENT ACTION




/**
 * WOOCOMMERCE
 *
 * 
 * 
 */


//  CHECKOUT PAGE
// Adds thumbnail to the product item list
add_filter( 'woocommerce_cart_item_name', 'quadlayers_product_image_checkout', 9999, 3 ); 
function quadlayers_product_image_checkout( $name, $cart_item, $cart_item_key ) {
    if ( ! is_checkout() ) 
        {return $name;}
    $product = $cart_item['data'];
    $thumbnail = $product->get_image( array( '100', '100' ), array( 'class' => 'alignleft thumbnail' ) ); 
    /*Above you can change the thumbnail size by changing array values e.g. array(‘100’, ‘100’) and also change alignment to alignright*/
    return $thumbnail . $name;
}

//  PRODUCT PAGE
// Add "Cantidad:" on Product page
function echo_qty_front_add_cart() {
    echo '<div class="qty">Cantidad: </div>'; 
}
add_action( 'woocommerce_before_add_to_cart_quantity', 'echo_qty_front_add_cart' );


// CATALOG | CATEGORY
// Breadcrumbs formatted
function ts_woocommerce_breadcrumbs_change() {
    return array(
        'delimiter' => ' <i class="icon-angle-right"></i> ',
        'wrap_before' => '<nav class="woocommerce-breadcrumb" itemprop="breadcrumb" style="margin-left:5%">',
        'wrap_after' => '</nav>',
        'before' => 'Category: ',
        'after' => '',
        'home' => _x( 'SuperStore', 'breadcrumb', 'woocommerce' ),
    );
}
add_filter( 'woocommerce_breadcrumb_defaults', 'ts_woocommerce_breadcrumbs_change' );



// Pasar orden a PROCESSING en MP
if ( WP_ENV === 'development' ) {
    add_action( 'woocommerce_thankyou', 'letsgo_auto_processing_orders');
    function letsgo_auto_processing_orders( $order_id ) {
        if ( ! $order_id )
            return;
            $order = wc_get_order( $order_id );
            //ID's de las pasarelas de pago a las que afecta
            $paymentMethods = array( 'woo-mercado-pago-basic' );
            if ( !in_array( $order->payment_method, $paymentMethods ) ) return;
            // If order is “pending” update status to “processing”
            if( $order->has_status( 'pending' ) ) {
                $order->update_status( 'completed' );
            } 
    }
}


// Pasar orden a ON-HOLD a PENDING PAYMENT, para Bank Transfer
add_action( 'woocommerce_thankyou', 'woocommerce_auto_processing_orders');
function woocommerce_auto_processing_orders( $order_id ) {
    if ( ! $order_id )
        return;

    $order = wc_get_order( $order_id );

    // If order is "on-hold" update status to "pending payment"
    if( $order->has_status( 'on-hold' ) ) {
        $order->update_status( 'pending payment' );
    }
}


/**
 * AUTOMATEWOO
 *
 * 
 * 
 */

// Register a template by adding a slug and name to the $templates array
add_filter( 'automatewoo_email_templates', 'my_automatewoo_email_templates' );
function my_automatewoo_email_templates( $templates ) {
	$templates['customer-representative-1'] = 'Email Mkt - Customer Representative #1';
	$templates['text-only'] = 'Email Mkt - Text Only';
	$templates['full-banner'] = 'Email Mkt - Full Banner';
	return $templates;
}   

// Add custom variables to the list
add_filter( 'automatewoo/variables', 'my_automatewoo_variables' );
function my_automatewoo_variables( $variables ) {
	// Add var to display total savings ($)
	$variables['cart']['savings'] = dirname(__FILE__) . '/automatewoo/variables/cart-total-savings.php';
	return $variables;
}


// Add custom product template
add_filter( 'automatewoo/variables/product_templates', 'my_automatewoo_product_templates' );
function my_automatewoo_product_templates( $templates ) {
	$templates['product-rows-buy-button.php'] = 'Product Rows - "buy now"';
	$templates['product-rows-buy-button-counter.php'] = 'Product Rows - "buy now" + counter';
	return $templates;
}



/**
 * FLATSOME - PAYMENT ICONS
 *
 * File named icon-bitpay.svg.php needs the key bitpay
 * File named icon-custom-paypal.svg.php needs the key custom-paypal
 */
add_filter( 'flatsome_payment_icons', function ( $icons ) {
	$icons['mercadopago'] = 'Mercado Pago';
	$icons['pagofacil'] = 'Pago Facil';
	$icons['rapipago'] = 'Rapipago';
	$icons['transfbancaria'] = 'Transferencia Bancaria';
	return $icons;
} );


/**
 * WORDPRESS
 *
 * 
 * 
 */

//  Create a new role
add_role( 'lead', __( 'Lead' ), array(
    'read' => true, 
  ));
    
//   Assign new role to 
add_filter( 'woocommerce_new_customer_data', 'wp_assign_custom_role' );
    function wp_assign_custom_role( $args ) {
    $args['role'] = 'lead';
    return $args;
}

// Disable XML-RPC
add_filter('xmlrpc_enabled', '__return_false');




// TEEEEEEEESTING
function color_customizer($wp_customize){
    $wp_customize->add_section( 'theme_extra_styles', array(
        'title' => __( 'Extra styles', 'extra_styles' ),
        'priority' => 5,
      ) );
  
      $theme_colors = array();
  
      // Navigation Background Color
      $theme_colors[] = array(
        'slug'=>'color_top_seller',
        'default' => '#000000',
        'label' => __('Top seller', 'themeslug')
      );

      $theme_colors[] = array(
        'slug'=>'color_new_arrival',
        'default' => '#000000',
        'label' => __('New arrival', 'themeslug')
      );      
  
      foreach( $theme_colors as $color ) {
  
        $wp_customize->add_setting(
          $color['slug'], array(
            'default' => $color['default'],
            'sanitize_callback' => 'sanitize_hex_color',
            'type' => 'theme_mod',
            'capability' => 'edit_theme_options'
          )
        );
  
        $wp_customize->add_control(
          new WP_Customize_Color_Control(
            $wp_customize,
            $color['slug'],
            array('label' => $color['label'],
            'section' => 'theme_extra_styles',
            'settings' => $color['slug'])
          )
        );
      }
    }
  
    add_action( 'customize_register', 'color_customizer' );

    function themename_customize_register($wp_customize){
        $wp_customize->add_setting( 'test_setting', array(
            'default'        => 'value_xyz',
            'capability'     => 'edit_theme_options',
            'type'           => 'theme_mod',
        ));
        $wp_customize->add_control( 'test_control', array(
            'label'      => __('Text Test', 'themename'),
            'section'    =>  'spacious_slider_number_section1',
            'settings'   => 'test_setting',
        ));
}
add_action('customize_register', 'themename_customize_register');


// HEADER JS
/* Inline script printed out in the header */
add_action('wp_head', 'add_custom_js_wp_head');
function add_custom_js_wp_head() {
    ?>
        <script>
            function GTM_push_custom_event(eventName){
                window.dataLayer = window.dataLayer || [];
                window.dataLayer.push({
                'event': eventName
                });
                return
            }
        </script>
    <?php
}

// FOOTER JS
/* Inline script printed out in the footer */
add_action('wp_footer', 'add_custom_js_wp_footer');
function add_custom_js_wp_footer() {
    ?>
        <script>
            var container_addonFonts = jQuery(".ywapo_group_container:has(h3:contains('Fuente'))")
            if(container_addonFonts.length) {
                jQuery( container_addonFonts ).addClass('container-addon-fonts');
                console.log('addon fonts')
            }
            
            var container_addonColors = jQuery(".ywapo_group_container:has(h3:contains('Colores'))")
            if(container_addonColors.length) {
                jQuery( container_addonColors ).addClass('container-addon-colors');
                console.log('addon colors')
            }
            
            var container_addonExpedite = jQuery(".ywapo_group_container:has(h3:contains('Express'))")
            if(container_addonExpedite.length) {
                jQuery( container_addonExpedite ).addClass('container-addon-expedite');
                console.log('addon expedite')
            }
            
            var container_addonExpedite = jQuery(".ywapo_group_container:has(h3:contains('Emoji'))")
            if(container_addonExpedite.length) {
                jQuery( container_addonExpedite ).addClass('container-addon-emoji');
                console.log('addon expedite')
            }                
        </script>
    <?php
}


?>



