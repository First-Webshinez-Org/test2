<table class="table table-bordered table-striped" id="imei_report_table" style="width: 100%">
    <thead>
        <tr>
            <th>SKU</th>
            <th>@lang('business.product')</th>
            <th>@lang('lang_v1.variation')</th>
            {{-- <th>@lang('product.category')</th> --}}
            <th>@lang('sale.location')</th>
            <th>@lang('report.current_stock')</th>
            <th>@lang('report.total_unit_sold')</th>
            <th>@lang('lang_v1.total_unit_transfered')</th>
            <th>@lang('lang_v1.total_unit_adjusted')</th>
        </tr>
    </thead>
    <tfoot>
        <tr class="bg-gray font-17 text-center footer-total">
            <td colspan="4"><strong>@lang('sale.total'):</strong></td>
            <td class="footer_total_stock"></td>
            <td class="footer_total_sold"></td>
            <td class="footer_total_transfered"></td>
            <td class="footer_total_adjusted"></td>
        </tr>
    </tfoot>
</table>