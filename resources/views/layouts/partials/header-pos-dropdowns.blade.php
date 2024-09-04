
    @php
    $user = auth()->user();
    $enabled_modules = ! empty(session('business.enabled_modules')) ? session('business.enabled_modules') : [];
    $common_settings = ! empty(session('business.common_settings')) ? session('business.common_settings') : [];

    $supplierMenuItems = [];
    $customerMenuItems = [];
    $paymentAccountsMenuItems = [];
    $purchaseMenuItems = [];

    if ($user->can('supplier.view') || $user->can('customer.view') || $user->can('supplier.view_own') || $user->can('customer.view_own')) {
        if ($user->can('supplier.view') || $user->can('supplier.view_own')) {
            $supplierMenuItems[] = [
                'name' => __('contact.supplier'),
                'url' => action([\App\Http\Controllers\ContactController::class, 'index'], ['type' => 'supplier']),
            ];
            $supplierMenuItems[] = [
                'name' => __('report.supplier'),
                'url' => action([\App\Http\Controllers\ContactController::class, 'index'], ['type' => 'supplier']),
            ];
        }
    }

    if ($user->can('supplier.view') || $user->can('customer.view') || $user->can('supplier.view_own') || $user->can('customer.view_own')) {
        if ($user->can('customer.view') || $user->can('customer.view_own')) {
            $customerMenuItems[] = [
                'name' => __('contact.customer'),
                'url' => action([\App\Http\Controllers\ContactController::class, 'index'], ['type' => 'customer']),
            ];
            $customerMenuItems[] = [
                'name' => __('report.customer'),
                'url' => action([\App\Http\Controllers\ContactController::class, 'index'], ['type' => 'customer']),
            ];
            $customerMenuItems[] = [
                'name' => __('lang_v1.customer_groups'),
                'url' => action([\App\Http\Controllers\CustomerGroupController::class, 'index'], ['type' => 'customer']),
            ];
        }
        if ($user->can('supplier.create') || $user->can('customer.create')) {
            $customerMenuItems[] = [
                'name' =>  __('lang_v1.import_contacts'),
                'url' =>  action([\App\Http\Controllers\ContactController::class, 'getImportContacts']),
            ];
        }
    }

    if ($user->can('supplier.view') || $user->can('customer.view') || $user->can('supplier.view_own') || $user->can('customer.view_own')) {
        if ($user->can('account.access') && in_array('account', $enabled_modules)) {
            $paymentAccountsMenuItems[] = [
                'name' => __('lang_v1.payment_accounts'),
                'url' => '',
            ];
            $paymentAccountsMenuItems[] = [
                'name' => __('account.list_accounts'),
                'url' => action([\App\Http\Controllers\AccountController::class, 'index']),
            ];
            $paymentAccountsMenuItems[] = [
                'name' => __('account.balance_sheet'),
                'url' => action([\App\Http\Controllers\AccountReportsController::class, 'balanceSheet']),
            ];
            $paymentAccountsMenuItems[] = [
                'name' => __('account.trial_balance'),
                'url' => action([\App\Http\Controllers\AccountReportsController::class, 'trialBalance']),
            ];
            $paymentAccountsMenuItems[] = [
                'name' => __('lang_v1.cash_flow'),
                'url' => action([\App\Http\Controllers\AccountController::class, 'cashFlow']),
            ];
            $paymentAccountsMenuItems[] = [
                'name' => __('account.payment_account_report'),
                'url' => action([\App\Http\Controllers\AccountReportsController::class, 'paymentAccountReport']),
            ];
        }
    }

    if (in_array('purchases', $enabled_modules) && ($user->can('purchase.view') || $user->can('purchase.create') || $user->can('purchase.update'))) {
        $purchaseMenuItems[] = [
                'name' =>  __('purchase.purchases'),
                'url' => ''
        ];  

        if (!empty($common_settings['enable_purchase_requisition']) && ($user->can('purchase_requisition.view_all') || $user->can('purchase_requisition.view_own'))) {
            $purchaseMenuItems[] = [
                'name' => __('lang_v1.purchase_requisition'),
                'url' => action([\App\Http\Controllers\PurchaseRequisitionController::class, 'index'])
            ];
        }

        if (!empty($common_settings['enable_purchase_order']) && ($user->can('purchase_order.view_all') || $user->can('purchase_order.view_own'))) {
            $purchaseMenuItems[] = [
                'name' => __('lang_v1.purchase_order'),
                'url' => action([\App\Http\Controllers\PurchaseOrderController::class, 'index'])
            ];
        }

        if ($user->can('purchase.view') || $user->can('view_own_purchase')) {
            $purchaseMenuItems[] = [
                'name' => __('purchase.list_purchase'),
                'url' => action([\App\Http\Controllers\PurchaseController::class, 'index'])
            ];
        }

        if ($user->can('purchase.create')) {
            $purchaseMenuItems[] = [
                'name' => __('purchase.add_purchase'),
                'url' => action([\App\Http\Controllers\PurchaseController::class, 'create'])
            ];
        }

        if ($user->can('purchase.update')) {
            $purchaseMenuItems[] = [
                'name' => __('lang_v1.list_purchase_return'),
                'url' => action([\App\Http\Controllers\PurchaseReturnController::class, 'index'])
            ];
        }
    }

    @endphp

    <div class="col-md-2">
      <div class="m-6 mt-5">
          @if(!empty($supplierMenuItems))
              <select class="form-control" onchange="window.location.href=this.value;">
                  @foreach($supplierMenuItems as $item)
                      <option value="{{ $item['url'] }}">{{ $item['name'] }}</option>
                  @endforeach
              </select>
          @endif
      </div>
    </div>

    <div class="col-md-2">
      <div class="m-6 mt-5">
          @if(!empty($customerMenuItems))
              <select class="form-control" onchange="window.location.href=this.value;">
                  @foreach($customerMenuItems as $item)
                      <option value="{{ $item['url'] }}">{{ $item['name'] }}</option>
                  @endforeach
              </select>
          @endif
      </div>
    </div>

    <div class="col-md-2">
      <div class="m-6 mt-5">
          @if(!empty($paymentAccountsMenuItems))
              <select class="form-control" onchange="window.location.href=this.value;">
                  @foreach($paymentAccountsMenuItems as $item)
                      <option value="{{ $item['url'] }}">{{ $item['name'] }}</option>
                  @endforeach
              </select>
          @endif
      </div>
    </div>

    <div class="col-md-2">
      <div class="m-6 mt-5">
          @if(!empty($purchaseMenuItems))
              <select class="form-control" onchange="window.location.href=this.value;">
                  @foreach($purchaseMenuItems as $item)
                      <option value="{{ $item['url'] }}">{{ $item['name'] }}</option>
                  @endforeach
              </select>
          @endif
      </div>
    </div>