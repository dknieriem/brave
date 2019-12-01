<?php
/**
 * /* Template Name: B2BB - Page w/ Hero
 * 
 * Uses a header with the hero image above the nav bar
 *
 * @package understrap
 */

get_header('hero');

$container   = get_theme_mod( 'understrap_container_type' );

?>

<div class="wrapper" id="page-wrapper">

	<div id="content" tabindex="-1"><!--  class="<?php echo esc_attr( $container ); ?>" -->

		<div class="row content__row">

			<div class="col-12 content__col-12">

			<main class="site-main" id="main">

				<?php while ( have_posts() ) : the_post(); ?>

					<article <?php post_class(); ?> id="post-<?php the_ID(); ?>">

					<div class="entry-content">

						<?php the_content(); ?>

					</div><!-- .entry-content -->

					<!-- <footer class="entry-footer">

						<?php //edit_post_link( __( 'Edit', 'understrap' ), '<span class="edit-link">', '</span>' ); ?>

					</footer><!-- .entry-footer -->

				</article><!-- #post-## -->

				<?php endwhile; // end of the loop. ?>

			</main><!-- #main -->

		</div><!-- .col-12 -->

	</div><!-- .row -->

</div><!-- Container end -->

</div><!-- Wrapper end -->

<?php get_footer(); ?>

