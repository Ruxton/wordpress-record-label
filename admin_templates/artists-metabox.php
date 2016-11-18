<div class="wrap">
  <input type="hidden" name="metabox_noncename" id="metabox_noncename" value="<?php wp_create_nonce( plugin_basename(__FILE__) ) ?>" />
  <div id="meta_inner">

    <table class="form-table">
      <tbody>
        <tr>
          <th scope="row">
            <label for="record_label_artist_id"><?php echo __("Artist"); ?></label>
          </th>
          <td>
            <select name="record_label_artist_id" id="record_label_artist_id" class="regular-text">
              <option value=""><?php echo __('Please choose an Artist'); ?></option>
              <?php foreach($artist_posts as $artist) : ?>
                <option value="<?php echo $artist->ID ?>"><?php echo $artist->post_title ?></option>
              <?php endforeach; ?>
            </select>

            <span class="add button-primary alignright"><?php _e('Add'); ?></span>
          </td>
        </tr>
      </tbody>
    </table>

    <table class="form-table striped sortable">
      <colgroup>
        <col/>
        <col width="10%"/>
      </colgroup>
      <thead>
        <tr>
          <th><?php _e('Artist') ?></th>
          <th><?php _e('Actions') ?></th>
        </tr>
      </thead>
      <tbody id="record_label_artist_template" style="display: none">
        <?php
          $artist_id = "REPLACETHISID";
          $artist_name = "REPLACETHISNAME";
          include $template_path.'artists-metabox_row.php';
        ?>
      </tbody>
      <tbody id="record_label_artist_prepend">
        <?php
        foreach($artists as $artist) {
          $artist_id = $artist;
          $artist_name = get_post($artist_id)->post_title;

          include $template_path.'artists-metabox_row.php';
        }
        ?>
      </tbody>
      <tfoot>
      </tfoot>
    </table>

    <script>
      jQuery(document).ready(function() {
          jQuery('#record_label_artist_id + .add').click(function() {
              template = jQuery("#record_label_artist_template tr")[0].cloneNode(true);
              elements = jQuery('.record_label_release_row').length;
              selected_artist = jQuery("#record_label_artist_id")[0];

              selected_artist_id = selected_artist.selectedOptions[0].value;
              selected_artist_name = selected_artist.selectedOptions[0].text;

              template.innerHTML = template.innerHTML.replace(/REPLACETHISID/g,selected_artist_id);
              template.innerHTML = template.innerHTML.replace(/REPLACETHISNAME/g,selected_artist_name);

              jQuery('#record_label_artist_prepend').prepend(template);
              return false;
          });
          jQuery(".remove").live('click', function() {
              jQuery(this).parent().parent().remove();
          });
      });
    </script>

  </div>
</div>
