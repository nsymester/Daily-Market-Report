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
            'menu_name'          => 'Market Reports',
            /* Custom archive label.  Must filter 'post_type_archive_title' to use. */
            'archive_title'      => __( 'Daily Market Reports', 'example-textdomain' ),
        );

        $args = array(
            'labels'              => $labels,
            'description'         => 'Holds our Market Reports data',
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,    
            'show_in_admin_bar'   => true,
            'menu_position'       => 5,
            'supports'            => array( 'title', 'editor', 'thumbnail' ),
            'label'               => 'Daily Market Reports',
            'has_archive'         => true,
            'exclude_from_search' => false,
            'query_var'           => true,
            'rewrite'             => array ( 'slug' => 'market-reports' ),
            
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

    function market_reports_search( $search_template ) {

        if ( is_search() ) {
            // checks if the file exists in the theme first,
            // otherwise serve the file from the plugin
            if ( $theme_file = locate_template( array ( 'search-market_reports.php' ) ) ) {

                $search_template = $theme_file;

            } else {

                $search_template = plugin_dir_path( __FILE__ ) . '/templates/search-market_reports.php';

            }

        }            
        
        return $search_template;
    }

    add_filter( 'template_include', 'market_reports_search', 1 );


    //route archive- template
    function market_reports_archive( $archive_template ){

      if(is_post_type_archive('market_reports')){

            $exists_in_theme = locate_template('archive-market_reports.php', false);

            if($exists_in_theme == ''){

                return plugin_dir_path(__FILE__) . '/templates/archive-market_reports.php';

            }

      }

      return $template;

    }

    add_filter('archive_template','market_reports_archive');


    //route single- template
    function market_reports_single( $single_template ){

        if(is_singular('market_reports')){

            $found = locate_template('single-market_reports.php', false);

            if($found == ''){

                return plugin_dir_path(__FILE__) .'/templates/single-market_reports.php';

            }

        }

        return $single_template;
    }

    add_filter('single_template','market_reports_single');


?>