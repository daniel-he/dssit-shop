<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($payment_methods) { ?>
<p><?php echo $text_payment_method; ?></p>
<table class="form">
  <tr>
    <td><span class="required">*</span> <?php echo $entry_dafis; ?></td>
    <td><input type="text" name="firstname" value="<?php echo $account; ?>" class="large-field" /></td>
  </tr>
</table>
<br />
<?php } ?>
<b><?php echo $text_comments; ?></b>
<textarea name="comment" rows="8" style="width: 98%;"><?php echo $comment; ?></textarea>
<br />
<br />
<div class="buttons">
  <div class="right">
    <input type="button" value="<?php echo $button_continue; ?>" id="button-payment-method" class="button" />
  </div>
</div>
<script type="text/javascript"><!--
$('.colorbox').colorbox({
	width: 640,
	height: 480
});
//--></script> 