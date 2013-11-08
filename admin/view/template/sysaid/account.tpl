<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table id="subcategory" class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $entry_account; ?></td>
              <td></td>
            </tr>
          </thead>
	  <tbody>
            <tr>
              <td class="left"><?php echo $entry_username; ?></td>
              <td><input type="text" name="sysaid-user" value="<?php echo $sysaid_user; ?>"></td>
            </tr>
            <tr>
              <td class="left"><?php echo $entry_password; ?></td>
              <td><input type="password" name="sysaid-password" value="<?php echo $sysaid_password; ?>"></td>
            </tr>
	  </tbody>
        </table>
      </form>
    </div>
  </div>
</div>