<?php echo $before_title; ?>
Releases
<?php echo $after_title; ?>
<?php
while($release_posts->have_posts()) {
  $release_posts->the_post();
  $artist = get_post_meta($post->ID, "record_label_subtitle", true);
  ?>
  <div class="release" itemscope itemtype="http://schema.org/MusicAlbum">
    <a href="<?php the_permalink(); ?>" itemprop="image">
      <?php the_post_thumbnail(); ?>
    </a>
    <a href="<?php the_permalink(); ?>">
      <div class="release_info">
        <h4 class="release-artist" itemprop="byArtist"><?php echo esc_html( $artist ); ?></h4>
        <?php the_title( '<h3 class="release-title entry-title" itemprop="name">', '</h3>' ); ?>
      </div>
    </a>
  </div>
  <?php
}
wp_reset_postdata();
?>
<br clear="all" />
<a class="more" href="<?php echo get_post_type_archive_link("release"); ?>">More releases</a>
