<?php
/**
 * The template for displaying a single artist.
 */

get_header();
?>

<?php while ( have_posts() ) : the_post(); ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class( 'artist' ); ?> itemscope itemtype="http://schema.org/MusicGroup" role="article">

		<?php if ( has_post_thumbnail() ) : ?>
			<p class="artist-photo">
				<a href="<?php echo wp_get_attachment_url( get_post_thumbnail_id() ); ?>" itemprop="image">
					<?php the_post_thumbnail('artist-single'); ?>
				</a>
			</p>
		<?php endif; ?>

		<header class="entry-header">
			<?php the_title( '<h1 class="release-title entry-title" itemprop="name">', '</h1>' ); ?>
		</header>

    <div class="entry-content" itemprop="description">
			<?php the_content( '' ); ?>
		</div>

    <h2>Releases with <?php the_title(); ?></h2>
		<?php if( $release_posts = $wp_record_label->get_artist_releases() ) :
      while($release_posts->have_posts()) :
        $release_posts->the_post();
        $artist = get_post_meta($post->ID, "record_label_subtitle", true); ?>
        <div class="release" itemscope itemtype="http://schema.org/MusicAlbum">
          <a href="<?php the_permalink(); ?>" itemprop="image">
            <?php the_post_thumbnail("thumbnail"); ?>
          </a>
          <a href="<?php the_permalink(); ?>" itemprop="url">
            <div class="release_info">
              <h4 class="release-artist" itemprop="byArtist"><?php echo esc_html( $artist ); ?></h4>
              <?php the_title( '<h3 class="release-title entry-title" itemprop="name">', '</h3>' ); ?>
            </div>
          </a>
        </div>
      <?php endwhile; ?>
      <?php wp_reset_postdata(); ?>
		<?php endif; ?>

	</article>

<?php endwhile; ?>

<?php get_footer(); ?>
