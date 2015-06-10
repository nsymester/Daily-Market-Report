<?php
/**
 * The Template for displaying all single posts
 *
 * @package WordPress
 * @subpackage Magna_Options
 * @since Magna Options 1.0
 */
get_header(); ?>

    <!-- -->
    <section>
        <div class="container">
            <div class="row">
                <div class="col-sm-3 col-md-3">
                <!-- Collect the nav links, forms, and other content for toggling -->
          
                    <div id="sidebar-menu" class="sidebar-menu clearfix">

                    <?php
                        wp_nav_menu( array(
                            'menu'              => 'secondary',
                            'theme_location'    => 'secondary',
                            'depth'             => 3,
                            'menu_class'        => 'nav nav-stacked navbar-left',
                            'container'         => false,
                            'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
                            'walker'            => new wp_bootstrap_navwalker())
                        );

                        //wp_list_pages('title_li='); // can also list pages

                    ?>
                
                    </div><!-- /.sidebar-menu -->  

                </div>

                <div class="col-sm-9 col-md-9">
                    <div class="mo-page">
                        <?php  
                            // Start the Loop.
                            while ( have_posts() ) : the_post();

                                /*
                                 * Include the post formatat-specific template for the content. If you want to
                                 * use this in a child theme, then include a file called called content-___.php
                                 * (where ___ is the post format) and that will be used instead.
                                 */
                                if('' === locate_template( array ( 'content-market_reports.php', true, false ) )){
                                    include('content-market_reports.php');
                                }

                                //get_template_part( 'content-market_reports', get_post_format() );

                            endwhile;
                            // Previous/next page navigation.
                            magnaoptions_post_nav();
                        ?>

                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- -->

<?php
get_footer();
