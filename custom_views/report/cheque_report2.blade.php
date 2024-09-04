@extends('layouts.app')
@section('title', __( 'lang_v1.cheque_report'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header no-print">
    <h1>
        {{ __('lang_v1.cheque_report')}}
    </h1>
</section>

<!-- Main content -->
<section class="content no-print">
    @component('components.filters', ['title' => __('report.filters')])
        @include('report.partials.cheque_list_filters')
    @endcomponent
    @component('components.widget', ['class' => 'box-primary', 'title' => __( 'lang_v1.cheque_report')])
        @if(auth()->user()->can('direct_sell.view') ||  auth()->user()->can('view_own_sell_only') ||  auth()->user()->can('view_commission_agent_sell'))
        @php
            $custom_labels = json_decode(session('business.custom_labels'), true);
         @endphp
            <table class="table table-bordered table-striped ajax_view" id="cheque_report_table" style="width: 100%;">
                <thead>
                    <tr>
                        <th>@lang('messages.action')</th>
                        <th>@lang('lang_v1.cheque_date')</th>
                        <th>@lang('sale.invoice_no')</th>
                        <th>@lang('sale.contact_name')</th>
                        <th>@lang('sale.contact_type')</th>
                        <th>@lang('sale.location')</th>
                        <th>@lang('lang_v1.cheque_status')</th>
                        <th>@lang('sale.cheque_amount')</th>
                        <th>@lang('lang_v1.added_by')</th>
                        <th>@lang('sale.payment_note')</th>
                    </tr>
                </thead>
                <tbody></tbody>
                <tfoot>
                    <tr class="bg-gray font-17 footer-total text-center">
                        <td colspan="7"><strong>@lang('sale.total'):</strong></td>
                        <td class="footer_cheque_total"></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        @endif
    @endcomponent
</section>
<!-- /.content -->
<div class="modal fade payment_modal" tabindex="-1" role="dialog" 
    aria-labelledby="gridSystemModalLabel">
</div>

<div class="modal fade edit_payment_modal" tabindex="-1" role="dialog" 
    aria-labelledby="gridSystemModalLabel">
</div>

<!-- This will be printed -->
<!-- <section class="invoice print_section" id="receipt_section">
</section> -->

@stop

@section('javascript')
<script type="text/javascript">
$(document).ready( function(){
    //Date range as a button
    $('#cheque_list_filter_date_range').daterangepicker(
        dateRangeSettings,
        function (start, end) {
            $('#cheque_list_filter_date_range').val(start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format));
            cheque_report_table.ajax.reload();
        }
    );
    $('#cheque_list_filter_date_range').on('cancel.daterangepicker', function(ev, picker) {
        $('#cheque_list_filter_date_range').val('');
        cheque_report_table.ajax.reload();
    });

    cheque_report_table = $('#cheque_report_table').DataTable({
        processing: true,
        serverSide: true,
        aaSorting: [[1, 'desc']],
        "ajax": {
            "url": "/reports/cheque-report",
            "data": function ( d ) {
                if($('#cheque_list_filter_date_range').val()) {
                    var start = $('#cheque_list_filter_date_range').data('daterangepicker').startDate.format('YYYY-MM-DD');
                    var end = $('#cheque_list_filter_date_range').data('daterangepicker').endDate.format('YYYY-MM-DD');
                    d.start_date = start;
                    d.end_date = end;
                }
                d.is_direct_sale = 1;

                d.location_id = $('#cheque_list_filter_location_id').val();
                
                d.cheque_status = $('#cheque_list_filter_cheque_status').val();
                
                d.contact_type = $('#cheque_list_filter_contact_type').val();

                d = __datatable_ajax_callback(d);
            }
        },
        scrollY:        "75vh",
        scrollX:        true,
        scrollCollapse: true,
        columns: [
            { data: 'action', name: 'action', orderable: false, "searchable": false},
            { data: 'cheque_date', name: 'cheque_date'  },
            { data: 'invoice_no', name: 'invoice_no'},
            { data: 'conatct_name', name: 'conatct_name'},
            { data: 'contact_type', name: 'conatct_type'},
            { data: 'business_location', name: 'bl.name'},
            { data: 'cheque_cleared', name: 'cheque_cleared'},
            { data: 'amount', name: 'amount'},
            { data: 'added_by', name: 'u.first_name'},
            { data: 'note', name: 'tp.note'},
        ],
        "fnDrawCallback": function (oSettings) {
            __currency_convert_recursively($('#cheque_report_table'));
        },
        "footerCallback": function ( row, data, start, end, display ) {
            var footer_cheque_total = 0;
            for (var r in data){
                footer_cheque_total += $(data[r].amount).data('orig-value') ? parseFloat($(data[r].amount).data('orig-value')) : 0;
            }

            $('.footer_cheque_total').html(__currency_trans_from_en(footer_cheque_total));
        },
        createdRow: function( row, data, dataIndex ) {
            $( row ).find('td:eq(4)').attr('class', 'clickable_td');
        }
    });

    $(document).on('change', '#cheque_list_filter_location_id, #cheque_list_filter_cheque_status, #cheque_list_filter_contact_type',  function() {
        cheque_report_table.ajax.reload();
    });

});
</script>
<script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script>
@endsection