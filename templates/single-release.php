<?php
/**
 * The template for displaying a single release.
 */

get_header();
?>

<?php while ( have_posts() ) : the_post(); ?>

	<article id="post-<?php the_ID(); ?>" <?php post_class( 'release' ); ?> itemscope itemtype="http://schema.org/MusicAlbum" role="article">

		<?php if ( has_post_thumbnail() ) : ?>
			<p class="release-artwork">
				<a href="<?php echo wp_get_attachment_url( get_post_thumbnail_id() ); ?>" itemprop="image">
					<?php the_post_thumbnail("single-release"); ?>
				</a>
			</p>
		<?php endif; ?>

		<header class="entry-header">
			<?php the_title( '<h1 class="release-title entry-title" itemprop="name">', '</h1>' ); ?>

			<?php if ( $artist = get_post_meta($post->ID, "record_label_subtitle", true) ) : ?>
				<h2 class="release-artist">by <span itemprop="byArtist"><?php echo esc_html( $artist ); ?></span></h2>
			<?php endif; ?>
    </header>

    <ul class="release-meta meta-list">
			<?php if ( $year = get_the_date() ) : ?>
				<li class="meta-item">
					<span class="label"><?php _e( 'Released', 'record_label' ); ?></span>
					<span itemprop="dateCreated"><?php echo esc_html( $year ); ?></span>
				</li>
			<?php endif; ?>
			<?php if ( $genre = get_post_meta($post->ID, "record_label_genre", true) ) : ?>
				<li class="meta-item">
					<span class="label"><?php _e( 'Genre', 'record_label' ); ?></span>
					<span itemprop="genre"><?php echo esc_html( $genre ); ?></span>
				</li>
			<?php endif; ?>
      <?php if ( $releaseType = $wp_record_label->get_release_type() ) : ?>
				<li class="meta-item">
					<span class="label"><?php _e( 'Release Type', 'record_label' ); ?></span>
					<span itemprop="albumReleaseType" content="<?php echo $releaseType; ?>"><?php echo esc_html( $wp_record_label->releaseTypes[$releaseType] ); ?></span>
				</li>
			<?php endif; ?>
      <?php if ( $productionType = $wp_record_label->get_release_production_type() ) : ?>
        <li class="meta-item">
          <span class="label"><?php _e( 'Production Type', 'record_label' ); ?></span>
          <span itemprop="albumProductionType"  content="<?php echo $productionType; ?>" ><?php echo esc_html( $wp_record_label->productionTypes[$productionType] ); ?></span>
        </li>
      <?php endif; ?>
		</ul>

    <?php if ( $tracklist = $wp_record_label->get_release_tracklist() ) : ?>
      <div class="tracklist-section">
        <h2 class="tracklist-title label"><?php _e( 'Track List', 'record_label' ); ?> (<span itemprop="numTracks"><?php echo count($tracklist); ?></span>)</h2>
        <ol class="tracklist">

          <?php foreach ( $tracklist as $num => $track ) : ?>
            <li id="track-<?php echo $num; ?>" class="track" itemprop="track" itemscope itemtype="http://schema.org/MusicRecording">
              <span class="track-info track-cell">
                <span itemprop="name"><?php echo $track; ?></span>
              </span>
            </li>

          <?php endforeach; ?>
        </ol>
      </div>
    <?php endif; ?>

    <?php if ( $releaseFormats = $wp_record_label->get_release_formats() ) : ?>
      <div class="format-table">
        <table>
          <colgroup>
            <col width="30%" />
            <col width="30%" />
            <col width="40%" />
          </colgroup>
          <thead>
            <tr>
              <th>Format</th>
              <th>Catalogue #</th>
              <th>GTIN13</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ( $releaseFormats as $releaseFormat => $releaseArr) : ?>
              <tr itemprop="albumRelease" itemscope itemtype="http://schema.org/MusicRelease">
                <td itemprop="musicReleaseFormat" content="<?php echo $releaseFormat; ?>">
                  <?php echo esc_html( $wp_record_label->releaseFormats[$releaseFormat] ); ?>
                </td>
                <td itemprop="catalogNumber">
                  <?php echo $releaseArr['catnum']; ?>
                </td>
                <td itemprop="isBasedOn" itemscope itemtype="http://schema.org/Product"><span itemprop="gtin13"><?php echo $releaseArr['gtin13']; ?></span></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
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

		<div class="entry-content" itemprop="description">
			<?php the_content( '' ); ?>
		</div>

	</article>

<?php endwhile; ?>

<?php get_footer(); ?>
