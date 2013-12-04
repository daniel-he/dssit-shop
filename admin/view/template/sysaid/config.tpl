<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
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
        <table id="service_center" class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $entry_service_center; ?></td>
              <td></td>
            </tr>
          </thead>
          <?php $service_center_row = 0; ?>
          <?php foreach ($service_centers as $service_center) { ?>
          <tbody id="service_center-row<?php echo $service_center_row; ?>">
            <tr>
              <td class="left"><input type="text" name="service_center<?php echo $service_center_row; ?>" value="<?php echo $service_center; ?>"></td>
              <td class="left"><a onclick="$('#service_center-row<?php echo $service_center_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
            </tr>
          </tbody>
          <?php $service_center_row++; ?>
          <?php } ?>
          <tfoot>
            <tr>
              <td colspan="1"></td>
              <td class="left"><a onclick="addService_Center();" class="button"><?php echo $button_add_service_center; ?></a></td>
            </tr>
          </tfoot>
        </table>
        <table id="building" class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $entry_building; ?></td>
              <td></td>
            </tr>
          </thead>
          <?php $building_row = 0; ?>
          <?php foreach ($buildings as $building) { ?>
          <tbody id="building-row<?php echo $building_row; ?>">
            <tr>
              <td class="left"><input type="text" name="building<?php echo $building_row; ?>" value="<?php echo $building; ?>"></td>
              <td class="left"><a onclick="$('#building-row<?php echo $building_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
            </tr>
          </tbody>
          <?php $building_row++; ?>
          <?php } ?>
          <tfoot>
            <tr>
              <td colspan="1"></td>
              <td class="left"><a onclick="addBuilding();" class="button"><?php echo $button_add_building; ?></a></td>
            </tr>
          </tfoot>
        </table>
      </form>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
var service_center_row = <?php echo $service_center_row; ?>;
var building_row = <?php echo $building_row; ?>;

function addService_Center() {	
	html  = '<tbody id="service_center-row' + service_center_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><input type="text" name="service_center' + service_center_row + '"></td>';
	html += '    <td class="left"><a onclick="$(\'#service_center-row' + service_center_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#service_center tfoot').before(html);
	
	service_center_row++;
}

function addBuilding() {	
	html  = '<tbody id="building-row' + building_row + '">';
	html += '  <tr>';
	html += '    <td class="left"><input type="text" name="building' + building_row + '"></td>';
	html += '    <td class="left"><a onclick="$(\'#building-row' + building_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
	html += '  </tr>';
	html += '</tbody>';
	
	$('#building tfoot').before(html);
	
	building_row++;
}
//--></script> 
<?php echo $footer; ?>