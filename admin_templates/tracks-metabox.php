<div class="wrap">
  <input type="hidden" name="metabox_noncename" id="metabox_noncename" value="<?php wp_create_nonce( plugin_basename(__FILE__) ) ?>" />
  <div id="meta_inner">

    <table class="form-table">
      <tbody>
        <tr>
          <th scope="row">
            <label for="record_label_track_name"><?php echo __("Track name"); ?></label>
          </th>
          <td>
            <input type="text" name="record_label_track_name" id="record_label_track_name" class="regular-text">
            <span class="add button-primary alignright"><?php _e('Add'); ?></span>
          </td>
        </tr>
      </tbody>
    </table>

    <table class="form-table striped sortable">
      <colgroup>
        <col width="5%"/>
        <col/>
        <col width="10%"/>
      </colgroup>
      <thead>
        <tr>
          <th><?php _e('#') ?></th>
          <th><?php _e('Track Name') ?></th>
          <th><?php _e('Actions') ?></th>
        </tr>
      </thead>
      <tbody id="record_label_tracklist_template" style="display: none">
        <?php
          $track_name = "REPLACETHISNAME";
          include $template_path.'tracks-metabox_row.php';
        ?>
      </tbody>
      <tbody id="record_label_tracklist_prepend">
        <?php
        foreach($tracklist as $track_name) {
          include $template_path.'tracks-metabox_row.php';
        }
        ?>
      </tbody>
      <tfoot>
      </tfoot>
    </table>
  </div>
</div>
