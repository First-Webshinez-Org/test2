@extends('layouts.app')
@section('title', __('home.financial'))

<style>
    /* Hide scrollbar for Chrome, Safari and Opera */
    .charts-row1::-webkit-scrollbar {
        display: none;
        /* width: 5px;
        height: 8px;
        background-color: #aaa;
        border-radius: 50px; */
    }

    /* Hide scrollbar for IE, Edge and Firefox */
    .charts-row1 {
        -ms-overflow-style: none;  /* IE and Edge */
        scrollbar-width: none;  /* Firefox */
    }

    /* charts */
    .charts-row1 {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 2rem;
        overflow-x: auto;
    }

    .chart-top {
        display: flex;
        gap: 0.6rem;
    }

    .chart-top > p {
        font-size: 0.7rem;
    }

    .chart-top {
        font-size: 1rem;
        text-align: start;
    }

    .chart-top span:after {
        display: inline-block;
        content: "";
        width: 2em;
        height: 0.8em;
        height: 0.8em;
        background: currentColor;
    }

    .bar-chart-container > h3 {
        color: rgba(8, 97, 175, 4);
        font-weight: 600;
    }


    .bar-chart-div {
        background-color: white;
        box-shadow: 2px 2px 4px rgba(136, 152, 170, 0.15) !important;
        position: relative;
        /* z-index: -1; */
        height: fit-content;
        padding: 1rem 2rem;
        width: 100%;
    }

    .cbar {
        cursor: pointer;
        position: relative;
        z-index: 1;
    }

    #bar-chart {
        margin: 0 auto;
        max-width: 100%;
        position: relative;
        padding-left: 4rem;
    }

    #bar-chart h1 {
        text-align: center;
        margin: 0;
        font-size: 1.5em;
        font-weight: 600;
    }

    #bar-chart .chart-row {
        position: relative;
        line-height: 1.25em;
        margin-bottom: 2em;
        height: 15rem;
        /* width: ; */
    }

    #bar-chart .chart-row .segment {
        -webkit-box-flex: 1;
        -ms-flex: 1 100%;
        flex: 1 100%;
        display: block;
        position: relative;
        -ms-flex-item-align: end;
        align-self: flex-end;
    }

    #bar-chart .chart-row .segment:after {
        content: "";
        display: block;
        width: 100%;
        bottom: 0;
        position: absolute;
        height: 0.5px;
        background-color: #414245;
        /* z-index: -1; */
    }

    #bar-chart .label {
        display: block;
        font-size: 0.7em;
        text-align: center;
    }

    /* X Axis */
    #bar-chart .chart-x-axis {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        position: absolute;
        padding: 0 2em 0 0.5em;
        height: 100%;
        width: 100%;
        margin-bottom: 3.5em;
    }

    #bar-chart .chart-x-axis .year {
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
        position: relative;
    }

    #bar-chart .chart-x-axis .year .col {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        height: 100%;
        margin: 0 8%;
        position: relative;
    }

    #bar-chart .chart-x-axis .year .col .cbar {
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
        /* background-color: #00aba9; */
        -ms-flex-item-align: end;
        align-self: flex-end;
        position: relative;
        margin: 0;
        height: 0px;
        -webkit-transition: height, 1s ease;
        -o-transition: height, 1s ease;
        transition: height, 1s ease;
    }

    #bar-chart .chart-x-axis .year .col .bar[style*="height"] {
        min-height: 2px;
    }

    .two-cols {
        margin-left: 0.8rem;
        display: flex;
        width: 100%;
        justify-content: center;
    }

    .marb {
        margin-top: 1rem;
    }

    #bar-chart .chart-x-axis .year .col .bar.negative {
        -ms-flex-item-align: start;
        align-self: flex-start;
        top: 100%;
    }

    #bar-chart .chart-x-axis .label {
        padding: 0.5em 0.25em 0;
        top: 15rem;
        /* 320 + 8*/
        position: absolute;
        line-height: 1.25em;
        width: 100%;
    }

    #bar-chart .chart-x-axis .label a {
        color: black;
        text-decoration: none;
        display: block;
    }

    #bar-chart .chart-x-axis .label a .name {
        display: none;
    }

    #bar-chart .chart-x-axis .year .col .cbar.tooltip,
    #bar-chart .chart-x-axis .year .col .cbar.tooltip:after {
        display: block;
        position: absolute;
        z-index: 2;
        left: 50%;
        -webkit-transform: translateX(-50%);
        -ms-transform: translateX(-50%);
        transform: translateX(-50%);
    }

    #bar-chart .chart-x-axis .year .col .cbar.tooltip {
        top: -2em;
        font-size: 0.75em;
        padding: 2px 5px;
    }

    /* Y Axis */
    #bar-chart .chart-y-axis {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        height: 100%;
        -webkit-box-orient: horizontal;
        -webkit-box-direction: normal;
        -ms-flex-direction: row;
        flex-direction: row;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
        position: absolute;
        width: 100%;
        padding: 0 1.5em 0 0.25em;
    }

    #bar-chart .chart-y-axis .label {
        margin: 0 auto -8px -3.5em;
        color: black;
        width: 2.5em;
        text-align: right;
        padding-right: 0;
        padding-left: 0.5em;
    }

    /*Flat UI colors*/

    .four {
        background: #3498db;
    }

    .five {
        background: rgb(41, 170, 185);
    }

    /* card */

    .card-container {
    display: flex;
    gap: 2rem;
    }

    .card {
    background-color: white;
    width: fit-content;
    padding: 1rem 1.7rem;
    }

    .card > p {
    font-size: 1.3rem;
    color: #888;
    }

    .card > h1 {
    color: black;
    font-size: 3.7rem;
    font-weight: 100;
    margin: -0.2rem 0;
    }

    .percent {
        /* color: black; */
    }


    /* //table */

    /* table */
    .tables {
        position: relative;
        top: 3rem;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 2rem;
    }

    .table-div{
        box-shadow: 2px 2px 4px rgba(136, 152, 170, 0.15) !important;
        width: 100%;
    }

    .head-table>h4 {
        font-size: 1.4rem;
    }

    #table-container {
        margin-top : -0.5rem;
    }

    .head-table,
    .table-container,
    .total {
        padding: 0 1rem;
    }

    .table-container p {
        font-size: 1.3rem;
    }

    .head-table {
        display: grid;
        grid-template-columns: 2fr 4fr 1fr;
        /* background-color: rgb(52,150,194); */
        background: linear-gradient(90deg, rgba(52,150,194,1) 0%, rgba(182,230,240,1) 90%);
        color: white;
    }

    .table-container {
        display: grid;
        grid-template-columns: 2fr 4fr 1fr;
        background-color: white;
        /* height: 7rem; */
    }

    .column {
        height: fit-content;
    }

    .column > p {
        text-align: start;
        margin-top: 1.1rem;
    }

    .total {
        height: 5rem;
        display: grid;
        grid-template-columns: 2fr 4fr 1fr;
        border-top: 1px solid black;
        background-color: white;
    }

    .total > h4 {
        font-size: 1.4rem;
    }

    .total>p {
        font-size: 1.3rem;
        margin-top: 1rem;
    }

    .table-hori-div {
        background-color: rgb(241, 235, 235);
        position: relative;
        top: 5rem;
        z-index: 1;
        height: 14rem;
        width: 26rem;
        padding-left: 6rem;
        padding-top: 1rem;
    }


    /* high charts */
    .highcharts-figure,
    .highcharts-data-table table {
        min-width: 320px;
        max-width: 800px;
        margin: 1em auto;
    }

    .highcharts-data-table table {
        font-family: Verdana, sans-serif;
        border-collapse: collapse;
        border: 1px solid #ebebeb;
        margin: 10px auto;
        text-align: center;
        width: 100%;
        max-width: 500px;
    }

    .highcharts-data-table caption {
        padding: 1em 0;
        font-size: 1.2em;
        color: #555;
    }

    .highcharts-data-table th {
        font-weight: 600;
        padding: 0.5em;
    }

    .highcharts-data-table td,
    .highcharts-data-table th,
    .highcharts-data-table caption {
        padding: 0.5em;
    }

    .highcharts-data-table thead tr,
    .highcharts-data-table tr:nth-child(even) {
        background: #f8f8f8;
    }

    .highcharts-data-table tr:hover {
        background: #f1f7ff;
    }

    input[type="number"] {
        min-width: 50px;
    }

    /* bar chart high */
    #container {
        min-width: 310px;
        max-width: 800px;
        height: 400px;
    }

    .buttons {
        min-width: 310px;
        text-align: center;
        margin: 1rem 0;
        font-size: 0;
    }

    .buttons button {
        cursor: pointer;
        border: 1px solid silver;
        border-right-width: 0;
        background-color: #f8f8f8;
        font-size: 1rem;
        padding: 0.5rem;
        transition-duration: 0.3s;
        margin: 0;
    }

    .buttons button:first-child {
        border-top-left-radius: 0.3em;
        border-bottom-left-radius: 0.3em;
    }

    .buttons button:last-child {
        border-top-right-radius: 0.3em;
        border-bottom-right-radius: 0.3em;
        border-right-width: 1px;
    }

    .buttons button:hover {
        color: white;
        background-color: rgb(158 159 163);
        outline: none;
    }

    .buttons button.active {
        background-color: #0051b4;
        color: white;
    }

    @media only screen and (max-width: 992px) {
        .charts-row1 {
            display: block;
            overflow: hidden;
        }
    }

    @media only screen and (max-width: 600px) {
        .table-div{
            width: 60rem;
        }

        .table-div-container{
            width: 100%;
            overflow-x: scroll;
        }
    }
</style>

@section('content')

<section class="content-header">
    <h1> Dashboard
        <small>Financial Chart</small>
    </h1>
</section>

<!-- Main content -->
<section class="content">
    <!-- toggle button start -->
    <div class="row toggle-flex">
        <div class="hidden-buttons" id="toggler-div">
            <div class="col-md-4 col-xs-12">
                <div class="form-group">
                    <!-- {{-- <div class="form-group pull-right"> --}} -->
                    <div class="input-group">
                        <button type="button" class="btn btn-primary" id="dashboard_date_filter2">
                            <span>
                                <i class="fa fa-calendar"></i> {{ __('messages.filter_by_date') }}
                            </span>
                            <i class="fa fa-caret-down"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-4 col-xs-12">
                <!-- {{-- <div class="col-md-8 col-xs-12"> --}} -->
                @if (count($all_locations) > 1)
                    {!! Form::select('dashboard_location', $all_locations, null, [
                    'class' => 'form-control select2',
                    'placeholder' => __('lang_v1.select_location'),
                    'id' => 'dashboard_location2',
                    ]) !!}
                @endif
            </div>
        </div>
        <div class="button-toggler"><button class="btn btn-primary" id="toggler">|||</button></div>
    </div>
    <!-- toggle button end -->
    <br>
    <!-- <br> -->
    <!-- <br> -->

    <!-- <div>
        <div class='buttons'>
            <button id='2000'>
                2000
            </button>
            <button id='2004'>
                2004
            </button>
            <button id='2008'>
                2008
            </button>
            <button id='2012'>
                2012
            </button>
            <button id='2016'>
                2016
            </button>
            <button id='2020' class='active'>
                2020
            </button>
        </div>
        <div id="container-bar"></div>
    </div> -->

    <!-- bottom cards start -->
            <div class="row bottom-cards">
                    <div class="col-md-3 col-sm-6 col-xs-12 col-custom">
                        <div class="col-md-8 info-box info-box-new-style justify-between" id="total-purchase-box">
                            <div class="col-md-8 info-box-content">
                                <span class="info-box-text">Sales</span>
                                <span class="info-box-number" id="revenueAmount"><i class="fas fa-sync fa-spin fa-fw margin-bottom"></i></span>
                                <p><span class="percent" id="revenuePercent"></span> from <span class="day"></span></p>
                            </div>

                            <span class="info-box-icon bg-aqua ml-15"><i class="ion ion-cash"></i></span>

                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->

                    <div class="col-md-3 col-sm-6 col-xs-12 col-custom">
                        <div class="col-md-8 info-box info-box-new-style justify-between" id="expense-box">
                            <div class="col-md-8 info-box-content">
                                <span class="info-box-text">Expenses</span>
                                <span class="info-box-number" id="expenseAmount"><i class="fas fa-sync fa-spin fa-fw margin-bottom"></i></span>
                                <p><span class="percent" id="expensePercent"></span> from <span class="day"></span></p>
                            </div>

                            <span class="info-box-icon bg-red ml-15"><i class="fa fa-dollar"></i><i class="fa fa-exclamation"></i></span>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                    <!-- /.col -->
                    <div class="col-md-3 col-sm-6 col-xs-12 col-custom">
                        <div class="col-md-8 info-box info-box-new-style justify-between" id="purchase-due-box">
                            <div class="col-md-8 info-box-content">
                                <span class="info-box-text">Profit</span>
                                <span class="info-box-number" id="profitAmount"><i class="fas fa-sync fa-spin fa-fw margin-bottom"></i></span>
                                <p><span class="percent" id="profitPercent"></span> from <span class="day"></span></p>
                            </div>

                            <span class="info-box-icon bg-green text-white ml-15"><i class="fas fa-undo-alt"></i></span>

                            <!-- /.info-box-content -->
                            {{-- <p class="mb-0 text-muted fs-10 mt-5">{{ __('lang_v1.total_purchase_return')}}: <span class="total_pr"></span><br>
                            {{ __('lang_v1.total_purchase_return_paid')}}<span class="total_prp"></span></p> --}}
                        </div>
                        <!-- /.info-box -->
                    </div>

                    <!-- expense -->
                    <div class="col-md-3 col-sm-6 col-xs-12 col-custom">
                        <div class="col-md-8 info-box info-box-new-style justify-between" id="total-purchase-return-box">
                            <div class="col-md-8 info-box-content">
                                <span class="info-box-text">
                                    Profit Margin
                                </span>
                                <span class="info-box-number" id="profitMarginAmount"><i class="fas fa-sync fa-spin fa-fw margin-bottom"></i></span>
                                <p><span class="percent" id="profitMarginPercent"></span> from <span class="day"></span></p>
                            </div>

                            <span class="info-box-icon bg-yellow ml-15"><i class="fas fa-minus-circle"></i></span>
                            <!-- /.info-box-content -->
                        </div>
                        <!-- /.info-box -->
                    </div>
                </div>
            <!-- bottom cards end -->

    
    <!-- table section start -->
    <div class="table-div-container">
        <div class="table-div">
        <div class="head-table">
          <h4>Branch</h4>
          <h4>Sales</h4>
          <h4>Profit</h4>
          <!-- <h4>Net profit Margin</h4> -->
        </div>
        <div class="table-container">
          <div class="column" id="col1">
            @foreach ($all_locations as $item)
                <p>{{ $item }}</p>
            @endforeach
          </div>
          <div class="column" id="col2">
           <div id="table-container"></div>
          </div>
          <div class="column" id="col4">
          </div>
          <!-- <div class="column" id="col5">
          </div> -->
        </div>
        <div class="total">
          <h4>Grand Total</h4>
          <p id="grand_revenue"></p>
          <p id="grand_profit"></p>
          <!-- <p id="grand_profit_margin"></p> -->
          <p></p>
        </div>
      </div>
    </div>
    <!-- table section end-->

    <div class="charts-row1">
        <div class="bar-chart-container">
            <h3>Sales</h3>
            <div class="bar-chart-div">
                <div class="chart-top">
                    <span style="color: #3498db"></span>
                    Total Sales by Month
                    <span style="color: rgb(41, 170, 185)"></span>
                    Total Sales by Month (current year & previous year)
                </div>
                <figure id="bar-chart">
                    <div class="chart-row bars">
                        <div class="chart-y-axis one" id="chart-yAxis"></div>
                        <div class="chart-x-axis" id="chart-xAxis"></div>
                    </div>
                </figure>
            </div>
        </div>
        <div class="bar-chart-container">
            <h3>Expenses</h3>
            <div class="bar-chart-div">
                <div class="chart-top">
                    <span style="color: #3498db"></span>
                    Total Expenses by Month
                    <span style="color: rgb(41, 170, 185)"></span>
                    Total Expenses by Month (current year & previous year)
                </div>
                <figure id="bar-chart">
                    <div class="chart-row bars">
                        <div class="chart-y-axis one" id="chart-yAxis2"></div>
                        <div class="chart-x-axis" id="chart-xAxis2"></div>
                    </div>
                </figure>
            </div>
        </div>
        <div class="bar-chart-container">
            <h3>Profit</h3>
            <div class="bar-chart-div">
                <div class="chart-top">
                    <span style="color: #3498db"></span>
                    Total Profit by Month
                    <span style="color: rgb(41, 170, 185)"></span>
                    Total Profit by Month (current year & previous year)
                </div>
                <figure id="bar-chart">
                    <div class="chart-row bars">
                        <div class="chart-y-axis one" id="chart-yAxis3"></div>
                        <div class="chart-x-axis" id="chart-xAxis3"></div>
                    </div>
                </figure>
            </div>
        </div>
        <div class="bar-chart-container">
            <h3>Profit Margin</h3>
            <div class="bar-chart-div">
                <div class="chart-top">
                    <span style="color: #3498db"></span>
                    Total Profit by Month
                    <span style="color: rgb(41, 170, 185)"></span>
                    Total Profit by Month (current year & previous year)
                </div>
                <figure id="bar-chart">
                    <div class="chart-row bars">
                        <div class="chart-y-axis one" id="chart-yAxis4"></div>
                        <div class="chart-x-axis" id="chart-xAxis4"></div>
                    </div>
                </figure>
            </div>
        </div>
        <div class="bar-chart-container">
            <h3>Purchases</h3>
            <div id="expense-container"></div>
        </div>
        <div class="bar-chart-container">
            <h3>Sales</h3>
            <div id="container"></div>
        </div>
    </div>
