<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <div class="checkout">
    <div id="payment-address">
      <div class="checkout-heading"><span><?php echo $text_checkout_payment_address; ?></span></div>
      <div class="checkout-content"></div>
    </div>
    <div id="payment-method">
      <div class="checkout-heading"><?php echo $text_checkout_payment_method; ?></div>
      <div class="checkout-content"></div>
    </div>
    <?php if ($shipping_required) { ?>
    <div id="shipping-method">
      <div class="checkout-heading"><?php echo $text_checkout_shipping_method; ?></div>
      <div class="checkout-content"></div>
    </div>
    <div id="shipping-address">
      <div class="checkout-heading"><?php echo $text_checkout_shipping_address; ?></div>
      <div class="checkout-content"></div>
    </div>
    <?php } ?>
    <div id="confirm">
      <div class="checkout-heading"><?php echo $text_checkout_confirm; ?></div>
      <div class="checkout-content"></div>
    </div>
  </div>
  <?php echo $content_bottom; ?></div>
<script type="text/javascript"><!--
$('.checkout-heading a').live('click', function() {
	$('.checkout-content').slideUp('slow');
	
	$(this).parent().parent().find('.checkout-content').slideDown('slow');
});

$(document).ready(function() {
	<?php if(isset($quickconfirm)) { ?>
		quickConfirm();
	<?php }else{ ?>
		$.ajax({
			url: 'index.php?route=checkout/payment_address',
			dataType: 'html',
			success: function(html) {
				$('#payment-address .checkout-content').html(html);
					
				$('#payment-address .checkout-content').slideDown('slow');
			},
			error: function(xhr, ajaxOptions, thrownError) {
				alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
			}
		});	
	<?php } ?>
});		

// Payment Address	
$('#button-payment-address').live('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/payment_address/validate',
		type: 'post',
		data: $('#payment-address input[type=\'text\'], #payment-address input[type=\'password\'], #payment-address input[type=\'checkbox\']:checked, #payment-address input[type=\'radio\']:checked, #payment-address input[type=\'hidden\'], #payment-address select'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-payment-address').attr('disabled', true);
			$('#button-payment-address').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},	
		complete: function() {
			$('#button-payment-address').attr('disabled', false);
			$('.wait').remove();
		},			
		success: function(json) {
			$('.warning, .error').remove();
			
			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				if (json['error']['warning']) {
					$('#payment-address .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
					
					$('.warning').fadeIn('slow');
				}
								
				if (json['error']['firstname']) {
					$('#payment-address input[name=\'firstname\']').after('<span class="error">' + json['error']['firstname'] + '</span>');
				}
				
				if (json['error']['lastname']) {
					$('#payment-address input[name=\'lastname\']').after('<span class="error">' + json['error']['lastname'] + '</span>');
				}	
				
				if (json['error']['telephone']) {
					$('#payment-address input[name=\'telephone\']').after('<span class="error">' + json['error']['telephone'] + '</span>');
				}		
				
				if (json['error']['company_id']) {
					$('#payment-address input[name=\'company_id\']').after('<span class="error">' + json['error']['company_id'] + '</span>');
				}	
				
				if (json['error']['tax_id']) {
					$('#payment-address input[name=\'tax_id\']').after('<span class="error">' + json['error']['tax_id'] + '</span>');
				}	
														
				if (json['error']['address_1']) {
					$('#payment-address input[name=\'address_1\']').after('<span class="error">' + json['error']['address_1'] + '</span>');
				}	
			} else {
				$.ajax({
					url: 'index.php?route=checkout/payment_method',
					dataType: 'html',
					success: function(html) {
						$('#payment-method .checkout-content').html(html);
					
						$('#payment-address .checkout-content').slideUp('slow');
						
						$('#payment-method .checkout-content').slideDown('slow');
						
						$('#payment-address .checkout-heading a').remove();
						$('#payment-method .checkout-heading a').remove();
						$('#shipping-address .checkout-heading a').remove();
						$('#shipping-method .checkout-heading a').remove();
						$('#payment-address .checkout-heading').append('<a><?php echo $text_modify; ?></a>');	
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});
				
				$.ajax({
					url: 'index.php?route=checkout/payment_address',
					dataType: 'html',
					success: function(html) {
						$('#payment-address .checkout-content').html(html);
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});				
			}	  
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});	
});

