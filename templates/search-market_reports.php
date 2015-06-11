<?php 
/**
 * The Template for displaying search results
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
                        <h2>Daily Market Report</h2>

                        <?php if ( have_posts() && strlen( trim(get_search_query()) ) != 0 ) : ?>

                        <div class="row">
                            <div class="col-sm-12">
                                <h3><?php printf( __( 'Search Results for: %s', 'shape' ), '<span>' . get_search_query() . '</span>' ); ?></h3>
                            </div>
                        </div>

                        <hr />

                        <div class="row m20 js-masonry">
                        <?php
                            $args = array_merge( $wp_query->query_vars, array( 'posts_per_page' => 10, 'post_type' => 'market_reports') );
                            query_posts($args);

                            // Start the Loop.
                            while ( have_posts() ) : the_post();?>

                            <div class="col-md-12 col-sm-12 masonry-item">
                                <div class="row post-block">
                                    <div class="col-sm-12">
                                        <div class="post-image" style="overflow: hidden !important;">
                                            <a href="<?php the_permalink(); ?>">
                                            <?php the_title(); ?>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php endwhile; wp_reset_query(); ?>

                        </div>

                        <?php else : ?>

                        <div class="row m20 js-masonry">
                            
                            <div class="col-sm-12">
                                <h2 class="text-uppercase text-center">Sorry no results were found</h2>
                                <p class="text-center"><?php printf( __( 'No results were found for: %s', 'shape' ), '<span style="text-decoration:underline;"><strong>' . get_search_query() . '</strong></span>' ); ?>. Please eneter a new search term or view our latest news below:</p>
                            </div>
                        </div>

                        <hr />     

                        <?php endif; ?>
                    </div>

                </div><!--//.col-sm-9 .col-md-9-->
            </div><!--//.row-->
        </div><!--//.;container-->
    </section>
    <!-- -->

<?php get_footer(); ?>