</section>
<!-- /.content -->
@stop
@section('javascript')
    <script src="{{ asset('js/home.js?v=' . $asset_v) }}"></script>
    <script src="{{ asset('js/payment.js?v=' . $asset_v) }}"></script>
    @includeIf('sales_order.common_js')
    @includeIf('purchase_order.common_js')

    <script type="text/javascript">
        function fetchData({selectedLocation , current , previous}) {
            var currentYear = current[0].slice(0,4);
            var previousYear = previous[0].slice(0,4);

            function getTimeDifference(startDate, endDate) {
                var start = new Date(startDate);
                var end = new Date(endDate);
                
                var timeDifference = end - start;

                const currDate = new Date(endDate).toISOString().split('T')[0];
                const today = new Date().toISOString().split('T')[0];

                if(timeDifference === 86399000 && currDate === today){
                    return 'today';
                }
                
                var millisecondsInDay = 24 * 60 * 60 * 1000;
                
                var daysDifference = Math.floor(timeDifference / millisecondsInDay);
                if (daysDifference + 1 === 1) {
                        return 'yesterday';
                }
                    
                return `previous ${daysDifference + 1} days`;
                }

            var timeDifference = getTimeDifference(current[0], current[1]);

            $('.day').text(timeDifference);

            $.ajax({
                url: '/financial',
                data: {
                    selectedLocation: selectedLocation,
                    current: current,
                    previous: previous,
                },
                success: function(response) {
                    var revenue_sum = 0;
                    var revenue_sum_previous = 0;
                    if (response.sales_sum[0].final_total !== null) {
                       revenue_sum = parseFloat(response.sales_sum[0].final_total);
                    }
                    if (response.sales_sum_previous[0].final_total !== null) {
                       revenue_sum_previous = parseFloat(response.sales_sum_previous[0].final_total);
                    }
                    
                    $('#revenuePercent').text(
                        revenue_sum === 0 ? '- 100.00%' 
                        :
                        (revenue_sum - revenue_sum_previous) > 0 
                        ? 
                        `+ ${(((revenue_sum - revenue_sum_previous)/revenue_sum)*100).toFixed(2)}%`
                        :
                        (revenue_sum - revenue_sum_previous) < 0
                        &&
                        `- ${(((revenue_sum - revenue_sum_previous)/revenue_sum)*100).toFixed(2).slice(1)}%`);

                    var expense_sum = 0;
                    var expense_sum_previous = 0;
                    if (response.expenses_sum[0].final_total !== null) {
                       expense_sum = parseFloat(response.expenses_sum[0].final_total);
                    }
                    if (response.expenses_sum_previous[0].final_total !== null) {
                       expense_sum_previous = parseFloat(response.expenses_sum_previous[0].final_total);
                    }

                    $('#expensePercent').text(
                        expense_sum === 0 ? '- 100.00%'
                        :
                        (expense_sum - expense_sum_previous) > 0 
                        ? 
                        `+ ${(((expense_sum - expense_sum_previous)/expense_sum)*100).toFixed(2)}%` 
                        :
                        (expense_sum - expense_sum_previous) < 0 
                        &&
                        `- ${(((expense_sum - expense_sum_previous)/expense_sum)*100).toFixed(2).slice(1)}%`);

                    $('#revenueAmount').text(response.sales_sum[0].final_total !== null ?`৳ ${parseFloat(response.sales_sum[0].final_total).toFixed(2)}`:'৳ 0.00');
                    $('#grand_revenue').text(`৳ ${response.sales_sum[0].final_total !== null ?(parseFloat(response.sales_sum[0].final_total)/1000).toString().split('.')[0]:0}k`);
                    $('#expenseAmount').text(response.expenses_sum[0].final_total !== null ?`৳ ${parseFloat(response.expenses_sum[0].final_total).toFixed(2)}`:'৳ 0.00');

                    var locations = @json($all_locations);
                    if (response.sells_by_location.length > 0) {
                        $('#col1').empty();
                    }
                    $('#col4').empty();
                    // $('#col5').empty();
                    
                    let table_categories = [];
                    let table_series_data = [];
                    let table_net_profit = [];
                    let table_net_profit_margin = [];

                    $.each(locations, function(index, value) {
                        table_net_profit.push(0);
                    });
                    
                    var sells = response.sells;
                    var sells_previous = response.sells_previous;
                    var expenses = response.expenses;
                    var expenses_previous = response.expenses_previous;
                    
                    var gross_profits = response.gross_profits ;
                    var sell_details = response.sell_details ;
                    var purchase_details = response.purchase_details ;
                    var transaction_totals = response.transaction_totals ;

                    // Initialize or update your graphs here with the response data
                    var maxRageYAxis;

                    //We can dynamically set this ChartData using API as well
                    var salesChartData = [
                        [{
                                year: "Jan",
                                revenue: 0,
                            },
                            {
                                year: "Jan",
                                revenue: 0,
                            },
                        ],
                        [{
                                year: "Feb",
                                revenue: 0,
                            },
                            {
                                year: "Feb",
                                revenue: 0,
                            },
                        ],
                        [{
                                year: "Mar",
                                revenue: 0,
                            },
                            {
                                year: "Mar",
                                revenue: 0,
                            },
                        ],
                        [{
                                year: "Apr",
                                revenue: 0,
                            },
                            {
                                year: "Apr",
                                revenue: 0,
                            },
                        ],
                        [{
                                year: "May",
                                revenue: 0,
                            },
                            {
                                year: "May",
                                revenue: 0,
                            },
                        ],
                        [{
                                year: "Jun",
                                revenue: 0,
                            },
                            {
                                year: "Jun",
                                revenue: 0,
                            },
                        ],
                        [{
                                year: "Jul",
                                revenue: 0,
                            },
                            {
                                year: "Jul",
                                revenue: 0,
                            },
                        ],
                        [{
                                year: "Aug",
                                revenue: 0,
                            },
                            {
                                year: "Aug",
                                revenue: 0,
                            },
                        ],
                        [{
                                year: "Sep",
                                revenue: 0,
                            },
                            {
                                year: "Sep",
                                revenue: 0,
                            },
                        ],
                        [{
                                year: "Oct",
                                revenue: 0,
                            },
                            {
                                year: "Oct",
                                revenue: 0,
                            },
                        ],
                        [{
                                year: "Nov",
                                revenue: 0,
                            },
                            {
                                year: "Nov",
                                revenue: 0,
                            },
                        ],
                        [{
                                year: "Dec",
                                revenue: 0,
                            },
                            {
                                year: "Dec",
                                revenue: 0,
                            },
                        ],
                    ];

                    for (let i = 0; i < salesChartData.length; i++) {
                        salesChartData[i][0].year = salesChartData[i][0].year + ` ${currentYear}`; 
                        salesChartData[i][1].year = salesChartData[i][1].year + ` ${previousYear}`; 
                    }

                    $.each(sells, function (index, sell) {
                        // console.log(sell);
                        var month = sell.month;

                        switch (month) {
                                case 1:
                                    salesChartData[0][0].revenue = parseInt(sell.final_total);
                                    break;
                                case 2:
                                    salesChartData[1][0].revenue = parseInt(sell.final_total);
                                    break;
                                case 3:
                                    salesChartData[2][0].revenue = parseInt(sell.final_total);
                                    break;
                                case 4:
                                    salesChartData[3][0].revenue = parseInt(sell.final_total);
                                    break;
                                case 5:
                                    salesChartData[4][0].revenue = parseInt(sell.final_total);
                                    break;
                                case 6:
                                    salesChartData[5][0].revenue = parseInt(sell.final_total);
                                    break;
                                case 7:
                                    salesChartData[6][0].revenue = parseInt(sell.final_total);
                                    break;
                                case 8:
                                    salesChartData[7][0].revenue = parseInt(sell.final_total);
                                    break;
                                case 9:
                                    salesChartData[8][0].revenue = parseInt(sell.final_total);
                                    break;
                                case 10:
                                    salesChartData[9][0].revenue = parseInt(sell.final_total);
                                    break;
                                case 11:
                                    salesChartData[10][0].revenue = parseInt(sell.final_total);
                                    break;
                                case 12:
                                    salesChartData[11][0].revenue = parseInt(sell.final_total);
                                    break;
                            }
                        });

                    $.each(sells_previous, function (index, sell) {
                        // console.log(sell);
                        var month = sell.month;

                        switch (month) {
                                case 1:
                                    salesChartData[0][1].revenue = parseInt(sell.final_total);
                                    break;
                                case 2:
                                    salesChartData[1][1].revenue = parseInt(sell.final_total);
                                    break;
                                case 3:
                                    salesChartData[2][1].revenue = parseInt(sell.final_total);
                                    break;
                                case 4:
                                    salesChartData[3][1].revenue = parseInt(sell.final_total);
                                    break;
                                case 5:
                                    salesChartData[4][1].revenue = parseInt(sell.final_total);
                                    break;
                                case 6:
                                    salesChartData[5][1].revenue = parseInt(sell.final_total);
                                    break;
                                case 7:
                                    salesChartData[6][1].revenue = parseInt(sell.final_total);
                                    break;
                                case 8:
                                    salesChartData[7][1].revenue = parseInt(sell.final_total);
                                    break;
                                case 9:
                                    salesChartData[8][1].revenue = parseInt(sell.final_total);
                                    break;
                                case 10:
                                    salesChartData[9][1].revenue = parseInt(sell.final_total);
                                    break;
                                case 11:
                                    salesChartData[10][1].revenue = parseInt(sell.final_total);
                                    break;
                                case 12:
                                    salesChartData[11][1].revenue = parseInt(sell.final_total);
                                    break;
                            }
                        });

                    var colors = ["four", "five"];

                    function buildSalesYAxis(salesChartData) {
                        if (salesChartData.length > 0) {
                            //Get & Sort the revenue Array in Desc Order
                            var revArr = [];
                            for (let j = 0; j < salesChartData.length; j++) {
                                for (let i = 0; i < salesChartData[j].length; i++) {
                                    revArr.push(salesChartData[j][i].revenue);
                                }
                            }
                            revArr.sort(function(a, b) {
                                return b - a;
                            });

                            //Setting Max Range here, will be used in height calculation of bar
                            maxRageYAxis = parseFloat(revArr[0]) + 5113;

                            var pointInterval = maxRageYAxis / 4;

                            var initialVal = parseFloat(revArr[0]) + 5113;

                            var dynamicYAxis = "";

                            for (let k = 0; k < 4; k++) {
                                if (initialVal>1000) {   
                                    dynamicYAxis =
                                        dynamicYAxis +
                                        '<div class="segment"><span class="label">' +
                                        String(initialVal / 1000).split(".")[0] +
                                        "k" +
                                        "</span></div>";
                                }else{
                                    dynamicYAxis =
                                    dynamicYAxis +
                                    '<div class="segment"><span class="label">' +
                                    String(initialVal).split(".")[0] +
                                    "</span></div>";
                                }
                                initialVal = initialVal - pointInterval;
                            }

                            //Appending 0 Label for Revenue, as Revenue can't go beyond Zero.
                            dynamicYAxis =
                                dynamicYAxis + '<div class="segment"><span class="label">0k</span></div>';

                            //Rendering Y Axis
                            document.getElementById("chart-yAxis").innerHTML = dynamicYAxis;
                        }
                    }

                    // console.log(salesChartData);

                    function renderSalesChart(salesChartData) {
                        if (salesChartData.length > 0) {
                            var dynamicXAxis = "";

                            for (let i = 0; i < salesChartData.length; i++) {

                                // To better represent % of height
                                // dynamicXAxis = dynamicXAxis + '<div class="two-cols"  title="'+ salesChartData[i][0].year + "&#10Sales: ৳ " +salesChartData[i][0].revenue + "&#10" + salesChartData[i][1].year + "&#10Sales: ৳ " +salesChartData[i][1].revenue + '">';

                                dynamicXAxis = dynamicXAxis + `<div class="two-cols" >`;
                                for (let j = 0; j < salesChartData[i].length; j++) {
                                    var numbersFull = (salesChartData[i][j].revenue / maxRageYAxis) * 80;
                                    dynamicXAxis =
                                        dynamicXAxis +
                                        `<div class="year wrap" title="
                                            ${j === 0 ? salesChartData[i][j].year:salesChartData[i][j-1].year}&#10Sales: ৳ ${j === 0 ? salesChartData[i][j].revenue:salesChartData[i][j-1].revenue} &#10${j === 0 ? salesChartData[i][j+1].year:salesChartData[i][j].year} &#10Sales: ৳ ${j === 0 ?salesChartData[i][j+1].revenue:salesChartData[i][j].revenue}" ><div class="col"><span class="cbar ${colors[j]}" style="height:` +
                                        numbersFull +
                                        '%" >' +
                                        "</span></div>" +
                                        "</div>";
                                }
                                if (i % 2 == 0) {
                                    dynamicXAxis =
                                        dynamicXAxis +
                                        `<span class="label"><a href="">${salesChartData[i][0].year}</a></span>`;
                                } else {
                                    dynamicXAxis =
                                        dynamicXAxis +
                                        `<span class="label marb"><a href="">${salesChartData[i][0].year}</a></span>`;
                                }
                                dynamicXAxis = dynamicXAxis + "</div>";
                                // document.getElementById("two-cols").innerHTML = dynamicXAxis;
                            }
                            document.getElementById("chart-xAxis").innerHTML = dynamicXAxis;
                        }
                    }

                    // Building Y-Axis, Chart along with X-Ais
                    buildSalesYAxis(salesChartData);
                    renderSalesChart(salesChartData);


                    // expense chart
                    
                    //We can dynamically set this ChartData using API as well
                    var expenseChartData = [
                        [{
                                year: "Jan",
                                revenue: 0,
                            },
                            {
                                year: "Jan",
                                revenue: 0,
                            },
                        ],
                        [{
                                year: "Feb",
                                revenue: 0,
                            },
                            {
                                year: "Feb",
                                revenue: 0,
                            },
                        ],
                        [{
                                year: "Mar",
                                revenue: 0,
                            },
                            {
                                year: "Mar",
                                revenue: 0,
                            },
                        ],
                        [{
                                year: "Apr",
                                revenue: 0,
                            },
                            {
                                year: "Apr",
                                revenue: 0,
                            },
                        ],
                        [{
                                year: "May",
                                revenue: 0,
                            },
                            {
                                year: "May",
                                revenue: 0,
                            },
                        ],
                        [{
                                year: "Jun",
                                revenue: 0,
                            },
                            {
                                year: "Jun",
                                revenue: 0,
                            },
                        ],
                        [{
                                year: "Jul",
                                revenue: 0,
                            },
                            {
                                year: "Jul",
                                revenue: 0,
                            },
                        ],
                        [{
                                year: "Aug",
                                revenue: 0,
                            },
                            {
                                year: "Aug",
                                revenue: 0,
                            },
                        ],
                        [{
                                year: "Sep",
                                revenue: 0,
                            },
                            {
                                year: "Sep",
                                revenue: 0,
                            },
                        ],
                        [{
                                year: "Oct",
                                revenue: 0,
                            },
                            {
                                year: "Oct",
                                revenue: 0,
                            },
                        ],
                        [{
                                year: "Nov",
                                revenue: 0,
                            },
                            {
                                year: "Nov",
                                revenue: 0,
                            },
                        ],
                        [{
                                year: "Dec",
                                revenue: 0,
                            },
                            {
                                year: "Dec",
                                revenue: 0,
                            },
                        ],
                    ];

                    

                    for (let i = 0; i < expenseChartData.length; i++) {
                        expenseChartData[i][0].year = expenseChartData[i][0].year + ` ${currentYear}`; 
                        expenseChartData[i][1].year = expenseChartData[i][1].year + ` ${previousYear}`; 
                    }

                    $.each(expenses, function (index, expense) {
                        var month = expense.month;

                    switch (month) {
                            case 01:
                                expenseChartData[0][0].revenue = parseInt(expense.final_total);
                                break;
                            case 02:
                                expenseChartData[1][0].revenue = parseInt(expense.final_total);
                                break;
                            case 03:
                                expenseChartData[2][0].revenue = parseInt(expense.final_total);
                                break;
                            case 04:
                                expenseChartData[3][0].revenue = parseInt(expense.final_total);
                                break;
                            case 05:
                                expenseChartData[4][0].revenue = parseInt(expense.final_total);
                                break;
                            case 06:
                                expenseChartData[5][0].revenue = parseInt(expense.final_total);
                                break;
                            case 07:
                                expenseChartData[6][0].revenue = parseInt(expense.final_total);
                                break;
                            case 08:
                                expenseChartData[7][0].revenue = parseInt(expense.final_total);
                                break;
                            case 09:
                                expenseChartData[8][0].revenue = parseInt(expense.final_total);
                                break;
                            case 10:
                                expenseChartData[9][0].revenue = parseInt(expense.final_total);
                                break;
                            case 11:
                                expenseChartData[10][0].revenue = parseInt(expense.final_total);
                                break;
                            case 12:
                                expenseChartData[11][0].revenue = parseInt(expense.final_total);
                                break;
                        }
                    });

                    $.each(expenses_previous, function (index, expense) {
                        var month = expense.month;

                    switch (month) {
                            case 01:
                                expenseChartData[0][1].revenue = parseInt(expense.final_total);
                                break;
                            case 02:
                                expenseChartData[1][1].revenue = parseInt(expense.final_total);
                                break;
                            case 03:
                                expenseChartData[2][1].revenue = parseInt(expense.final_total);
                                break;
                            case 04:
                                expenseChartData[3][1].revenue = parseInt(expense.final_total);
                                break;
                            case 05:
                                expenseChartData[4][1].revenue = parseInt(expense.final_total);
                                break;
                            case 06:
                                expenseChartData[5][1].revenue = parseInt(expense.final_total);
                                break;
                            case 07:
                                expenseChartData[6][1].revenue = parseInt(expense.final_total);
                                break;
                            case 08:
                                expenseChartData[7][1].revenue = parseInt(expense.final_total);
                                break;
                            case 09:
                                expenseChartData[8][1].revenue = parseInt(expense.final_total);
                                break;
                            case 10:
                                expenseChartData[9][1].revenue = parseInt(expense.final_total);
                                break;
                            case 11:
                                expenseChartData[10][1].revenue = parseInt(expense.final_total);
                                break;
                            case 12:
                                expenseChartData[11][1].revenue = parseInt(expense.final_total);
                                break;
                        }
                    });

                    function buildExpenseYAxis(expenseChartData) {
                        if (expenseChartData.length > 0) {
                            //Get & Sort the revenue Array in Desc Order
                            var revArr = [];
                            for (let j = 0; j < expenseChartData.length; j++) {
                                for (let i = 0; i < expenseChartData[j].length; i++) {
                                    revArr.push(expenseChartData[j][i].revenue);
                                }
                            }
                            revArr.sort(function(a, b) {
                                return b - a;
                            });

                            //Setting Max Range here, will be used in height calculation of bar
                            maxRageYAxis = parseFloat(revArr[0]) + 5113;

                            var pointInterval = maxRageYAxis / 4;

                            var initialVal = parseFloat(revArr[0]) + 5113;

                            var dynamicYAxis = "";

                            for (let k = 0; k < 4; k++) {
                                if (initialVal>1000) {   
                                    dynamicYAxis =
                                        dynamicYAxis +
                                        '<div class="segment"><span class="label">' +
                                        String(initialVal / 1000).split(".")[0] +
                                        "k" +
                                        "</span></div>";
                                }else{
                                    dynamicYAxis =
                                    dynamicYAxis +
                                    '<div class="segment"><span class="label">' +
                                    String(initialVal).split(".")[0] +
                                    "</span></div>";
                                }
                                initialVal = initialVal - pointInterval;
                            }

                            //Appending 0 Label for Revenue, as Revenue can't go beyond Zero.
                            dynamicYAxis =
                                dynamicYAxis + '<div class="segment"><span class="label">0k</span></div>';

                            //Rendering Y Axis
                            document.getElementById("chart-yAxis2").innerHTML = dynamicYAxis;
                        }
                    }

                    function renderExpenseChart(expenseChartData) {
                        if (expenseChartData.length > 0) {
                            var dynamicXAxis = "";

                            for (let i = 0; i < expenseChartData.length; i++) {
                                // To better represent % of height
                                dynamicXAxis = dynamicXAxis + `<div class="two-cols" >`;

                                for (let j = 0; j < expenseChartData[i].length; j++) {
                                    var numbersFull = (expenseChartData[i][j].revenue / maxRageYAxis) * 80;
                                    dynamicXAxis =
                                        dynamicXAxis +
                                        `<div class="year wrap" title="
                                            ${j === 0 ? expenseChartData[i][j].year:expenseChartData[i][j-1].year}&#10Expenses: ৳ ${j === 0 ? expenseChartData[i][j].revenue:expenseChartData[i][j-1].revenue} &#10${j === 0 ? expenseChartData[i][j+1].year:expenseChartData[i][j].year} &#10Expenses: ৳ ${j === 0 ?expenseChartData[i][j+1].revenue:expenseChartData[i][j].revenue}" ><div class="col"><span class="cbar ${colors[j]}" style="height:` +
                                        numbersFull +
                                        '%" >' +
                                        "</span></div>" +
                                        "</div>";
                                }
                                if (i % 2 == 0) {
                                    dynamicXAxis =
                                        dynamicXAxis +
                                        `<span class="label"><a href="">${expenseChartData[i][0].year}</a></span>`;
                                } else {
                                    dynamicXAxis =
                                        dynamicXAxis +
                                        `<span class="label marb"><a href="">${expenseChartData[i][0].year}</a></span>`;
                                }
                                dynamicXAxis = dynamicXAxis + "</div>";
                                // document.getElementById("two-cols").innerHTML = dynamicXAxis;
                            }
                            document.getElementById("chart-xAxis2").innerHTML = dynamicXAxis;
                        }
                    }

                    // Building Y-Axis, Chart along with X-Ais
                    buildExpenseYAxis(expenseChartData);
                    renderExpenseChart(expenseChartData);

                    // profit chart
                    var profitChartData = [
                        [{
                                year: "Jan",
                                revenue: 0,
                            },
                            {
                                year: "Jan",
                                revenue: 0,
                            },
                        ],
                        [{
                                year: "Feb",
                                revenue: 0,
                            },
                            {
                                year: "Feb",
                                revenue: 0,
                            },
                        ],
                        [{
                                year: "Mar",
                                revenue: 0,
                            },
                            {
                                year: "Mar",
                                revenue: 0,
                            },
                        ],
                        [{
                                year: "Apr",
                                revenue: 0,
                            },
                            {
                                year: "Apr",
                                revenue: 0,
                            },
                        ],
                        [{
                                year: "May",
                                revenue: 0,
                            },
                            {
                                year: "May",
                                revenue: 0,
                            },
                        ],
                        [{
                                year: "Jun",
                                revenue: 0,
                            },
                            {
                                year: "Jun",
                                revenue: 0,
                            },
                        ],
                        [{
                                year: "Jul",
                                revenue: 0,
                            },
                            {
                                year: "Jul",
                                revenue: 0,
                            },
                        ],
                        [{
                                year: "Aug",
                                revenue: 0,
                            },
                            {
                                year: "Aug",
                                revenue: 0,
                            },
                        ],
                        [{
                                year: "Sep",
                                revenue: 0,
                            },
                            {
                                year: "Sep",
                                revenue: 0,
                            },
                        ],
                        [{
                                year: "Oct",
                                revenue: 0,
                            },
                            {
                                year: "Oct",
                                revenue: 0,
                            },
                        ],
                        [{
                                year: "Nov",
                                revenue: 0,
                            },
                            {
                                year: "Nov",
                                revenue: 0,
                            },
                        ],
                        [{
                                year: "Dec",
                                revenue: 0,
                            },
                            {
                                year: "Dec",
                                revenue: 0,
                            },
                        ],
                    ];

                    var gross_profits_data = [];
                    var sell_details_data = [];
                    var purchase_details_data = [];
                    var transaction_totals_data = [];

                    var gross_profits_previous_data = [];
                    var sell_details_previous_data = [];
                    var purchase_details_previous_data = [];
                    var transaction_totals_previous_data = [];

                    for (let i = 0; i < 12; i++) {
                        gross_profits_data.push(0);
                        sell_details_data.push(0);
                        purchase_details_data.push(0);
                        transaction_totals_data.push(0);

                        gross_profits_previous_data.push(0);
                        sell_details_previous_data.push(0);
                        purchase_details_previous_data.push(0);
                        transaction_totals_previous_data.push(0);
                    }

                    $.each(gross_profits, function (index, gp) {
                        var month = gp.transaction_date.split(" ")[0].split("-")[1];

                    switch (month) {
                            case "01":
                                    if(gp.gross_profit !== null){
                                        gross_profits_data[0] += parseFloat(gp.gross_profit);
                                    }
                                break;
                            case "02":
                                    if(gp.gross_profit !== null){
                                        gross_profits_data[1] += parseFloat(gp.gross_profit);
                                    }
                                break;
                            case "03":
                                    if(gp.gross_profit !== null){
                                        gross_profits_data[2] += parseFloat(gp.gross_profit);
                                    }
                                break;
                            case "04":
                                    if(gp.gross_profit !== null){
                                        gross_profits_data[3] += parseFloat(gp.gross_profit);
                                    }
                                break;
                            case "05":
                                    if(gp.gross_profit !== null){
                                        gross_profits_data[4] += parseFloat(gp.gross_profit);
                                    }
                                break;
                            case "06":
                                    if(gp.gross_profit !== null){
                                        gross_profits_data[5] += parseFloat(gp.gross_profit);
                                    }
                                break;
                            case "07":
                                    if(gp.gross_profit !== null){
                                        gross_profits_data[6] += parseFloat(gp.gross_profit);
                                    }
                                break;
                            case "08":
                                    if(gp.gross_profit !== null){
                                        gross_profits_data[7] += parseFloat(gp.gross_profit);
                                    }
                                break;
                            case "09":
                                    if(gp.gross_profit !== null){
                                        gross_profits_data[8] += parseFloat(gp.gross_profit);
                                    }
                                break;
                            case "10":
                                    if(gp.gross_profit !== null){
                                        gross_profits_data[9] += parseFloat(gp.gross_profit);
                                    }
                                break;
                            case "11":
                                    if(gp.gross_profit !== null){
                                        gross_profits_data[10] += parseFloat(gp.gross_profit);
                                    }
                                break;
                            case "12":
                                    if(gp.gross_profit !== null){
                                        gross_profits_data[11] += parseFloat(gp.gross_profit);
                                    }
                                break;
                        }
                    });

                    $.each(response.gross_profits_previous, function (index, gp) {
                        var month = gp.transaction_date.split(" ")[0].split("-")[1];

                    switch (month) {
                            case "01":
                                    if(gp.gross_profit !== null){
                                        gross_profits_previous_data[0] += parseFloat(gp.gross_profit);
                                    }
                                break;
                            case "02":
                                    if(gp.gross_profit !== null){
                                        gross_profits_previous_data[1] += parseFloat(gp.gross_profit);
                                    }
                                break;
                            case "03":
                                    if(gp.gross_profit !== null){
                                        gross_profits_previous_data[2] += parseFloat(gp.gross_profit);
                                    }
                                break;
                            case "04":
                                    if(gp.gross_profit !== null){
                                        gross_profits_previous_data[3] += parseFloat(gp.gross_profit);
                                    }
                                break;
                            case "05":
                                    if(gp.gross_profit !== null){
                                        gross_profits_previous_data[4] += parseFloat(gp.gross_profit);
                                    }
                                break;
                            case "06":
                                    if(gp.gross_profit !== null){
                                        gross_profits_previous_data[5] += parseFloat(gp.gross_profit);
                                    }
                                break;
                            case "07":
                                    if(gp.gross_profit !== null){
                                        gross_profits_previous_data[6] += parseFloat(gp.gross_profit);
                                    }
                                break;
                            case "08":
                                    if(gp.gross_profit !== null){
                                        gross_profits_previous_data[7] += parseFloat(gp.gross_profit);
                                    }
                                break;
                            case "09":
                                    if(gp.gross_profit !== null){
                                        gross_profits_previous_data[8] += parseFloat(gp.gross_profit);
                                    }
                                break;
                            case "10":
                                    if(gp.gross_profit !== null){
                                        gross_profits_previous_data[9] += parseFloat(gp.gross_profit);
                                    }
                                break;
                            case "11":
                                    if(gp.gross_profit !== null){
                                        gross_profits_previous_data[10] += parseFloat(gp.gross_profit);
                                    }
                                break;
                            case "12":
                                    if(gp.gross_profit !== null){
                                        gross_profits_previous_data[11] += parseFloat(gp.gross_profit);
                                    }
                                break;
                        }
                    });

                    $.each(sell_details, function (index, sd) {
                        var month = sd.transaction_date.split(" ")[0].split("-")[1];

                    switch (month) {
                            case "01":
                                    if(sd.total_additional_expense !== null){
                                        sell_details_data[0] += parseFloat(sd.total_additional_expense);
                                    }
                                    if(sd.total_shipping_charges !== null){
                                        sell_details_data[0] += parseFloat(sd.total_shipping_charges);
                                    }
                                break;
                            case "02":
                                    if(sd.total_additional_expense !== null){
                                        sell_details_data[1] += parseFloat(sd.total_additional_expense);
                                    }
                                    if(sd.total_shipping_charges !== null){
                                        sell_details_data[1] += parseFloat(sd.total_shipping_charges);
                                    }
                                break;
                            case "03":
                                    if(sd.total_additional_expense !== null){
                                        sell_details_data[2] += parseFloat(sd.total_additional_expense);
                                    }
                                    if(sd.total_shipping_charges !== null){
                                        sell_details_data[2] += parseFloat(sd.total_shipping_charges);
                                    }
                                break;
                            case "04":
                                    if(sd.total_additional_expense !== null){
                                        sell_details_data[3] += parseFloat(sd.total_additional_expense);
                                    }
                                    if(sd.total_shipping_charges !== null){
                                        sell_details_data[3] += parseFloat(sd.total_shipping_charges);
                                    }
                                break;
                            case "05":
                                    if(sd.total_additional_expense !== null){
                                        sell_details_data[4] += parseFloat(sd.total_additional_expense);
                                    }
                                    if(sd.total_shipping_charges !== null){
                                        sell_details_data[4] += parseFloat(sd.total_shipping_charges);
                                    }
                                break;
                            case "06":
                                    if(sd.total_additional_expense !== null){
                                        sell_details_data[5] += parseFloat(sd.total_additional_expense);
                                    }
                                    if(sd.total_shipping_charges !== null){
                                        sell_details_data[5] += parseFloat(sd.total_shipping_charges);
                                    }
                                break;
                            case "07":
                                    if(sd.total_additional_expense !== null){
                                        sell_details_data[6] += parseFloat(sd.total_additional_expense);
                                    }
                                    if(sd.total_shipping_charges !== null){
                                        sell_details_data[6] += parseFloat(sd.total_shipping_charges);
                                    }
                                break;
                            case "08":
                                    if(sd.total_additional_expense !== null){
                                        sell_details_data[7] += parseFloat(sd.total_additional_expense);
                                    }
                                    if(sd.total_shipping_charges !== null){
                                        sell_details_data[7] += parseFloat(sd.total_shipping_charges);
                                    }
                                break;
                            case "09":
                                    if(sd.total_additional_expense !== null){
                                        sell_details_data[8] += parseFloat(sd.total_additional_expense);
                                    }
                                    if(sd.total_shipping_charges !== null){
                                        sell_details_data[8] += parseFloat(sd.total_shipping_charges);
                                    }
                                break;
                            case "10":
                                    if(sd.total_additional_expense !== null){
                                        sell_details_data[9] += parseFloat(sd.total_additional_expense);
                                    }
                                    if(sd.total_shipping_charges !== null){
                                        sell_details_data[9] += parseFloat(sd.total_shipping_charges);
                                    }
                                break;
                            case "11":
                                    if(sd.total_additional_expense !== null){
                                        sell_details_data[10] += parseFloat(sd.total_additional_expense);
                                    }
                                    if(sd.total_shipping_charges !== null){
                                        sell_details_data[10] += parseFloat(sd.total_shipping_charges);
                                    }
                                break;
                            case "12":
                                    if(sd.total_additional_expense !== null){
                                        sell_details_data[11] += parseFloat(sd.total_additional_expense);
                                    }
                                    if(sd.total_shipping_charges !== null){
                                        sell_details_data[11] += parseFloat(sd.total_shipping_charges);
                                    }
                                break;
                        }
                    });
                    
                    $.each(response.sell_details_previous, function (index, sd) {
                        var month = sd.transaction_date.split(" ")[0].split("-")[1];

                    switch (month) {
                            case "01":
                                    if(sd.total_additional_expense !== null){
                                        sell_details_previous_data[0] += parseFloat(sd.total_additional_expense);
                                    }
                                    if(sd.total_shipping_charges !== null){
                                        sell_details_previous_data[0] += parseFloat(sd.total_shipping_charges);
                                    }
                                break;
                            case "02":
                                    if(sd.total_additional_expense !== null){
                                        sell_details_previous_data[1] += parseFloat(sd.total_additional_expense);
                                    }
                                    if(sd.total_shipping_charges !== null){
                                        sell_details_previous_data[1] += parseFloat(sd.total_shipping_charges);
                                    }
                                break;
                            case "03":
                                    if(sd.total_additional_expense !== null){
                                        sell_details_previous_data[2] += parseFloat(sd.total_additional_expense);
                                    }
                                    if(sd.total_shipping_charges !== null){
                                        sell_details_previous_data[2] += parseFloat(sd.total_shipping_charges);
                                    }
                                break;
                            case "04":
                                    if(sd.total_additional_expense !== null){
                                        sell_details_previous_data[3] += parseFloat(sd.total_additional_expense);
                                    }
                                    if(sd.total_shipping_charges !== null){
                                        sell_details_previous_data[3] += parseFloat(sd.total_shipping_charges);
                                    }
                                break;
                            case "05":
                                    if(sd.total_additional_expense !== null){
                                        sell_details_previous_data[4] += parseFloat(sd.total_additional_expense);
                                    }
                                    if(sd.total_shipping_charges !== null){
                                        sell_details_previous_data[4] += parseFloat(sd.total_shipping_charges);
                                    }
                                break;
                            case "06":
                                    if(sd.total_additional_expense !== null){
                                        sell_details_previous_data[5] += parseFloat(sd.total_additional_expense);
                                    }
                                    if(sd.total_shipping_charges !== null){
                                        sell_details_previous_data[5] += parseFloat(sd.total_shipping_charges);
                                    }
                                break;
                            case "07":
                                    if(sd.total_additional_expense !== null){
                                        sell_details_previous_data[6] += parseFloat(sd.total_additional_expense);
                                    }
                                    if(sd.total_shipping_charges !== null){
                                        sell_details_previous_data[6] += parseFloat(sd.total_shipping_charges);
                                    }
                                break;
                            case "08":
                                    if(sd.total_additional_expense !== null){
                                        sell_details_previous_data[7] += parseFloat(sd.total_additional_expense);
                                    }
                                    if(sd.total_shipping_charges !== null){
                                        sell_details_previous_data[7] += parseFloat(sd.total_shipping_charges);
                                    }
                                break;
                            case "09":
                                    if(sd.total_additional_expense !== null){
                                        sell_details_previous_data[8] += parseFloat(sd.total_additional_expense);
                                    }
                                    if(sd.total_shipping_charges !== null){
                                        sell_details_previous_data[8] += parseFloat(sd.total_shipping_charges);
                                    }
                                break;
                            case "10":
                                    if(sd.total_additional_expense !== null){
                                        sell_details_previous_data[9] += parseFloat(sd.total_additional_expense);
                                    }
                                    if(sd.total_shipping_charges !== null){
                                        sell_details_previous_data[9] += parseFloat(sd.total_shipping_charges);
                                    }
                                break;
                            case "11":
                                    if(sd.total_additional_expense !== null){
                                        sell_details_previous_data[10] += parseFloat(sd.total_additional_expense);
                                    }
                                    if(sd.total_shipping_charges !== null){
                                        sell_details_previous_data[10] += parseFloat(sd.total_shipping_charges);
                                    }
                                break;
                            case "12":
                                    if(sd.total_additional_expense !== null){
                                        sell_details_previous_data[11] += parseFloat(sd.total_additional_expense);
                                    }
                                    if(sd.total_shipping_charges !== null){
                                        sell_details_previous_data[11] += parseFloat(sd.total_shipping_charges);
                                    }
                                break;
                        }
                    });

                    $.each(purchase_details, function (index, pd) {
                        var month = pd.transaction_date.split(" ")[0].split("-")[1];

                    switch (month) {
                            case "01":
                                    if(pd.total_additional_expense !== null){
                                        purchase_details_data[0] -= parseFloat(pd.total_additional_expense);
                                    }
                                    if (pd.total_shipping_charges !== null) {
                                        purchase_details_data[0] -= parseFloat(pd.total_shipping_charges);
                                    }
                                break;
                            case "02":
                                    if(pd.total_additional_expense !== null){
                                        purchase_details_data[1] -= parseFloat(pd.total_additional_expense);
                                    }
                                    if (pd.total_shipping_charges !== null) {
                                        purchase_details_data[1] -= parseFloat(pd.total_shipping_charges);
                                    }
                                break;
                            case "03":
                                    if(pd.total_additional_expense !== null){
                                        purchase_details_data[2] -= parseFloat(pd.total_additional_expense);
                                    }
                                    if (pd.total_shipping_charges !== null) {
                                        purchase_details_data[2] -= parseFloat(pd.total_shipping_charges);
                                    }
                                break;
                            case "04":
                                    if(pd.total_additional_expense !== null){
                                        purchase_details_data[3] -= parseFloat(pd.total_additional_expense);
                                    }
                                    if (pd.total_shipping_charges !== null) {
                                        purchase_details_data[3] -= parseFloat(pd.total_shipping_charges);
                                    }
                                break;
                            case "05":
                                    if(pd.total_additional_expense !== null){
                                        purchase_details_data[4] -= parseFloat(pd.total_additional_expense);
                                    }
                                    if (pd.total_shipping_charges !== null) {
                                        purchase_details_data[4] -= parseFloat(pd.total_shipping_charges);
                                    }
                                break;
                            case "06":
                                    if(pd.total_additional_expense !== null){
                                        purchase_details_data[5] -= parseFloat(pd.total_additional_expense);
                                    }
                                    if (pd.total_shipping_charges !== null) {
                                        purchase_details_data[5] -= parseFloat(pd.total_shipping_charges);
                                    }
                                break;
                            case "07":
                                    if(pd.total_additional_expense !== null){
                                        purchase_details_data[6] -= parseFloat(pd.total_additional_expense);
                                    }
                                    if (pd.total_shipping_charges !== null) {
                                        purchase_details_data[6] -= parseFloat(pd.total_shipping_charges);
                                    }
                                break;
                            case "08":
                                    if(pd.total_additional_expense !== null){
                                        purchase_details_data[7] -= parseFloat(pd.total_additional_expense);
                                    }
                                    if (pd.total_shipping_charges !== null) {
                                        purchase_details_data[7] -= parseFloat(pd.total_shipping_charges);
                                    }
                                break;
                            case "09":
                                    if(pd.total_additional_expense !== null){
                                        purchase_details_data[8] -= parseFloat(pd.total_additional_expense);
                                    }
                                    if (pd.total_shipping_charges !== null) {
                                        purchase_details_data[8] -= parseFloat(pd.total_shipping_charges);
                                    }
                                break;
                            case "10":
                                    if(pd.total_additional_expense !== null){
                                        purchase_details_data[9] -= parseFloat(pd.total_additional_expense);
                                    }
                                    if (pd.total_shipping_charges !== null) {
                                        purchase_details_data[9] -= parseFloat(pd.total_shipping_charges);
                                    }
                                break;
                            case "11":
                                    if(pd.total_additional_expense !== null){
                                        purchase_details_data[10] -= parseFloat(pd.total_additional_expense);
                                    }
                                    if (pd.total_shipping_charges !== null) {
                                        purchase_details_data[10] -= parseFloat(pd.total_shipping_charges);
                                    }
                                break;
                            case "12":
                                    if(pd.total_additional_expense !== null){
                                        purchase_details_data[11] -= parseFloat(pd.total_additional_expense);
                                    }
                                    if (pd.total_shipping_charges !== null) {
                                        purchase_details_data[11] -= parseFloat(pd.total_shipping_charges);
                                    }
                                break;
                        }
                    });

                    $.each(response.purchase_details_previous, function (index, pd) {
                        var month = pd.transaction_date.split(" ")[0].split("-")[1];

                    switch (month) {
                            case "01":
                                    if(pd.total_additional_expense !== null){
                                        purchase_details_previous_data[0] -= parseFloat(pd.total_additional_expense);
                                    }
                                    if (pd.total_shipping_charges !== null) {
                                        purchase_details_previous_data[0] -= parseFloat(pd.total_shipping_charges);
                                    }
                                break;
                            case "02":
                                    if(pd.total_additional_expense !== null){
                                        purchase_details_previous_data[1] -= parseFloat(pd.total_additional_expense);
                                    }
                                    if (pd.total_shipping_charges !== null) {
                                        purchase_details_previous_data[1] -= parseFloat(pd.total_shipping_charges);
                                    }
                                break;
                            case "03":
                                    if(pd.total_additional_expense !== null){
                                        purchase_details_previous_data[2] -= parseFloat(pd.total_additional_expense);
                                    }
                                    if (pd.total_shipping_charges !== null) {
                                        purchase_details_previous_data[2] -= parseFloat(pd.total_shipping_charges);
                                    }
                                break;
                            case "04":
                                    if(pd.total_additional_expense !== null){
                                        purchase_details_previous_data[3] -= parseFloat(pd.total_additional_expense);
                                    }
                                    if (pd.total_shipping_charges !== null) {
                                        purchase_details_previous_data[3] -= parseFloat(pd.total_shipping_charges);
                                    }
                                break;
                            case "05":
                                    if(pd.total_additional_expense !== null){
                                        purchase_details_previous_data[4] -= parseFloat(pd.total_additional_expense);
                                    }
                                    if (pd.total_shipping_charges !== null) {
                                        purchase_details_previous_data[4] -= parseFloat(pd.total_shipping_charges);
                                    }
                                break;
                            case "06":
                                    if(pd.total_additional_expense !== null){
                                        purchase_details_previous_data[5] -= parseFloat(pd.total_additional_expense);
                                    }
                                    if (pd.total_shipping_charges !== null) {
                                        purchase_details_previous_data[5] -= parseFloat(pd.total_shipping_charges);
                                    }
                                break;
                            case "07":
                                    if(pd.total_additional_expense !== null){
                                        purchase_details_previous_data[6] -= parseFloat(pd.total_additional_expense);
                                    }
                                    if (pd.total_shipping_charges !== null) {
                                        purchase_details_previous_data[6] -= parseFloat(pd.total_shipping_charges);
                                    }
                                break;
                            case "08":
                                    if(pd.total_additional_expense !== null){
                                        purchase_details_previous_data[7] -= parseFloat(pd.total_additional_expense);
                                    }
                                    if (pd.total_shipping_charges !== null) {
                                        purchase_details_previous_data[7] -= parseFloat(pd.total_shipping_charges);
                                    }
                                break;
                            case "09":
                                    if(pd.total_additional_expense !== null){
                                        purchase_details_previous_data[8] -= parseFloat(pd.total_additional_expense);
                                    }
                                    if (pd.total_shipping_charges !== null) {
                                        purchase_details_previous_data[8] -= parseFloat(pd.total_shipping_charges);
                                    }
                                break;
                            case "10":
                                    if(pd.total_additional_expense !== null){
                                        purchase_details_previous_data[9] -= parseFloat(pd.total_additional_expense);
                                    }
                                    if (pd.total_shipping_charges !== null) {
                                        purchase_details_previous_data[9] -= parseFloat(pd.total_shipping_charges);
                                    }
                                break;
                            case "11":
                                    if(pd.total_additional_expense !== null){
                                        purchase_details_previous_data[10] -= parseFloat(pd.total_additional_expense);
                                    }
                                    if (pd.total_shipping_charges !== null) {
                                        purchase_details_previous_data[10] -= parseFloat(pd.total_shipping_charges);
                                    }
                                break;
                            case "12":
                                    if(pd.total_additional_expense !== null){
                                        purchase_details_previous_data[11] -= parseFloat(pd.total_additional_expense);
                                    }
                                    if (pd.total_shipping_charges !== null) {
                                        purchase_details_previous_data[11] -= parseFloat(pd.total_shipping_charges);
                                    }
                                break;
                        }
                    });

                    $.each(transaction_totals, function (index, tt) {
                        var month = tt.transaction_date.split(" ")[0].split("-")[1];

                    switch (month) {
                            case "01":
                                    if(tt.total_adjustment !== null){
                                        transaction_totals_data[0] -= parseFloat(tt.total_adjustment);
                                    }
                                    if( tt.total_expense !== null){
                                        transaction_totals_data[0] -= parseFloat(tt.total_expense);
                                    }
                                    if( tt.total_purchase_discount !== null){
                                        transaction_totals_data[0] += parseFloat(tt.total_purchase_discount);
                                    }
                                    if( tt.total_recovered !== null){
                                        transaction_totals_data[0] += parseFloat(tt.total_recovered);
                                    }
                                    if( tt.total_reward_amount !== null){
                                        transaction_totals_data[0] -= parseFloat(tt.total_reward_amount);
                                    }
                                    if( tt.total_sell_discount !== null){
                                        transaction_totals_data[0] -= parseFloat(tt.total_sell_discount);
                                    }
                                    if( tt.total_sell_return_discount !== null){
                                        transaction_totals_data[0] += parseFloat(tt.total_sell_return_discount);
                                    }
                                    if( tt.total_sell_round_off !== null){
                                        transaction_totals_data[0] += parseFloat(tt.total_sell_round_off);
                                    }
                                    if( tt.total_transfer_shipping_charges !== null){
                                        transaction_totals_data[0] -= parseFloat(tt.total_transfer_shipping_charges);
                                    }
                                break;
                            case "02":
                                    if(tt.total_adjustment !== null){
                                        transaction_totals_data[1] -= parseFloat(tt.total_adjustment);
                                    }
                                    if( tt.total_expense !== null){
                                        transaction_totals_data[1] -= parseFloat(tt.total_expense);
                                    }
                                    if( tt.total_purchase_discount !== null){
                                        transaction_totals_data[1] += parseFloat(tt.total_purchase_discount);
                                    }
                                    if( tt.total_recovered !== null){
                                        transaction_totals_data[1] += parseFloat(tt.total_recovered);
                                    }
                                    if( tt.total_reward_amount !== null){
                                        transaction_totals_data[1] -= parseFloat(tt.total_reward_amount);
                                    }
                                    if( tt.total_sell_discount !== null){
                                        transaction_totals_data[1] -= parseFloat(tt.total_sell_discount);
                                    }
                                    if( tt.total_sell_return_discount !== null){
                                        transaction_totals_data[1] += parseFloat(tt.total_sell_return_discount);
                                    }
                                    if( tt.total_sell_round_off !== null){
                                        transaction_totals_data[1] += parseFloat(tt.total_sell_round_off);
                                    }
                                    if( tt.total_transfer_shipping_charges !== null){
                                        transaction_totals_data[1] -= parseFloat(tt.total_transfer_shipping_charges);
                                    }
                                break;
                            case "03":
                                if(tt.total_adjustment !== null){
                                        transaction_totals_data[2] -= parseFloat(tt.total_adjustment);
                                    }
                                    if(tt.total_expense !== null){
                                        transaction_totals_data[2] -= parseFloat(tt.total_expense);
                                    }
                                    if(tt.total_purchase_discount !== null){
                                        transaction_totals_data[2] += parseFloat(tt.total_purchase_discount);
                                    }
                                    if(tt.total_recovered !== null){
                                        transaction_totals_data[2] += parseFloat(tt.total_recovered);
                                    }
                                    if(tt.total_reward_amount !== null){
                                        transaction_totals_data[2] -= parseFloat(tt.total_reward_amount);
                                    }
                                    if(tt.total_sell_discount !== null){
                                        transaction_totals_data[2] -= parseFloat(tt.total_sell_discount);
                                    }
                                    if(tt.total_sell_return_discount !== null){
                                        transaction_totals_data[2] += parseFloat(tt.total_sell_return_discount);
                                    }
                                    if(tt.total_sell_round_off !== null){
                                        transaction_totals_data[2] += parseFloat(tt.total_sell_round_off);
                                    }
                                    if(tt.total_transfer_shipping_charges !== null){
                                        transaction_totals_data[2] -= parseFloat(tt.total_transfer_shipping_charges);
                                    }
                                break;
                            case "04":
                                    if(tt.total_adjustment !== null){
                                        transaction_totals_data[3] -= parseFloat(tt.total_adjustment);
                                    }
                                    if( tt.total_expense !== null){
                                        transaction_totals_data[3] -= parseFloat(tt.total_expense);
                                    }
                                    if( tt.total_purchase_discount !== null){
                                        transaction_totals_data[3] += parseFloat(tt.total_purchase_discount);
                                    }
                                    if( tt.total_recovered !== null){
                                        transaction_totals_data[3] += parseFloat(tt.total_recovered);
                                    }
                                    if( tt.total_reward_amount !== null){
                                        transaction_totals_data[3] -= parseFloat(tt.total_reward_amount);
                                    }
                                    if( tt.total_sell_discount !== null){
                                        transaction_totals_data[3] -= parseFloat(tt.total_sell_discount);
                                    }
                                    if( tt.total_sell_return_discount !== null){
                                        transaction_totals_data[3] += parseFloat(tt.total_sell_return_discount);
                                    }
                                    if( tt.total_sell_round_off !== null){
                                        transaction_totals_data[3] += parseFloat(tt.total_sell_round_off);
                                    }
                                    if( tt.total_transfer_shipping_charges !== null){
                                        transaction_totals_data[3] -= parseFloat(tt.total_transfer_shipping_charges);
                                    }
                                break;
                            case "05":
                                    if(tt.total_adjustment !== null){
                                        transaction_totals_data[4] -= parseFloat(tt.total_adjustment);
                                    }
                                    if( tt.total_expense !== null){
                                        transaction_totals_data[4] -= parseFloat(tt.total_expense);
                                    }
                                    if( tt.total_purchase_discount !== null){
                                        transaction_totals_data[4] += parseFloat(tt.total_purchase_discount);
                                    }
                                    if( tt.total_recovered !== null){
                                        transaction_totals_data[4] += parseFloat(tt.total_recovered);
                                    }
                                    if( tt.total_reward_amount !== null){
                                        transaction_totals_data[4] -= parseFloat(tt.total_reward_amount);
                                    }
                                    if( tt.total_sell_discount !== null){
                                        transaction_totals_data[4] -= parseFloat(tt.total_sell_discount);
                                    }
                                    if( tt.total_sell_return_discount !== null){
                                        transaction_totals_data[4] += parseFloat(tt.total_sell_return_discount);
                                    }
                                    if( tt.total_sell_round_off !== null){
                                        transaction_totals_data[4] += parseFloat(tt.total_sell_round_off);
                                    }
                                    if( tt.total_transfer_shipping_charges !== null){
                                        transaction_totals_data[4] -= parseFloat(tt.total_transfer_shipping_charges);
                                    }
                                break;
                            case "06":
                                    if(tt.total_adjustment !== null){
                                        transaction_totals_data[5] -= parseFloat(tt.total_adjustment);
                                    }
                                    if( tt.total_expense !== null){
                                        transaction_totals_data[5] -= parseFloat(tt.total_expense);
                                    }
                                    if( tt.total_purchase_discount !== null){
                                        transaction_totals_data[5] += parseFloat(tt.total_purchase_discount);
                                    }
                                    if( tt.total_recovered !== null){
                                        transaction_totals_data[5] += parseFloat(tt.total_recovered);
                                    }
                                    if( tt.total_reward_amount !== null){
                                        transaction_totals_data[5] -= parseFloat(tt.total_reward_amount);
                                    }
                                    if( tt.total_sell_discount !== null){
                                        transaction_totals_data[5] -= parseFloat(tt.total_sell_discount);
                                    }
                                    if( tt.total_sell_return_discount !== null){
                                        transaction_totals_data[5] += parseFloat(tt.total_sell_return_discount);
                                    }
                                    if( tt.total_sell_round_off !== null){
                                        transaction_totals_data[5] += parseFloat(tt.total_sell_round_off);
                                    }
                                    if( tt.total_transfer_shipping_charges !== null){
                                        transaction_totals_data[5] -= parseFloat(tt.total_transfer_shipping_charges);
                                    }
                                break;
                            case "07":
                                    if(tt.total_adjustment !== null){
                                        transaction_totals_data[6] -= parseFloat(tt.total_adjustment);
                                    }
                                    if( tt.total_expense !== null){
                                        transaction_totals_data[6] -= parseFloat(tt.total_expense);
                                    }
                                    if( tt.total_purchase_discount !== null){
                                        transaction_totals_data[6] += parseFloat(tt.total_purchase_discount);
                                    }
                                    if( tt.total_recovered !== null){
                                        transaction_totals_data[6] += parseFloat(tt.total_recovered);
                                    }
                                    if( tt.total_reward_amount !== null){
                                        transaction_totals_data[6] -= parseFloat(tt.total_reward_amount);
                                    }
                                    if( tt.total_sell_discount !== null){
                                        transaction_totals_data[6] -= parseFloat(tt.total_sell_discount);
                                    }
                                    if( tt.total_sell_return_discount !== null){
                                        transaction_totals_data[6] += parseFloat(tt.total_sell_return_discount);
                                    }
                                    if( tt.total_sell_round_off !== null){
                                        transaction_totals_data[6] += parseFloat(tt.total_sell_round_off);
                                    }
                                    if( tt.total_transfer_shipping_charges !== null){
                                        transaction_totals_data[6] -= parseFloat(tt.total_transfer_shipping_charges);
                                    }
                                break;
                            case "08":
                                    if(tt.total_adjustment !== null){
                                        transaction_totals_data[7] -= parseFloat(tt.total_adjustment);
                                    }
                                    if( tt.total_expense !== null){
                                        transaction_totals_data[7] -= parseFloat(tt.total_expense);
                                    }
                                    if( tt.total_purchase_discount !== null){
                                        transaction_totals_data[7] += parseFloat(tt.total_purchase_discount);
                                    }
                                    if( tt.total_recovered !== null){
                                        transaction_totals_data[7] += parseFloat(tt.total_recovered);
                                    }
                                    if( tt.total_reward_amount !== null){
                                        transaction_totals_data[7] -= parseFloat(tt.total_reward_amount);
                                    }
                                    if( tt.total_sell_discount !== null){
                                        transaction_totals_data[7] -= parseFloat(tt.total_sell_discount);
                                    }
                                    if( tt.total_sell_return_discount !== null){
                                        transaction_totals_data[7] += parseFloat(tt.total_sell_return_discount);
                                    }
                                    if( tt.total_sell_round_off !== null){
                                        transaction_totals_data[7] += parseFloat(tt.total_sell_round_off);
                                    }
                                    if( tt.total_transfer_shipping_charges !== null){
                                        transaction_totals_data[7] -= parseFloat(tt.total_transfer_shipping_charges);
                                    }
                                break;
                            case "09":
                                if(tt.total_adjustment !== null){
                                        transaction_totals_data[8] -= parseFloat(tt.total_adjustment);
                                    }
                                    if(tt.total_expense !== null){
                                        transaction_totals_data[8] -= parseFloat(tt.total_expense);
                                    }
                                    if(tt.total_purchase_discount !== null){
                                        transaction_totals_data[8] += parseFloat(tt.total_purchase_discount);
                                    }
                                    if(tt.total_recovered !== null){
                                        transaction_totals_data[8] += parseFloat(tt.total_recovered);
                                    }
                                    if(tt.total_reward_amount !== null){
                                        transaction_totals_data[8] -= parseFloat(tt.total_reward_amount);
                                    }
                                    if(tt.total_sell_discount !== null){
                                        transaction_totals_data[8] -= parseFloat(tt.total_sell_discount);
                                    }
                                    if(tt.total_sell_return_discount !== null){
                                        transaction_totals_data[8] += parseFloat(tt.total_sell_return_discount);
                                    }
                                    if(tt.total_sell_round_off !== null){
                                        transaction_totals_data[8] += parseFloat(tt.total_sell_round_off);
                                    }
                                    if(tt.total_transfer_shipping_charges !== null){
                                        transaction_totals_data[8] -= parseFloat(tt.total_transfer_shipping_charges);
                                    }
                                break;
                            case "10":
                                    if(tt.total_adjustment !== null){
                                        transaction_totals_data[9] -= parseFloat(tt.total_adjustment);
                                    }
                                    if( tt.total_expense !== null){
                                        transaction_totals_data[9] -= parseFloat(tt.total_expense);
                                    }
                                    if( tt.total_purchase_discount !== null){
                                        transaction_totals_data[9] += parseFloat(tt.total_purchase_discount);
                                    }
                                    if( tt.total_recovered !== null){
                                        transaction_totals_data[9] += parseFloat(tt.total_recovered);
                                    }
                                    if( tt.total_reward_amount !== null){
                                        transaction_totals_data[9] -= parseFloat(tt.total_reward_amount);
                                    }
                                    if( tt.total_sell_discount !== null){
                                        transaction_totals_data[9] -= parseFloat(tt.total_sell_discount);
                                    }
                                    if( tt.total_sell_return_discount !== null){
                                        transaction_totals_data[9] += parseFloat(tt.total_sell_return_discount);
                                    }
                                    if( tt.total_sell_round_off !== null){
                                        transaction_totals_data[9] += parseFloat(tt.total_sell_round_off);
                                    }
                                    if( tt.total_transfer_shipping_charges !== null){
                                        transaction_totals_data[9] -= parseFloat(tt.total_transfer_shipping_charges);
                                    }
                                break;
                            case "11":
                                if(tt.total_adjustment !== null){
                                        transaction_totals_data[10] -= parseFloat(tt.total_adjustment);
                                    }
                                    if(tt.total_expense !== null){
                                        transaction_totals_data[10] -= parseFloat(tt.total_expense);
                                    }
                                    if(tt.total_purchase_discount !== null){
                                        transaction_totals_data[10] += parseFloat(tt.total_purchase_discount);
                                    }
                                    if(tt.total_recovered !== null){
                                        transaction_totals_data[10] += parseFloat(tt.total_recovered);
                                    }
                                    if(tt.total_reward_amount !== null){
                                        transaction_totals_data[10] -= parseFloat(tt.total_reward_amount);
                                    }
                                    if(tt.total_sell_discount !== null){
                                        transaction_totals_data[10] -= parseFloat(tt.total_sell_discount);
                                    }
                                    if(tt.total_sell_return_discount !== null){
                                        transaction_totals_data[10] += parseFloat(tt.total_sell_return_discount);
                                    }
                                    if(tt.total_sell_round_off !== null){
                                        transaction_totals_data[10] += parseFloat(tt.total_sell_round_off);
                                    }
                                    if(tt.total_transfer_shipping_charges !== null){
                                        transaction_totals_data[10] -= parseFloat(tt.total_transfer_shipping_charges);
                                    }
                                break;
                            case "12":
                                    if(tt.total_adjustment !== null){
                                        transaction_totals_data[11] -= parseFloat(tt.total_adjustment);
                                    }
                                    if( tt.total_expense !== null){
                                        transaction_totals_data[11] -= parseFloat(tt.total_expense);
                                    }
                                    if( tt.total_purchase_discount !== null){
                                        transaction_totals_data[11] += parseFloat(tt.total_purchase_discount);
                                    }
                                    if( tt.total_recovered !== null){
                                        transaction_totals_data[11] += parseFloat(tt.total_recovered);
                                    }
                                    if( tt.total_reward_amount !== null){
                                        transaction_totals_data[11] -= parseFloat(tt.total_reward_amount);
                                    }
                                    if( tt.total_sell_discount !== null){
                                        transaction_totals_data[11] -= parseFloat(tt.total_sell_discount);
                                    }
                                    if( tt.total_sell_return_discount !== null){
                                        transaction_totals_data[11] += parseFloat(tt.total_sell_return_discount);
                                    }
                                    if( tt.total_sell_round_off !== null){
                                        transaction_totals_data[11] += parseFloat(tt.total_sell_round_off);
                                    }
                                    if( tt.total_transfer_shipping_charges !== null){
                                        transaction_totals_data[11] -= parseFloat(tt.total_transfer_shipping_charges);
                                    }
                                break;
                        }
                    });

                    $.each(response.transaction_totals_previous, function (index, tt) {
                        var month = tt.transaction_date.split(" ")[0].split("-")[1];

                    switch (month) {
                            case "01":
                                    if(tt.total_adjustment !== null){
                                        transaction_totals_previous_data[0] -= parseFloat(tt.total_adjustment);
                                    }
                                    if( tt.total_expense !== null){
                                        transaction_totals_previous_data[0] -= parseFloat(tt.total_expense);
                                    }
                                    if( tt.total_purchase_discount !== null){
                                        transaction_totals_previous_data[0] += parseFloat(tt.total_purchase_discount);
                                    }
                                    if( tt.total_recovered !== null){
                                        transaction_totals_previous_data[0] += parseFloat(tt.total_recovered);
                                    }
                                    if( tt.total_reward_amount !== null){
                                        transaction_totals_previous_data[0] -= parseFloat(tt.total_reward_amount);
                                    }
                                    if( tt.total_sell_discount !== null){
                                        transaction_totals_previous_data[0] -= parseFloat(tt.total_sell_discount);
                                    }
                                    if( tt.total_sell_return_discount !== null){
                                        transaction_totals_previous_data[0] += parseFloat(tt.total_sell_return_discount);
                                    }
                                    if( tt.total_sell_round_off !== null){
                                        transaction_totals_previous_data[0] += parseFloat(tt.total_sell_round_off);
                                    }
                                    if( tt.total_transfer_shipping_charges !== null){
                                        transaction_totals_previous_data[0] -= parseFloat(tt.total_transfer_shipping_charges);
                                    }
                                break;
                            case "02":
                                    if(tt.total_adjustment !== null){
                                        transaction_totals_previous_data[1] -= parseFloat(tt.total_adjustment);
                                    }
                                    if( tt.total_expense !== null){
                                        transaction_totals_previous_data[1] -= parseFloat(tt.total_expense);
                                    }
                                    if( tt.total_purchase_discount !== null){
                                        transaction_totals_previous_data[1] += parseFloat(tt.total_purchase_discount);
                                    }
                                    if( tt.total_recovered !== null){
                                        transaction_totals_previous_data[1] += parseFloat(tt.total_recovered);
                                    }
                                    if( tt.total_reward_amount !== null){
                                        transaction_totals_previous_data[1] -= parseFloat(tt.total_reward_amount);
                                    }
                                    if( tt.total_sell_discount !== null){
                                        transaction_totals_previous_data[1] -= parseFloat(tt.total_sell_discount);
                                    }
                                    if( tt.total_sell_return_discount !== null){
                                        transaction_totals_previous_data[1] += parseFloat(tt.total_sell_return_discount);
                                    }
                                    if( tt.total_sell_round_off !== null){
                                        transaction_totals_previous_data[1] += parseFloat(tt.total_sell_round_off);
                                    }
                                    if( tt.total_transfer_shipping_charges !== null){
                                        transaction_totals_previous_data[1] -= parseFloat(tt.total_transfer_shipping_charges);
                                    }
                                break;
                            case "03":
                                if(tt.total_adjustment !== null){
                                        transaction_totals_previous_data[2] -= parseFloat(tt.total_adjustment);
                                    }
                                    if(tt.total_expense !== null){
                                        transaction_totals_previous_data[2] -= parseFloat(tt.total_expense);
                                    }
                                    if(tt.total_purchase_discount !== null){
                                        transaction_totals_previous_data[2] += parseFloat(tt.total_purchase_discount);
                                    }
                                    if(tt.total_recovered !== null){
                                        transaction_totals_previous_data[2] += parseFloat(tt.total_recovered);
                                    }
                                    if(tt.total_reward_amount !== null){
                                        transaction_totals_previous_data[2] -= parseFloat(tt.total_reward_amount);
                                    }
                                    if(tt.total_sell_discount !== null){
                                        transaction_totals_previous_data[2] -= parseFloat(tt.total_sell_discount);
                                    }
                                    if(tt.total_sell_return_discount !== null){
                                        transaction_totals_previous_data[2] += parseFloat(tt.total_sell_return_discount);
                                    }
                                    if(tt.total_sell_round_off !== null){
                                        transaction_totals_previous_data[2] += parseFloat(tt.total_sell_round_off);
                                    }
                                    if(tt.total_transfer_shipping_charges !== null){
                                        transaction_totals_previous_data[2] -= parseFloat(tt.total_transfer_shipping_charges);
                                    }
                                break;
                            case "04":
                                    if(tt.total_adjustment !== null){
                                        transaction_totals_previous_data[3] -= parseFloat(tt.total_adjustment);
                                    }
                                    if( tt.total_expense !== null){
                                        transaction_totals_previous_data[3] -= parseFloat(tt.total_expense);
                                    }
                                    if( tt.total_purchase_discount !== null){
                                        transaction_totals_previous_data[3] += parseFloat(tt.total_purchase_discount);
                                    }
                                    if( tt.total_recovered !== null){
                                        transaction_totals_previous_data[3] += parseFloat(tt.total_recovered);
                                    }
                                    if( tt.total_reward_amount !== null){
                                        transaction_totals_previous_data[3] -= parseFloat(tt.total_reward_amount);
                                    }
                                    if( tt.total_sell_discount !== null){
                                        transaction_totals_previous_data[3] -= parseFloat(tt.total_sell_discount);
                                    }
                                    if( tt.total_sell_return_discount !== null){
                                        transaction_totals_previous_data[3] += parseFloat(tt.total_sell_return_discount);
                                    }
                                    if( tt.total_sell_round_off !== null){
                                        transaction_totals_previous_data[3] += parseFloat(tt.total_sell_round_off);
                                    }
                                    if( tt.total_transfer_shipping_charges !== null){
                                        transaction_totals_previous_data[3] -= parseFloat(tt.total_transfer_shipping_charges);
                                    }
                                break;
                            case "05":
                                    if(tt.total_adjustment !== null){
                                        transaction_totals_previous_data[4] -= parseFloat(tt.total_adjustment);
                                    }
                                    if( tt.total_expense !== null){
                                        transaction_totals_previous_data[4] -= parseFloat(tt.total_expense);
                                    }
                                    if( tt.total_purchase_discount !== null){
                                        transaction_totals_previous_data[4] += parseFloat(tt.total_purchase_discount);
                                    }
                                    if( tt.total_recovered !== null){
                                        transaction_totals_previous_data[4] += parseFloat(tt.total_recovered);
                                    }
                                    if( tt.total_reward_amount !== null){
                                        transaction_totals_previous_data[4] -= parseFloat(tt.total_reward_amount);
                                    }
                                    if( tt.total_sell_discount !== null){
                                        transaction_totals_previous_data[4] -= parseFloat(tt.total_sell_discount);
                                    }
                                    if( tt.total_sell_return_discount !== null){
                                        transaction_totals_previous_data[4] += parseFloat(tt.total_sell_return_discount);
                                    }
                                    if( tt.total_sell_round_off !== null){
                                        transaction_totals_previous_data[4] += parseFloat(tt.total_sell_round_off);
                                    }
                                    if( tt.total_transfer_shipping_charges !== null){
                                        transaction_totals_previous_data[4] -= parseFloat(tt.total_transfer_shipping_charges);
                                    }
                                break;
                            case "06":
                                    if(tt.total_adjustment !== null){
                                        transaction_totals_previous_data[5] -= parseFloat(tt.total_adjustment);
                                    }
                                    if( tt.total_expense !== null){
                                        transaction_totals_previous_data[5] -= parseFloat(tt.total_expense);
                                    }
                                    if( tt.total_purchase_discount !== null){
                                        transaction_totals_previous_data[5] += parseFloat(tt.total_purchase_discount);
                                    }
                                    if( tt.total_recovered !== null){
                                        transaction_totals_previous_data[5] += parseFloat(tt.total_recovered);
                                    }
                                    if( tt.total_reward_amount !== null){
                                        transaction_totals_previous_data[5] -= parseFloat(tt.total_reward_amount);
                                    }
                                    if( tt.total_sell_discount !== null){
                                        transaction_totals_previous_data[5] -= parseFloat(tt.total_sell_discount);
                                    }
                                    if( tt.total_sell_return_discount !== null){
                                        transaction_totals_previous_data[5] += parseFloat(tt.total_sell_return_discount);
                                    }
                                    if( tt.total_sell_round_off !== null){
                                        transaction_totals_previous_data[5] += parseFloat(tt.total_sell_round_off);
                                    }
                                    if( tt.total_transfer_shipping_charges !== null){
                                        transaction_totals_previous_data[5] -= parseFloat(tt.total_transfer_shipping_charges);
                                    }
                                break;
                            case "07":
                                    if(tt.total_adjustment !== null){
                                        transaction_totals_previous_data[6] -= parseFloat(tt.total_adjustment);
                                    }
                                    if( tt.total_expense !== null){
                                        transaction_totals_previous_data[6] -= parseFloat(tt.total_expense);
                                    }
                                    if( tt.total_purchase_discount !== null){
                                        transaction_totals_previous_data[6] += parseFloat(tt.total_purchase_discount);
                                    }
                                    if( tt.total_recovered !== null){
                                        transaction_totals_previous_data[6] += parseFloat(tt.total_recovered);
                                    }
                                    if( tt.total_reward_amount !== null){
                                        transaction_totals_previous_data[6] -= parseFloat(tt.total_reward_amount);
                                    }
                                    if( tt.total_sell_discount !== null){
                                        transaction_totals_previous_data[6] -= parseFloat(tt.total_sell_discount);
                                    }
                                    if( tt.total_sell_return_discount !== null){
                                        transaction_totals_previous_data[6] += parseFloat(tt.total_sell_return_discount);
                                    }
                                    if( tt.total_sell_round_off !== null){
                                        transaction_totals_previous_data[6] += parseFloat(tt.total_sell_round_off);
                                    }
                                    if( tt.total_transfer_shipping_charges !== null){
                                        transaction_totals_previous_data[6] -= parseFloat(tt.total_transfer_shipping_charges);
                                    }
                                break;
                            case "08":
                                    if(tt.total_adjustment !== null){
                                        transaction_totals_previous_data[7] -= parseFloat(tt.total_adjustment);
                                    }
                                    if( tt.total_expense !== null){
                                        transaction_totals_previous_data[7] -= parseFloat(tt.total_expense);
                                    }
                                    if( tt.total_purchase_discount !== null){
                                        transaction_totals_previous_data[7] += parseFloat(tt.total_purchase_discount);
                                    }
                                    if( tt.total_recovered !== null){
                                        transaction_totals_previous_data[7] += parseFloat(tt.total_recovered);
                                    }
                                    if( tt.total_reward_amount !== null){
                                        transaction_totals_previous_data[7] -= parseFloat(tt.total_reward_amount);
                                    }
                                    if( tt.total_sell_discount !== null){
                                        transaction_totals_previous_data[7] -= parseFloat(tt.total_sell_discount);
                                    }
                                    if( tt.total_sell_return_discount !== null){
                                        transaction_totals_previous_data[7] += parseFloat(tt.total_sell_return_discount);
                                    }
                                    if( tt.total_sell_round_off !== null){
                                        transaction_totals_previous_data[7] += parseFloat(tt.total_sell_round_off);
                                    }
                                    if( tt.total_transfer_shipping_charges !== null){
                                        transaction_totals_previous_data[7] -= parseFloat(tt.total_transfer_shipping_charges);
                                    }
                                break;
                            case "09":
                                if(tt.total_adjustment !== null){
                                        transaction_totals_previous_data[8] -= parseFloat(tt.total_adjustment);
                                    }
                                    if(tt.total_expense !== null){
                                        transaction_totals_previous_data[8] -= parseFloat(tt.total_expense);
                                    }
                                    if(tt.total_purchase_discount !== null){
                                        transaction_totals_previous_data[8] += parseFloat(tt.total_purchase_discount);
                                    }
                                    if(tt.total_recovered !== null){
                                        transaction_totals_previous_data[8] += parseFloat(tt.total_recovered);
                                    }
                                    if(tt.total_reward_amount !== null){
                                        transaction_totals_previous_data[8] -= parseFloat(tt.total_reward_amount);
                                    }
                                    if(tt.total_sell_discount !== null){
                                        transaction_totals_previous_data[8] -= parseFloat(tt.total_sell_discount);
                                    }
                                    if(tt.total_sell_return_discount !== null){
                                        transaction_totals_previous_data[8] += parseFloat(tt.total_sell_return_discount);
                                    }
                                    if(tt.total_sell_round_off !== null){
                                        transaction_totals_previous_data[8] += parseFloat(tt.total_sell_round_off);
                                    }
                                    if(tt.total_transfer_shipping_charges !== null){
                                        transaction_totals_previous_data[8] -= parseFloat(tt.total_transfer_shipping_charges);
                                    }
                                break;
                            case "10":
                                    if(tt.total_adjustment !== null){
                                        transaction_totals_previous_data[9] -= parseFloat(tt.total_adjustment);
                                    }
                                    if( tt.total_expense !== null){
                                        transaction_totals_previous_data[9] -= parseFloat(tt.total_expense);
                                    }
                                    if( tt.total_purchase_discount !== null){
                                        transaction_totals_previous_data[9] += parseFloat(tt.total_purchase_discount);
                                    }
                                    if( tt.total_recovered !== null){
                                        transaction_totals_previous_data[9] += parseFloat(tt.total_recovered);
                                    }
                                    if( tt.total_reward_amount !== null){
                                        transaction_totals_previous_data[9] -= parseFloat(tt.total_reward_amount);
                                    }
                                    if( tt.total_sell_discount !== null){
                                        transaction_totals_previous_data[9] -= parseFloat(tt.total_sell_discount);
                                    }
                                    if( tt.total_sell_return_discount !== null){
                                        transaction_totals_previous_data[9] += parseFloat(tt.total_sell_return_discount);
                                    }
                                    if( tt.total_sell_round_off !== null){
                                        transaction_totals_previous_data[9] += parseFloat(tt.total_sell_round_off);
                                    }
                                    if( tt.total_transfer_shipping_charges !== null){
                                        transaction_totals_previous_data[9] -= parseFloat(tt.total_transfer_shipping_charges);
                                    }
                                break;
                            case "11":
                                if(tt.total_adjustment !== null){
                                        transaction_totals_previous_data[10] -= parseFloat(tt.total_adjustment);
                                    }
                                    if(tt.total_expense !== null){
                                        transaction_totals_previous_data[10] -= parseFloat(tt.total_expense);
                                    }
                                    if(tt.total_purchase_discount !== null){
                                        transaction_totals_previous_data[10] += parseFloat(tt.total_purchase_discount);
                                    }
                                    if(tt.total_recovered !== null){
                                        transaction_totals_previous_data[10] += parseFloat(tt.total_recovered);
                                    }
                                    if(tt.total_reward_amount !== null){
                                        transaction_totals_previous_data[10] -= parseFloat(tt.total_reward_amount);
                                    }
                                    if(tt.total_sell_discount !== null){
                                        transaction_totals_previous_data[10] -= parseFloat(tt.total_sell_discount);
                                    }
                                    if(tt.total_sell_return_discount !== null){
                                        transaction_totals_previous_data[10] += parseFloat(tt.total_sell_return_discount);
                                    }
                                    if(tt.total_sell_round_off !== null){
                                        transaction_totals_previous_data[10] += parseFloat(tt.total_sell_round_off);
                                    }
                                    if(tt.total_transfer_shipping_charges !== null){
                                        transaction_totals_previous_data[10] -= parseFloat(tt.total_transfer_shipping_charges);
                                    }
                                break;
                            case "12":
                                    if(tt.total_adjustment !== null){
                                        transaction_totals_previous_data[11] -= parseFloat(tt.total_adjustment);
                                    }
                                    if( tt.total_expense !== null){
                                        transaction_totals_previous_data[11] -= parseFloat(tt.total_expense);
                                    }
                                    if( tt.total_purchase_discount !== null){
                                        transaction_totals_previous_data[11] += parseFloat(tt.total_purchase_discount);
                                    }
                                    if( tt.total_recovered !== null){
                                        transaction_totals_previous_data[11] += parseFloat(tt.total_recovered);
                                    }
                                    if( tt.total_reward_amount !== null){
                                        transaction_totals_previous_data[11] -= parseFloat(tt.total_reward_amount);
                                    }
                                    if( tt.total_sell_discount !== null){
                                        transaction_totals_previous_data[11] -= parseFloat(tt.total_sell_discount);
                                    }
                                    if( tt.total_sell_return_discount !== null){
                                        transaction_totals_previous_data[11] += parseFloat(tt.total_sell_return_discount);
                                    }
                                    if( tt.total_sell_round_off !== null){
                                        transaction_totals_previous_data[11] += parseFloat(tt.total_sell_round_off);
                                    }
                                    if( tt.total_transfer_shipping_charges !== null){
                                        transaction_totals_previous_data[11] -= parseFloat(tt.total_transfer_shipping_charges);
                                    }
                                break;
                        }
                    });

                    var total_profit = 0;
                    var total_profit_previous = 0;

                    for (let i = 0; i < profitChartData.length; i++) {
                        total_profit += (gross_profits_data[i] + sell_details_data[i] + purchase_details_data[i] + transaction_totals_data[i]);

                        total_profit_previous += (gross_profits_previous_data[i] + sell_details_previous_data[i] + purchase_details_previous_data[i] + transaction_totals_previous_data[i]);

                        profitChartData[i][0].revenue = (gross_profits_data[i] + sell_details_data[i] + purchase_details_data[i] + transaction_totals_data[i]);

                        profitChartData[i][1].revenue = (gross_profits_previous_data[i] + sell_details_previous_data[i] + purchase_details_previous_data[i] + transaction_totals_previous_data[i]);
                    }     
                    
                    $('#profitAmount').text(`৳ ${parseFloat(total_profit).toFixed(2)}`);
                    $('#grand_profit').text(`৳ ${parseFloat(total_profit).toFixed(2)}`);

                    $('#profitPercent').text(
                        total_profit === 0 ? '- 100.00%'
                        :
                        (total_profit - total_profit_previous) > 0 
                        ? 
                        `+ ${(((total_profit - total_profit_previous)/total_profit)*100).toFixed(2)}%` 
                        :
                        (total_profit - total_profit_previous) < 0 
                        &&  
                        `- ${(((total_profit - total_profit_previous)/total_profit)*100).toFixed(2).slice(1)}%`);

                    for (let i = 0; i < profitChartData.length; i++) {
                        profitChartData[i][0].year = profitChartData[i][0].year + ` ${currentYear}`; 
                        profitChartData[i][1].year = profitChartData[i][1].year + ` ${previousYear}`; 
                    }

                    function buildProfitYAxis(profitChartData) {
                        if (profitChartData.length > 0) {
                            //Get & Sort the revenue Array in Desc Order
                            var revArr = [];
                            for (let j = 0; j < profitChartData.length; j++) {
                                for (let i = 0; i < profitChartData[j].length; i++) {
                                    revArr.push(profitChartData[j][i].revenue);
                                }
                            }
                            revArr.sort(function(a, b) {
                                return b - a;
                            });

                            //Setting Max Range here, will be used in height calculation of bar
                            maxRageYAxis = parseFloat(revArr[0]) + 5113;

                            var pointInterval = maxRageYAxis / 4;

                            var initialVal = parseFloat(revArr[0]) + 5113;

                            var dynamicYAxis = "";

                            for (let k = 0; k < 4; k++) {
                                if (initialVal>1000) {   
                                    dynamicYAxis =
                                        dynamicYAxis +
                                        '<div class="segment"><span class="label">' +
                                        String(initialVal / 1000).split(".")[0] +
                                        "k" +
                                        "</span></div>";
                                }else{
                                    dynamicYAxis =
                                    dynamicYAxis +
                                    '<div class="segment"><span class="label">' +
                                    String(initialVal).split(".")[0] +
                                    "</span></div>";
                                }
                                initialVal = initialVal - pointInterval;
                            }

                            //Appending 0 Label for Revenue, as Revenue can't go beyond Zero.
                            dynamicYAxis =
                                dynamicYAxis + '<div class="segment"><span class="label">0k</span></div>';

                            //Rendering Y Axis
                            document.getElementById("chart-yAxis3").innerHTML = dynamicYAxis;
                        }
                    }

                    function renderProfitChart(profitChartData) {
                        if (profitChartData.length > 0) {
                            var dynamicXAxis = "";

                            for (let i = 0; i < profitChartData.length; i++) {
                                // To better represent % of height
                                dynamicXAxis = dynamicXAxis + `<div class="two-cols" >`;

                                for (let j = 0; j < profitChartData[i].length; j++) {
                                    var numbersFull = (profitChartData[i][j].revenue / maxRageYAxis) * 80;
                                    dynamicXAxis =
                                        dynamicXAxis +
                                        `<div class="year wrap" title="
                                            ${j === 0 ? profitChartData[i][j].year:profitChartData[i][j-1].year}&#10Profit: ৳ ${j === 0 ? profitChartData[i][j].revenue:profitChartData[i][j-1].revenue} &#10${j === 0 ? profitChartData[i][j+1].year:profitChartData[i][j].year} &#10Profit: ৳ ${j === 0 ?profitChartData[i][j+1].revenue:profitChartData[i][j].revenue}" ><div class="col"><span class="cbar ${colors[j]}" style="height:` +
                                        numbersFull +
                                        '%" >' +
                                        "</span></div>" +
                                        "</div>";
                                }
                                if (i % 2 == 0) {
                                    dynamicXAxis =
                                        dynamicXAxis +
                                        `<span class="label"><a href="">${profitChartData[i][0].year}</a></span>`;
                                } else {
                                    dynamicXAxis =
                                        dynamicXAxis +
                                        `<span class="label marb"><a href="">${profitChartData[i][0].year}</a></span>`;
                                }
                                dynamicXAxis = dynamicXAxis + "</div>";
                                // document.getElementById("two-cols").innerHTML = dynamicXAxis;
                            }
                            document.getElementById("chart-xAxis3").innerHTML = dynamicXAxis;
                        }
                    }

                    // Building Y-Axis, Chart along with X-Ais
                    buildProfitYAxis(profitChartData);
                    renderProfitChart(profitChartData);

                    //profit margin data
                    var profitMarginChartData = [
                        [{
                                year: "Jan",
                                revenue: 0,
                            },
                            {
                                year: "Jan",
                                revenue: 0,
                            },
                        ],
                        [{
                                year: "Feb",
                                revenue: 0,
                            },
                            {
                                year: "Feb",
                                revenue: 0,
                            },
                        ],
                        [{
                                year: "Mar",
                                revenue: 0,
                            },
                            {
                                year: "Mar",
                                revenue: 0,
                            },
                        ],
                        [{
                                year: "Apr",
                                revenue: 0,
                            },
                            {
                                year: "Apr",
                                revenue: 0,
                            },
                        ],
                        [{
                                year: "May",
                                revenue: 0,
                            },
                            {
                                year: "May",
                                revenue: 0,
                            },
                        ],
                        [{
                                year: "Jun",
                                revenue: 0,
                            },
                            {
                                year: "Jun",
                                revenue: 0,
                            },
                        ],
                        [{
                                year: "Jul",
                                revenue: 0,
                            },
                            {
                                year: "Jul",
                                revenue: 0,
                            },
                        ],
                        [{
                                year: "Aug",
                                revenue: 0,
                            },
                            {
                                year: "Aug",
                                revenue: 0,
                            },
                        ],
                        [{
                                year: "Sep",
                                revenue: 0,
                            },
                            {
                                year: "Sep",
                                revenue: 0,
                            },
                        ],
                        [{
                                year: "Oct",
                                revenue: 0,
                            },
                            {
                                year: "Oct",
                                revenue: 0,
                            },
                        ],
                        [{
                                year: "Nov",
                                revenue: 0,
                            },
                            {
                                year: "Nov",
                                revenue: 0,
                            },
                        ],
                        [{
                                year: "Dec",
                                revenue: 0,
                            },
                            {
                                year: "Dec",
                                revenue: 0,
                            },
                        ],
                    ];

                    function safeDivision(numerator, denominator) {
                        if (denominator === 0) {
                            return 0;
                        }

                        return numerator / denominator;
                    }
  
                    for (let i = 0; i < profitMarginChartData.length; i++) {
                        profitMarginChartData[i][0].revenue = (safeDivision(profitChartData[i][0].revenue,salesChartData[i][0].revenue) * 100).toFixed(2);
                        
                        profitMarginChartData[i][1].revenue = (safeDivision(profitChartData[i][1].revenue,salesChartData[i][1].revenue) * 100).toFixed(2);
                    }  

                    var total_profit_margin = 0.00;
                    var total_profit_margin_previous = 0.00;

                    if (response.sales_sum[0].final_total !== null && parseFloat(total_profit)!== 0) {
                        total_profit_margin = (parseFloat(total_profit)/parseFloat(response.sales_sum[0].final_total))*100;
                    }

                    if (response.sales_sum_previous[0].final_total !== null && parseFloat(total_profit_previous)!== 0) {
                        total_profit_margin_previous = (parseFloat(total_profit_previous)/parseFloat(response.sales_sum_previous[0].final_total))*100;
                    }
                    
                    $('#profitMarginAmount').text(`${parseFloat(total_profit_margin).toFixed(2)}%`);
                    
                    $('#profitMarginPercent').text(
                        (total_profit_margin - total_profit_margin_previous) > 0 
                        ? 
                        `+ ${(total_profit_margin - total_profit_margin_previous).toFixed(2)}%` : 
                        (total_profit_margin - total_profit_margin_previous) < 0 
                        && 
                        `- ${(total_profit_margin - total_profit_margin_previous).toFixed(2).slice(1)}%`);

                    // $('#grand_profit_margin').text(`${parseFloat(total_profit_margin).toFixed(2)}%`);                    

                    for (let i = 0; i < profitMarginChartData.length; i++) {
                        profitMarginChartData[i][0].year = profitMarginChartData[i][0].year + ` ${currentYear}`; 
                        profitMarginChartData[i][1].year = profitMarginChartData[i][1].year + ` ${previousYear}`; 
                    }

                    function buildProfitMarginYAxis(profitMarginChartData) {
                        if (profitMarginChartData.length > 0) {
                            //Get & Sort the revenue Array in Desc Order
                            var revArr = [];
                            for (let j = 0; j < profitMarginChartData.length; j++) {
                                for (let i = 0; i < profitMarginChartData[j].length; i++) {
                                    revArr.push(profitMarginChartData[j][i].revenue);
                                }
                            }
                            revArr.sort(function(a, b) {
                                return b - a;
                            });

                            //Setting Max Range here, will be used in height calculation of bar
                            maxRageYAxis = parseFloat(revArr[0]) + 100;

                            var pointInterval = maxRageYAxis / 4;

                            var initialVal = parseFloat(revArr[0]) + 100;

                            var dynamicYAxis = "";

                            for (let k = 0; k < 4; k++) {
                                if (initialVal>1000) {   
                                    dynamicYAxis =
                                        dynamicYAxis +
                                        '<div class="segment"><span class="label">' +
                                        String(initialVal / 1000).split(".")[0] +
                                        "k" +
                                        "</span></div>";
                                }else{
                                    dynamicYAxis =
                                    dynamicYAxis +
                                    '<div class="segment"><span class="label">' +
                                    String(initialVal).split(".")[0] +
                                    "</span></div>";
                                }
                                initialVal = initialVal - pointInterval;
                            }

                            //Appending 0 Label for Revenue, as Revenue can't go beyond Zero.
                            dynamicYAxis =
                                dynamicYAxis + '<div class="segment"><span class="label">0k</span></div>';

                            //Rendering Y Axis
                            document.getElementById("chart-yAxis4").innerHTML = dynamicYAxis;
                        }
                    }

                    function renderProfitMarginChart(profitMarginChartData) {
                        if (profitMarginChartData.length > 0) {
                            var dynamicXAxis = "";

                            for (let i = 0; i < profitMarginChartData.length; i++) {
                                // To better represent % of height
                                dynamicXAxis = dynamicXAxis + `<div class="two-cols" >`;

                                for (let j = 0; j < profitMarginChartData[i].length; j++) {
                                    var numbersFull = (profitMarginChartData[i][j].revenue / maxRageYAxis) * 80;
                                    dynamicXAxis =
                                        dynamicXAxis +
                                        `<div class="year wrap" title="
                                            ${j === 0 ? profitChartData[i][j].year:profitChartData[i][j-1].year}&#10Profit Margin: ${j === 0 ? profitChartData[i][j].revenue:profitChartData[i][j-1].revenue}% &#10${j === 0 ? profitChartData[i][j+1].year:profitChartData[i][j].year} &#10Profit Margin: ${j === 0 ?profitChartData[i][j+1].revenue:profitChartData[i][j].revenue}%" ><div class="col"><span class="cbar ${colors[j]}" style="height:` +
                                        numbersFull +
                                        '%" >' +
                                        "</span></div>" +
                                        "</div>";
                                }
                                if (i % 2 == 0) {
                                    dynamicXAxis =
                                        dynamicXAxis +
                                        `<span class="label"><a href="">${profitMarginChartData[i][0].year}</a></span>`;
                                } else {
                                    dynamicXAxis =
                                        dynamicXAxis +
                                        `<span class="label marb"><a href="">${profitMarginChartData[i][0].year}</a></span>`;
                                }
                                dynamicXAxis = dynamicXAxis + "</div>";
                                // document.getElementById("two-cols").innerHTML = dynamicXAxis;
                            }
                            document.getElementById("chart-xAxis4").innerHTML = dynamicXAxis;
                        }
                    }

                    // Building Y-Axis, Chart along with X-Ais
                    buildProfitMarginYAxis(profitMarginChartData);
                    renderProfitMarginChart(profitMarginChartData);

                    //table
                    $.each(response.sells_by_location, function(i, obj) {
                        $('#col1').append(`<p>${locations[obj.location_id]}</p>`);

                        table_categories.push(`${(parseInt(obj.total_amount)/1000).toString().split('.')[0]}k`);
                        table_series_data.push([parseInt(obj.total_amount)]);
                    });

                    $.each(response.gross_profits_location, function(i, obj) {
                        var keys = Object.keys(obj);

                        $.each(keys, function(index, key) {
                            table_net_profit[i] += parseFloat(obj[key]);
                        });
                    });
                    
                    $.each(response.sell_details_location, function(i, obj) {
                        var keys = Object.keys(obj);

                        $.each(keys, function(index, key) {
                            table_net_profit[i] += parseFloat(obj[key]);
                        });
                    });
                    
                    $.each(response.purchase_details_location, function(i, obj) {
                        var keys = Object.keys(obj);

                        $.each(keys, function(index, key) {
                            table_net_profit[i] += parseFloat(obj[key]);
                        });
                    });

                    $.each(response.transaction_totals_location, function(i, obj) {
                       table_net_profit[i] -= parseFloat(obj.total_adjustment);
                       table_net_profit[i] -= parseFloat(obj.total_expense);
                       table_net_profit[i] += parseFloat(obj.total_purchase_discount);
                       table_net_profit[i] += parseFloat(obj.total_recovered);
                       table_net_profit[i] -= parseFloat(obj.total_reward_amount);
                       table_net_profit[i] -= parseFloat(obj.total_sell_discount);
                       table_net_profit[i] += parseFloat(obj.total_sell_return_discount);
                       table_net_profit[i] += parseFloat(obj.total_sell_round_off);
                       table_net_profit[i] -= parseFloat(obj.total_transfer_shipping_charges)
                    });

                    $.each(table_net_profit, function(i, obj) {
                        if(parseFloat(obj) !== 0 ){
                            $('#col4').append(`<p>৳ ${obj}</p>`);
                            table_net_profit_margin.push(parseFloat(safeDivision(obj,table_series_data[i][0])).toFixed(2));
                        }
                    });

                    // $.each(table_net_profit_margin, function(i, obj) {
                    //     console.log(obj);
                    //     $('#col5').append(`<p>৳ ${obj}</p>`);
                    // });

                    // var height;
                    // if (response.sells_by_location.length==1) {
                    //     height = 70;
                    //     //  $('.table-container').css('height', '4rem');
                    // }else{
                    //     height = 161.812;
                    //     //  $('.table-container').css('height', '7rem');
                    // }

                    var chartHeight;
                    if (response.sells_by_location.length==1) {
                        chartHeight = ($('#col1').height()) + 30;
                    }else{
                        chartHeight = ($('#col1').height())+ 25;
                    }

                    // var chartHeight = ($('#col1').height()) + 15;

                    Highcharts.chart({
                        chart: {
                            renderTo: 'table-container',
                            type: 'bar',
                            width: '300',
                            height: `${chartHeight}`,
                            backgroundColor: "rgba(0,0,0,0)",
                            spacingLeft: 0,
                            spacingRight: 0,
                        },
                        title: {
                            text: '',
                            align: 'left'
                        },
                        xAxis: {
                            categories:  response.value == 'all' && table_categories.length == 0 ?  [0,0] : response.value != 'all' && table_categories.length == 0 ? [0] : table_categories,
                            title: {
                                text: null
                            },
                            gridLineWidth: 0,
                            lineWidth: 0
                        },
                        yAxis: {
                            min: 0,
                            title: {
                                text: '',
                                align: 'high'
                            },
                            gridLineWidth: 2,
                            labels: {
                                y: 20
                            }
                        },
                        tooltip: {
                            valueSuffix: '৳'
                        },
                        plotOptions: {
                            bar: {
                                dataLabels: {
                                    enabled: false
                                },
                                // groupPadding: 1,
                                pointWidth: 18
                            }
                        },
                        legend: {
                            layout: 'vertical',
                            align: 'right',
                            verticalAlign: 'top',
                            x: -40,
                            y: 80,
                            floating: true,
                            borderWidth: 1,
                            backgroundColor:
                                Highcharts.defaultOptions.legend.backgroundColor || '#FFFFFF',
                            shadow: true
                        },
                        credits: {
                            enabled: false
                        },
                        series: [{
                            showInLegend: false, 
                            name: 'Sales',
                            // pointWidth: 20,
                            data: response.value == 'all' && table_series_data.length == 0 ?  [[0],[0]] : response.value != 'all' && table_series_data.length == 0 ? [[0]] : table_series_data 
                        }],
                        exporting: {
                            enabled: false
                        }

                    });
                    
                    //revenue pie chart
                    var total_revenues_data = [];

                    for (let i = 0; i < 12; i++) {
                        switch (i) {
                            case 0:
                                total_revenues_data.push({
                                name: 'Jan',
                                y: salesChartData[i][0].revenue
                                });
                                break;
                            case 1:
                                total_revenues_data.push({
                                name: 'Feb',
                                y: salesChartData[i][0].revenue
                                });
                                break;
                            case 2:
                                total_revenues_data.push({
                                name: 'Mar',
                                y: salesChartData[i][0].revenue
                                });
                                break;
                            case 3:
                                total_revenues_data.push({
                                name: 'Apr',
                                y: salesChartData[i][0].revenue
                                });
                                break;
                            case 4:
                                total_revenues_data.push({
                                name: 'May',
                                y: salesChartData[i][0].revenue
                                });
                                break;
                            case 5:
                                total_revenues_data.push({
                                name: 'Jun',
                                y: salesChartData[i][0].revenue
                                });
                                break;
                            case 6:
                                total_revenues_data.push({
                                name: 'Jul',
                                y: salesChartData[i][0].revenue
                                });
                                break;
                            case 7:
                                total_revenues_data.push({
                                name: 'Aug',
                                y: salesChartData[i][0].revenue
                                });
                                break;
                            case 8:
                                total_revenues_data.push({
                                name: 'Sep',
                                y: salesChartData[i][0].revenue
                                });
                                break;
                            case 9:
                                total_revenues_data.push({
                                name: 'Oct',
                                y: salesChartData[i][0].revenue
                                });
                                break;
                            case 10:
                                total_revenues_data.push({
                                name: 'Nov',
                                y: salesChartData[i][0].revenue
                                });
                                break;
                            case 11:
                                total_revenues_data.push({
                                name: 'Dec',
                                y: salesChartData[i][0].revenue
                                });
                                break;
                        }
                    }

                    Highcharts.chart({
                        chart: {
                            renderTo: 'container',
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie',
                            // width: '500',
                            shadow: {
                                color: 'rgba(136, 152, 170, 0.15)',
                                offsetX: 1,
                                offsetY: 1,
                                opacity: '0.1',
                                width: 6
                            }
                        },
                        credits: {
                            enabled: false
                        },
                        title: {
                            text: '',
                            align: 'center'
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                        },
                        accessibility: {
                            point: {
                                valueSuffix: '%'
                            }
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                size: '80%',
                                dataLabels: {
                                    enabled: true,
                                    distance: -50,
                                    format: '{point.percentage:.1f} %'
                                },
                                showInLegend: true,
                            }
                        },
                        legend: {
                            layout: 'vertical',
                            align: 'right',
                            verticalAlign: 'middle',
                            itemMarginTop: 2,
                            itemMarginBottom: 2
                            },
                        series: [{
                            name: 'Sales',
                            colorByPoint: true,
                            data: total_revenues_data
                        }]
                    }); 

                    // expenses pie chart
                    var total_purchases_data = [];

                    for (let i = 0; i < 12; i++) {
                        switch (i) {
                            case 0:
                                total_purchases_data.push({
                                name: 'Jan',
                                y: 0
                                });
                                break;
                            case 1:
                                total_purchases_data.push({
                                name: 'Feb',
                                y: 0
                                });
                                break;
                            case 2:
                                total_purchases_data.push({
                                name: 'Mar',
                                y: 0
                                });
                                break;
                            case 3:
                                total_purchases_data.push({
                                name: 'Apr',
                                y: 0
                                });
                                break;
                            case 4:
                                total_purchases_data.push({
                                name: 'May',
                                y: 0
                                });
                                break;
                            case 5:
                                total_purchases_data.push({
                                name: 'Jun',
                                y: 0
                                });
                                break;
                            case 6:
                                total_purchases_data.push({
                                name: 'Jul',
                                y: 0
                                });
                                break;
                            case 7:
                                total_purchases_data.push({
                                name: 'Aug',
                                y: 0
                                });
                                break;
                            case 8:
                                total_purchases_data.push({
                                name: 'Sep',
                                y: 0
                                });
                                break;
                            case 9:
                                total_purchases_data.push({
                                name: 'Oct',
                                y: 0
                                });
                                break;
                            case 10:
                                total_purchases_data.push({
                                name: 'Nov',
                                y: 0
                                });
                                break;
                            case 11:
                                total_purchases_data.push({
                                name: 'Dec',
                                y: 0
                                });
                                break;
                        }
                    }

                    $.each(response.purchases, function (index, purchase) {
                        var month = purchase.month;
                        total_purchases_data[month-1].y = parseFloat(purchase.final_total);
                    })

                    Highcharts.chart({
                        chart: {
                            renderTo: 'expense-container',
                            plotBackgroundColor: null,
                            plotBorderWidth: null,
                            plotShadow: false,
                            type: 'pie',
                            // width: '500',
                            shadow: {
                                color: 'rgba(136, 152, 170, 0.15)',
                                offsetX: 1,
                                offsetY: 1,
                                opacity: '0.1',
                                width: 6
                            }
                        },
                        credits: {
                            enabled: false
                        },
                        title: {
                            text: '',
                            align: 'left'
                        },
                        tooltip: {
                            pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
                        },
                        accessibility: {
                            point: {
                                valueSuffix: '%'
                            }
                        },
                        plotOptions: {
                            pie: {
                                allowPointSelect: true,
                                cursor: 'pointer',
                                size: '80%',
                                dataLabels: {
                                    enabled: true,
                                    distance: -50,
                                    format: '{point.percentage:.1f} %'
                                },
                                showInLegend: true,
                            }
                        },
                        legend: {
                            layout: 'vertical',
                            align: 'right',
                            verticalAlign: 'middle',
                            itemMarginTop: 2,
                            itemMarginBottom: 2
                            },
                        series: [{
                            name: 'Purchases',
                            colorByPoint: true,
                            data: total_purchases_data
                        }]
                    }); 

                },
                error: function(xhr, status, error) {
                    console.error('Error:', error);
                }
            });
        }

        var currentYear = new Date().getFullYear();
        var selectedDate = {current: [`${currentYear}-01-01 00:00:00` , `${currentYear}-12-31 23:59:59`], previous: [`${currentYear-1}-01-01 00:00:00`, `${currentYear-1}-12-31 23:59:59`]};
        var selectedLocation = 'all';

        $(document).ready( function(){
            fetchData({selectedLocation:selectedLocation, current: selectedDate.current, previous: selectedDate.previous});
        })
        $('#dashboard_location2').on('change', function() {
            var selectedValue = $(this).val();
            selectedLocation = selectedValue;
            fetchData({selectedLocation:selectedLocation, current: selectedDate.current, previous: selectedDate.previous});
        });

        if ($('#dashboard_date_filter2').length == 1) {
            $('#dashboard_date_filter2').daterangepicker(dateRangeSettings, function(start, end) {
                var current_start = (start._d).toISOString().slice(0, 19).replace('T', ' ');
                var current_end = (end._d).toISOString().slice(0, 19).replace('T', ' ');
                
                var previous_start = current_start.replace(/^(\d{4})/, `${parseInt((start._d).toISOString().slice(0, 19).replace('T', ' ').slice(0,4))-1}`);
                var previous_end = current_end.replace(/^(\d{4})/, `${parseInt((start._d).toISOString().slice(0, 19).replace('T', ' ').slice(0,4))-1}`);
                
                selectedDate = {current: [current_start, current_end], previous: [previous_start,previous_end]};
                
                $('#dashboard_date_filter2 span').html(
                    start.format(moment_date_format) + ' ~ ' + end.format(moment_date_format)
                    );
                    
                    currentYear = (start._d).toISOString().slice(0, 19).replace('T', ' ').slice(0,4);
                    previousYear = (parseInt((start._d).toISOString().slice(0, 19).replace('T', ' ').slice(0,4))-1).toString();
                    fetchData({selectedLocation:selectedLocation, current: selectedDate.current, previous: selectedDate.previous});
            });
        }
    </script>

    <script>
        $(document).ready(function() {
            var toggle = false;
            $("#toggler").click(function() {
                toggle = !toggle;
                if (toggle == true) {
                    $("#toggler-div").removeClass("hidden-buttons");
                    $("#toggler-div").addClass("show-buttons");
                } else {
                    $("#toggler-div").removeClass("show-buttons");
                    $("#toggler-div").addClass("hidden-buttons");
                }

            });
        });
        
    </script>


    <!-- <script type="text/javascript">
        const dataPrev = {
            2020: [
                ['kr', 9],
                ['jp', 12],
                ['au', 8],
                ['de', 17],
                ['ru', 19],
                ['cn', 26],
                ['gb', 27],
                ['us', 46]
            ],
            2016: [
                ['kr', 13],
                ['jp', 7],
                ['au', 8],
                ['de', 11],
                ['ru', 20],
                ['cn', 38],
                ['gb', 29],
                ['us', 47]
            ],
            2012: [
                ['kr', 13],
                ['jp', 9],
                ['au', 14],
                ['de', 16],
                ['ru', 24],
                ['cn', 48],
                ['gb', 19],
                ['us', 36]
            ],
            2008: [
                ['kr', 9],
                ['jp', 17],
                ['au', 18],
                ['de', 13],
                ['ru', 29],
                ['cn', 33],
                ['gb', 9],
                ['us', 37]
            ],
            2004: [
                ['kr', 8],
                ['jp', 5],
                ['au', 16],
                ['de', 13],
                ['ru', 32],
                ['cn', 28],
                ['gb', 11],
                ['us', 37]
            ],
            2000: [
                ['kr', 7],
                ['jp', 3],
                ['au', 9],
                ['de', 20],
                ['ru', 26],
                ['cn', 16],
                ['gb', 1],
                ['us', 44]
            ]
        };

        const data = {
            2020: [
                ['kr', 6],
                ['jp', 27],
                ['au', 17],
                ['de', 10],
                ['ru', 20],
                ['cn', 38],
                ['gb', 22],
                ['us', 39]
            ],
            2016: [
                ['kr', 9],
                ['jp', 12],
                ['au', 8],
                ['de', 17],
                ['ru', 19],
                ['cn', 26],
                ['gb', 27],
                ['us', 46]
            ],
            2012: [
                ['kr', 13],
                ['jp', 7],
                ['au', 8],
                ['de', 11],
                ['ru', 20],
                ['cn', 38],
                ['gb', 29],
                ['us', 47]
            ],
            2008: [
                ['kr', 13],
                ['jp', 9],
                ['au', 14],
                ['de', 16],
                ['ru', 24],
                ['cn', 48],
                ['gb', 19],
                ['us', 36]
            ],
            2004: [
                ['kr', 9],
                ['jp', 17],
                ['au', 18],
                ['de', 13],
                ['ru', 29],
                ['cn', 33],
                ['gb', 9],
                ['us', 37]
            ],
            2000: [
                ['kr', 8],
                ['jp', 5],
                ['au', 16],
                ['de', 13],
                ['ru', 32],
                ['cn', 28],
                ['gb', 11],
                ['us', 37]
            ]
        };

        const countries = {
            kr: {
                name: 'South Korea',
                color: '#FE2371'
            },
            jp: {
                name: 'Japan',
                color: '#544FC5'
            },
            au: {
                name: 'Australia',
                color: '#2CAFFE'
            },
            de: {
                name: 'Germany',
                color: '#FE6A35'
            },
            ru: {
                name: 'Russia',
                color: '#6B8ABC'
            },
            cn: {
                name: 'China',
                color: '#1C74BD'
            },
            gb: {
                name: 'Great Britain',
                color: '#00A6A6'
            },
            us: {
                name: 'United States',
                color: '#D568FB'
            }
        };

        // Add upper case country code
        for (const [key, value] of Object.entries(countries)) {
            value.ucCode = key.toUpperCase();
        }


        const getData = data => data.map(point => ({
            name: point[0],
            y: point[1],
            color: countries[point[0]].color
        }));

        const chart = Highcharts.chart({
            chart: {
                renderTo: 'container-bar',
                type: 'column'
            },
            // Custom option for templates
            countries,
            title: {
                text: 'Summer Olympics 2020 - Top 5 countries by Gold medals',
                align: 'left'
            },
            subtitle: {
                text: 'Comparing to results from Summer Olympics 2016 - Source: <a ' +
                    'href="https://olympics.com/en/olympic-games/tokyo-2020/medals"' +
                    'target="_blank">Olympics</a>',
                align: 'left'
            },
            plotOptions: {
                series: {
                    grouping: false,
                    borderWidth: 0
                }
            },
            legend: {
                enabled: false
            },
            tooltip: {
                shared: true,
                headerFormat: '<span style="font-size: 15px">' +
                    '{series.chart.options.countries.(point.key).name}' +
                    '</span><br/>',
                pointFormat: '<span style="color:{point.color}">\u25CF</span> ' +
                    '{series.name}: <b>{point.y} medals</b><br/>'
            },
            xAxis: {
                type: 'category',
                accessibility: {
                    description: 'Countries'
                },
                max: 4,
                labels: {
                    useHTML: true,
                    animate: true,
                    format: '{chart.options.countries.(value).ucCode}<br>' +
                        '<span class="f32">' +
                        '<span style="display:inline-block;height:32px;vertical-align:text-top;" ' +
                        'class="flag {value}"></span></span>',
                    style: {
                        textAlign: 'center'
                    }
                }
            },
            yAxis: [{
                title: {
                    text: 'Gold medals'
                },
                showFirstLabel: false
            }],
            series: [{
                color: 'rgba(158, 159, 163, 0.5)',
                pointPlacement: -0.2,
                linkedTo: 'main',
                data: dataPrev[2020].slice(),
                name: '2016'
            }, {
                name: '2020',
                id: 'main',
                dataSorting: {
                    enabled: true,
                    matchByName: true
                },
                dataLabels: [{
                    enabled: true,
                    inside: true,
                    style: {
                        fontSize: '16px'
                    }
                }],
                data: getData(data[2020]).slice()
            }],
            exporting: {
                allowHTML: true
            }
        });

        const locations = [
            {
                city: 'Tokyo',
                year: 2020
            }, {
                city: 'Rio',
                year: 2016
            }, {
                city: 'London',
                year: 2012
            }, {
                city: 'Beijing',
                year: 2008
            }, {
                city: 'Athens',
                year: 2004
            }, {
                city: 'Sydney',
                year: 2000
            }
        ];

        locations.forEach(location => {
            const btn = document.getElementById(location.year);

            btn.addEventListener('click', () => {

                document.querySelectorAll('.buttons button.active')
                    .forEach(active => {
                        active.className = '';
                    });
                btn.className = 'active';

                chart.update({
                    title: {
                        text: 'Summer Olympics ' + location.year +
                            ' - Top 5 countries by Gold medals'
                    },
                    subtitle: {
                        text: 'Comparing to results from Summer Olympics ' +
                            (location.year - 4) + ' - Source: <a href="https://olympics.com/en/olympic-games/' +
                            (location.city.toLowerCase()) + '-' + (location.year) + '/medals" target="_blank">Olympics</a>'
                    },
                    series: [{
                        name: location.year - 4,
                        data: dataPrev[location.year].slice()
                    }, {
                        name: location.year,
                        data: getData(data[location.year]).slice()
                    }]
                }, true, false, {
                    duration: 800
                });
            });
        });

    </script> -->

@endsection