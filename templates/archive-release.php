<?php
/**
 * The template to display list of releases.
 */

get_header();
?>

<header class="archive-header">
	<?php the_archive_title( '<h1 class="archive-title">', '</h1>' ); ?>
</header>

<div class="releases">
	<?php while ( have_posts() ) : the_post(); ?>
		<article id="post-<?php the_ID(); ?>" <?php post_class(); ?> role="article" itemscope itemtype="http://schema.org/MusicAlbum">
			<?php if ( has_post_thumbnail() ) : ?>
        <p class="release-artwork">
          <a href="<?php the_permalink(); ?>" itemprop="image">
            <?php the_post_thumbnail( ); ?>
          </a>
        </p>
			<?php endif; ?>
			<?php the_title( '<h2 class="release-title entry-title" itemprop="name"><a href="' . esc_url( get_permalink() ) . '">', '</a></h2>' ); ?>
			<?php
			$artist = get_post_meta($post->ID, "record_label_subtitle", true);
			$releaseDate = get_the_date();

			if ( $artist || $releaseDate ) :
				?>
				<p class="release-meta entry-meta">
					<?php if ( $artist ) : ?>
						by <strong class="release-artist" itemprop="byArtist"><?php echo esc_html( $artist ); ?></strong>
					<?php endif; ?>
          <?php if ( $artist && $releaseDate ) : ?>
            <br/>
          <?php endif; ?>
					<?php if ( $releaseDate ) : ?>
						<span class="release-date">Released: <span itemprop="dateCreated"><?php echo esc_html( $releaseDate ); ?></span></span>
					<?php endif; ?>
				</p>
			<?php endif; ?>

      <?php if ( $links = $wp_record_label->get_release_links() ) : ?>
        <div class="release-links">
          <ul class="release-links-list">
            <?php foreach ( $links as $link ) : ?>
              <li class="release-links-item" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                <?php printf(
                  '<a href="%s" class="release-line %s"%s itemprop="url"><span>%s</span></a></li>',
                  $link['url'],
                  str_replace(' ', '_', $link['name']),
                  ( false === strpos( $link['url'], home_url() ) ) ? ' target="_blank"' : '',
                  $link['name']
                ); ?>
              </li>
            <?php endforeach; ?>
          </ul>
        </div>
      <?php endif; ?>
		</article>

	<?php endwhile; ?>

</div>

<?php get_footer(); ?>
