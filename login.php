<style>
	.tml-links{display: none;}
</style>


<?php get_header(); ?>
<main id="content">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
		<article class="post" style="max-width: 20rem;">
			<h1 class="entry-title"><?php the_title(); ?></h1> <?php edit_post_link(); ?>
			<div class="entry-content">
				<?php the_content(); ?>
				<div class="entry-links"><?php wp_link_pages(); ?></div>
			</div>
		</article>
		<?php if ( comments_open() && ! post_password_required() ) { comments_template( '', true ); } ?>
	<?php endwhile; endif; ?>
</main>
<?php get_footer(); ?>