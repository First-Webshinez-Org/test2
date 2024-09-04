@if(!session('business.enable_price_tax')) 
    @php
        $default = 0;
        $class = 'hide';
    @endphp
@else
    @php
        $default = null;
        $class = '';
    @endphp
@endif

@php
 $array_name = 'product_variation_edit';
 $variation_array_name = 'variations_edit';
 if($action == 'duplicate'){
    $array_name = 'product_variation';
    $variation_array_name = 'variations';
 }

    $common_settings = session()->get('business.common_settings');
@endphp

<tr class="variation_row">
    <td>
        {!! Form::text($array_name . '[' . $row_index .'][name]', $product_variation->name, ['class' => 'form-control input-sm variation_name', 'required', 'readonly']); !!}

        {!! Form::hidden($array_name . '[' . $row_index .'][variation_template_id]', $product_variation->variation_template_id); !!}

        <input type="hidden" class="row_index" value="@if($action == 'edit'){{$row_index}}@else{{$loop->index}}@endif">
        <input type="hidden" class="row_edit" value="edit">
    </td>

    <td>
        <table class="table table-condensed table-bordered blue-header variation_value_table">
            <thead>
            <tr>
                <th class="col-md-1">@lang('product.sku') @show_tooltip(__('tooltip.sub_sku'))</th>
                <th class="col-md-1">@lang('product.value')</th>
                <th class="{{$class}} col-md-4">@lang('product.default_purchase_price') 
                    <br/>
                    <span class="pull-left"><small><i>@lang('product.exc_of_tax')</i></small></span>

                    <span class="pull-right"><small><i>@lang('product.inc_of_tax')</i></small></span>
                </th>
                @if(session()->get('business.enable_commission_percent') == 1 )
                    <th>@lang('product.commission_percent') @show_tooltip(__('tooltip.commission_percent'))</th>
                @endif
                <th class="{{$class}}">@lang('product.profit_percent')</th>
                <th class="{{$class}} col-md-2">@lang('product.default_selling_price') 
                <br/>
                <small><i><span class="dsp_label"></span></i></small>
                </th>
                <th class="col-md-3">@lang('lang_v1.variation_images')</th>
                <th><button type="button" class="btn btn-success btn-xs add_variation_value_row">+</button></th>
            </tr>
            </thead>

            <tbody>
            @forelse ($product_variation->variations as $variation)
                @php
                    $variation_row_index = $variation->id;
                    $sub_sku_required = 'required';
                    if($action == 'duplicate'){
                        $variation_row_index = $loop->index;
                        $sub_sku_required = '';
                    }
                @endphp
                <tr>
                    <td class="col-md-1">
                        @if($action != 'duplicate')
                            <input type="hidden" class="row_variation_id" value="{{$variation->id}}">
                        @endif
                        {!! Form::text($array_name . '[' . $row_index .'][' . $variation_array_name . '][' . $variation_row_index . '][sub_sku]', $action == 'edit' ? $variation->sub_sku : null, ['class' => 'form-control input-sm input_sub_sku', $sub_sku_required]); !!}
                    </td>
                    <td class="col-md-1">
                        {!! Form::text($array_name . '[' . $row_index .'][' . $variation_array_name . '][' . $variation_row_index . '][value]', $variation->name, ['class' => 'form-control input-sm variation_value_name', 'required', 'readonly']); !!}

                        {!! Form::hidden($array_name . '[' . $row_index .'][' . $variation_array_name . '][' . $variation_row_index . '][variation_value_id]', $variation->variation_value_id); !!}
                    </td>
                    <td class="{{$class}} col-md-4">
                        <div class="col-md-6 custom-padding">
                            {!! Form::text($array_name . '[' . $row_index .'][' . $variation_array_name . '][' . $variation_row_index . '][default_purchase_price]', @num_format($variation->default_purchase_price), ['class' => 'form-control input-sm variable_dpp input_number', 'placeholder' => __('product.exc_of_tax'), 'required']); !!}
                        </div>

                        <div class="col-md-6" style="padding: 0;">
                            {!! Form::text($array_name . '[' . $row_index .'][' . $variation_array_name . '][' . $variation_row_index . '][dpp_inc_tax]', @num_format($variation->dpp_inc_tax), ['class' => 'form-control input-sm variable_dpp_inc_tax input_number', 'placeholder' => __('product.inc_of_tax'), 'required']); !!}
                        </div>
                    </td>
                    @if(session()->get('business.enable_commission_percent') == 1 )
                    <td class="{{$class}}">
                        {!! Form::text($array_name . '[' . $row_index .'][' . $variation_array_name . '][' . $variation_row_index . '][commission_percent]', @num_format($variation->commission_percent), ['class' => 'form-control input-sm variable_commission_percent input_number', 'required']); !!}
                    </td>
                    @endif
                    <td class="{{$class}}">
                        {!! Form::text($array_name . '[' . $row_index .'][' . $variation_array_name . '][' . $variation_row_index . '][profit_percent]', @num_format($variation->profit_percent), ['class' => 'form-control input-sm variable_profit_percent input_number', 'required']); !!}
                    </td>
                    <td class="{{$class}} col-md-2">
                        {!! Form::text($array_name . '[' . $row_index .'][' . $variation_array_name . '][' . $variation_row_index . '][default_sell_price]', @num_format($variation->default_sell_price), ['class' => 'form-control input-sm variable_dsp input_number', 'placeholder' => __('product.exc_of_tax'), 'required']); !!}

                        {!! Form::text($array_name . '[' . $row_index .'][' . $variation_array_name . '][' . $variation_row_index . '][sell_price_inc_tax]', @num_format($variation->sell_price_inc_tax), ['class' => 'form-control input-sm variable_dsp_inc_tax input_number', 'placeholder' => __('product.inc_of_tax'), 'required']); !!}
                    </td>
                    <td class="col-md-3">
                        @php 
                            $action = !empty($action) ? $action : '';
                        @endphp
                        @if($action !== 'duplicate')
                            @foreach($variation->media as $media)
                                <div class="img-thumbnail">
                                    <span class="badge bg-red delete-media" data-href="{{ action([\App\Http\Controllers\ProductController::class, 'deleteMedia'], ['media_id' => $media->id])}}"><i class="fas fa-times"></i></span>
                                    {!! $media->thumbnail() !!}
                                </div>
                            @endforeach
                            {!! Form::file('edit_variation_images_' . $row_index . '_' . $variation_row_index . '[]',
                                 ['class' => 'variation_images', 'accept' => 'image/*', 'multiple', 'style' => 'width: 100%;']); !!}
                        @else
                            {!! Form::file('edit_variation_images_' . $row_index . '_' . $variation_row_index . '[]', 
                                ['class' => 'variation_images', 'accept' => 'image/*', 'multiple', 'style' => 'width: 100%;']); !!}
                        @endif
                    </td>
                    <td>
                        <button type="button" class="btn btn-danger btn-xs remove_variation_value_row">-</button>
                        <input type="hidden" class="variation_row_index" value="@if($action == 'duplicate'){{$loop->index}}@else{{0}}@endif">
                    </td>
                </tr>
            @empty
                &nbsp;
            @endforelse
            </tbody>
        </table>
    </td>
</tr>