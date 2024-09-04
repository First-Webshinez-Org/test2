@extends('layouts.app')
@section('title', __('lang_v1.imei_report'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header no-print">
    <h1>@lang('lang_v1.imei_report')
        <small></small>
    </h1>
    <!-- <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
    </ol> -->
</section>

<!-- Main content -->
<section class="content no-print">
    @component('components.filters', ['title' => __('report.filters')])
        {!! Form::open(['url' => action([\App\Http\Controllers\ReportController::class, 'getImeiReport2']), 'method' => 'get', 'id' => 'stock_report_filter_form' ]) !!}
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('location_id',  __('purchase.business_location') . ':') !!}
                        {!! Form::select('location_id', $business_locations, null, ['class' => 'form-control select2', 'style' => 'width:100%']); !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('category_id', __('category.category') . ':') !!}
                        {!! Form::select('category', $categories, null, ['placeholder' => __('messages.all'), 'class' => 'form-control select2', 'style' => 'width:100%', 'id' => 'category_id']); !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('sub_category_id', __('product.sub_category') . ':') !!}
                        {!! Form::select('sub_category', array(), null, ['placeholder' => __('messages.all'), 'class' => 'form-control select2', 'style' => 'width:100%', 'id' => 'sub_category_id']); !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('brand', __('product.brand') . ':') !!}
                        {!! Form::select('brand', $brands, null, ['placeholder' => __('messages.all'), 'class' => 'form-control select2', 'style' => 'width:100%']); !!}
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        {!! Form::label('unit',__('product.unit') . ':') !!}
                        {!! Form::select('unit', $units, null, ['placeholder' => __('messages.all'), 'class' => 'form-control select2', 'style' => 'width:100%']); !!}
                    </div>
                </div>
                @if(Module::has('Manufacturing'))
                    <div class="col-md-3">
                        <div class="form-group">
                            <br>
                            <div class="checkbox">
                                <label>
                                  {!! Form::checkbox('only_mfg', 1, false, 
                                  [ 'class' => 'input-icheck', 'id' => 'only_mfg_products']); !!} {{ __('manufacturing::lang.only_mfg_products') }}
                                </label>
                            </div>
                        </div>
                    </div>
                @endif
                {!! Form::close() !!}
    @endcomponent

    @component('components.widget', ['class' => 'box-primary', 'title' => __('purchase.all_purchases')])
         @slot('tool')
            @if(session('business.enable_imei_number'))
                <div class="col-sm-2 box-tools">
                    <div id="imei_table_imei_filter" class="dataTables_filter">
                        <label>
                            <input type="search" class="form-control input-sm" placeholder="Search IMEI...">
                        </label>
                    </div>
                </div>
            @endif
        @endslot
        @include('report.partials.imei_table')
    @endcomponent

    <div class="modal fade product_modal" tabindex="-1" role="dialog" 
    	aria-labelledby="gridSystemModalLabel">
    </div>

    <div class="modal fade payment_modal" tabindex="-1" role="dialog" 
        aria-labelledby="gridSystemModalLabel">
    </div>

    <div class="modal fade edit_payment_modal" tabindex="-1" role="dialog" 
        aria-labelledby="gridSystemModalLabel">
    </div>

    @include('purchase.partials.update_purchase_status_modal')

</section>

<section id="receipt_section" class="print_section"></section>

<!-- /.content -->
@stop
@section('javascript')
{{-- <script src="{{ asset('js/purchase.js?v=' . $asset_v) }}"></script> --}}
{{-- <script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script> --}}
<script>

     //Purchase table
    imei_report = $('#imei_table').DataTable({
        processing: true,
        serverSide: true,
        scrollY: '75vh',
        scrollX: true,
        scrollCollapse: true,
        ajax: {
            url: '/reports/imei-report',
            data: function (d) {
               d.location_id = $('#location_id').val();
                d.category_id = $('#category_id').val();
                d.sub_category_id = $('#sub_category_id').val();
                d.brand_id = $('#brand').val();
                d.unit_id = $('#unit').val();
                d.only_mfg_products = $('#only_mfg_products').length && $('#only_mfg_products').is(':checked') ? 1 : 0;

                d = __datatable_ajax_callback(d);
            },
        },
        aaSorting: [[1, 'desc']],
        columns: [
            // { data: 'action', name: 'action', orderable: false, searchable: false },
            // { data: 'transaction_date', name: 'transaction_date' },
            // { data: 'ref_no', name: 'ref_no' },
            // { data: 'location_name', name: 'BS.name' },
            // { data: 'name', name: 'contacts.name' },
            // { data: 'final_total', name: 'final_total' },
            { data: 'sub_sku', name: 'v.sub_sku' },
            { data: 'pl_id', name: 'pl.id' },
            { data: 'product', name: 'products.name' },
            { data: 'location_name', name: 'location_name', searchable: false },
            // { data: 'lot_number', name: 'pl.lot_number' },
            { data: 'imei_number', name: 'imei_number', searchable: false  },
            { data: 'warranty_date', name: 'warranty_date', searchable: false  },
            // { data: 'exp_date', name: 'pl.exp_date' },
            { data: 'stock', name: 'stock', searchable: false },
            { data: 'total_sold', name: 'total_sold', searchable: false },
            // { data: 'total_transfered', name: 'total_transfered', searchable: false },
            { data: 'total_adjusted', name: 'total_adjusted', searchable: false },
        ],
        fnDrawCallback: function (oSettings) {
            __currency_convert_recursively($('#imei_table'));
        },
        footerCallback: function (row, data, start, end, display) {
            $('#imei_footer_total_stock').html(__sum_stock($('#imei_table'), 'total_stock'));
            $('#imei_footer_total_sold').html(__sum_stock($('#imei_table'), 'total_sold'));
            $('#imei_footer_total_adjusted').html(__sum_stock($('#imei_table'), 'total_adjusted'));
            __show_date_diff_for_human($('#imei_table'));
        },
        createdRow: function (row, data, dataIndex) {
            $(row).find('td:eq(5)').attr('class', 'clickable_td');
            $(row).attr('data-imei', JSON.stringify(data.imei));
        },
    });

    if ($('table#imei_table').length == 1) {
        $('#location_id, #category_id, #sub_category_id, #unit, #brand').change(function () {
            imei_report.ajax.reload();
        });
    }

    // Custom search event based on IMEI
    $('#imei_table_imei_filter input').on('keyup', function () {
        var searchTerm = this.value.toLowerCase();

        imei_report.rows().every(function () {
            var row = $(this.node());
            var imeiData = row.data('imei');

            var match = imeiData.some(
                (imei) => imei && imei.toLowerCase().includes(searchTerm.toLowerCase())
            );

            if (match) {
                row.show();
            }

            if (!match) {
                row.hide();
            }
        });
    });
   
</script>
	
@endsection