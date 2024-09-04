@extends('layouts.app')
@section('title', __('home.accounts'))

@section('content')

<!-- Content Header (Page header) -->
<!-- <section class="content-header content-header-custom">
    <h1>{{ __('home.welcome_message', ['name' => Session::get('user.first_name')]) }}
    </h1>
</section> -->
<section class="content-header">
    <h1> Dashboard
        <small>Accounts</small>
    </h1>
</section>
<!-- Main content -->
<section class="content">
    <br>
    @if(auth()->user()->can('dashboard.data'))
        {{-- @if($is_admin)
        	<!-- toggle button end -->
            <br>
            <br>
            <br>
        @endif --}}
        <!-- end is_admin check -->
         {{--@if(auth()->user()->can('sell.view') || auth()->user()->can('direct_sell.view'))
            @if(!empty($widgets['after_sales_last_30_days']))
                @foreach($widgets['after_sales_last_30_days'] as $widget)
                    {!! $widget !!}
                @endforeach
            @endif
            @if(!empty($all_locations))
              	<div class="row">
              		<div class="col-sm-12">
                        @component('components.widget', ['class' => 'box-primary', 'title' => __('home.sells_current_fy')])
                          {!! $sells_chart_2->container() !!}
                        @endcomponent
              		</div>
              	</div>
            @endif
        @endif--}}
      	<!-- sales chart end -->
        @if(!empty($widgets['after_sales_current_fy']))
            @foreach($widgets['after_sales_current_fy'] as $widget)
                {!! $widget !!}
            @endforeach
        @endif
      	<!-- products less than alert quntity -->
      	<div class="row">
            @if(auth()->user()->can('sell.view') || auth()->user()->can('direct_sell.view'))
                <div class="col-sm-6">
                    @component('components.widget', ['class' => 'box-warning'])
                      @slot('icon')
                        <i class="fa fa-exclamation-triangle text-yellow" aria-hidden="true"></i>
                      @endslot
                      @slot('title')
                        {{ __('lang_v1.sales_payment_dues') }} @show_tooltip(__('lang_v1.tooltip_sales_payment_dues'))
                      @endslot
                        <div class="row">
                            @if(count($all_locations) > 1)
                                <div class="col-md-6 col-sm-6 col-md-offset-6 mb-10">
                                    {!! Form::select('sales_payment_dues_location', $all_locations, null, ['class' => 'form-control select2', 'placeholder' => __('lang_v1.select_location'), 'id' => 'sales_payment_dues_location']); !!}
                                </div>
                            @endif
                            <div class="col-md-12">
                                <table class="table table-bordered table-striped" id="sales_payment_dues_table" style="width: 100%;">
                                    <thead>
                                      <tr>
                                        <th>@lang( 'contact.customer' )</th>
                                        <th>@lang( 'sale.invoice_no' )</th>
                                        <th>@lang( 'home.due_amount' )</th>
                                        <th>@lang( 'messages.action' )</th>
                                      </tr>
                                    </thead>
                                </table>
                            </div>
                        </div>
                    @endcomponent
                </div>
            @endif
            @can('purchase.view')
                <div class="col-sm-6">
                    @component('components.widget', ['class' => 'box-warning'])
                    @slot('icon')
                    <i class="fa fa-exclamation-triangle text-yellow" aria-hidden="true"></i>
                    @endslot
                    @slot('title')
                    {{ __('lang_v1.purchase_payment_dues') }} @show_tooltip(__('tooltip.payment_dues'))
                    @endslot
                    <div class="row">
                        @if(count($all_locations) > 1)
                            <div class="col-md-6 col-sm-6 col-md-offset-6 mb-10">
                                {!! Form::select('purchase_payment_dues_location', $all_locations, null, ['class' => 'form-control select2', 'placeholder' => __('lang_v1.select_location'), 'id' => 'purchase_payment_dues_location']); !!}
                            </div>
                        @endif
                        <div class="col-md-12">
                            <table class="table table-bordered table-striped" id="purchase_payment_dues_table" style="width: 100%;">
                                <thead>
                                  <tr>
                                    <th>@lang( 'purchase.supplier' )</th>
                                    <th>@lang( 'purchase.ref_no' )</th>
                                    <th>@lang( 'home.due_amount' )</th>
                                    <th>@lang( 'messages.action' )</th>
                                  </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                    @endcomponent
                </div>
            @endcan
        </div>
        
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
    <script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script>
@endsection

