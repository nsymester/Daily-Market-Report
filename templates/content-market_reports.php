<?php
/**
 * The default template for displaying content
 *
 * Used for both single and index/archive/search.
 *
 * @package WordPress
 * @subpackage Magna_Options
 * @since Magna Options 1.0
 */
?>

<div class="row">
	<div class="col-sm-12">

		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
			<?php magnaoptions_post_thumbnail(); ?>

			<header class="entry-header">
				<?php if ( in_array( 'category', get_object_taxonomies( get_post_type() ) ) && magnaoptions_categorized_blog() ) : ?>
				<div class="entry-meta">
					<span class="cat-links"><?php echo get_the_category_list( _x( ', ', 'Used between list items, there is a space after the comma.', 'magnaoptions' ) ); ?></span>
				</div>
				<?php
					endif;

					// Get the Current price
					$currentPrice = get_post_meta( get_the_ID(), '_current_price', true );

					if ( is_single() ) :
						the_title( '<p class="entry-title"><strong>', '</strong> Current price: '. $currentPrice . '</p>');
					else :
						the_title( '<p class="entry-title"><strong>', '</strong> Current price: '. $currentPrice . '</p>' );
					endif;
				?>

				<div class="entry-meta">
					<?php
						if ( 'post' == get_post_type() )
							magnaoptions_posted_on();

						edit_post_link( __( 'Edit', 'magnaoptions' ), '<span class="edit-link">', '</span>' );
					?>
				</div><!-- .entry-meta -->
			</header><!-- .entry-header -->

			<?php if ( is_search() ) : ?>
			<div class="entry-summary">
				<?php the_content(); ?>
				<p>content</p>
			</div><!-- .entry-summary -->
			<?php else : ?>
			<div class="entry-content">
				<?php
					/* translators: %s: Name of current post */
					the_content( sprintf(
						__( 'Continue reading %s <span class="meta-nav">&rarr;</span>', 'magnaoptions' ),
						the_title( '<span class="screen-reader-text">', '</span>', false )
					) );

					wp_link_pages( array(
						'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'magnaoptions' ) . '</span>',
						'after'       => '</div>',
						'link_before' => '<span>',
						'link_after'  => '</span>',
					) );

					// Get the Support levels
					$supportLevels = get_post_meta( get_the_ID(), '_support_levels', true );
					echo '<p>Support levels: ' . $supportLevels . '</p>';	

					// Get the Resistance levels
					$resistanceLevels = get_post_meta( get_the_ID(), '_resistance_levels', true );
					echo '<p>Resistance levels: ' . $resistanceLevels . '</p>';	
				?>
			</div><!-- .entry-content -->
			<?php endif; ?>

			<?php the_tags( '<footer class="entry-meta"><span class="tag-links">', '', '</span></footer>' ); ?>
			<hr>
		</article><!-- #post-## -->
	</div>
</div>
