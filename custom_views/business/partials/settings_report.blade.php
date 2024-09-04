<!--Purchase related settings -->
<div class="pos-tab-content">
    <div class="row">

        <div class="col-sm-6">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                    {!! Form::checkbox('report_settings[enable_profit_loss_report]', 1, $report_settings['enable_profit_loss_report'] ?? 1 , [ 'class' => 'input-icheck', 'id' => 'enable_profit_loss_report']); !!} {{ __( 'lang_v1.enable_profit_loss_report' ) }}
                    </label>
                     @show_tooltip(__('lang_v1.profit_loss_report_help_text'))
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                    {!! Form::checkbox('report_settings[enable_purchase_n_sale]', 1, $report_settings['enable_purchase_n_sale'] ?? 1 , [ 'class' => 'input-icheck', 'id' => 'enable_purchase_n_sale']); !!} {{ __( 'lang_v1.enable_purchase_n_sale' ) }}
                    </label>
                     @show_tooltip(__('lang_v1.purchase_n_sale_help_text'))
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                    {!! Form::checkbox('report_settings[enable_tax_report]', 1, $report_settings['enable_tax_report'] ?? 1 , [ 'class' => 'input-icheck', 'id' => 'enable_tax_report']); !!} {{ __( 'lang_v1.enable_tax_report' ) }}
                    </label>
                     @show_tooltip(__('lang_v1.tax_report_help_text'))
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                    {!! Form::checkbox('report_settings[enable_supplier_n_customer_report]', 1, $report_settings['enable_supplier_n_customer_report'] ?? 1 , [ 'class' => 'input-icheck', 'id' => 'enable_supplier_n_customer_report']); !!} {{ __( 'lang_v1.enable_supplier_n_customer_report' ) }}
                    </label>
                     @show_tooltip(__('lang_v1.supplier_n_customer_report_help_text'))
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                    {!! Form::checkbox('report_settings[enable_customer_groups_report]', 1, $report_settings['enable_customer_groups_report'] ?? 1 , [ 'class' => 'input-icheck', 'id' => 'enable_customer_groups_report']); !!} {{ __( 'lang_v1.enable_customer_groups_report' ) }}
                    </label>
                     @show_tooltip(__('lang_v1.customer_groups_report_help_text'))
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                    {!! Form::checkbox('report_settings[enable_stock_report]', 1, $report_settings['enable_stock_report'] ?? 1 , [ 'class' => 'input-icheck', 'id' => 'enable_stock_report']); !!} {{ __( 'lang_v1.enable_stock_report' ) }}
                    </label>
                     @show_tooltip(__('lang_v1.stock_report_help_text'))
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                    {!! Form::checkbox('report_settings[enable_stock_adjustment_report]', 1, $report_settings['enable_stock_adjustment_report'] ?? 1 , [ 'class' => 'input-icheck', 'id' => 'enable_stock_adjustment_report']); !!} {{ __( 'lang_v1.enable_stock_adjustment_report' ) }}
                    </label>
                     @show_tooltip(__('lang_v1.stock_adjustment_report_help_text'))
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                    {!! Form::checkbox('report_settings[enable_trending_products]', 1, $report_settings['enable_trending_products'] ?? 1 , [ 'class' => 'input-icheck', 'id' => 'enable_trending_products']); !!} {{ __( 'lang_v1.enable_trending_products' ) }}
                    </label>
                     @show_tooltip(__('lang_v1.trending_products_help_text'))
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                    {!! Form::checkbox('report_settings[enable_items_report]', 1, $report_settings['enable_items_report'] ?? 1 , [ 'class' => 'input-icheck', 'id' => 'enable_items_report']); !!} {{ __( 'lang_v1.enable_items_report' ) }}
                    </label>
                     @show_tooltip(__('lang_v1.items_report_help_text'))
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                    {!! Form::checkbox('report_settings[enable_product_purchase_report]', 1, $report_settings['enable_product_purchase_report'] ?? 1 , [ 'class' => 'input-icheck', 'id' => 'enable_product_purchase_report']); !!} {{ __( 'lang_v1.enable_product_purchase_report' ) }}
                    </label>
                     @show_tooltip(__('lang_v1.product_purchase_report_help_text'))
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                    {!! Form::checkbox('report_settings[enable_product_sell_report]', 1, $report_settings['enable_product_sell_report'] ?? 1 , [ 'class' => 'input-icheck', 'id' => 'enable_product_sell_report']); !!} {{ __( 'lang_v1.enable_product_sell_report' ) }}
                    </label>
                     @show_tooltip(__('lang_v1.product_sell_report_help_text'))
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                    {!! Form::checkbox('report_settings[enable_purchase_payment_report]', 1, $report_settings['enable_purchase_payment_report'] ?? 1 , [ 'class' => 'input-icheck', 'id' => 'enable_purchase_payment_report']); !!} {{ __( 'lang_v1.enable_purchase_payment_report' ) }}
                    </label>
                     @show_tooltip(__('lang_v1.purchase_payment_report_help_text'))
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                    {!! Form::checkbox('report_settings[enable_sell_payment_report]', 1, $report_settings['enable_sell_payment_report'] ?? 1 , [ 'class' => 'input-icheck', 'id' => 'enable_sell_payment_report']); !!} {{ __( 'lang_v1.enable_sell_payment_report' ) }}
                    </label>
                     @show_tooltip(__('lang_v1.sell_payment_report_help_text'))
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                    {!! Form::checkbox('report_settings[enable_expense_report]', 1, $report_settings['enable_expense_report'] ?? 1 , [ 'class' => 'input-icheck', 'id' => 'enable_expense_report']); !!} {{ __( 'lang_v1.enable_expense_report' ) }}
                    </label>
                     @show_tooltip(__('lang_v1.expense_report_help_text'))
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                    {!! Form::checkbox('report_settings[enable_register_report]', 1, $report_settings['enable_register_report'] ?? 1 , [ 'class' => 'input-icheck', 'id' => 'enable_register_report']); !!} {{ __( 'lang_v1.enable_register_report' ) }}
                    </label>
                     @show_tooltip(__('lang_v1.register_report_help_text'))
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                    {!! Form::checkbox('report_settings[enable_sales_representative_report]', 1, $report_settings['enable_sales_representative_report'] ?? 1 , [ 'class' => 'input-icheck', 'id' => 'enable_sales_representative_report']); !!} {{ __( 'lang_v1.enable_sales_representative_report' ) }}
                    </label>
                     @show_tooltip(__('lang_v1.sales_representative_report_help_text'))
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                    {!! Form::checkbox('report_settings[enable_service_staff_report]', 1, $report_settings['enable_service_staff_report'] ?? 1 , [ 'class' => 'input-icheck', 'id' => 'enable_service_staff_report']); !!} {{ __( 'lang_v1.enable_service_staff_report' ) }}
                    </label>
                     @show_tooltip(__('lang_v1.service_staff_report_help_text'))
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                    {!! Form::checkbox('report_settings[enable_activity_log]', 1, $report_settings['enable_activity_log'] ?? 1 , [ 'class' => 'input-icheck', 'id' => 'enable_activity_log']); !!} {{ __( 'lang_v1.enable_activity_log' ) }}
                    </label>
                     @show_tooltip(__('lang_v1.activity_log_help_text'))
                </div>
            </div>
        </div>

        <div class="col-sm-6">
            <div class="form-group">
                <div class="checkbox">
                    <label>
                    {!! Form::checkbox('report_settings[enable_cheque_report]', 1, $report_settings['enable_cheque_report'] ?? 1 , [ 'class' => 'input-icheck', 'id' => 'enable_cheque_report']); !!} {{ __( 'lang_v1.enable_cheque_report' ) }}
                    </label>
                     @show_tooltip(__('lang_v1.activity_log_help_text'))
                </div>
            </div>
        </div>

    </div>
</div>
