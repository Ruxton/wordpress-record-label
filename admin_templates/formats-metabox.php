<div class="wrap">
  <input type="hidden" name="metabox_noncename" id="metabox_noncename" value="<?php wp_create_nonce( plugin_basename(__FILE__) ) ?>" />
  <div class="meta_inner">
    <label for="record_label_release_formats"><?php echo __('Release Formats'); ?></label>
    <?php
    foreach ($this->releaseFormats as $releaseFormatKey => $releaseFormatStr) {
      ?>
      <div class="record_label_release_format">

        <input type="checkbox" name="record_label_release_formats[]" id="record_label_release_format_<?php echo $releaseFormatKey ?>" value="<?php echo $releaseFormatKey ?>"<?php echo array_key_exists($releaseFormatKey,$releaseFormats) ? ' CHECKED' : ''?> />
        <label for="record_label_release_format_<?php echo $releaseFormatKey ?>"><?php echo $releaseFormatStr; ?></label>
        <div class="record_label_release_format_identifiers">
          <label for="record_label_release_format_<?php echo $releaseFormatKey ?>_gtin13">UPC/EAN</label>
          <input type="text" name="record_label_release_format_<?php echo $releaseFormatKey ?>_gtin13" value="<?php echo $releaseFormats[$releaseFormatKey]['gtin13']; ?>" />
          <label for="record_label_release_format_<?php echo $releaseFormatKey ?>_catnum">Catalogue Number</label>
          <input type="text" name="record_label_release_format_<?php echo $releaseFormatKey ?>_catnum" value="<?php echo $releaseFormats[$releaseFormatKey]['catnum']; ?>" />
        </div>
      </div>
      <?php
    }
    ?>
  </div>
</div>
