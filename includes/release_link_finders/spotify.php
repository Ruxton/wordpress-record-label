<?php
class MasterLinkSpotifyFinder implements MasterLinkIFinder {
  private static $uri_base = "https://api.spotify.com/v1/search?query=upc:%d&offset=0&limit=1&type=album";

  public function __construct() {
  }

  public function find($upc) {
    $searchData = $this->getData($upc);
    if(isset($searchData->albums->items[0])) {
      $return = array();
      $return['id'] = str_replace("https://open.spotify.com/","",$searchData->albums->items[0]->external_urls->spotify);
      $return['cover'] = $searchData->albums->images[0]->url;
      return $return;
    } else {
      return null;
    }
  }

  private function searchURI($upc) {
    return sprintf(MasterLinkSpotifyFinder::$uri_base,$upc);
  }

  private function getData($upc) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $this->searchURI($upc));
    $result = curl_exec($ch);
    curl_close($ch);

    return json_decode($result);
  }
}
?>
