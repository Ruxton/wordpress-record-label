<tr class="record_label_links_row">
  <td><input type="text" name="record_label_link[<?php echo $i; ?>][name]" value="<?php echo esc_attr( $link['name'] ); ?>" placeholder="<?php esc_attr_e( 'Text', 'audiotheme' ); ?>" class="record-link-name audiotheme-clear-on-add" style="width: 8em"></td>
  <td><input type="text" name="record_label_link[<?php echo $i; ?>][url]" value="<?php echo esc_url( $link['url'] ); ?>" placeholder="<?php esc_attr_e( 'URL', 'audiotheme' ); ?>" class="widefat audiotheme-clear-on-add"></td>
  <td><button class="remove button-secondary"><?php _e('Remove') ?></button></td>
</tr>
