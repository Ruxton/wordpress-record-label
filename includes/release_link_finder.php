<?php
require_once "release_link_finders/base.php";
require_once "release_link_finders/deezer.php";
require_once "release_link_finders/itunes.php";
require_once "release_link_finders/spotify.php";


class RecordLabelReleaseLinkFinder {

  function find($store,$upc) {
    $store=ucfirst($store);
    $function = "find".$store;

    if(method_exists($this,$function)) {
      return $this->$function($upc);
    } else {
      return NULL;
    }
  }

  private

  function findItunes($upc) {
    $finder = new MasterLinkiTunesFinder();
    return $finder->find($upc);
  }

  function findDeezer($upc) {
    $finder = new MasterLinkDeezerFinder();
    return $finder->find($upc);
  }

  function findSpotify($upc) {
    $finder = new MasterLinkSpotifyFinder();
    return $finder->find($upc);
  }
}
?>
