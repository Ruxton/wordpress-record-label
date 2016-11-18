<?php
class MasterLinkDeezerFinder implements MasterLinkIFinder {
  private static $uri_base = "https://api.deezer.com/album/upc:%d";

  public function __construct() {
  }

  public function find($upc) {
    $searchData = $this->getData($upc);
    if(isset($searchData->id)) {
      $return = array();
      $return['id'] = $searchData->id;
      $return['cover'] = $searchData->cover_big;
      return $return;
    } else {
      return null;
    }
  }

  private function searchURI($upc) {
    return sprintf(MasterLinkDeezerFinder::$uri_base,$upc);
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
