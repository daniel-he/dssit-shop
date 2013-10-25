<table class="form">
  <tr>
    <td><span class="required">*</span> <?php echo $entry_firstname; ?></td>
    <td><input type="text" name="firstname" value="<?php echo $firstname; ?>" class="large-field" /></td>
  </tr>
  <tr>
    <td><span class="required">*</span> <?php echo $entry_lastname; ?></td>
    <td><input type="text" name="lastname" value="<?php echo $lastname; ?>" class="large-field" /></td>
  </tr>
   <tr>
   <td><span class="required">*</span> <?php echo $entry_email; ?></td>
   <td><input type="text" name="email" value="<?php echo $email; ?>" class="large-field" /></td>
   </tr>
  <tr>
    <td><span class="required">*</span> <?php echo $entry_address_1; ?></td>
    <td><input type="text" name="address_1" value="<?php echo $address_1; ?>" class="large-field" /></td>
  </tr>
  <tr>
    <td><span class="required">*</span> <?php echo $entry_building; ?></td>
    <td><select name="building_id" class="large-field">
        <option value=""><?php echo $text_select; ?></option>
        <?php foreach ($buildings as $building) { ?>
        <?php if ($building['building_id'] == $building_id) { ?>
        <option value="<?php echo $building['building_id']; ?>" selected="selected"><?php echo $building['name']; ?></option>
        <?php } else { ?>
        <option value="<?php echo $building['building_id']; ?>"><?php echo $building['name']; ?></option>
        <?php } ?>
        <?php } ?>
      </select></td>
  </tr>
</table>
<br />
<div class="buttons">
  <div class="right"><input type="button" value="<?php echo $button_continue; ?>" id="button-shipping-address" class="button" /></div>
</div>