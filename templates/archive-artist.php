<?php
/**
 * The template to display list of artists.
 */

get_header();
?>

<header class="archive-header">
	<?php the_archive_title( '<h1 class="archive-title">', '</h1>' ); ?>
</header>

<div class="artists">
	<?php while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="article" itemscope itemtype="http://schema.org/MusicAlbum">
			<?php if ( has_post_thumbnail() ) : ?>
        <p class="artist-photo">
  				<a href="<?php the_permalink(); ?>" itemprop="image">
  					<?php the_post_thumbnail( ); ?>
  				</a>
  			</p>
			<?php endif; ?><?php the_title( '<h2 class="artist-title entry-title" itemprop="name"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' ); ?>
      <p><?php the_excerpt() ?></p>
		</article>
	<?php endwhile; ?>
</div>
<?php get_footer(); ?>
