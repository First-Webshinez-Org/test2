<div class="row">
	<div class="col-sm-12">
		@forelse($locations as $key => $value)
		<div class="box box-solid">
			<div class="box-header">
	            <h3 class="box-title">@lang('sale.location'): {{$value}}</h3>
	        </div>
			<div class="box-body">
				<div class="row">
					<div class="col-sm-12">
						<table class="table table-condensed table-bordered text-center table-striped add_opening_stock_table">
							<thead>
								<tr class="bg-green">
									<th>@lang( 'product.product_name' )</th>
									<th>@lang( 'lang_v1.quantity_left' )</th>
									<th>@lang( 'purchase.unit_cost_before_tax' )</th>
									@if($enable_expiry == 1 && $product->enable_stock == 1)
										<th>Exp. Date</th>
									@endif
									@if($enable_lot == 1)
										<th>@lang( 'lang_v1.lot_number' )</th>
									@endif
									@if($product->enable_sr_no == 1)
										<th>@lang( 'lang_v1.imei_number' )</th>
									@endif
									<th>@lang( 'purchase.subtotal_before_tax' )</th>
									<th>@lang( 'lang_v1.date' )</th>
									@if($is_warranty_enabled == 1)
										<th>@lang( 'lang_v1.warranty' )</th>
									@endif
									<th>@lang( 'brand.note' )</th>
									<th>&nbsp;</th>
								</tr>
							</thead>
							<tbody>
@php
	$subtotal = 0;
@endphp
@foreach($product->variations as $variation)
	@if(empty($purchases[$key][$variation->id]))
		@php
			$purchases[$key][$variation->id][] = ['quantity' => 0, 
			'purchase_price' => $variation->default_purchase_price,
			'purchase_line_id' => null,
			'lot_number' => null,
			'imei_number' => null,
			'warranty_date' => null,
			'transaction_date' => null,
			'purchase_line_note' => null,
			'secondary_unit_quantity' => 0
			]
		@endphp
	@endif

@foreach($purchases[$key][$variation->id] as $sub_key => $var)
	@php

	$purchase_line_id = $var['purchase_line_id'];

	$qty = $var['quantity'];

	$purcahse_price = $var['purchase_price'];

	$row_total = $qty * $purcahse_price;

	$subtotal += $row_total;
	$lot_number = $var['lot_number'];
	$imei_number = $var['imei_number'];
	$warranty_date = $var['warranty_date'];
	$transaction_date = $var['transaction_date'];
	$purchase_line_note = $var['purchase_line_note'];
	$dataWarranties = json_encode($warranties);
	@endphp

<tr data-key="{{ $key }}" data-sub-key="{{ $sub_key }}" data-variation-id="{{ $variation->id }}" data-warranties="{{ $dataWarranties }}" data-enable-sr-no="{{$product->enable_sr_no}}">
	<td>
		{{ $product->name }} @if( $product->type == 'variable' ) (<b>{{ $variation->product_variation->name }}</b> : {{ $variation->name }}) @endif

		@if(!empty($purchase_line_id))
			{!! Form::hidden('stocks[' . $key . '][' . $variation->id . '][' . $sub_key . '][purchase_line_id]', $purchase_line_id); !!}
		@endif
	</td>
	<td>
		<div class="input-group">
		  {!! Form::text('stocks[' . $key . '][' . $variation->id . '][' . $sub_key . '][quantity]', @format_quantity($qty) , ['class' => 'form-control input-sm input_number purchase_quantity input_quantity', 'required']); !!}
		  <span class="input-group-addon">
		    {{ $product->unit->short_name }}
		  </span>
		</div>
		@if(!empty($product->second_unit))
			<br>
            <span>
            @lang('lang_v1.quantity_in_second_unit', ['unit' => $product->second_unit->short_name])*:</span><br>
            {!! Form::text('stocks[' . $key . '][' . $variation->id . '][' . $sub_key . '][secondary_unit_quantity]', @format_quantity($var['secondary_unit_quantity']) , ['class' => 'form-control input-sm input_number input_quantity', 'required']); !!}
		@endif
	</td>
	<td>
		{!! Form::text('stocks[' . $key . '][' . $variation->id . '][' . $sub_key . '][purchase_price]', @num_format($purcahse_price) , ['class' => 'form-control input-sm input_number unit_price', 'required']); !!}
	</td>

@if($enable_expiry == 1 && $product->enable_stock == 1)
	<td>
		{!! Form::text('stocks[' . $key . '][' . $variation->id . '][' . $sub_key . '][exp_date]', !empty($var['exp_date']) ? @format_date($var['exp_date']) : null , ['class' => 'form-control input-sm os_exp_date', 'readonly']); !!}
	</td>
@endif

@if($enable_lot == 1)
	<td>
		{!! Form::text('stocks[' . $key . '][' . $variation->id . '][' . $sub_key . '][lot_number]', $lot_number , ['class' => 'form-control input-sm']); !!}
	</td>
