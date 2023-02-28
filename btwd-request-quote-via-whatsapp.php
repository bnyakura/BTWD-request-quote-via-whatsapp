<?php
/**
 * Plugin Name: Request quote via WhatsApp
 * Description: Easily add Whatsapp link to request quote in woocommerce product.
 * Plugin URI: https://github.com/bnyakura/BTWD-request-quote-via-whatsapp
 * Author: Brandon TheWebDev
 * Version: 1.0
 * Author URI: https://bnyakura.github.io
 
 */

 function myplugin_enqueue_styles() {
    wp_enqueue_style( 'myplugin-style', plugins_url( 'css/style.css', __FILE__ ) );
}
add_action( 'wp_enqueue_scripts', 'myplugin_enqueue_styles' );


function whatsapp_quote_button_shortcode( $atts ) {
  global $product;
  if ( $product && is_product() ) {
    $whatsapp_number = get_option( 'whatsapp_number' );
    $whatsapp_url = 'https://api.whatsapp.com/send?phone=' . $whatsapp_number . '&text=Hi,%20I%20would%20like%20to%20make%20an%20enquiry%20on%20' . $product->get_name() . '%20(product%20link:%20' . get_permalink() . ')';
    return '<a href="' . $whatsapp_url . '" target="_blank" rel="noopener noreferrer">Request Quote on WhatsApp</a>';
  }
}
add_shortcode( 'whatsapp_quote_button', 'whatsapp_quote_button_shortcode' );


function whatsapp_quote_button_settings_init() {
    add_settings_section( 'whatsapp_quote_button_settings', 'WhatsApp Quote Button Settings', 'whatsapp_quote_button_settings_callback', 'general' );
    add_settings_field( 'whatsapp_number', 'WhatsApp Number', 'whatsapp_number_field_callback', 'general', 'whatsapp_quote_button_settings' );
    register_setting( 'general', 'whatsapp_number' );
  }
  
  function whatsapp_quote_button_settings_callback() {
    echo '<p>Enter your WhatsApp number below. This number will be used as the default number for the WhatsApp Request Quote Button.</p>';
  }
  
  function whatsapp_number_field_callback() {
    $whatsapp_number = get_option( 'whatsapp_number' );
    echo '<input type="text" name="whatsapp_number" value="' . esc_attr( $whatsapp_number ) . '" />';
  }
  
  add_action( 'admin_init', 'whatsapp_quote_button_settings_init' );
 
function whatsapp_quote_button_plugin_settings_link( $links ) {
    $settings_link = '<a href="options-general.php#whatsapp_quote_button_settings">' . __( 'Settings' ) . '</a>';
    array_unshift( $links, $settings_link );
    return $links;
  }
  
  $plugin_basename = plugin_basename( __FILE__ );
  add_filter( "plugin_action_links_$plugin_basename", 'whatsapp_quote_button_plugin_settings_link' );
  