<div class="modal-dialog modal-lg" role="document">
  <div class="modal-content">

    <div class="modal-header">
      <button type="button" class="close no-print" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h3 class="modal-title">
        @lang( 'cash_register.register_details' ) 
        <span class="modal-date">
            ( {{ \Carbon::createFromFormat('Y-m-d H:i:s', $open_time ?? $open_time)->format('jS M, Y h:i A') }} -  {{\Carbon::createFromFormat('Y-m-d H:i:s', $close_time)->format('jS M, Y h:i A')}} )
        </span>
      </h3>
    </div>

    <div class="register-checkbox-div">
        <div class="col-md-9">
            <div class="form-group">
                <label>
                    {!! Form::checkbox('product_details', 1, false, ['class' => 'input-icheck', 'id' => 'product_details']); !!} <strong>@lang('lang_v1.product_details')</strong>
                </label>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label>
                    {!! Form::checkbox('product_details_brand', 1, false, ['class' => 'input-icheck', 'id' => 'product_details_brand']); !!} <strong>@lang('lang_v1.product_details_brand')</strong>
                </label>
            </div>
        </div>
        <div class="col-md-12">
            <div class="form-group">
                <label>
                    {!! Form::checkbox('product_purchase_details', 1, false, ['class' => 'input-icheck', 'id' => 'product_purchase_details']); !!} <strong>@lang('lang_v1.product_purchase_details')</strong>
                </label>
            </div>
        </div>
        @if($details['types_of_service_details'])
        <div class="col-md-9">
            <div class="form-group">
                <label>
                    {!! Form::checkbox('service_details', 1, false, ['class' => 'input-icheck', 'id' => 'service_details']); !!} <strong>@lang('lang_v1.service_details')</strong>
                </label>
            </div>
        </div>
        @endif
        <div class="col-md-9">
            <div class="form-group">
                <label>
                    {!! Form::checkbox('payments_details', 1, true, ['class' => 'input-icheck', 'id' => 'payments_details']); !!} <strong>@lang('lang_v1.payments_details')</strong>
                </label>
            </div>
        </div>
    </div>

    <div class="date-location">
        <!-- <div class="col-md-4 col-xs-12"> -->
        <div>
            @if (count($business_locations) > 1)
                {!! Form::select('register_location', $business_locations, null, [
                'class' => 'form-control select2',
                'placeholder' => __('lang_v1.select_location'),
                'id' => 'register_location',
                ]) !!}
            @endif
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <div class="input-group">
                        <button type="button" class="btn btn-primary" id="register_date_filter">
                            <span>
                            <i class="fa fa-calendar"></i> {{ __('messages.filter_by_date') }}
                            </span>
                            <i class="fa fa-caret-down"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal-body">
        @if(!empty($payment_types))
          @include('cash_register.payment_details')
        @endif
        <hr>
        @if(!empty($register_details->denominations))
            @php
            $total = 0;
            @endphp
            <div class="row">
                <div class="col-md-8 col-sm-12">
                    <h3>@lang( 'lang_v1.cash_denominations' )</h3>
                    <table class="table table-slim">
                    <thead>
                        <tr>
                        <th width="20%" class="text-right">@lang('lang_v1.denomination')</th>
                        <th width="20%">&nbsp;</th>
                        <th width="20%" class="text-center">@lang('lang_v1.count')</th>
                        <th width="20%">&nbsp;</th>
                        <th width="20%" class="text-left">@lang('sale.subtotal')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($register_details->denominations as $key => $value)
                        <tr>
                        <td class="text-right">{{$key}}</td>
                        <td class="text-center">X</td>
                        <td class="text-center">{{$value ?? 0}}</td>
                        <td class="text-center">=</td>
                        <td class="text-left">
                            @format_currency($key * $value)
                        </td>
                        </tr>
                        @php
                        $total += ($key * $value);
                        @endphp
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                        <th colspan="4" class="text-center">@lang('sale.total')</th>
                        <td>@format_currency($total)</td>
                        </tr>
                    </tfoot>
                    </table>
                </div>
            </div>
        @endif

        <div class="row">
            <div class="col-xs-6">
                <b>@lang('report.user'):</b> {{ $user_details['user_name'] ?? ''}}<br>
                <b>@lang('business.email'):</b> {{ $user_details['email'] ?? ''}}<br>
                <b>@lang('business.business_location'):</b> {{ $user_details['location_name'] ?? ''}}<br>
            </div>
            @if(!empty($register_details->closing_note))
                <div class="col-xs-6">
                    <strong>@lang('cash_register.closing_note'):</strong><br>
                    {{$register_details->closing_note}}
                </div>
            @endif
        </div>

        <div class="modal-footer">

            <button type="button" class="btn btn-primary no-print" id="send_ledger"><i class="fas fa-envelope"></i> Send</button>

            <button type="button" class="btn btn-primary no-print" 
                aria-label="Print" 
                onclick="$(this).closest('div.modal').printThis();">
                <i class="fa fa-print"></i> @lang( 'messages.print' )
            </button>

            <button type="button" class="btn btn-default no-print" 
                data-dismiss="modal">@lang( 'messages.cancel' )
            </button>
        </div>
        
    </div>
    
  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
