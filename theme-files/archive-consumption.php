<?php
/**
 * The template for displaying Consumption pages.  Based on TwentyTen.
 * Installation instructions: Copy this file into your theme directory.
 * Customize the copy as needed.
 *
 * @package WordPress
 * @subpackage Consume This
 * @since 1.0
 */

get_header(); ?>

		<div id="container">
			<div id="content" role="main">

			<h1 class="page-title">
				<?php post_type_archive_title(); ?>
			</h1>

	<?php /* Display navigation to next/previous pages when applicable */ ?>
	<?php if ( $wp_query->max_num_pages > 1 ) : ?>
		<div id="nav-above" class="navigation">
			<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts' ) ); ?></div>
			<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>' ) ); ?></div>
		</div><!-- #nav-above -->
	<?php endif; ?>

	<?php /* If there are no posts to display, such as an empty archive page */ ?>
	<?php if ( ! have_posts() ) : ?>
		<div id="post-0" class="post error404 not-found">
			<h1 class="entry-title"><?php _e( 'Not Found' ); ?></h1>
			<div class="entry-content">
				<p><?php _e( 'Apologies, but no results were found for the requested archive. Perhaps searching will help find a related post.' ); ?></p>
				<?php get_search_form(); ?>
			</div><!-- .entry-content -->
		</div><!-- #post-0 -->
	<?php endif; ?>

	<?php while ( have_posts() ) : the_post(); ?>

			<div id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
				<?php
				printf( __( '<span class="%1$s">On</span> %2$s <span class="meta-sep">, </span> %3$s consumed' ),
					'meta-prep meta-prep-author',
					sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><span class="entry-date">%3$s</span></a>',
						get_permalink(),
						esc_attr( get_the_time() ),
						get_the_date()
					),
					sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
						get_author_posts_url( get_the_author_meta( 'ID' ) ),
						sprintf( esc_attr__( 'View all posts by %s' ), get_the_author() ),
						get_the_author()
					)
				);
				?>
				<?php
				$url = get_post_meta(get_the_ID(), '_mint_consume_link', true);
				if ( !empty($url) ) {
					?><a href="<?php echo $url; ?>" title="<?php printf( esc_attr__( 'Permalink to %s' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark"><?php the_title(); ?></a><?php
				} else {
					the_title();
				}
				?>

				<?php the_terms( get_the_ID(), 'consumable', '#', ', #' ); ?>
			</div><!-- #post-## -->

	<?php endwhile; // End the loop. Whew. ?>

	<?php /* Display navigation to next/previous pages when applicable */ ?>
	<?php if (  $wp_query->max_num_pages > 1 ) : ?>
					<div id="nav-below" class="navigation">
						<div class="nav-previous"><?php next_posts_link( __( '<span class="meta-nav">&larr;</span> Older posts' ) ); ?></div>
						<div class="nav-next"><?php previous_posts_link( __( 'Newer posts <span class="meta-nav">&rarr;</span>' ) ); ?></div>
					</div><!-- #nav-below -->
	<?php endif; ?>

			</div><!-- #content -->
		</div><!-- #container -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
