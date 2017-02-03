<?php
/*
Plugin Name:  Record Label Post Types
Plugin URI:   https://ignite.digitalignition.net/code/record-label-wordpress-plugin
Description:  Create a release post type with artist and genres
Author:       Greg Tangey
Author URI:   http://ignite.digitalignition.net/
Version:      0.1.0
*/

/*  Copyright 2015  Greg Tangey  (email : greg@digitalignition.net)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if ( ! defined( 'RECORD_LABEL_DIR' ) ) {
	define( 'RECORD_LABEL_DIR', plugin_dir_path( __FILE__ ) );
}

require( RECORD_LABEL_DIR . 'includes/recent_artists-widget.php' );
require( RECORD_LABEL_DIR . 'includes/recent_releases-widget.php' );

if(!class_exists('RecordLabel_Plugin'))
{
  class RecordLabel_Plugin
  {

    public $store_uris = array(
      "itunes" => "https://geo.itunes.apple.com/album/id%s?app=itunes",
      "applemusic" => "https://geo.itunes.apple.com/album/id%s?app=music",
      "spotify" => "https://play.spotify.com/%s",
      "deezer" => "http://www.deezer.com/album/%s"
    );

    public $releaseTypes = array(
      "EPRelease" => "E.P.",
      "SingleRelease" => "Single",
      "AlbumRelease" => "Album",
      "BroadcastRelease" => "Broadcast"
    );

    public $releaseFormats = array(
      "CDFormat" => "Compact Disc",
      "CassetteFormat" => "Cassette",
      "DigitalFormat" => "Digital ",
      "VinylFormat" => "Vinyl"
    );

    public $productionTypes = array(
      "CompilationAlbum" => "Compilation",
      "DJMixAlbum" => "DJ Mix",
      "DemoAlbum" => "Demo",
      "LiveAlbum" => "Live",
      "MixtapeAlbum" => "Mixtape",
      "RemixAlbum" => "Remix",
      "SoundtrackAlbum" => "Soundtrack",
      "SpokenWordAlbum" => "Spoken Word",
      "StudioAlbum" => "Studio"
    );

    public function __construct()
    {
      // Initialize Settings
      include_once( ABSPATH . 'wp-admin/includes/plugin.php');

      $plugin = plugin_basename(__FILE__);
      add_action("init", array($this, 'create_post_types'));
      add_action('add_meta_boxes', array($this,'register_meta_boxes' ));
      add_action( 'admin_print_scripts-post-new.php', array($this,'add_meta_box_js'), 11 );
      add_action( 'admin_print_scripts-post.php', array($this,'add_meta_box_js'), 11 );
      add_filter( 'get_user_option_metaboxhidden_nav-menus', array($this,'always_visible_post_types'), 10, 3 );
      add_action('save_post', array($this,'save_custom_meta'), 10, 3 );
      add_action('widgets_init', array($this,'register_widgets'));
      add_action( 'edit_form_after_title', array( $this, 'add_subtitle_field' ) );
      add_filter( 'manage_release_posts_columns', array($this, 'set_release_columns') );
      add_action( 'manage_release_posts_custom_column' , array($this, 'add_release_columns'), 10, 2 );
      add_action( 'template_include', array($this, 'template_include') );
      add_filter( 'get_the_archive_title', function ( $title ) {
          if( is_post_type_archive() ) {
              $title = post_type_archive_title( '', false );
          }
          return $title;
      });
      add_filter('document_title_parts', function($titleArr) {
        global $post;
        if(is_singular('release')) {
          $titleArr['title'] = $titleArr['title'] . " by " . $this->get_release_subtitle();
        }
        return $titleArr;
      },10,2);
    }

    function always_visible_post_types( $result, $option, $user )
    {
        $show_custom_posts = array("add-post-type-release","add-post-type-artist");
        if(!in_array($show_custom_posts, $result)) {
          $result = array_diff( $result, $show_custom_posts );
        }

        return $result;
    }

    function locate_template( $template_names, $load = false, $require_once = true ) {
    	$template = '';
    	foreach ( (array) $template_names as $template_name ) {
    		if ( ! $template_name ) {
    			continue;
    		}
    		if ( file_exists( get_template_directory() . $template_name ) ) {
    			$template = get_template_directory() . $template_name;
    			break;
    		} elseif ( file_exists( RECORD_LABEL_DIR . 'templates/' . $template_name ) ) {
          $template = RECORD_LABEL_DIR . 'templates/' . $template_name;
    			break;
    		}
    	}
    	if ( $load && ! empty( $template ) ) {
    		load_template( $template, $require_once );
    	}

    	return $template;
    }

    function load_template( $template_file, $data = array(), $locate = false, $require_once = false ) {
    	if ( is_array( $data ) && ! empty( $data ) ) {
    		extract( $data, EXTR_SKIP );
    		unset( $data );
    	}
    	// Locate the template file specified as the first parameter.
    	if ( $locate ) {
    		$template_file = $this->locate_template( $template_file );
    	}
    	if ( $require_once ) {
    		require_once( $template_file );
    	} else {
    		require( $template_file );
    	}
    }

    function template_include($template) {
    	if ( is_post_type_archive( array( 'release', 'artist' ) ) || is_tax( 'genre' ) ) {
    		if ( is_post_type_archive( 'artist' ) ) {
    			$templates[] = 'archive-artist.php';
    		}

    		// if ( is_tax() ) {
    		// 	$term = get_queried_object();
    		// 	$slug = str_replace( 'record-type-', '', $term->slug );
    		// 	$taxonomy = str_replace( 'audiotheme_', '', $term->taxonomy );
    		// 	$templates[] = "taxonomy-$taxonomy-{$slug}.php";
    		// 	$templates[] = "taxonomy-$taxonomy.php";
    		// }

    		$templates[] = 'archive-release.php';
    		$template = $this->locate_template( $templates );
    	} elseif ( is_singular( 'release' ) ) {
    		$template = $this->locate_template( 'single-release.php' );
    	} elseif ( is_singular( 'artist' ) ) {
    		$template = $this->locate_template( 'single-artist.php' );
    	}

    	return $template;
    }

    function register_widgets() {
      register_widget('RecentReleasesWidget');
      register_widget('RecentArtistsWidget');
    }

    function create_post_types() {

      $artist_args = array(
        'labels' => array(
          'name' => __( 'Artists' ),
          'singular_name' => __( 'Artist' ),
          'add_new_item' => __( 'Add New Artist' ),
          'edit_item' => __( 'Edit Artist' ),
          'new_item' => __( 'New Artist' ),
          'view_item' => __( 'View Artist' ),
          'search_items' => __( 'Search Artists' ),
          'not_found' => __( 'No artist found' ),
          'not_found_in_trash' => __( 'No artist found in Trash' ),
          'parent_item_colon' => null,
          'all_items' => __( 'All Artists' ),
          'archives' => __( 'Artist Archives' ),
          'insert_into_item' => __( 'Insert into artist' ),
          'uploaded_to_this_item' => __( 'Uploaded to this artist' ),
          'featured_image' => __( 'Artist Image' ),
          'set_featured_image' => __( 'Set artist image' ),
          'remove_featured_image' => __( 'Remove artist image' ),
          'use_featured_image' => __( 'Use as artist image' ),
          'filter_items_list' => __( 'Filter artist' ),
          'items_list_navigation' => __( 'Artist list navigation' ),
          'items_list' => __( 'Artist list' ),
        ),
        'hierarchical' => false,
        'public' => true,
        'has_archive' => 'artists',
        'supports' => array('title','editor','thumbnail','tags'),
        'taxonomies' => array('tags'),
        'rewrite' => array('slug' => 'artists'),
      );

      $release_args = array(
        'labels' => array(
          'name' => __( 'Releases' ),
          'singular_name' => __( 'Release' ),
          'add_new_item' => __( 'Add New Release' ),
          'edit_item' => __( 'Edit Release' ),
          'new_item' => __( 'New Release' ),
          'view_item' => __( 'View Release' ),
          'search_items' => __( 'Search Releases' ),
          'not_found' => __( 'No release found' ),
          'not_found_in_trash' => __( 'No release found in Trash' ),
          'parent_item_colon' => null,
          'all_items' => __( 'All Releases' ),
          'archives' => __( 'Release Archives' ),
          'insert_into_item' => __( 'Insert into release' ),
          'uploaded_to_this_item' => __( 'Uploaded to this release' ),
          'featured_image' => __( 'Release Image' ),
          'set_featured_image' => __( 'Set release image' ),
          'remove_featured_image' => __( 'Remove release image' ),
          'use_featured_image' => __( 'Use as release image' ),
          'filter_items_list' => __( 'Filter release' ),
          'items_list_navigation' => __( 'Releases list navigation' ),
          'items_list' => __( 'Releases list' ),
        ),
        'hierarchical' => false,
        'public' => true,
        'has_archive' => 'releases',
        'supports' => array('title','editor','thumbnail','tags'),
        'taxonomies' => array('tags'),
        'rewrite' => array('slug' => 'releases'),
      );

      $genre_args = array(
        'hierarchical'      => false,
        'labels'            => array(
          'name'              => _x( 'Genres', 'taxonomy general name' ),
      		'singular_name'     => _x( 'Genre', 'taxonomy singular name' ),
      		'search_items'      => __( 'Search Genres' ),
      		'all_items'         => __( 'All Genres' ),
      		'parent_item'       => null,
      		'parent_item_colon' => null,
      		'edit_item'         => __( 'Edit Genre' ),
      		'update_item'       => __( 'Update Genre' ),
      		'add_new_item'      => __( 'Add New Genre' ),
      		'new_item_name'     => __( 'New Genre Name' ),
      		'menu_name'         => __( 'Genre' ),
        ),
        'show_ui'           => true,
        'show_admin_column' => true,
        'query_var'         => true,
        'rewrite'           => array( 'slug' => 'genre' ),
      );

      register_post_type('release',$release_args);
      register_post_type('artist',$artist_args);

      register_taxonomy( 'genre', ['release','artist'], $genre_args );

      flush_rewrite_rules();

      add_action( 'admin_enqueue_scripts', array( $this, 'load_custom_wp_admin_style') );
    }
    
    function set_release_columns($columns) {
      return array(
          'cb' => '<input type="checkbox" />',
          'cover' => __('Cover'),
          'title' => __('Title'),
          'release_artist' => __( 'Artist' ),
          'taxonomy-genre' => __('Genre'),
          'date' => __('Date'),
      );
    }

    function add_release_columns( $column, $post_id ) {

    	switch ( $column ) {
    		case 'release_artist':
    			echo get_post_meta( $post_id, 'record_label_subtitle', true );
    			break;
        case 'cover':
          echo get_the_post_thumbnail($post_id,'thumbnail');
          break;
    	}
    }

    function register_meta_boxes() {
      add_meta_box("artist_meta", "Artist", array($this,"release_belongs_to_artist"), "release", "normal", "low");
      add_meta_box("release_info", "Release Information", array($this, "release_info"), "release", "side", "low");
      add_meta_box("release_formats", "Release Formats", array($this, "release_formats"), "release", "side", "low");
      add_meta_box("release_tracklist", "Tracklist", array($this, "tracklist_meta"), "release", "normal", "high");
      add_meta_box("release_links", "Buy Links", array($this, "release_links"), "release", "normal", "high");
    }

    function add_meta_box_js() {
      global $post_type;
      if( 'release' == $post_type ) {
        wp_enqueue_script('record_label-admin_scripts',trailingslashit( plugin_dir_url(__FILE__) ).'/admin_javascript/releases.js');
      }
    }

    function save_custom_meta( $post_id, $post, $update ) {
      if ( "release" != $post->post_type ) {
          return;
      }

      // Save release artists
      if(isset($_REQUEST['record_label_artist'])) {
        $artists = $_REQUEST['record_label_artist'];
        if (($key = array_search('REPLACETHISID', $artists)) !== false) {
          unset($artists[$key]);
        }
        delete_post_meta($post_id,'record_label_artists');
        foreach($artists as $artist) {
          add_post_meta($post_id,'record_label_artists',$artist);
        }
      }

      // Save release artist subtitle
      if(isset($_REQUEST['record_label_subtitle'])) {
        update_post_meta($post_id, 'record_label_subtitle', sanitize_text_field($_REQUEST['record_label_subtitle']));
      }

      // Save release tracklist
      if(isset($_REQUEST['record_label_track'])) {
        $tracks = stripslashes_deep($_REQUEST['record_label_track']);
        if (($key = array_search('REPLACETHISNAME', $tracks)) !== false) {
          unset($tracks[$key]);
        }
        $tracks = array_map('sanitize_text_field',$tracks);

        update_post_meta($post_id, 'record_label_tracklist', serialize($tracks));
      }

      // Arrange purchase links
      $oldlinks = $this->get_release_links();
      $links = array();

      if(isset($_REQUEST['record_label_link'])) {
        $posted_links = $_REQUEST['record_label_link'];
        if (isset($posted_links["REPLACEID"])) {
          unset($posted_links["REPLACEID"]);
        }
        foreach($posted_links as $link) {
          if( !empty($link['name']) && !empty($link['url']) ) {
            $link['url'] = esc_url_raw( $link['url']);
            $links[] = $link;
          }
        }
      }

      // Save release format
      if(isset($_REQUEST['record_label_release_formats'])) {
        $releaseFormats = $_REQUEST['record_label_release_formats'];
        $validFormats = array_keys($this->releaseFormats);
        $checkedFormats = array_intersect($validFormats,$releaseFormats);

        $storageArr = array();
        foreach($checkedFormats as $format) {
          $key = "record_label_release_format_".$format."_gtin13";
          $gtin13 = $_REQUEST[$key];

          $key = "record_label_release_format_".$format."_catnum";
          $catnum = $_REQUEST[$key];

          $storageArr[$format] = array(
            "catnum" => $catnum,
            "gtin13" => $gtin13
          );
        }

        // Search for links
        $current_digital_ean = $this->get_release_ean();
        if(isset($storageArr['DigitalFormat'])) {
          $eanVal = $storageArr['DigitalFormat']['gtin13'];
          if($eanVal != "" && $eanVal != $current_digital_ean) {
            require_once("includes/release_link_finder.php");
            $releaseLinkFinder = new RecordLabelReleaseLinkFinder();
            foreach(['itunes','deezer','spotify'] as $store) {
              $storeData = $releaseLinkFinder->find($store,$eanVal);
              if($storeData != NULL) {
                $link=array();
                $link['url'] = sprintf($this->store_uris[$store],$storeData['id']);
                $link['name'] = $store;

                // Ensure any existing stores are upated
                if($key = array_search($store,array_column($links,"names"))) {
                  $links[$key] = $link;
                } else {
                  $links[] = $link;
                }
                if($store == "itunes") {
                  $link=array();
                  $link['url'] = sprintf($this->store_uris["applemusic"],$storeData['id']);
                  $link['name'] = "apple music";
                  if($key = array_search("apple music",array_column($links,"names"))) {
                    $links[$key] = $link;
                  } else {
                    $links[] = $link;
                  }
                }
              }
            }
          }
        }
        update_post_meta($post_id, 'record_label_release_formats', serialize($storageArr));
      }

      // Save purchase links
      if(isset($_REQUEST['record_label_link'])) {
        update_post_meta($post_id, 'record_label_links', serialize($links));
      }

      // Save release type
      if(isset($_REQUEST['record_label_release_type'])) {
        $releaseTypeVal = sanitize_text_field($_REQUEST['record_label_release_type']);
        if($releaseTypeVal != "" && in_array($releaseTypeVal,array_keys($this->releaseTypes)) ) {
          update_post_meta($post_id, 'record_label_release_type', $releaseTypeVal);
        }
      }

      // Save production type
      if(isset($_REQUEST['record_label_production_type'])) {
        $productionTypeVal = sanitize_text_field($_REQUEST['record_label_production_type']);
        if($productionTypeVal != "" && in_array($productionTypeVal,array_keys($this->productionTypes)) ) {
          update_post_meta($post_id, 'record_label_production_type', $productionTypeVal);
        }
      }
    }

    function add_subtitle_field($post) {
      if($post->post_type == "release") {
        $subtitle = get_post_meta($post->ID, "record_label_subtitle", true);
        ?>
        <div id="subtitlediv" class="top">
          <div id="subtitlewrap">
            <input style="width: 100%; height: 1.5em; font-size: 1.5em; padding: 3px 8px;" type="text" id="wpsubtitle" name="record_label_subtitle" value="<?php echo $subtitle; ?>" autocomplete="off" placeholder="Enter a subtitle here" />
          </div>
        </div>
        <?php
      }
    }


    function load_custom_wp_admin_style($hook) {
      global $post;
      if($post->post_type == "release") {
        wp_register_style( 'record_label_admin_css', trailingslashit( plugin_dir_url(__FILE__) ).'admin.css', false, '1.0.0' );
        wp_enqueue_style( 'record_label_admin_css' );
      }
    }


    function tracklist_meta() {
      global $post;
      $template_path = RECORD_LABEL_DIR."admin_templates/";

      $tracklist = $this->get_release_tracklist();
      include $template_path."tracks-metabox.php";
    }

    function release_links() {
      global $post;
      $template_path = RECORD_LABEL_DIR."admin_templates/";

      $links = $this->get_release_links();

      include $template_path."links-metabox.php";
    }

    function release_formats() {
      global $post;
      $template_path = RECORD_LABEL_DIR."admin_templates/";
      $releaseFormats = $this->get_release_formats();

      include $template_path."formats-metabox.php";
    }

    function release_info() {
      global $post;
      $template_path = RECORD_LABEL_DIR."admin_templates/";
      $releaseType = $this->get_release_type();
      $productionType = $this->get_release_production_type();

      include $template_path."info-metabox.php";
    }

    function release_belongs_to_artist() {
      global $post;
      $template_path = RECORD_LABEL_DIR."admin_templates/";

      $args = array('numberposts' => -1, 'post_type' => 'artist', 'order'=> 'ASC', 'orderby' => 'title' );
      $artist_posts = get_posts($args);

      $artists = $this->get_release_artists();

      include $template_path."artists-metabox.php";
    }

    function get_release_ean($format="DigitalFormat") {
      $formats = $this->get_release_formats();
      return $formats[$format]["gtin13"];
    }

    function get_release_production_type() {
      global $post;
      $type = get_post_meta($post->ID, 'record_label_production_type',true);
      return $type;
    }

    function get_release_type() {
      global $post;
      $type = get_post_meta($post->ID, 'record_label_release_type',true);
      return $type;
    }

    function get_artist_releases() {
      global $post;

      $args = array(
        'meta_key' => 'record_label_artists',
        'meta_value' => $post->ID,
        'post_type' => 'release'
      );
      $query = new WP_Query($args);

      return $query;
    }

    function get_release_artists() {
      global $post;
      $data = get_post_meta($post->ID,'record_label_artists',false);
      return $data;
    }

    function get_release_tracklist() {
      $tracks= $this->get_multi_value('record_label_tracklist');
      return $tracks;
    }

    function get_release_links() {
      return $this->get_multi_value('record_label_links');
    }

    function get_release_formats() {
      return $this->get_multi_value('record_label_release_formats');
    }

    function get_release_subtitle() {
      global $post;
      $subtitle = get_post_meta($post->ID, "record_label_subtitle", true);
      return $subtitle;
    }

    function get_multi_value($meta_name) {
      global $post;

      $data = get_post_meta($post->ID,$meta_name,true);
      if($data == false) {
        return array();
      }
      else {
        return unserialize($data);
      }
    }
  }

}

if(class_exists('RecordLabel_Plugin'))
{
  $wp_record_label = new RecordLabel_Plugin();
}

if(!function_exists('record_label_subtitle')) {
  function record_label_subtitle() {
    global $post;
    if($post->post_type == "release") {
      echo get_post_meta($post->ID, "record_label_subtitle", true);
    }
  }
}

?>
