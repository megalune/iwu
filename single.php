<?php get_header(); ?>
<!-- single -->

<?php if($current_user->user_login == "demo"){ ?>

<main id="content" class="demo">
	demo
</main>








<?php } else { ?>

<main id="content" class="am_class <?php echo $user; ?>">
	<?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>

	<?php
		if(strpos($_SERVER['HTTP_REFERER'], "megalune") !== false){
			echo '<a class="button" style="display: inline-block;" href="'.$_SERVER['HTTP_REFERER'].'">Back to Timeline</a>';
		}
	?>

	<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

		<h1>
			<!-- <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" rel="bookmark"><?php the_title(); ?></a> -->
			<?php
				$title = get_the_title();
				$title = str_replace('&#8220;', "<i>", $title);
				$title = str_replace('&#8221;', "</i>", $title);
				echo $title;
				// the_title(); 
			?>
		</h1>




		<section class="post-meta">
			<?php
				$plural = "";
				$end_date = "";
				if(get_field('date_end') != ""){
					$plural = "s";
					// $end_date = " &ndash; ".date("Y", strtotime(get_field('date_end')));
					$end_date = " &ndash; ".get_field('date_end');
				}
				// echo "<p>Date".$plural.": ".date("Y", strtotime(get_field('date_start'))).$end_date."</p>";
				echo "<p>Date".$plural.": ".get_field('date_start').$end_date."</p>";
			?>
			<p>Category: <?php
				$categories = get_the_category(); 
				if ( ! empty( $categories ) ) {
				    echo esc_html( $categories[0]->name );   
				}
			?>
			</p>
			<?php echo do_shortcode( '[travelers-map init_maxzoom=3 height=250px post_id='.get_the_ID().']' ); ?>
		</section>





		<div>
			<?php if ( has_post_thumbnail() ) : ?>
			<a href="<?php $src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full', false ); echo esc_url( $src[0] ); ?>" title="<?php the_title_attribute(); ?>"><?php the_post_thumbnail(); ?></a>
			<?php endif; ?>

			<?php the_content(); ?>

		</div>




	</article>

	<?php endwhile; endif; ?>
</main>
<?php } ?>
<?php get_footer(); ?>