@endif
@if($product->enable_sr_no == 1)
	<td class="imei-inputs-container">

		{!! Form::text('stocks[' . $key . '][' . $variation->id . '][' . $sub_key . '][imei_number][0]', isset($imei_number[0]) ? $imei_number[0] : '', ['class' => 'form-control input-sm', 'style' => 'margin-bottom: 0.5rem;']); !!}

		@for ($i = 1; $i < $qty; $i++)
			{!! Form::text('stocks[' . $key . '][' . $variation->id . '][' . $sub_key . '][imei_number]'.'['. $i . ']', isset($imei_number[$i]) ? $imei_number[$i] : '' , ['class' => 'form-control input-sm', 'style' => 'margin-bottom: 0.5rem;']); !!}
		@endfor

	</td>
@endif
	<td>
		<span class="row_subtotal_before_tax">{{@num_format($row_total)}}</span>
	</td>
	<td>
		<div class="input-group date">
		{!! Form::text('stocks[' . $key . '][' . $variation->id . '][' . $sub_key . '][transaction_date]', $transaction_date , ['class' => 'form-control input-sm os_date', 'readonly']); !!}
		</div>
	</td>

	@if($is_warranty_enabled == 1)
		<td class="warranty_container">
			<div class="form-group date">
				{!! Form::select('stocks[' . $key . '][' . $variation->id . '][' . $sub_key . '][warranty_date][0]', $warranties, isset($warranty_date[0]) ? intval($warranty_date[0]) : null , ['class' => 'form-control select2', 'style' => 'margin-bottom: 0.5rem;', 'placeholder' => __('messages.please_select')]); !!}
			</div>
			@if($product->enable_sr_no == 1)
				@for ($i = 1; $i < $qty; $i++)
					<div class="form-group date">
						{!! Form::select('stocks[' . $key . '][' . $variation->id . '][' . $sub_key . '][warranty_date]['. $i .']', $warranties, isset($warranty_date[$i]) ? intval($warranty_date[$i]) : null , ['class' => 'form-control select2', 'style' => 'margin-bottom: 0.5rem;', 'placeholder' => __('messages.please_select')]); !!}
					</div>
				@endfor
			@endif
		</td>
	@endif
	<td>
		{!! Form::textarea('stocks[' . $key . '][' . $variation->id . '][' . $sub_key . '][purchase_line_note]', $purchase_line_note , ['class' => 'form-control input-sm', 'rows' => 3 ]); !!}
	</td>
	<td>
		@if($loop->index == 0)
			<button type="button" class="btn btn-primary btn-xs add_stock_row" data-sub-key="{{ count($purchases[$key][$variation->id])}}" 
				data-row-html='<tr  data-key="{{ $key }}" data-sub-key="__subkey__" data-variation-id="{{ $variation->id }}" data-warranties="{{$dataWarranties}}">
					<td>
						{{ $product->name }} @if( $product->type == "variable" ) (<b>{{ $variation->product_variation->name }}</b> : {{ $variation->name }}) @endif
					</td>
					<td>
					<div class="input-group">
	              		<input class="form-control input-sm input_number purchase_quantity input_quantity" required="" name="stocks[{{$key}}][{{$variation->id}}][__subkey__][quantity]" type="text" value="0">
			              <span class="input-group-addon">
			                {{ $product->unit->short_name }}
			              </span>
	        			</div>
					</td>
	<td>
		<input class="form-control input-sm input_number unit_price" required="" name="stocks[{{$key}}][{{$variation->id}}][__subkey__][purchase_price]" type="text" value="{{@num_format($purcahse_price)}}">
	</td>

	@if($enable_expiry == 1 && $product->enable_stock == 1)
	<td>
		<input class="form-control input-sm os_exp_date" required="" name="stocks[{{$key}}][{{$variation->id}}][__subkey__][exp_date]" type="text" readonly>
	</td>
	@endif

	@if($enable_lot == 1)
	<td>
		<input class="form-control input-sm" name="stocks[{{$key}}][{{$variation->id}}][__subkey__][lot_number]" type="text">
	</td>
	@endif

	@if($product->enable_sr_no == 1)
	<td class="imei-inputs-container-add">
		<input class="form-control input-sm" name="stocks[{{$key}}][{{$variation->id}}][__subkey__][imei_number][0]" type="text">
	</td>
	@endif
	
	<td>
		<span class="row_subtotal_before_tax">
			0.00
		</span>
	</td>
	<td>
		<div class="input-group date">
			<input class="form-control input-sm os_date" name="stocks[{{$key}}][{{$variation->id}}][__subkey__][transaction_date]" type="text" readonly>
		</div>
	</td>
	@if($is_warranty_enabled == 1)
		<td class="warranty_container_add">
			<div class="input-group date">
				<select name="stocks[{{$key}}][{{$variation->id}}][__subkey__][warranty_date][0]" class="form-control select2">
					<option value="">{{ __("messages.please_select") }}</option>
					@foreach($warranties as $warranty_id => $warranty_name)
						<option value="{{ $warranty_id }}">{{ $warranty_name }}</option>
					@endforeach
				</select>
			</div>
		</td>
	@endif
	<td>
		<textarea rows="3" class="form-control input-sm" name="stocks[{{$key}}][{{$variation->id}}][__subkey__][purchase_line_note]"></textarea>
	</td>
	<td><i class="fa fa-times text-danger stock_remove_row cursor-pointer" aria-hidden="true"></i></td>
