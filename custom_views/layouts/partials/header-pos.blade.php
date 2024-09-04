<!-- default value -->
@php
    $go_back_url = action([\App\Http\Controllers\SellPosController::class, 'index']);
    $transaction_sub_type = '';
    $view_suspended_sell_url = action([\App\Http\Controllers\SellController::class, 'index']).'?suspended=1';
    $pos_redirect_url = action([\App\Http\Controllers\SellPosController::class, 'create']);
@endphp

@if(!empty($pos_module_data))
    @foreach($pos_module_data as $key => $value)
        @php
            if(!empty($value['go_back_url'])) {
                $go_back_url = $value['go_back_url'];
            }

            if(!empty($value['transaction_sub_type'])) {
                $transaction_sub_type = $value['transaction_sub_type'];
                $view_suspended_sell_url .= '&transaction_sub_type='.$transaction_sub_type;
                $pos_redirect_url .= '?sub_type='.$transaction_sub_type;
            }
        @endphp
    @endforeach
@endif
<input type="hidden" name="transaction_sub_type" id="transaction_sub_type" value="{{$transaction_sub_type}}">
@inject('request', 'Illuminate\Http\Request')
<div class="col-md-12 no-print pos-header">
  <input type="hidden" id="pos_redirect_url" value="{{$pos_redirect_url}}">
  <div class="row">
    <div class="col-md-2">
      <div class="m-6 mt-5" style="display: flex;">
        <p ><strong>@lang('sale.location'): &nbsp;</strong> 
          @if(empty($transaction->location_id))
            @if(count($business_locations) > 1)
            <div style="width: 100%;margin-bottom: 5px;">
               {!! Form::select('select_location_id', $business_locations, $default_location->id ?? null , ['class' => 'form-control input-sm',
                'id' => 'select_location_id', 
                'required', 'autofocus'], $bl_attributes); !!}
            </div>
            @else
              {{$default_location->name}}
            @endif
          @endif

          <!-- @if(!empty($transaction->location_id)) {{$transaction->location->name}} @endif &nbsp; <span class="curr_datetime">{{ @format_datetime('now') }}</span> -->
           <!-- <i class="fa fa-keyboard hover-q text-muted" aria-hidden="true" data-container="body" data-toggle="popover" data-placement="bottom" data-content="@include('sale_pos.partials.keyboard_shortcuts_details')" data-html="true" data-trigger="hover" data-original-title="" title=""></i> -->
        </p>
      </div>
    </div>

    @include('layouts.partials.header-pos-dropdowns')
   
    <div class="col-md-2">
      <a href="{{$go_back_url}}" title="{{ __('lang_v1.go_back') }}" class="btn btn-info btn-flat m-6 m-5 pull-right">
        <strong><i class="fa fa-backward fa-lg"></i></strong>
      </a>
      <!-- start -->
      <!-- <div class="btn-group"> -->
        <button id="header_shortcut_dropdown" type="button" class="dropdown-toggle btn btn-info btn-flat m-6 m-5 btn-modal pull-right" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <i class="fas fa-plus-circle fa-lg"></i>
        </button>
        <ul class="dropdown-menu">
          @if(!empty($pos_settings['inline_service_staff']))
          <li>
            <button type="button" id="show_service_staff_availability" title="{{ __('lang_v1.service_staff_availability') }}" class="header-buttons btn-modal" data-container=".view_modal" 
              data-href="{{ action([\App\Http\Controllers\SellPosController::class, 'showServiceStaffAvailibility'])}}">
                <i class="fa fa-users fa-lg"></i>{{ __('cash_register.close_register') }}
            </button>
          </li>
          @endif
         
          @can('close_cash_register')
          <li>
            <button type="button" id="close_register" title="{{ __('cash_register.close_register') }}" class="header-buttons btn-modal" data-container=".close_register_modal" 
              data-href="{{ action([\App\Http\Controllers\CashRegisterController::class, 'getCloseRegister'])}}">
                <i class="fa fa-window-close fa-lg"></i>{{ __('cash_register.close_register') }}
          </button>
          </li>
          @endcan

          @can('view_cash_register')
              <li>
                <button type="button" id="register_details" title="{{ __('cash_register.register_details') }}" data-toggle="tooltip" data-placement="bottom" class="header-buttons btn-modal" data-container=".register_details_modal" 
              data-href="{{ action([\App\Http\Controllers\CashRegisterController::class, 'getRegisterDetails'])}}">
                  <i class="fa fa-briefcase fa-lg" aria-hidden="true"></i> {{ __('cash_register.register_details') }}
                </button>
              </li>
            @endcan

            <li>
              <button title="@lang('lang_v1.calculator')" id="btnCalculator" type="button" class="header-buttons popover-default" data-toggle="popover" data-trigger="click" data-content='@include("layouts.partials.calculator")' data-html="true" data-placement="bottom">
                    <i class="fa fa-calculator fa-lg" aria-hidden="true"></i>@lang('lang_v1.calculator')
              </button>
            </li>

            <li>
              <button type="button" class="header-buttons popover-default" id="return_sale" title="@lang('lang_v1.sell_return')" data-toggle="popover" data-trigger="click" data-content='<div class="m-8"><input type="text" class="form-control" placeholder="@lang("sale.invoice_no")" id="send_for_sell_return_invoice_no"></div><div class="w-100 text-center"><button type="button" class="btn btn-danger" id="send_for_sell_return">@lang("lang_v1.send")</button></div>' data-html="true" data-placement="bottom">
                    <i class="fas fa-undo fa-lg"></i>@lang('lang_v1.sell_return')
              </button>
            </li>

            <li>
              <button type="button" title="{{ __('lang_v1.full_screen') }}" class="header-buttons hidden-xs" id="full_screen">
                    <i class="fa fa-window-maximize fa-lg"></i>{{ __('lang_v1.full_screen') }}
              </button>
            </li>

            <li>
              <button type="button" id="view_suspended_sales" title="{{ __('lang_v1.view_suspended_sales') }}" class="header-buttons btn-modal" data-container=".view_modal" 
                  data-href="{{$view_suspended_sell_url}}">
                    <i class="fa fa-pause-circle fa-lg"></i>{{ __('lang_v1.view_suspended_sales') }}
              </button>
            </li>

            @if(empty($pos_settings['hide_product_suggestion']) && isMobile())
            <li>
              <button type="button" title="{{ __('lang_v1.view_products') }}"   
                data-placement="bottom" class="header-buttons" data-toggle="modal" data-target="#mobile_product_suggestion_modal">
                  <i class="fa fa-cubes fa-lg"></i>{{ __('lang_v1.view_products') }}
              </button>
            </li>
            @endif

            @if(Module::has('Repair') && $transaction_sub_type != 'repair')
              @include('repair::layouts.partials.pos_header')
            @endif
            <!-- end -->
            @if(in_array('pos_sale', $enabled_modules) && !empty($transaction_sub_type))
              @can('sell.create')
              <li>
                <a href="{{action([\App\Http\Controllers\SellPosController::class, 'create'])}}" title="@lang('sale.pos_sale')">
                  <i class="fa fa-th-large"></i> @lang('sale.pos_sale')
                </a>
              </li>
              @endcan
            @endif

            @can('expense.add')
            <li>
              <button type="button" title="{{ __('expense.add_expense') }}"   
                data-placement="bottom" class="header-buttons btn-modal" id="add_expense">
                  <i class="fa fas fa-minus-circle"></i> @lang('expense.add_expense')
              </button>
            </li>
            @endcan

          </ul>
      <!-- </div> -->
    </div>
    
  </div>
</div>
