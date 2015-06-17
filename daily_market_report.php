<?php
/*
Plugin Name: Daily Market Report
Plugin URI: http://www.magnaoptions.com/
Description: A plugin which creates custom post type displaying Daily Market Report.
Version: 1.1
Author: Neil Symester
Author URI: http://www.pixel-twist.com/
License: GPLv2
*/

// Custom template tags for this theme.
require 'inc/template-tags.php';

class MODailyMarketReport {
     
    /**
     * Constructor. Called when plugin is initialised
     */
    function __construct() {
        add_action( 'init', array( $this, 'register_custom_post_market_reports' ) );
        add_filter( 'post_updated_messages', array( $this, 'my_updated_messages') );

        add_action( 'add_meta_boxes', array( $this, 'register_meta_boxes' ) ); 

        add_action( 'save_post', array( $this, 'save_current_price' ) );
        add_action( 'save_post', array( $this, 'save_support_levels' ) );
        add_action( 'save_post', array( $this, 'save_resistance_levels' ) );

        add_filter( 'template_include', array( $this, 'market_reports_search' ), 1 );
        add_filter( 'archive_template', array( $this, 'market_reports_archive' ) );
        add_filter( 'single_template', array( $this, 'market_reports_single' ) );

    }
     
    /**
     * Register a custom post called 'Market Reports'
     */
    function register_custom_post_market_reports() {
        
        register_post_type( 

            'market_reports', array (

                'labels' => array (
                    'name'               => _x( 'Market Reports', 'post type general name', plugin_basename( __FILE__ ) ),
                    'singular_name'      => _x( 'Market Report', 'post type singular name', plugin_basename( __FILE__ ) ),
                    'menu_name'          => _x( 'Market Reports', 'admin menu', plugin_basename( __FILE__ )),
                    'name_admin_bar'     => _x( 'Market Report', 'add new on admin bar', plugin_basename( __FILE__ ) ),
                    'add_new'            => _x( 'Add New', 'Market Report', plugin_basename( __FILE__ ) ),
                    'add_new_item'       => __( 'Add New Market Report', plugin_basename( __FILE__ ) ),
                    'new_item'           => __( 'New Market Report', plugin_basename( __FILE__ ) ),
                    'edit_item'          => __( 'Edit Market Report', plugin_basename( __FILE__ ) ),
                    'view_item'          => __( 'View Market Report', plugin_basename( __FILE__ ) ),
                    'all_items'          => __( 'All Market Reports', plugin_basename( __FILE__ ) ),
                    'search_items'       => __( 'Search Market Reports', plugin_basename( __FILE__ ) ),
                    'parent_item_colon'  => __( 'Parent Contacts:', plugin_basename( __FILE__ ) ),
                    'not_found'          => __( 'No Market Reports found', plugin_basename( __FILE__ ) ),
                    'not_found_in_trash' => __( 'No Market Reports found in the Trash', plugin_basename( __FILE__ ) ), 
                    /* Custom archive label.  Must filter 'post_type_archive_title' to use. */
                    'archive_title'      => __( 'Daily Market Reports', plugin_basename( __FILE__ ) ),
                ),

                'description'         => 'Holds our Market Reports data',

                // Frontend
                'has_archive'         => true,
                'public'              => true,
                'publicly_queryable'  => true,

                // Admin
                'capability_type'     => 'post',
                'menu_icon'           => 'dashicons-analytics',
                'menu_position'       => 5,
                'query_var'           => true,
                'show_ui'             => true,
                'show_in_menu'        => true,
                'show_in_nav_menus'   => true,    
                'show_in_admin_bar'   => true,
                'supports'            => array( 
                    'title', 
                    'author',
                    'editor'
                ),

                'label'               => 'Daily Market Reports',
                'exclude_from_search' => false,
                'rewrite'             => array ( 'slug' => 'market-reports' ),
            )            

        );

    }

    //CUSTOM INTERACTION MESSAGES
    /**
     * Custom Interaction Messages
     * @param  string $messages messages
     */
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

    /**
    * Registers Meta Boxes on our Market Reports Custom Post Type
    */
    function register_meta_boxes() {
        add_meta_box( 
            'current-price',
            __( 'Current price', plugin_basename( __FILE__ ) ),
            array( $this, 'output_current_price'),
            'market_reports',
            'normal',
            'high'
        );

        add_meta_box( 
            'support-levels',
            __( 'Support Levels', plugin_basename( __FILE__ ) ),
            array( $this, 'support_levels'),
            'market_reports',
            'normal',
            'high'
        );

        add_meta_box( 
            'resistance-levels',
            __( 'Resistance levels',  plugin_basename( __FILE__ ) ),
            array( $this, 'resistance_levels'),
            'market_reports',
            'normal',
            'high'
        );

    }

    /**
     * Output a Current Price meta box
     * @param  WP_Post $post WordPress Post object
     */
    function output_current_price( $post ) {

        $currentPrice = get_post_meta($post->ID, '_current_price', true);

        // Add a nonce field so we can check for it later.
        wp_nonce_field( 'save_current_price', 'current_price_nonce' );
     
        // Output label and field
        echo '<label for="current_price"></label>';
        echo '<input type="text" id="current_price" name="current_price" placeholder="enter the current price" value="' . esc_attr($currentPrice) . '"/>';
    }

