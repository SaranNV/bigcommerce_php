<h1><a href="<?php echo WOMBAT_BASE_URL.'/index' ?>">Wombat Webhooks</a></h1>

<?php

$webhooks = array(

	'get_products' => (object) array(
		'default_input' => array(
			'request_id' => '',
			'parameters' => array(
				'sku' => 'BIKE-TURQ'
			)
		),
		'doc_link' => 'https://developer.bigcommerce.com/api/stores/v2/products#list-products'
	),
	
	'get_orders' => (object) array(
		'default_input' => array(
			'request_id' => '',
			'parameters' => array(
				'customer_id' => '3'
			)
		),
		'doc_link' => 'https://developer.bigcommerce.com/api/stores/v2/orders#list-orders'
	),

	'get_customers' => (object) array(
		'default_input' => array(
			'request_id' => '',
			'parameters' => array(
				'customer_group_id' => '0',
			)
		),
		'doc_link' => 'https://developer.bigcommerce.com/api/stores/v2/customers#list-customers'
	),

	'get_shipments' => (object) array(
		'default_input' => array(
			'request_id' => '',
			'parameters' => array(
				'limit' => '100',
				//'order_id' => '104',
			)
		),
		'doc_link' => 'https://developer.bigcommerce.com/api/stores/v2/orders/shipments#list-shipments'
	),
	'get_shipment' => (object) array(
		'default_input' => array(
			'request_id' => '',
			'parameters' => array(
				'limit' => '100',
				//'order_id' => '104',
			),
			'order_id' => 101,
			'shipment_id' => 4,
		),
		'doc_link' => 'https://developer.bigcommerce.com/api/stores/v2/orders/shipments#get-shipment'
	),

	'add_product' => (object) array(
		'default_input' => array(
			'request_id' => '',
			'parameters' => array(
				'limit' => '100',
				),
			'product' => array(
				'name' => "Test product",
				'price' => "19.99",
				'description' => "Neato thing",
				'categories' => array(20),
				'type' => 'physical',
				'availability' => 'available',
				'weight' => "1.0",
			),
		),
		'doc_link' => 'https://developer.bigcommerce.com/api/stores/v2/products#create-product'
	),

	'add_order' => (object) array(
		'default_input' => array(
			'request_id' => '',
			'parameters' => array(
				'limit' => '100',
				),
			'order' => array(
		    'products' => array(
					(object) array(
						'product_id' => 107,
						'quantity' => rand(1,10),
						),
					(object) array(
						'product_id' => 84,
						'quantity' => rand(1,10),
						),

					),
				'billing_address' => (object) array(
			    'first_name' => 'Some',
			    'last_name' => 'Person',
			    'company' => '',
			    'street_1' => '123 Some St',
			    'street_2' => '',
			    'city' => 'Austin',
			    'state' => 'Texas',
			    'zip' => '78757',
			    'country' => 'United States',
			    'country_iso2' => 'US',
			    'phone' => '',
			    'email' => 'some.person@example.com',
	  		),

			),
		),
		'doc_link' => 'https://developer.bigcommerce.com/api/stores/v2/orders#create-order'
	),

	'add_customer' => (object) array(
		'default_input' => array(
			'request_id' => '',
			'parameters' => array(
				'limit' => '100',
				),
			'customer' => array(
		    'firstname' => 'Some',
				'lastname' => 'Person',
				'email' => 'some.person'.rand().'@example.com',
			),
		),
		'doc_link' => 'https://developer.bigcommerce.com/api/stores/v2/orders#create-order'
	),
	
);
		
?>

<div class="block api_connection_info" style="display:none;">
	<label><span>Username:</span><input type="text" name="api_username" value="saran-neem" placeholder="athleticapi"></label>
	<label><span>Path:</span><input type="text" name="api_path" value="https://store-da8jw5h.mybigcommerce.com/api/v2/" placeholder="https://store-pijlvyhy.mybigcommerce.com/api/v2/"></label>
	<label><span>Token:</span><input type="text" name="api_token" value="761392f0b806303ee51e7ad721b6cc95a6f79808" placeholder="d1ee45ad7d3a7c7c97b102eea8fd9763bb7cf9c9"></label>
</div>
<p><button type="button" class="small right showbutton" data-showelement=".api_connection_info">Edit api connection info</button></p>