$('#button-payment-method').live('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/payment_method/validate', 
		type: 'post',
		data: $('#payment-method input[type=\'text\'], #payment-method textarea'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-payment-method').attr('disabled', true);
			$('#button-payment-method').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},	
		complete: function() {
			$('#button-payment-method').attr('disabled', false);
			$('.wait').remove();
		},			
		success: function(json) {
			$('.warning, .error').remove();
			
			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				if (json['error']['warning']) {
					$('#payment-method .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
					
					$('.warning').fadeIn('slow');
				}			
			} else {
			  <?php if ($shipping_required) { ?>
			    $.ajax({
			      url: 'index.php?route=checkout/shipping_method',
				  dataType: 'html',
				  success: function(html) {
				  $('#shipping-method .checkout-content').html(html);
				  
				  $('#payment-method .checkout-content').slideUp('slow');
				  
				  $('#shipping-method .checkout-content').slideDown('slow');
				  
				  $('#shipping-address .checkout-heading a').remove();
				  $('#shipping-method .checkout-heading a').remove();
				  $('#payment-method .checkout-heading a').remove();
				  
				  $('#payment-method .checkout-heading').append('<a><?php echo $text_modify; ?></a>');	
				},
				  error: function(xhr, ajaxOptions, thrownError) {
				  alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			      });
			    <?php } else { ?>
			    $.ajax({
			      url: 'index.php?route=checkout/confirm',
				  dataType: 'html',
				  success: function(html) {
				  $('#confirm .checkout-content').html(html);
				  
				  $('#payment-method .checkout-content').slideUp('slow');
				  
				  $('#confirm .checkout-content').slideDown('slow');
				  
				  $('#payment-method .checkout-heading a').remove();
				  
				  $('#payment-method .checkout-heading').append('<a><?php echo $text_modify; ?></a>');	
				},
				  error: function(xhr, ajaxOptions, thrownError) {
				  alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			      });
			    <?php } ?>
							
			    $.ajax({
				url: 'index.php?route=checkout/payment_method',
				dataType: 'html',
				success: function(html) {
					$('#payment-method .checkout-content').html(html);
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
            	            });
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});	
});

// Shipping Method
var code = '';
$('#button-shipping-method').live('click', function() {
	$.ajax({
		url: 'index.php?route=checkout/shipping_method/validate',
		type: 'post',
		data: $('#shipping-method input[type=\'radio\']:checked, #shipping-method textarea'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-shipping-method').attr('disabled', true);
			$('#button-shipping-method').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
			code = $('#shipping-method input[type=\'radio\']:checked').val();
			code = code.split('.')[0];
		},	
		complete: function() {
			$('#button-shipping-method').attr('disabled', false);
			$('.wait').remove();
		},			
		success: function(json) {
			$('.warning, .error').remove();
			
			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
				if (json['error']['warning']) {
					$('#shipping-method .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
					
					$('.warning').fadeIn('slow');
				}
			} else if (code != '') {
				$.ajax({
				        url: 'index.php?route=shipping/'+code,
					dataType: 'html',
					success: function(html) {
						$('#shipping-address .checkout-content').html(html);
						
						$('#shipping-method .checkout-content').slideUp('slow');
						
						$('#shipping-address .checkout-content').slideDown('slow');

						$('#shipping-method .checkout-heading a').remove();
						$('#shipping-address .checkout-heading a').remove();
						
						$('#shipping-method .checkout-heading').append('<a><?php echo $text_modify; ?></a>'); 
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				    }
				});

			    $.ajax({
				url: 'index.php?route=checkout/shipping_method',
				dataType: 'html',
				success: function(html) {
					$('#shipping-method .checkout-content').html(html);
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
            	            });
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});	
});

// Shipping Address			
$('#button-shipping-address').live('click', function() {
	$.ajax({
		url: 'index.php?route=shipping/'+code+'/validate',
		type: 'post',
		data: $('#shipping-address input[type=\'text\'], #shipping-address input[type=\'password\'], #shipping-address input[type=\'checkbox\']:checked, #shipping-address input[type=\'radio\']:checked, #shipping-address select, #shipping-address textarea'),
		dataType: 'json',
		beforeSend: function() {
			$('#button-shipping-address').attr('disabled', true);
			$('#button-shipping-address').after('<span class="wait">&nbsp;<img src="catalog/view/theme/default/image/loading.gif" alt="" /></span>');
		},	
		complete: function() {
			$('#button-shipping-address').attr('disabled', false);
		},
		success: function(json) {
			$('.warning, .error').remove();
			
			if (json['redirect']) {
				location = json['redirect'];
			} else if (json['error']) {
			        $('.wait').remove();
				if (json['error']['warning']) {
					$('#shipping-address .checkout-content').prepend('<div class="warning" style="display: none;">' + json['error']['warning'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
					
					$('.warning').fadeIn('slow');
				}
								
				if (json['error']['firstname']) {
					$('#shipping-address input[name=\'firstname\']').after('<span class="error">' + json['error']['firstname'] + '</span>');
				}
				
				if (json['error']['lastname']) {
					$('#shipping-address input[name=\'lastname\']').after('<span class="error">' + json['error']['lastname'] + '</span>');
				}	
				
				if (json['error']['email']) {
					$('#shipping-address input[name=\'email\']').after('<span class="error">' + json['error']['email'] + '</span>');
				}
				
				if (json['error']['telephone']) {
					$('#shipping-address input[name=\'telephone\']').after('<span class="error">' + json['error']['telephone'] + '</span>');
				}		
										
				if (json['error']['address_1']) {
					$('#shipping-address input[name=\'address_1\']').after('<span class="error">' + json['error']['address_1'] + '</span>');
				}	
				
				if (json['error']['city']) {
					$('#shipping-address input[name=\'city\']').after('<span class="error">' + json['error']['city'] + '</span>');
				}	
				
				if (json['error']['postcode']) {
					$('#shipping-address input[name=\'postcode\']').after('<span class="error">' + json['error']['postcode'] + '</span>');
				}	
				
				if (json['error']['country']) {
					$('#shipping-address select[name=\'country_id\']').after('<span class="error">' + json['error']['country'] + '</span>');
				}	
				
				if (json['error']['zone']) {
					$('#shipping-address select[name=\'zone_id\']').after('<span class="error">' + json['error']['zone'] + '</span>');
				}
			} else {
				$.ajax({
					url: 'index.php?route=checkout/confirm',
					dataType: 'html',
					success: function(html) {
						$('.wait').remove();
						$('#confirm .checkout-content').html(html);
						
						$('#shipping-address .checkout-content').slideUp('slow');
						
						$('#confirm .checkout-content').slideDown('slow');
						
						$('#shipping-address .checkout-heading a').remove();
						$('#shipping-method .checkout-heading a').remove();
						$('#payment-address .checkout-heading a').remove();
						$('#payment-method .checkout-heading a').remove();
						
						$.ajax({
							url: 'index.php?route=shipping/'+code,
							dataType: 'html',
							success: function(html) {
								$('#shipping-address .checkout-content').html(html);
							},
							error: function(xhr, ajaxOptions, thrownError) {
								alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
							}
						});
					},
					error: function(xhr, ajaxOptions, thrownError) {
						alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
					}
				});					
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});	
});

function quickConfirm(module){
	$.ajax({
		url: 'index.php?route=checkout/confirm',
		dataType: 'html',
		success: function(html) {
			$('#confirm .checkout-content').html(html);
			$('#confirm .checkout-content').slideDown('slow');
			
				
			$('.checkout-heading a').remove();
				
			$('#checkout .checkout-heading a').remove();
			$('#checkout .checkout-heading').append('<a><?php echo $text_modify; ?></a>');
			
			$('#shipping-address .checkout-heading a').remove();
			$('#shipping-address .checkout-heading').append('<a><?php echo $text_modify; ?></a>');	
			
			$('#shipping-method .checkout-heading a').remove();
			$('#shipping-method .checkout-heading').append('<a><?php echo $text_modify; ?></a>');	
			
			$('#payment-address .checkout-heading a').remove();			
			$('#payment-address .checkout-heading').append('<a><?php echo $text_modify; ?></a>');	
							
			$('#payment-method .checkout-heading a').remove();
			$('#payment-method .checkout-heading').append('<a><?php echo $text_modify; ?></a>');	

		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});	
}
//--></script> 
<?php echo $footer; ?>