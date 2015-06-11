<?php
/**
 * The template for displaying Archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each specific one. For example, Twenty Fourteen
 * already has tag.php for Tag archives, category.php for Category archives,
 * and author.php for Author archives.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Magna_Options
 * @since Magna Options 1.0
 */

  get_header();
?>


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
          			<?php get_search_form(); ?>
            		<div class="mo-page">
              			<h2>Daily Market Report</h2>

						<?php if ( have_posts() ) : 
							// Start the Loop.
							while ( have_posts() ) : the_post();

								/*
								 * Include the post format-specific template for the content. If you want to
								 * use this in a child theme, then include a file called called content-___.php
								 * (where ___ is the post format) and that will be used instead.
								 */
                                if('' === locate_template( array ( 'content-market_reports.php', true, false ) )){
                                    include('content-market_reports.php');
                                }

								//get_template_part( 'content-market_reports', get_post_format() );

							endwhile;
							// Previous/next page navigation.
							magnaoptions_paging_nav();

						else :
							// If no content, include the "No posts found" template.
							get_template_part( 'content-market_reports', 'none' );

						endif;
						?>

            		</div>
          		</div>
        	</div>
      	</div>
    </section>
    <!-- -->
          

<?php
  get_footer();
?>

