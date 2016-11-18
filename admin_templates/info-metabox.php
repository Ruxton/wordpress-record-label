<div class="wrap">
  <input type="hidden" name="metabox_noncename" id="metabox_noncename" value="<?php wp_create_nonce( plugin_basename(__FILE__) ) ?>" />
  <div class="meta_inner">
    <label><?php echo __('Type'); ?></label>
    <?php
    foreach($this->releaseTypes as $releaseTypeKey => $releaseTypeStr) {
      ?>
      <input type="radio" name="record_label_release_type" id="record_label_release_type_<?php echo $releaseTypeKey; ?>" value="<?php echo $releaseTypeKey ?>"<?php echo ($releaseType == $releaseTypeKey) ? ' CHECKED' : ''?> />
      <label for="record_label_release_type_<?php echo $releaseTypeKey; ?>"><?php echo $releaseTypeStr; ?></label><br/>
      <?php
    }
    ?>

    <label><?php echo __('Production Type'); ?></label>
    <?php
    foreach($this->productionTypes as $productionTypeKey => $productionTypeStr) {
      ?>
      <input type="radio" name="record_label_production_type" id="record_label_production_type_<?php echo $productionTypeKey; ?>" value="<?php echo $productionTypeKey ?>"<?php echo ($productionType == $productionTypeKey) ? ' CHECKED' : ''?> />
      <label for="record_label_production_type_<?php echo $productionTypeKey; ?>"><?php echo $productionTypeStr; ?></label><br/>
      <?php
    }
    ?>
  </div>
</div>
