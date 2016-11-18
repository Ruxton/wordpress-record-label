<?php
class RecentReleasesWidget extends WP_Widget {

  function __construct() {
    // Instantiate the parent object
    parent::__construct( 'record_label_recent_releases',
      __('Recent Releases'), //Name
      array( 'description' => __( 'A widget to display the most recent releases' ), ) // Args
     );
  }

  function widget( $args, $instance ) {
    global $post;
    extract($args, EXTR_SKIP);

    $args = array('numberposts' => 4, 'post_type' => 'release', 'order'=> 'DESC', 'orderby' => 'post_date' );
    $release_posts = new WP_Query($args);
    print $before_widget;
    include RECORD_LABEL_DIR."widget_templates/releases_widget.php";
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
