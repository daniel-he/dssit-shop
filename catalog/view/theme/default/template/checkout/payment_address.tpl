<div id="payment-new" style="display: <?php echo 'block'; ?>;">
  <table class="form">
    <tr>
      <td><span class="required">*</span> <?php echo $entry_firstname; ?></td>
      <td><input type="text" name="firstname" value="<?php echo $customer_firstname; ?>" class="large-field" /></td>
    </tr>
    <tr>
      <td><span class="required">*</span> <?php echo $entry_lastname; ?></td>
      <td><input type="text" name="lastname" value="<?php echo $customer_lastname; ?>" class="large-field" /></td>
    </tr>
    <tr>
      <td><span class="required">*</span> <?php echo $entry_email; ?></td>
      <td><input type="text" name="email" value="<?php echo $customer_email; ?>" class="large-field" /></td>
    </tr>
    <tr>
      <td><?php echo $entry_telephone; ?></td>
      <td><input type="text" name="telephone" value="<?php echo $customer_telephone; ?>" class="large-field" /></td>
    </tr>
    <tr>
      <td><span class="required">*</span> <?php echo $entry_address_1; ?></td>
      <td><input type="text" name="address_1" value=<?php echo "'" . $address_1 . "'"; ?> class="large-field" /></td>
    </tr>
    <tr>
      <td><span class="required">*</span> <?php echo $entry_building; ?></td>
      <td><select name="building" class="large-field">
          <option value=""><?php echo $text_select; ?></option>
          <?php foreach ($buildings as $thebuilding) { ?>
          <?php if ($thebuilding == $building) { ?>
          <option value="<?php echo $thebuilding; ?>" selected="selected"><?php echo $thebuilding; ?></option>
          <?php } else { ?>
          <option value="<?php echo $thebuilding; ?>"><?php echo $thebuilding; ?></option>
          <?php } ?>
          <?php } ?>
        </select></td>
    </tr>
  </table>
</div>
<br />
<div class="buttons">
  <div class="right">
    <input type="button" value="<?php echo $button_continue; ?>" id="button-payment-address" class="button" />
  </div>
</div>