</tr>'
	><i class="fa fa-plus"></i></button>
	@else
		<!-- &nbsp; -->
		<i class="fa fa-times text-danger stock_remove_row cursor-pointer" aria-hidden="true"></i>
	@endif
			</td>
			</tr>
		@endforeach
	@endforeach
								</tbody>
								<tfoot>
								<tr>
									<td colspan="@if($enable_expiry == 1 && $product->enable_stock == 1 && $enable_lot == 1) 5 @elseif(($enable_expiry == 1 && $product->enable_stock == 1) || $enable_lot == 1) @else 3 @endif"></td>
									<td><strong>@lang( 'lang_v1.total_amount_exc_tax' ): </strong> <span id="total_subtotal">{{@num_format($subtotal)}}</span>
									<input type="hidden" id="total_subtotal_hidden" value=0>
									</td>
								</tr>
								</tfoot>
						</table>	
					</div>
				</div>
			</div>
		</div> <!--box end-->
		@empty
    		<h3>@lang( 'lang_v1.product_not_assigned_to_any_location' )</h3>
		@endforelse
	</div>
</div>

<script>
	$(document).ready(function() {

		//Remove row on click on remove row
		$('table.add_opening_stock_table tbody').on('click', 'i.stock_remove_row', function () {
			console.log('here1');
			$(this)
				.parents('tr')
				.remove();		
			// pos_total_row();
		});

    	$(document).on('change', '.input_quantity', function () {
			var qty = parseInt($(this).val());
			var tr = $(this).closest('tr');
	        var key = tr.data('key');
	        var subKey = tr.data('sub-key');
	        var variation_id = tr.data('variation-id');
			
			var imei_arr = @json($purchases)[key][variation_id][subKey].imei_number;
			var warranty_arr = @json($purchases)[key][variation_id][subKey].warranty_date;

			var container = tr.find('.imei-inputs-container');
			var container_add = tr.find('.imei-inputs-container-add');

			var warranty_container = tr.find('.warranty_container');
        	var warranty_container_add = tr.find('.warranty_container_add');

        	var warranties = @json($warranties);
			var enableSrNo = @json($product).enable_sr_no;

			if (container.length > 0 ) {
				container.empty(); 

				for (var i = 0; i < qty; i++) {
					// Assuming imei_arr is already defined and accessible here
					var imeiValue = (imei_arr && imei_arr[i] !== undefined) ? imei_arr[i] : '';

					var inputHtml = '<input type="text" name="stocks[' + key + '][' + variation_id + '][' + subKey + '][imei_number][' + i + ']" value="' + imeiValue + '" class="form-control input-sm" style="margin-bottom: 0.5rem;">';
					container.append(inputHtml);
				}
			}
			
			if (container_add.length > 0 ) {
				container_add.empty(); 
				
				for (var i = 0; i < qty; i++) {
					var inputHtmlAdd = '<input type="text" name="stocks[' + key + '][' + variation_id + '][' + subKey + '][imei_number][' + i + ']" value="' + imeiValue + '" class="form-control input-sm" style="margin-bottom: 0.5rem;">';
					container_add.append(inputHtmlAdd);
				}
			}


			if (enableSrNo == 1) {
				if (warranty_container.length > 0) {
					warranty_container.empty();
					
					for (var i = 0; i < qty; i++) {
						var options = '<option value="">Please Select</option>';
	
						$.each(warranties, function (key, value) {
							var selectedAttr = (warranty_arr && warranty_arr[i] && parseInt(warranty_arr[i]) == key) ? 'selected' : '';
	
							options += `<option value="${key}" ${selectedAttr}>${value}</option>`;
						});
	
						var inputDateHtml = '<div class="input-group date" style="margin-bottom: 0.5rem;"> <select name="stocks[' + key +']['+ variation_id +']['+ subKey +'][warranty_date][' + i + ']" class="form-control select2">' + options + '</select></div>';
	
						warranty_container.append(inputDateHtml);
					}
	
				}
			}
			
			if (enableSrNo == 1) {
				if (warranty_container_add.length > 0) {
					warranty_container_add.empty();
	
					for (var i = 0; i < qty; i++) {
						var options_add = '<option value="">Please Select</option>';
	
						$.each(warranties, function (key, value) {
							var selectedAttrAdd = (key == parseInt(warranty_arr[i])) ? 'selected' : '';
							options_add += `<option value="${key}" ${selectedAttrAdd}>${value}</option>`;
						});
	
						var inputDateHtmlAdd =  '<div class="input-group date" style="margin-bottom: 0.5rem;"> <select name="stocks['+ key +']['+ variation_id +'][' + subKey +'][warranty_date][' + i + ']" class="form-control select2">' + options_add + '</select></div>';
						warranty_container_add.append(inputDateHtmlAdd);
					}
				}
			}
		});
	});
</script>