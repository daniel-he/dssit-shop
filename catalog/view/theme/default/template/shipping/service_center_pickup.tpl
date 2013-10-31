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
    <td><span class="required">*</span> <?php echo $entry_service_center; ?></td>
    <td><select name="service_center" class="large-field">
        <option value=""><?php echo $text_select; ?></option>
        <?php foreach ($service_centers as $theserv) { ?>
        <?php if ($theserv == $service_center) { ?>
        <option value="<?php echo $theserv; ?>" selected="selected"><?php echo $theserv; ?></option>
        <?php } else { ?>
        <option value="<?php echo $theserv; ?>"><?php echo $theserv; ?></option>
        <?php } ?>
        <?php } ?>
      </select></td>
  </tr>
</table>
<br />
<div class="buttons">
  <div class="right"><input type="button" value="<?php echo $button_continue; ?>" id="button-shipping-address" class="button" /></div>
</div>