    /**
     * Saves the Current Price meta box field data
     * @param  int $post_id Post ID
     */
    function save_current_price( $post_id ) {

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }

        // Check if our nonce is set.
        if ( ! isset( $_POST['current_price_nonce'] ) ) {
            return $post_id;    
        }

        // Verify that the nonce is valid.
        if ( !wp_verify_nonce( $_POST['current_price_nonce'], 'save_current_price') ) {
            return $post_id;
        }

        // Check this is the Market Reports Custom Post Type
        if ( 'market_reports' != $_POST['post_type'] ) {
            return $post_id;
        }
        
        // Check the logged in user has permission to edit this post
        if ( !current_user_can( 'edit_page', $post_id ) ) {
            return $post_id;
        }
        
        // OK to save meta data
        $currentPrice = $_POST['current_price'];
        update_post_meta( $post_id, '_current_price', $currentPrice );

    }


    /**
     * Output a Support Levels meta box
     * @param  WP_Post $post WordPress Post object
     */
    function support_levels( $post ) {

        $supportLevels = get_post_meta($post->ID, '_support_levels', true);

        // Add a nonce field so we can check for it later.
        wp_nonce_field( 'save_support_levels', 'support_levels_nonce' );
     
        // Output label and field
        echo '<label for="support_levels"></label>';
        echo '<input type="text" id="support_levels" name="support_levels" placeholder="enter the support level" value="' . esc_attr($supportLevels) . '"/>';
    }


    /**
     * Save the Support Levels meta box field data
     * @param  int $post_id Post ID
     */
    function save_support_levels( $post_id ) {

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }

        // Check if our nonce is set.
        if ( ! isset( $_POST['support_levels_nonce'] ) ) {
            return $post_id;    
        }

        // Verify that the nonce is valid.
        if ( !wp_verify_nonce( $_POST['support_levels_nonce'], 'save_support_levels') ) {
            return $post_id;
        }

        // Check this is the Market Reports Custom Post Type
        if ( 'market_reports' != $_POST['post_type'] ) {
            return $post_id;
        }
        
        // Check the logged in user has permission to edit this post
        if ( !current_user_can( 'edit_page', $post_id ) ) {
            return $post_id;
        }

        $supportLevels = $_POST['support_levels'];
        update_post_meta( $post_id, '_support_levels', $supportLevels );

    }


    /**
     * Output a Resistance Levels meta box
     * @param  WP_Post $post WordPress Post object
     */
    function resistance_levels( $post ) {

        $resistanceLevels = get_post_meta($post->ID, '_resistance_levels', true);

        // Add a nonce field so we can check for it later.
        wp_nonce_field( 'save_resistance_levels', 'resistance_levels_nonce' );
     
        // Output label and field
        echo '<label for="resistance_levels"></label>';
        echo '<input type="text" id="resistance_levels" name="resistance_levels" placeholder="enter the resistance levels" value="' . esc_attr($resistanceLevels) . '"/>';
    }


    /**
     * Save the Support Levels meta box field data
     * @param  int $post_id Post ID
     */
    function save_resistance_levels( $post_id ) {

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return $post_id;
        }

        // Check if our nonce is set.
        if ( ! isset( $_POST['resistance_levels_nonce'] ) ) {
            return $post_id;    
        }

        // Verify that the nonce is valid.
        if ( !wp_verify_nonce( $_POST['resistance_levels_nonce'], 'save_resistance_levels' ) ) {
            return $post_id;
        }

        // Check this is the Market Reports Custom Post Type
        if ( 'market_reports' != $_POST['post_type'] ) {
            return $post_id;
        }

        // Check the logged in user has permission to edit this post
        if ( !current_user_can( 'edit_page', $post_id ) ) {
            return $post_id;
        }
 
        $resistanceLevels = $_POST['resistance_levels'];
        update_post_meta( $post_id, '_resistance_levels', $resistanceLevels );

    }    

    /**
     * Display market report search results
     * @param  string $search_template name of the search template
     */
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


    /**
     * Display all market reports
     * @param  string $archive_template name of archive template
     */
    function market_reports_archive( $archive_template ){

      if(is_post_type_archive('market_reports')){

            $exists_in_theme = locate_template('archive-market_reports.php', false);

            if($exists_in_theme == ''){

                return plugin_dir_path(__FILE__) . '/templates/archive-market_reports.php';

            }

      }

      return $template;

    }


    /**
     * Display a single market report
     * @param  string $single_template name of single template
     */
    function market_reports_single( $single_template ){

        if(is_singular('market_reports')){

            $found = locate_template('single-market_reports.php', false);

            if($found == ''){

                return plugin_dir_path(__FILE__) .'/templates/single-market_reports.php';

            }

        }

        return $single_template;
    }


}
 
$moDailyMarketReport = new MODailyMarketReport;

?>