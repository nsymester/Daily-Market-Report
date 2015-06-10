<?php
/*
Plugin Name: Daily Market Report
Plugin URI: http://www.magnaoptions.com/
Description: A plugin which creates custom post type displaying Daily Market Report.
Version: 1.0
Author: Neil Symester
Author URI: http://www.pixel-twist.com/
License: GPLv2
*/

/*****************************************************************
:: SCRIPT - Declare CONSTANTS
******************************************************************/

    // Custom template tags for this theme.
    require 'inc/template-tags.php';


    function my_custom_post_market_reports() {
      $labels = array(
        'name'               => _x( 'Market Reports', 'post type general name' ),
        'singular_name'      => _x( 'Market Report', 'post type singular name' ),
        'add_new'            => _x( 'Add New', 'Market Report' ),
        'add_new_item'       => __( 'Add New Market Report' ),
        'edit_item'          => __( 'Edit Market Report' ),
        'new_item'           => __( 'New Market Report' ),
        'all_items'          => __( 'All Market Reports' ),
        'view_item'          => __( 'View Market Report' ),
        'search_items'       => __( 'Search Market Reports' ),
        'not_found'          => __( 'No Market Reports found' ),
        'not_found_in_trash' => __( 'No Market Reports found in the Trash' ), 
        'parent_item_colon'  => '',
        'menu_name'          => 'Market Reports'
      );
      $args = array(
        'labels'        => $labels,
        'description'   => 'Holds our Market Reports data',
        'public'        => true,
        'menu_position' => 5,
        'supports'      => array( 'title', 'editor', 'thumbnail' ),
        'has_archive'   => true,
      );
      register_post_type( 'market_reports', $args ); 
    }
    add_action( 'init', 'my_custom_post_market_reports' );


    //CUSTOM INTERACTION MESSAGES
    function my_updated_messages( $messages ) {
      global $post, $post_ID;
      $messages['market_reports'] = array(
        0 => '', 
        1 => sprintf( __('Market Report updated. <a href="%s">View Market Report</a>'), esc_url( get_permalink($post_ID) ) ),
        2 => __('Custom field updated.'),
        3 => __('Custom field deleted.'),
        4 => __('Market Report updated.'),
        5 => isset($_GET['revision']) ? sprintf( __('Market Report restored to revision from %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
        6 => sprintf( __('Market Report published. <a href="%s">View Market Report</a>'), esc_url( get_permalink($post_ID) ) ),
        7 => __('Market Report saved.'),
        8 => sprintf( __('Market Report submitted. <a target="_blank" href="%s">Preview Market Report</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
        9 => sprintf( __('Market Report scheduled for: <strong>%1$s</strong>. <a target="_blank" href="%2$s">Preview Market Report</a>'), date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
        10 => sprintf( __('Market Report draft updated. <a target="_blank" href="%s">Preview Market Report</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
      );
      return $messages;
    }
    add_filter( 'post_updated_messages', 'my_updated_messages' );


/*****************************************************************
:: SCRIPT - market_report_current_price Meta Box
******************************************************************/

    //DEFINING THE META BOX
    add_action( 'add_meta_boxes', 'market_report_current_price_box' );
    function market_report_current_price_box() {
        add_meta_box( 
            'market_report_current_price_box',
            __( 'Current price', 'myplugin_textdomain' ),
            'market_report_current_price_box_content',
            'market_reports',
            'normal',
            'high'
        );
    }

    //DEFINING THE CONTENT OF THE META BOX
    function market_report_current_price_box_content( $post ) {
        wp_nonce_field( plugin_basename( __FILE__ ), 'market_report_current_price_box_content_nonce' );
        $meta_values = get_post_meta($post->ID, 'market_report_current_price', true);
     
        echo '<label for="market_report_current_price"></label>';
        if($meta_values != '') {
            echo '<input type="text" id="market_report_current_price" name="market_report_current_price" placeholder="enter the current price" value="' . $meta_values . '"/>';
        } else {
            echo '<input type="text" id="market_report_current_price" name="market_report_current_price" placeholder="enter the current price"/>';
        }
    }

/*****************************************************************
:: SCRIPT - Saving market_report_current_price Meta Box data
******************************************************************/

    //HANDLING SUBMITTED DATA
    add_action( 'save_post', 'market_report_current_price_box_save' );

    function market_report_current_price_box_save( $post_id ) {

      if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return;

      if ( !wp_verify_nonce( $_POST['market_report_current_price_box_content_nonce'], plugin_basename( __FILE__ ) ) )
      return;

      if ( 'page' == $_POST['post_type'] ) {
        if ( !current_user_can( 'edit_page', $post_id ) )
        return;
      } else {
        if ( !current_user_can( 'edit_post', $post_id ) )
        return;
      }

      $market_report_current_price = $_POST['market_report_current_price'];
      update_post_meta( $post_id, 'market_report_current_price', $market_report_current_price );

    }


/*****************************************************************
:: SCRIPT - market_report_support_levels Meta Box
******************************************************************/

    //DEFINING THE META BOX
    add_action( 'add_meta_boxes', 'market_report_support_levels_box' );
    function market_report_support_levels_box() {
        add_meta_box( 
            'market_report_support_levels_box',
            __( 'Support Levels', 'myplugin_textdomain' ),
            'market_report_support_levels_box_content',
            'market_reports',
            'normal',
            'high'
        );
    }

    //DEFINING THE CONTENT OF THE META BOX
    function market_report_support_levels_box_content( $post ) {
        wp_nonce_field( plugin_basename( __FILE__ ), 'market_report_support_levels_box_content_nonce' );
        $meta_values = get_post_meta($post->ID, 'market_report_support_levels', true);
     
        echo '<label for="market_report_support_levels"></label>';
        if($meta_values != '') {
            echo '<input type="text" id="market_report_support_levels" name="market_report_support_levels" placeholder="enter the support level" value="' . $meta_values . '"/>';
        } else {
            echo '<input type="text" id="market_report_support_levels" name="market_report_support_levels" placeholder="enter the support level"/>';
        }
    }

/*****************************************************************
:: SCRIPT - Saving market_report_support_levels Meta Box data
******************************************************************/

    //HANDLING SUBMITTED DATA
    add_action( 'save_post', 'market_report_support_levels_box_save' );

    function market_report_support_levels_box_save( $post_id ) {

      if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return;

      if ( !wp_verify_nonce( $_POST['market_report_support_levels_box_content_nonce'], plugin_basename( __FILE__ ) ) )
      return;

      if ( 'page' == $_POST['post_type'] ) {
        if ( !current_user_can( 'edit_page', $post_id ) )
        return;
      } else {
        if ( !current_user_can( 'edit_post', $post_id ) )
        return;
      }

      $market_report_support_levels = $_POST['market_report_support_levels'];
      update_post_meta( $post_id, 'market_report_support_levels', $market_report_support_levels );

    }

/*****************************************************************
:: SCRIPT - market_report_resistance_levels Meta Box
******************************************************************/

    //DEFINING THE META BOX
    add_action( 'add_meta_boxes', 'market_report_resistance_levels_box' );
    function market_report_resistance_levels_box() {
        add_meta_box( 
            'market_report_resistance_levels_box',
            __( 'Resistance levels', 'myplugin_textdomain' ),
            'market_report_resistance_levels_box_content',
            'market_reports',
            'normal',
            'high'
        );
    }

    //DEFINING THE CONTENT OF THE META BOX
    function market_report_resistance_levels_box_content( $post ) {
        wp_nonce_field( plugin_basename( __FILE__ ), 'market_report_resistance_levels_box_content_nonce' );
        $meta_values = get_post_meta($post->ID, 'market_report_resistance_levels', true);
     
        echo '<label for="market_report_resistance_levels"></label>';
        if($meta_values != '') {
            echo '<input type="text" id="market_report_resistance_levels" name="market_report_resistance_levels" placeholder="enter the resistance levels" value="' . $meta_values . '"/>';
        } else {
            echo '<input type="text" id="market_report_resistance_levels" name="market_report_resistance_levels" placeholder="enter the resistance levels"/>';
        }
    }

/*****************************************************************
:: SCRIPT - Saving market_report_resistance_levels Meta Box data
******************************************************************/

    //HANDLING SUBMITTED DATA
    add_action( 'save_post', 'market_report_resistance_levels_box_save' );

    function market_report_resistance_levels_box_save( $post_id ) {

      if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
      return;

      if ( !wp_verify_nonce( $_POST['market_report_resistance_levels_box_content_nonce'], plugin_basename( __FILE__ ) ) )
      return;

      if ( 'page' == $_POST['post_type'] ) {
        if ( !current_user_can( 'edit_page', $post_id ) )
        return;
      } else {
        if ( !current_user_can( 'edit_post', $post_id ) )
        return;
      }

      $market_report_resistance_levels = $_POST['market_report_resistance_levels'];
      update_post_meta( $post_id, 'market_report_resistance_levels', $market_report_resistance_levels );

    }

 
/*****************************************************************
:: SCRIPT - Template Functions
******************************************************************/

    add_filter( 'template_include', 'include_template_function', 1 );

    function include_template_function( $template_path ) {
        if ( get_post_type() == 'market_reports' ) {
            if ( is_single() ) {
                // checks if the file exists in the theme first,
                // otherwise serve the file from the plugin
                if ( $theme_file = locate_template( array ( 'single-market_reports.php' ) ) ) {
                    $template_path = $theme_file;
                } else {
                    $template_path = plugin_dir_path( __FILE__ ) . '/templates/single-market_reports.php';
                }
            } else if ( is_archive() ) {
                // checks if the file exists in the theme first,
                // otherwise serve the file from the plugin
                if ( $theme_file = locate_template( array ( 'archive-market_reports.php' ) ) ) {
                    $template_path = $theme_file;
                } else {
                    $template_path = plugin_dir_path( __FILE__ ) . '/templates/archive-market_reports.php';
                }
            }
        }
        return $template_path;
    }

?>