<div class="block">
	<dl>
		<?php foreach($webhooks as $webhook => $webhook_info): ?>	
			<dt><?php echo $webhook; ?></dt>
			<dd>
				<p><a href="<?php echo $webhook_info->doc_link; ?>" target="_blank"><?php echo isset($webhook_info->doc_title) ? $webhook_info->doc_title : 'Supported parameters'; ?></a></p>
				<form class="one_column" action="<?php echo WOMBAT_BASE_URL.'/'.$webhook ?>">
					<fieldset>
						<legend>&rsaquo; <?php echo $webhook; ?> &rsaquo; <em>request</em></legend>
						<textarea><?php echo json_encode($webhook_info->default_input,JSON_PRETTY_PRINT); ?></textarea>
						<button type="submit">Send</button>
					</fieldset>
					<fieldset style="display:none;">
						<legend>&rsaquo; <?php echo $webhook; ?> &rsaquo; <em>response</em><span class="response_status"></span></legend>
						<div class="response"><textarea></textarea></div>
						<button type="button" class="response_clear">Clear</button>
					</fieldset>
				</form>
			</dd>
		<?php endforeach; ?>
	</dl>
</div>

<script>
	$('form').on('submit',function(event){
		event.preventDefault();
		
		var $form = $(this);
		var $button = $form.find('button[type=submit]');
		
		if(!$button.is(':disabled'))
			WombatTestFront.request($form);
	});
	
	$('.response_clear').on('click',function(){
		$(this).parent().fadeOut(function(){
			$(this).parents('.two_columns').attr('class','one_column');
		});
	});
	
	$('button[type=submit]').after('<div class="loading-spinner" style="display:none;"></div>');
	
	var WombatTestFront = {
		request: function($form){
			// useful elements
			var $response = $form.find('.response');
			var $response_status = $form.find('.response_status');
			
			// button reflects request status...
			var $button = $form.find('button[type=submit]');
			var button_text = $button.text();
			$button.text('Loading...');
			$button.next('.loading-spinner').fadeIn();
			$button.prop('disabled', true);
			
			// get data to send with request
			var request_data = $form.find('fieldset:first textarea').val();
			var request_object;
			
			// parse input JSON
			try { request_object = $.parseJSON(request_data); }
			catch (e) { }
			
			if(!request_object) {
			
				$response_status.addClass('response_error');
				$response.find('textarea').val('Input JSON is invalid.');
				
				// update form to reflect complete request
				$button.text(button_text);
				$button.prop('disabled', false);
				$button.next('.loading-spinner').fadeOut();
				
				$response_status.html('error');
				$response_status.attr('title','Request URL:\n'+$form.attr('action'));
				
				$response.parents('.one_column').attr('class','two_columns');
				$response.parent().fadeIn();
			
			} else { // request object parsed
				// make request
				request_object.parameters.api_username = encodeURIComponent($('.api_connection_info input[name=api_username]').val());
				request_object.parameters.api_path = encodeURIComponent($('.api_connection_info input[name=api_path]').val());
				request_object.parameters.api_token = encodeURIComponent($('.api_connection_info input[name=api_token]').val());
				
				$response.find('textarea').val('Loading...');
				$response_status.attr('class','response_status').html('loading');
				
				$.ajax({
					type: "POST",
					url: $form.attr('action'),
					data: request_object,
					error: function(jqXHR,textStatus,errorThrown){
						$response_status.addClass('response_error');
						$response.find('textarea').val(errorThrown);
					},
					success: function(data,textStatus,jqXHR) {
						if(typeof data === 'object')
							data = JSON.stringify(data,null,4);
					
						$response_status.addClass('response_success');
						$response.find('textarea').val(data);
					},
					complete: function(jqXHR, textStatus) {
						// update form to reflect complete request
						$button.text(button_text);
						$button.prop('disabled', false);
						$button.next('.loading-spinner').fadeOut();
						
						$response_status.html(textStatus);
						$response_status.attr('title','Request URL:\n'+$form.attr('action'));
						
						$response.parents('.one_column').attr('class','two_columns');
						$response.parent().fadeIn();
					}
				});
			}
		}
	};
	
	$('.showbutton').on('click',function(){
		var data_show_ele = $(this).attr('data-showelement');
		$(data_show_ele).slideDown();
		$(this).parent().fadeOut();
	});
</script>