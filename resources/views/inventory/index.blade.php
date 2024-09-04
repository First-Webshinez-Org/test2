@extends('layouts.app')
@section('title', __('home.home'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1> Dashboard
        <small>Inventory</small>
    </h1>
</section>
<!-- Main content -->
<section class="content">
    <br>
    @if(auth()->user()->can('dashboard.data'))
        {{-- @if($is_admin)
            <br>
            <br>
            <br>
        @endif --}}
        <!-- end is_admin check -->
        
        <!-- stock -->
        @can('stock_report.view')
            <div class="row">
                <div class="@if((session('business.enable_product_expiry') != 1) && auth()->user()->can('stock_report.view')) col-sm-12 @else col-sm-6 @endif">
                    @component('components.widget', ['class' => 'box-warning'])
                      @slot('icon')
                        <i class="fa fa-exclamation-triangle text-yellow" aria-hidden="true"></i>
                      @endslot
                      @slot('title')
                        {{ __('home.product_stock_alert') }} @show_tooltip(__('tooltip.product_stock_alert'))
                      @endslot
                      <div class="row">
                            @if(count($all_locations) > 1)
                                <div class="col-md-6 col-sm-6 col-md-offset-6 mb-10">
                                    {!! Form::select('stock_alert_location', $all_locations, null, ['class' => 'form-control select2', 'placeholder' => __('lang_v1.select_location'), 'id' => 'stock_alert_location']); !!}
                                </div>
                            @endif
                            <div class="col-md-12">
                                <table class="table table-bordered table-striped" id="stock_alert_table" style="width: 100%;">
                                    <thead>
                                      <tr>
                                        <th>@lang( 'sale.product' )</th>
                                        <th>@lang( 'business.location' )</th>
                                        <th>@lang( 'report.current_stock' )</th>
                                      </tr>
                                    </thead>
                                </table>
                            </div>
                      </div>
                    @endcomponent
                </div>
                @if(session('business.enable_product_expiry') == 1)
                    <div class="col-sm-6">
                        @component('components.widget', ['class' => 'box-warning'])
                          @slot('icon')
                            <i class="fa fa-exclamation-triangle text-yellow" aria-hidden="true"></i>
                          @endslot
                          @slot('title')
                            {{ __('home.stock_expiry_alert') }} @show_tooltip( __('tooltip.stock_expiry_alert', [ 'days' =>session('business.stock_expiry_alert_days', 30) ]) )
                          @endslot
                          <input type="hidden" id="stock_expiry_alert_days" value="{{ \Carbon::now()->addDays(session('business.stock_expiry_alert_days', 30))->format('Y-m-d') }}">
                          <table class="table table-bordered table-striped" id="stock_expiry_alert_table">
                            <thead>
                              <tr>
                                  <th>@lang('business.product')</th>
                                  <th>@lang('business.location')</th>
                                  <th>@lang('report.stock_left')</th>
                                  <th>@lang('product.expires_in')</th>
                              </tr>
                            </thead>
                          </table>
                        @endcomponent
                    </div>
                @endif
      	    </div>
        @endcan
        
        @if(auth()->user()->can('account.access') && config('constants.show_payments_recovered_today') == true)
            @component('components.widget', ['class' => 'box-warning'])
              @slot('icon')
                  <i class="fas fa-money-bill-alt text-yellow fa-lg" aria-hidden="true"></i>
              @endslot
              @slot('title')
                  @lang('lang_v1.payment_recovered_today')
              @endslot
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="cash_flow_table">
                        <thead>
                            <tr>
                                <th>@lang( 'messages.date' )</th>
                                <th>@lang( 'account.account' )</th>
                                <th>@lang( 'lang_v1.description' )</th>
                                <th>@lang( 'lang_v1.payment_method' )</th>
                                <th>@lang( 'lang_v1.payment_details' )</th>
                                <th>@lang('account.credit')</th>
                                <th>@lang( 'lang_v1.account_balance' ) @show_tooltip(__('lang_v1.account_balance_tooltip'))</th>
                                <th>@lang( 'lang_v1.total_balance' ) @show_tooltip(__('lang_v1.total_balance_tooltip'))</th>
                            </tr>
                        </thead>
                        <tfoot>
                            <tr class="bg-gray font-17 footer-total text-center">
                                <td colspan="5"><strong>@lang('sale.total'):</strong></td>
                                <td class="footer_total_credit"></td>
                                <td colspan="2"></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @endcomponent
        @endif

        @if(!empty($widgets['after_dashboard_reports']))
          @foreach($widgets['after_dashboard_reports'] as $widget)
            {!! $widget !!}
          @endforeach
        @endif

    @endif
   <!-- can('dashboard.data') end -->
</section>
<!-- /.content -->
<div class="modal fade payment_modal" tabindex="-1" role="dialog" 
    aria-labelledby="gridSystemModalLabel">
</div>
<div class="modal fade edit_pso_status_modal" tabindex="-1" role="dialog"></div>
<div class="modal fade edit_payment_modal" tabindex="-1" role="dialog" 
    aria-labelledby="gridSystemModalLabel">
</div>
@stop
@section('javascript')
    <script src="{{ asset('js/home.js?v=' . $asset_v) }}"></script>
@endsection