<style type="text/css">
  .date-location {
    margin-left : 2rem;
    display: flex;
    gap: 2rem;
  }

  .register-checkbox-div {
    margin-top: 2rem;
    display: grid;
    grid-template-columns: 2fr 2fr 2fr;
  }

  @media print {
    .modal {
        position: absolute;
        left: 0;
        top: 0;
        margin: 0;
        padding: 0;
        overflow: visible!important;
    }

    .register-checkbox-div, .date-location {
      display: none;
    }
}
</style>
<script>

  var register_data = {start_date : null, end_date : null, location : null};
  var loc = null;

    $('#register_location').on('change', function () {
        var selectedValue = $(this).val();

        if ($('#register_date_filter').length == 1) {
            dateRangeSettings.startDate = moment('{{$open_time}}', 'YYYY-MM-DD');
            dateRangeSettings.endDate = moment('{{$close_time}}', 'YYYY-MM-DD');
        }

        var current_start = new Date(dateRangeSettings.startDate._i);;
        var current_end = new Date(dateRangeSettings.endDate._i);

            // Function to format with ordinal numbers
        function getOrdinal(n) {
            var s = ["th", "st", "nd", "rd"],
                v = n % 100;
            return n + (s[(v - 20) % 10] || s[v] || s[0]);
        }
        // Months array to format month
        const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

        // Format date
        const formattedStartDate = `${getOrdinal(current_start.getDate())} ${months[current_start.getMonth()]}, ${current_start.getFullYear()} 00:00 AM`;
        const formattedEndDate = `${getOrdinal(current_end.getDate())} ${months[current_end.getMonth()]}, ${current_end.getFullYear()} 11:59 PM`;

        var container = '.register_details_modal';

        var isChecked = $('#product_details').is(':checked');
        var isCheckedBrand = $('#product_details_brand').is(':checked');
        var isCheckedService = $('#service_details').is(':checked');
        var isCheckedPurchase = $('#product_purchase_details').is(':checked');
        var isCheckedPayments = $('#payments_details').is(':checked');

        var data = { start_date: dateRangeSettings.startDate._i, end_date: dateRangeSettings.endDate._i, location: selectedValue};

        $.ajax({
                url: '/cash-register/register-details',
                dataType: 'html',
                data: data,
                success: function (result) {
                    $(container)
                        .html(result);
                    // .modal('show');

                    __currency_convert_recursively($(container));

                    $('.modal-date').html(`(${formattedStartDate} - ${formattedEndDate})`);

                    $('#register_date_filter span').html(
                        dateRangeSettings.startDate.format(moment_date_format) + ' ~ ' + dateRangeSettings.endDate.format(moment_date_format)
                    );

                    $('#register_location').data('placeholder', @json($business_locations)[selectedValue] );
                    
                    $('#product_details').prop('checked', isChecked);
                    $('#product_details_brand').prop('checked', isCheckedBrand);
                    $('#service_details').prop('checked', isCheckedService);
                    $('#product_purchase_details').prop('checked', isCheckedPurchase);
                    $('#payments_details').prop('checked', isCheckedPayments);

                    loc = selectedValue;
                },
            });
    });

    // register details filtered over date
    if ($('#register_date_filter').length == 1) {
        dateRangeSettings.startDate = moment('{{$open_time}}', 'YYYY-MM-DD');
        dateRangeSettings.endDate = moment('{{$close_time}}', 'YYYY-MM-DD');
        $('#register_date_filter').daterangepicker(dateRangeSettings, function (start, end) {
            var current_start_date = (start._d).toISOString().slice(0, 19).replace('T', ' ');
            var current_end_date = (end._d).toISOString().slice(0, 19).replace('T', ' ');

            var current_start = new Date(current_start_date);;
            var current_end = new Date(current_end_date);

            // Function to format with ordinal numbers
            function getOrdinal(n) {
                var s = ["th", "st", "nd", "rd"],
                    v = n % 100;
                return n + (s[(v - 20) % 10] || s[v] || s[0]);
            }
            // Months array to format month
            const months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

            // Format date
            const formattedStartDate = `${getOrdinal(current_start.getDate())} ${months[current_start.getMonth()]}, ${current_start.getFullYear()} 00:00 AM`;
            const formattedEndDate = `${getOrdinal(current_end.getDate())} ${months[current_end.getMonth()]}, ${current_end.getFullYear()} 11:59 PM`;

            var container = '.register_details_modal';
            var data = { start_date: current_start_date, end_date: current_end_date, location : loc };
            
            var isChecked = $('#product_details').is(':checked');
            var isCheckedBrand = $('#product_details_brand').is(':checked');
            var isCheckedService = $('#service_details').is(':checked');
            var isCheckedPurchase = $('#product_purchase_details').is(':checked');
            var isCheckedPayments = $('#payments_details').is(':checked');

            var selected_loc = loc;

            $.ajax({
                url: '/cash-register/register-details',
                dataType: 'html',
                data: data,
                success: function (result) {
                    $(container)
                        .html(result);
                    // .modal('show');

                    __currency_convert_recursively($(container));

                    $('.modal-date').html(`(${formattedStartDate} - ${formattedEndDate})`);

                    $('#register_date_filter span').html(
                        start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format)
                    );

                    $('#register_location').data('placeholder', @json($business_locations)[selected_loc]);

                    $('#product_details').prop('checked', isChecked);
                    $('#product_details_brand').prop('checked', isCheckedBrand);
                    $('#service_details').prop('checked', isCheckedService);
                    $('#product_purchase_details').prop('checked', isCheckedPurchase);
                    $('#payments_details').prop('checked', isCheckedPayments);

                    loc = selected_loc;

                },
            });
        });
    }

</script>
<script src="{{ asset('js/app.js?v=' . $asset_v) }}"></script>


