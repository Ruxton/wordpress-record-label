<?php echo $before_title; ?>
Artists
<?php echo $after_title; ?>
<?php
while($artist_posts->have_posts()) {
  $artist_posts->the_post();
  ?>
  <div class="artist" itemscope itemtype="http://schema.org/MusicGroup">
    <a href="<?php the_permalink(); ?>" itemprop="image">
      <?php the_post_thumbnail(); ?>
    </a>
    <a href="<?php the_permalink(); ?>">
      <div class="artist_info">
        <?php the_title( '<h3 class="artist-title entry-title" itemprop="name">', '</h3>' ); ?>
      </div>
    </a>
  </div>
  <?php
}
wp_reset_postdata();
?>
<br clear="all" />
<a class="more" href="<?php echo get_post_type_archive_link("artist"); ?>">More artists</a>
