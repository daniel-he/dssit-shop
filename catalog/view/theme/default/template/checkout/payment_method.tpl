<?php if (isset($error_warning) && $error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<span class="required">*</span><b><?php echo $entry_dafis; ?></b>
<textarea name="account" rows="8" style="width: 98%;"><?php echo $account; ?></textarea>
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