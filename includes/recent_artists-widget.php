<?php
class RecentArtistsWidget extends WP_Widget {
  function __construct() {
    // Instantiate the parent object
    parent::__construct( 'record_label_recent_artists',
      __('Recent Artists'), //Name
      array( 'description' => __( 'A widget to display the most recent artists' ), ) // Args
     );
  }

  function widget( $args, $instance ) {
    extract($args, EXTR_SKIP);

    $args = array('numberposts' => 4, 'post_type' => 'artist', 'order'=> 'DESC', 'orderby' => 'post_date' );
    $artist_posts = new WP_Query($args);
    print $before_widget;
    include RECORD_LABEL_DIR."widget_templates/artists_widget.php";
    print $after_widget;
  }

  function update( $new_instance, $old_instance ) {
    // Save widget options
  }

  function form( $instance ) {
    // Output admin widget options form
  }
}
?>
