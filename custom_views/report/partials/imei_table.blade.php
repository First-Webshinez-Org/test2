<table class="table table-bordered table-striped ajax_view" id="imei_table" style="width: 100%;">
    <thead>
        <tr>
            {{-- <th>@lang('messages.action')</th> --}}
            {{-- <th>@lang('messages.date')</th>
            <th>@lang('purchase.ref_no')</th>
            <th>@lang('purchase.location')</th>
            <th>@lang('purchase.supplier')</th>
            <th>@lang('purchase.grand_total')</th> --}}
            <th>SKU</th>
            <th>@lang('lang_v1.purchase_id')</th>
            <th>@lang('business.product')</th>
            <th>@lang('business.location')</th>
            {{-- <th>@lang('lang_v1.lot_number')</th> --}}
            <th>@lang('lang_v1.imei_number')</th>
            <th>@lang('lang_v1.warranty')</th>
            {{-- <th>@lang('product.exp_date')</th> --}}
            <th>@lang('report.current_stock')</th>
            <th>@lang('report.total_unit_sold')</th>
            {{-- <th>@lang('lang_v1.total_unit_transfered')</th> --}}
            <th>@lang('lang_v1.total_unit_adjusted')</th>
        </tr>
    </thead>
    <tfoot>
        <tr class="bg-gray font-17 text-center footer-total">
            <td colspan="5"><strong>@lang('sale.total'):</strong></td>
            <td id="imei_footer_total_stock"></td>
            <td id="imei_footer_total_sold"></td>
            <td id="imei_footer_total_transfered"></td>
            <td id="imei_footer_total_adjusted"></td>
        </tr>
    </tfoot>
</table>