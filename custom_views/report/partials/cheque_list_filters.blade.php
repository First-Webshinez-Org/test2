@if(empty($only) || in_array('cheque_list_filter_location_id', $only))
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('cheque_list_filter_location_id',  __('purchase.business_location') . ':') !!}

        {!! Form::select('cheque_list_filter_location_id', $business_locations, null, ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('lang_v1.all') ]); !!}
    </div>
</div>
@endif
@if(empty($only) || in_array('cheque_list_filter_cheque_status', $only))
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('cheque_list_filter_cheque_status',  __('lang_v1.cheque_status') . ':') !!}
        {!! Form::select('cheque_list_filter_cheque_status', ['cleared' => __('lang_v1.cleared'), 'pending' => __('lang_v1.pending')], null, ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('lang_v1.all')]); !!}
    </div>
</div>
@endif
@if(empty($only) || in_array('cheque_list_filter_contact_type', $only))
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('cheque_list_filter_contact_type',  __('sale.contact_type') . ':') !!}
        {!! Form::select('cheque_list_filter_contact_type', ['customer' => __('sale.customer'), 'supplier' => __('sale.supplier')], null, ['class' => 'form-control select2', 'style' => 'width:100%', 'placeholder' => __('lang_v1.all')]); !!}
    </div>
</div>
@endif
@if(empty($only) || in_array('cheque_list_filter_date_range', $only))
<div class="col-md-3">
    <div class="form-group">
        {!! Form::label('cheque_list_filter_date_range', __('report.date_range') . ':') !!}
        {!! Form::text('cheque_list_filter_date_range', null, ['placeholder' => __('lang_v1.select_a_date_range'), 'class' => 'form-control', 'readonly']); !!}
    </div>
</div>
@endif