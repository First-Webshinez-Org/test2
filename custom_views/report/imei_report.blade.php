@extends('layouts.app')
@section('title', __('lang_v1.imei_report'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>{{ __('lang_v1.imei_report')}}</h1>
</section>

<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-md-12">
            @component('components.filters', ['title' => __('report.filters')])
              {!! Form::open(['url' => action([\App\Http\Controllers\ReportController::class, 'getImeiReport']), 'method' => 'get', 'id' => 'imei_report_filter_form' ]) !!}
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
                @if($show_manufacturing_data)
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
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            @component('components.widget', ['class' => 'box-solid'])
                @include('report.partials.imei_report_table')
            @endcomponent
        </div>
    </div>
</section>
<!-- /.content -->

@endsection

@section('javascript')
    {{-- <script src="{{ asset('js/report.js?v=' . $asset_v) }}"></script> --}}
    <script>
            var imei_report_cols = [
            { data: "sku", name: "variations.sub_sku" },
            { data: "product", name: "p.name" },
            { data: "variation", name: "variation" },
            // { data: "category_name", name: "c.name" },
            { data: "location_name", name: "l.name" },
            { data: "stock", name: "stock", searchable: false },
        ];

        imei_report_cols.push({
            data: "total_sold",
            name: "total_sold",
            searchable: false,
        });
        imei_report_cols.push({
            data: "total_transfered",
            name: "total_transfered",
            searchable: false,
        });
        imei_report_cols.push({
            data: "total_adjusted",
            name: "total_adjusted",
            searchable: false,
        });

        //imei report table
        imei_report_table = $("#imei_report_table").DataTable({
            processing: true,
            order: [[1, "asc"]],
            serverSide: true,
            scrollY: "75vh",
            scrollX: true,
            scrollCollapse: true,
            ajax: {
            url: "/reports/imei-report",
            data: function (d) {
                d.location_id = $("#location_id").val();
                d.category_id = $("#category_id").val();
                d.sub_category_id = $("#sub_category_id").val();
                d.brand_id = $("#brand").val();
                d.unit_id = $("#unit").val();

                d.only_mfg_products =
                $("#only_mfg_products").length &&
                $("#only_mfg_products").is(":checked")
                    ? 1
                    : 0;
            },
            },
            columns: imei_report_cols,
            fnDrawCallback: function (oSettings) {
            __currency_convert_recursively($("#imei_report_table"));
            // $(".footer_total_stock").html(
            //     __sum_stock($("#imei_report_table"), "current_stock")
            // );
            },
            footerCallback: function (row, data, start, end, display) {
            var footer_total_sold = 0;
            var footer_total_transfered = 0;
            var total_adjusted = 0;

            for (var r in data) {
                footer_total_sold += $(data[r].total_sold).data("orig-value")
                ? parseFloat($(data[r].total_sold).data("orig-value"))
                : 0;

                footer_total_transfered += $(data[r].total_transfered).data(
                "orig-value"
                )
                ? parseFloat($(data[r].total_transfered).data("orig-value"))
                : 0;

                total_adjusted += $(data[r].total_adjusted).data("orig-value")
                ? parseFloat($(data[r].total_adjusted).data("orig-value"))
                : 0;
            }

            $(".footer_total_sold").html(
                __currency_trans_from_en(footer_total_sold, false)
            );
            $(".footer_total_transfered").html(
                __currency_trans_from_en(footer_total_transfered, false)
            );
            $(".footer_total_adjusted").html(
                __currency_trans_from_en(total_adjusted, false)
            );
            },
        });

        if ($("#trending_product_date_range").length == 1) {
            get_sub_categories();
            $("#trending_product_date_range").daterangepicker({
            ranges: ranges,
            autoUpdateInput: false,
            locale: {
                format: moment_date_format,
                cancelLabel: LANG.clear,
                applyLabel: LANG.apply,
                customRangeLabel: LANG.custom_range,
            },
            });
            $("#trending_product_date_range").on(
            "apply.daterangepicker",
            function (ev, picker) {
                $(this).val(
                picker.startDate.format(moment_date_format) +
                    " ~ " +
                    picker.endDate.format(moment_date_format)
                );
            }
            );

            $("#trending_product_date_range").on(
            "cancel.daterangepicker",
            function (ev, picker) {
                $(this).val("");
            }
            );
        }

        $(
            "#imei_report_filter_form #location_id, #imei_report_filter_form #category_id, #imei_report_filter_form #sub_category_id, #imei_report_filter_form #brand, #imei_report_filter_form #unit, #imei_report_filter_form #view_stock_filter"
        ).change(function () {
            imei_report_table.ajax.reload();
        });

        $("#only_mfg_products").on("ifChanged", function (event) {
            imei_report_table.ajax.reload();
        });
    </script>
@endsection