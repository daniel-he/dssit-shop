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
        <table id="supplier" class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $entry_suppliers; ?></td>
              <td></td>
            </tr>
          </thead>
          <?php $supplier_row = 0; ?>
          <?php foreach ($suppliers as $supplier) { ?>
          <tbody id="supplier-row<?php echo $supplier_row; ?>">
            <tr>
              <td class="left"><input type="text" name="supplier<?php echo $supplier_row; ?>" value="<?php echo $supplier; ?>"></td>
              <td class="left"><a onclick="$('#supplier-row<?php echo $supplier_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
            </tr>
          </tbody>
          <?php $supplier_row++; ?>
          <?php } ?>
          <tfoot>
            <tr>
              <td colspan="2"></td>
              <td class="left"><a onclick="addSupplier();" class="button"><?php echo $button_add_supplier; ?></a></td>
            </tr>
          </tfoot>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
var supplier_row = <?php echo $supplier_row; ?>;

function addSupplier() {	
	html  = '<tbody id="supplier-row' + supplier_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><input type="text" name="supplier' + supplier_row + '"></td>';
	html += '    <td class="left"><a onclick="$(\'#supplier-row' + supplier_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#supplier tfoot').before(html);
	
	supplier_row++;
}
//--></script> 
<?php echo $footer; ?>