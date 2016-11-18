<div class="wrap">
  <input type="hidden" name="metabox_noncename" id="metabox_noncename" value="<?php wp_create_nonce( plugin_basename(__FILE__) ) ?>" />
  <div id="meta_inner">

    <table class="form-table">
      <tbody>
        <tr>
          <th scope="row">
            <label for="record_label_link"><?php echo __("Link"); ?></label>
          </th>
          <td>
            <input type="text" name="record_label_link" id="record_label_link" class="regular-text">
            <span class="add button-primary alignright"><?php _e('Add'); ?></span>
          </td>
        </tr>
      </tbody>
    </table>

    <table class="form-table striped sortable">
      <colgroup>
        <col/>
        <col/>
        <col width="10%"/>
      </colgroup>
      <thead>
        <tr>
          <th><?php _e('Name') ?></th>
          <th><?php _e('URL') ?></th>
          <th><?php _e('Actions') ?></th>
        </tr>
      </thead>
      <tbody id="record_label_links_template" style="display: none">
        <?php
          $link = array();
          $link['url'] = "REPLACEURL";
          $link['name'] = "REPLACENAME";

          $i = "REPLACEID";

          include $template_path.'links-metabox_row.php';
        ?>
      </tbody>
      <tbody id="record_label_links_prepend">
        <?php
        foreach($links as $i => $link) {
          include $template_path.'links-metabox_row.php';
        }
        ?>
      </tbody>
      <tfoot>
      </tfoot>
    </table>
  </div>
</div>
