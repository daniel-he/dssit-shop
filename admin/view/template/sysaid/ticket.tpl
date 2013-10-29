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
              <td class="left"><?php echo $entry_subcategory; ?></td>
              <td></td>
            </tr>
          </thead>
          <?php $subcategory_row = 0; ?>
          <?php foreach ($subcategories as $subcategory) { ?>
          <tbody id="subcategory-row<?php echo $subcategory_row; ?>">
            <tr>
              <td class="left"><input type="text" name="subcategory<?php echo $subcategory_row; ?>" value="<?php echo $subcategory; ?>"></td>
              <td class="left"><a onclick="$('#subcategory-row<?php echo $subcategory_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
            </tr>
          </tbody>
          <?php $subcategory_row++; ?>
          <?php } ?>
          <tfoot>
            <tr>
              <td colspan="2"></td>
              <td class="left"><a onclick="addSubcategory();" class="button"><?php echo $button_add_subcategory; ?></a></td>
            </tr>
          </tfoot>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
var subcategory_row = <?php echo $subcategory_row; ?>;

function addSubcategory() {	
	html  = '<tbody id="subcategory-row' + subcategory_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><input type="text" name="subcategory' + subcategory_row + '"></td>';
	html += '    <td class="left"><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#subcategory tfoot').before(html);
	
	subcategory_row++;
}
//--></script> 
<?php echo $footer; ?>