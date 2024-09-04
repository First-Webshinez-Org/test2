$(document).ready(function () {
    $(document).on('change', '.purchase_quantity', function () {
        update_table_total($(this).closest('table'));
    });

    $(document).on('change', '.unit_price', function () {
        update_table_total($(this).closest('table'));
    });

    $('.os_exp_date').datepicker({
        autoclose: true,
        format: datepicker_date_format,
    });

    $(document).on('click', '.add_stock_row', function () {
        var tr = $(this).data('row-html');
        var key = parseInt($(this).data('sub-key'));

        tr = tr.replace(/\__subkey__/g, key);
        $(this).data('sub-key', key + 1);

        $(tr)
            .insertAfter($(this).closest('tr'))
            .find('.os_exp_date')
            .datepicker({
                autoclose: true,
                format: datepicker_date_format,
            });

        $(this).closest('tr').next('tr').find('.os_date').datetimepicker({
            format: moment_date_format + ' ' + moment_time_format,
            ignoreReadonly: true,
        });
    });

    $(document).on('change', '.input_quantity', function () {
        var qty = parseInt($(this).val());
        var tr = $(this).closest('tr');

        var key = tr.data('key');
        var subKey = tr.data('sub-key');
        var variationId = tr.data('variation-id');
        var enableSrNo = tr.data('enable-sr-no');
        
        var warranties = $(this).closest('table').find('tr').eq(1).data('warranties');

        var container = tr.find('.imei-inputs-container');
        var container_add = tr.find('.imei-inputs-container-add');

        var warranty_container = tr.find('.warranty_container');
        var warranty_container_add = tr.find('.warranty_container_add');

        if (enableSrNo === 1) {
            if (container.length > 0) {
                container.empty();

                for (var i = 0; i < qty; i++) {
                    var inputHtml = '<input type="text" name="stocks[' + key + '][' + variationId + '][' + subKey + '][imei_number][' + i + ']" class="form-control input-sm" style="margin-bottom: 0.5rem;">';
                    container.append(inputHtml);
                }
            }
        }

        if (enableSrNo === 1) {
            if (container_add.length > 0) {
                container_add.empty();

                for (var i = 0; i < qty; i++) {
                    var inputHtmlAdd = '<input type="text" name="stocks[' + key + '][' + variationId + '][' + subKey + '][imei_number][' + i + ']" class="form-control input-sm" style="margin-bottom: 0.5rem;">';
                    container_add.append(inputHtmlAdd);
                }
            }
        }

        if (enableSrNo === 1) {
            if (warranty_container.length > 0) {
                warranty_container.empty();

                var options = '<option value="">Please Select</option>';

                $.each(warranties, function (key, value) {
                    options += `<option value="${key}">${value}</option>`;
                });

                for (var i = 0; i < qty; i++) {
                    var inputDateHtml = `<div class="input-group date" style="margin-bottom: 0.5rem;">
                                    <select name="stocks[${key}][${variationId}][${subKey}][warranty_date][${i}]" class="form-control select2">
                                        ${options}
                                    </select>
                                </div>`;
                    warranty_container.append(inputDateHtml);
                }

            }
        }

        if (enableSrNo === 1) {
            if (warranty_container_add.length > 0) {
                warranty_container_add.empty();

                var options_add = '<option value="">Please Select</option>';

                $.each(warranties, function (key, value) {
                    options_add += `<option value="${key}">${value}</option>`;
                });

                for (var i = 0; i < qty; i++) {
                    var inputDateHtmlAdd = `<div class="input-group date" style="margin-bottom: 0.5rem;">
                                    <select name="stocks[${key}][${variationId}][${subKey}][warranty_date][${i}]" class="form-control select2">
                                        ${options_add}
                                    </select>
                                </div>`;
                    warranty_container_add.append(inputDateHtmlAdd);
                }
            }
        }
    });

    //Remove row on click on remove row
    $('table.add_opening_stock_table tbody').on('click', 'i.stock_remove_row', function () {
        $(this)
            .parents('tr')
            .remove();
        // pos_total_row();
    });

    $(document).on('click', '.add-opening-stock', function (e) {
        e.preventDefault();
        $.ajax({
            url: $(this).data('href'),
            dataType: 'html',
            success: function (result) {
                $('#opening_stock_modal')
                    .html(result)
                    .modal('show');
            },
        });
    });
});

//Re-initialize data picker on modal opening
$('#opening_stock_modal').on('shown.bs.modal', function (e) {
    $('#opening_stock_modal .os_exp_date').datepicker({
        autoclose: true,
        format: datepicker_date_format,
    });
    $('#opening_stock_modal .os_date').datetimepicker({
        format: moment_date_format + ' ' + moment_time_format,
        ignoreReadonly: true,
        widgetPositioning: {
            horizontal: 'right',
            vertical: 'bottom'
        }
    });
});

$(document).on('click', 'button#add_opening_stock_btn', function (e) {
    e.preventDefault();
    var btn = $(this);
    var data = $('form#add_opening_stock_form').serialize();

    $.ajax({
        method: 'POST',
        url: $('form#add_opening_stock_form').attr('action'),
        dataType: 'json',
        data: data,
        beforeSend: function (xhr) {
            __disable_submit_button(btn);
        },
        success: function (result) {
            if (result.success == true) {
                $('#opening_stock_modal').modal('hide');
                toastr.success(result.msg);
            } else {
                toastr.error(result.msg);
            }
        },
    });
    return false;
});

function update_table_total(table) {
    var total_subtotal = 0;
    table.find('tbody tr').each(function () {
        var qty = __read_number($(this).find('.purchase_quantity'));
        var unit_price = __read_number($(this).find('.unit_price'));
        var row_subtotal = qty * unit_price;
        $(this)
            .find('.row_subtotal_before_tax')
            .text(__number_f(row_subtotal));
        total_subtotal += row_subtotal;
    });
    table.find('tfoot tr #total_subtotal').text(__currency_trans_from_en(total_subtotal, true));
    table.find('tfoot tr #total_subtotal_hidden').val(total_subtotal);
}
