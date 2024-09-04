<?php

namespace App\Http\Controllers;

use App\Account;
use App\Business;
use App\BusinessLocation;
use App\Contact;
use App\CustomerGroup;
use App\InvoiceScheme;
use App\Media;
use App\Product;
use App\SellingPriceGroup;
use App\TaxRate;
use App\Transaction;
use App\TransactionSellLine;
use App\TypesOfService;
use App\User;
use App\Utils\BusinessUtil;
use App\Utils\ContactUtil;
use App\Utils\ModuleUtil;
use App\Utils\ProductUtil;
use App\Utils\TransactionUtil;
use App\Warranty;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Spatie\Activitylog\Models\Activity;
use Yajra\DataTables\Facades\DataTables;
use Carbon\Carbon;

class FinancialController extends Controller
{
    /**
     * All Utils instance.
     */
    protected $contactUtil;

    protected $businessUtil;

    protected $transactionUtil;

    protected $productUtil;

    /**
     * Constructor
     *
     * @param  ProductUtils  $product
     * @return void
     */
    public function __construct(ContactUtil $contactUtil, BusinessUtil $businessUtil, TransactionUtil $transactionUtil, ModuleUtil $moduleUtil, ProductUtil $productUtil)
    {
        $this->contactUtil = $contactUtil;
        $this->businessUtil = $businessUtil;
        $this->transactionUtil = $transactionUtil;
        $this->moduleUtil = $moduleUtil;
        $this->productUtil = $productUtil;

        $this->dummyPaymentLine = ['method' => '', 'amount' => 0, 'note' => '', 'card_transaction_number' => '', 'card_number' => '', 'card_type' => '', 'card_holder_name' => '', 'card_month' => '', 'card_year' => '', 'card_security' => '', 'cheque_number' => '', 'bank_account_number' => '',
            'is_return' => 0, 'transaction_no' => '', ];

        $this->shipping_status_colors = [
            'ordered' => 'bg-yellow',
            'packed' => 'bg-info',
            'shipped' => 'bg-navy',
            'delivered' => 'bg-green',
            'cancelled' => 'bg-red',
        ];
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $business_id = request()->session()->get('user.business_id');
        $all_locations = BusinessLocation::forDropdown($business_id)->toArray();
        
        if (request()->ajax()) {
            $selectedLocation = $request->input('selectedLocation');
            $current = $request->input('current');
            $previous = $request->input('previous');

            //sells chart data
            $sells_query = Transaction::where('business_id',$business_id)
                ->where('type','sell')->where('status', 'final')
                ->whereBetween('transaction_date', $current)
                ->select(DB::raw('SUM(final_total) as final_total'), DB::raw('MONTH(transaction_date) as month'))
                ->groupBy(DB::raw('MONTH(transaction_date)'));

            $sells_query_previous = Transaction::where('business_id',$business_id)
                ->where('type','sell')->where('status', 'final')
                ->whereBetween('transaction_date', $previous)
                ->select(DB::raw('SUM(final_total) as final_total'), DB::raw('MONTH(transaction_date) as month'))
                ->groupBy(DB::raw('MONTH(transaction_date)'));

            $sells_query_sum = Transaction::where('business_id',$business_id)
                ->where('type','sell')->where('status', 'final')
                ->whereBetween('transaction_date', $current)
                ->select(DB::raw('SUM(final_total) as final_total'));

            $sells_query_sum_previous = Transaction::where('business_id',$business_id)
                ->where('type','sell')->where('status', 'final')
                ->whereBetween('transaction_date', $previous)
                ->select(DB::raw('SUM(final_total) as final_total'));

            $sales_by_location_query = Transaction::whereNotNull('location_id')->where('business_id',$business_id)
                    ->where('type','sell')->where('status', 'final')
                    ->whereBetween('transaction_date', $current)
                    ->groupBy('location_id')
                    ->select('location_id',DB::raw('SUM(final_total) as total_amount'));

            if ($selectedLocation != 'all') {
                $sells_query->where('location_id', $selectedLocation);
                $sells_query_previous->where('location_id', $selectedLocation);
                $sells_query_sum->where('location_id', $selectedLocation);
                $sells_query_sum_previous->where('location_id', $selectedLocation);
                $sales_by_location_query->where('location_id', $selectedLocation);
            }

            $sales_sum = $sells_query_sum->get();
            $sales_sum_previous = $sells_query_sum_previous->get();
            $sells = $sells_query->get();
            $sells_previous = $sells_query_previous->get();
            $sells_by_location = $sales_by_location_query->get();

            // purchase data

            $purchases_query =Transaction::where('business_id', $business_id)
                ->where('type','purchase')
                ->whereBetween('transaction_date', $current)
                ->select(DB::raw('SUM(final_total) as final_total'), DB::raw('MONTH(transaction_date) as month'))
                ->groupBy(DB::raw('MONTH(transaction_date)'));

            if ($selectedLocation != 'all') {
                $purchases_query->where('location_id', $selectedLocation);
            }

            $purchases = $purchases_query->get();

            // expense chart data
            $expenses_query = Transaction::where('business_id',$business_id)
                        ->whereIn('type', ['expense', 'expense_refund'])
                        ->where('status', 'final')
                        ->whereBetween('transaction_date', $current)
                        ->select(DB::raw("SUM( IF(transactions.type='expense_refund', -1 * final_total, final_total) ) as final_total"), DB::raw('MONTH(transaction_date) as month'))
                        ->groupBy(DB::raw('MONTH(transaction_date)'));

            $expenses_query_previous = Transaction::where('business_id',$business_id)
                        ->whereIn('type', ['expense', 'expense_refund'])
                        ->where('status', 'final')
                        ->whereBetween('transaction_date', $previous)
                        ->select(DB::raw("SUM( IF(transactions.type='expense_refund', -1 * final_total, final_total) ) as final_total"), DB::raw('MONTH(transaction_date) as month'))
                        ->groupBy(DB::raw('MONTH(transaction_date)'));

            $expense_query_sum = Transaction::where('business_id',$business_id)
                ->whereIn('type', ['expense', 'expense_refund'])
                ->where('status', 'final')
                ->whereBetween('transaction_date', $current)
                ->select(DB::raw("SUM( IF(transactions.type='expense_refund', -1 * final_total, final_total) ) as final_total"));

            $expense_query_sum_previous = Transaction::where('business_id',$business_id)
                ->whereIn('type', ['expense', 'expense_refund'])
                ->where('status', 'final')
                ->whereBetween('transaction_date', $previous)
                ->select(DB::raw("SUM( IF(transactions.type='expense_refund', -1 * final_total, final_total) ) as final_total"));

            $expenses_query_location = Transaction::whereNotNull('location_id')->where('business_id',$business_id)
                        ->whereIn('type', ['expense', 'expense_refund'])
                        ->where('status', 'final')
                        ->whereBetween('transaction_date', $current)
                        ->select(DB::raw("( IF(transactions.type='expense_refund', -1 * final_total, final_total) ) as final_total"),'transaction_date')
                        ->groupBy('location_id')
                        ->select( DB::raw('SUM(final_total) as total_amount'));

            if ($selectedLocation != 'all') {
                $expenses_query->where('location_id', $selectedLocation);
                $expenses_query_previous->where('location_id', $selectedLocation);
                $expense_query_sum->where('location_id', $selectedLocation);
                $expense_query_sum_previous->where('location_id', $selectedLocation);
                $expenses_query_location->where('location_id', $selectedLocation);
            }

            $expenses_sum = $expense_query_sum->get();
            $expenses_sum_previous = $expense_query_sum_previous->get();
            $expenses = $expenses_query->get();
            $expenses_previous = $expenses_query_previous->get();
            $expenses_by_location = $expenses_query_location->get();

            // profit data
            //working with gross profit here
            $gross_profit_query = TransactionSellLine::join('transactions as sale', 'transaction_sell_lines.transaction_id', '=', 'sale.id')
                ->leftjoin('transaction_sell_lines_purchase_lines as TSPL', 'transaction_sell_lines.id', '=', 'TSPL.sell_line_id')
                ->leftjoin(
                    'purchase_lines as PL',
                    'TSPL.purchase_line_id',
                    '=',
                    'PL.id'
                )
                ->where('sale.type', 'sell')
                ->where('sale.status', 'final')
                ->join('products as P', 'transaction_sell_lines.product_id', '=', 'P.id')
                ->where('sale.business_id', $business_id)
                ->where('transaction_sell_lines.children_type', '!=', 'combo')
                ->whereBetween('transaction_date', $current);

            $gross_profit_query_previous = TransactionSellLine::join('transactions as sale', 'transaction_sell_lines.transaction_id', '=', 'sale.id')
                ->leftjoin('transaction_sell_lines_purchase_lines as TSPL', 'transaction_sell_lines.id', '=', 'TSPL.sell_line_id')
                ->leftjoin(
                    'purchase_lines as PL',
                    'TSPL.purchase_line_id',
                    '=',
                    'PL.id'
                )
                ->where('sale.type', 'sell')
                ->where('sale.status', 'final')
                ->join('products as P', 'transaction_sell_lines.product_id', '=', 'P.id')
                ->where('sale.business_id', $business_id)
                ->where('transaction_sell_lines.children_type', '!=', 'combo')
                ->whereBetween('transaction_date', $previous);

            $gross_profit_query_location = TransactionSellLine::join('transactions as sale', 'transaction_sell_lines.transaction_id', '=', 'sale.id')
                ->leftjoin('transaction_sell_lines_purchase_lines as TSPL', 'transaction_sell_lines.id', '=', 'TSPL.sell_line_id')
                ->leftjoin(
                    'purchase_lines as PL',
                    'TSPL.purchase_line_id',
                    '=',
                    'PL.id'
                )
                ->whereNotNull('location_id')
                ->where('sale.type', 'sell')
                ->where('sale.status', 'final')
                ->join('products as P', 'transaction_sell_lines.product_id', '=', 'P.id')
                ->where('sale.business_id', $business_id)
                ->where('transaction_sell_lines.children_type', '!=', 'combo')
                ->whereBetween('transaction_date', $current);    

            if ($selectedLocation != 'all') {
                $gross_profit_query->where('location_id', $selectedLocation);
                $gross_profit_query_previous->where('location_id', $selectedLocation);
                $gross_profit_query_location->where('location_id', $selectedLocation);
            }

            //If type combo: find childrens, sale price parent - get PP of childrens
            $gross_profit_query->select(DB::raw('(IF (TSPL.id IS NULL AND P.type="combo", ( 
                SELECT ((tspl2.quantity - tspl2.qty_returned) * (tsl.unit_price_inc_tax - pl2.purchase_price_inc_tax)) AS total
                    FROM transaction_sell_lines AS tsl
                        JOIN transaction_sell_lines_purchase_lines AS tspl2
                    ON tsl.id=tspl2.sell_line_id 
                    JOIN purchase_lines AS pl2 
                    ON tspl2.purchase_line_id = pl2.id 
                    WHERE tsl.parent_sell_line_id = transaction_sell_lines.id), IF(P.enable_stock=0,(transaction_sell_lines.quantity - transaction_sell_lines.quantity_returned) * transaction_sell_lines.unit_price_inc_tax,   
                    (TSPL.quantity - TSPL.qty_returned) * (transaction_sell_lines.unit_price_inc_tax - PL.purchase_price_inc_tax)) )) AS gross_profit'),'transaction_date'
            );

            $gross_profit_query_previous->select(DB::raw('(IF (TSPL.id IS NULL AND P.type="combo", ( 
                SELECT ((tspl2.quantity - tspl2.qty_returned) * (tsl.unit_price_inc_tax - pl2.purchase_price_inc_tax)) AS total
                    FROM transaction_sell_lines AS tsl
                        JOIN transaction_sell_lines_purchase_lines AS tspl2
                    ON tsl.id=tspl2.sell_line_id 
                    JOIN purchase_lines AS pl2 
                    ON tspl2.purchase_line_id = pl2.id 
                    WHERE tsl.parent_sell_line_id = transaction_sell_lines.id), IF(P.enable_stock=0,(transaction_sell_lines.quantity - transaction_sell_lines.quantity_returned) * transaction_sell_lines.unit_price_inc_tax,   
                    (TSPL.quantity - TSPL.qty_returned) * (transaction_sell_lines.unit_price_inc_tax - PL.purchase_price_inc_tax)) )) AS gross_profit'),'transaction_date'
            );

            $gross_profit_query_location->groupBy('location_id')
                                        ->select(DB::raw('SUM(IF (TSPL.id IS NULL AND P.type="combo", ( 
                                            SELECT Sum((tspl2.quantity - tspl2.qty_returned) * (tsl.unit_price_inc_tax - pl2.purchase_price_inc_tax)) AS total
                                                FROM transaction_sell_lines AS tsl
                                                    JOIN transaction_sell_lines_purchase_lines AS tspl2
                                                ON tsl.id=tspl2.sell_line_id 
                                                JOIN purchase_lines AS pl2 
                                                ON tspl2.purchase_line_id = pl2.id 
                                                WHERE tsl.parent_sell_line_id = transaction_sell_lines.id), IF(P.enable_stock=0,(transaction_sell_lines.quantity - transaction_sell_lines.quantity_returned) * transaction_sell_lines.unit_price_inc_tax,   
                                                (TSPL.quantity - TSPL.qty_returned) * (transaction_sell_lines.unit_price_inc_tax - PL.purchase_price_inc_tax)) )) AS gross_profit')
                                        );

            $gross_profits = $gross_profit_query->get();
            $gross_profits_previous = $gross_profit_query_previous->get();
            $gross_profits_location = $gross_profit_query_location->get();

            // working with sells details here
            $sell_details_query = Transaction::where('transactions.business_id', $business_id)
                        ->where('transactions.type', 'sell')
                        ->where('transactions.status', 'final')
                        ->whereBetween('transaction_date', $current)
                        ->select(
                            DB::raw('(shipping_charges) as total_shipping_charges'),
                            DB::raw('(additional_expense_value_1 + additional_expense_value_2 + additional_expense_value_3 + additional_expense_value_4) as total_additional_expense'),
                            'transaction_date'
                        );

            $sell_details_query_previous = Transaction::where('transactions.business_id', $business_id)
                        ->where('transactions.type', 'sell')
                        ->where('transactions.status', 'final')
                        ->whereBetween('transaction_date', $previous)
                        ->select(
                            DB::raw('(shipping_charges) as total_shipping_charges'),
                            DB::raw('(additional_expense_value_1 + additional_expense_value_2 + additional_expense_value_3 + additional_expense_value_4) as total_additional_expense'),
                            'transaction_date'
                        );

            $sell_details_query_location = Transaction::whereNotNull('location_id')->where('transactions.business_id', $business_id)
                        ->where('transactions.type', 'sell')
                        ->where('transactions.status', 'final')
                        ->whereBetween('transaction_date', $current)
                        ->groupBy('location_id')
                        ->select(
                            DB::raw('SUM(shipping_charges) as total_shipping_charges'),
                            DB::raw('SUM(additional_expense_value_1 + additional_expense_value_2 + additional_expense_value_3 + additional_expense_value_4) as total_additional_expense')
                        );

            if ($selectedLocation != 'all') {
                $sell_details_query->where('location_id', $selectedLocation);
                $sell_details_query_previous->where('location_id', $selectedLocation);
                $sell_details_query_location->where('location_id', $selectedLocation);
            }

            $sell_details = $sell_details_query->get();
            $sell_details_previous = $sell_details_query_previous->get();
            $sell_details_location = $sell_details_query_location->get();

            //working with purchase details here
            $purchase_details_query = Transaction::where('business_id', $business_id)
                            ->where('type', 'purchase')
                            ->whereBetween('transaction_date', $current)
                            ->select(
                                DB::raw('(shipping_charges) as total_shipping_charges'),
                                DB::raw('(additional_expense_value_1 + additional_expense_value_2 + additional_expense_value_3 + additional_expense_value_4) as total_additional_expense'),
                                'transaction_date'
                            );

            $purchase_details_query_previous = Transaction::where('business_id', $business_id)
                            ->where('type', 'purchase')
                            ->whereBetween('transaction_date', $previous)
                            ->select(
                                DB::raw('(shipping_charges) as total_shipping_charges'),
                                DB::raw('(additional_expense_value_1 + additional_expense_value_2 + additional_expense_value_3 + additional_expense_value_4) as total_additional_expense'),
                                'transaction_date'
                            );

            $purchase_details_query_location = Transaction::whereNotNull('location_id')->where('business_id', $business_id)
                            ->where('type', 'purchase')
                            ->whereBetween('transaction_date', $current)
                            ->groupBy('location_id')
                            ->select(
                                DB::raw('SUM(shipping_charges) as total_shipping_charges'),
                                DB::raw('SUM(additional_expense_value_1 + additional_expense_value_2 + additional_expense_value_3 + additional_expense_value_4) as total_additional_expense')
                            );

            if ($selectedLocation != 'all') {
                $purchase_details_query->where('location_id', $selectedLocation);
                $purchase_details_query_previous->where('location_id', $selectedLocation);
                $purchase_details_query_location->where('location_id', $selectedLocation);
            }

            $purchase_details = $purchase_details_query->get();
            $purchase_details_previous = $purchase_details_query_previous->get();
            $purchase_details_location = $purchase_details_query_location->get();

            // working with transactions details here
            $transaction_totals_query = Transaction::where('business_id', $business_id)
                                        ->whereBetween('transaction_date', $current);
            $transaction_totals_query_previous = Transaction::where('business_id', $business_id)
                                        ->whereBetween('transaction_date', $previous);

            $transaction_totals_query_location = Transaction::whereNotNull('location_id')->where('business_id', $business_id)->whereBetween('transaction_date', $current);

            if ($selectedLocation != 'all') {
                $transaction_totals_query->where('location_id', $selectedLocation);
                $transaction_totals_query_previous->where('location_id', $selectedLocation);
                $transaction_totals_query_location->where('location_id', $selectedLocation);
            }

            $transaction_types = [
                'purchase_return', 'sell_return', 'expense', 'stock_adjustment', 'sell_transfer', 'purchase', 'sell',
            ];

            if (in_array('sell_return', $transaction_types)) {
                $transaction_totals_query->addSelect(
                    DB::raw("(IF(transactions.type='sell_return', IF(discount_type = 'percentage', COALESCE(discount_amount, 0)*total_before_tax/100, COALESCE(discount_amount, 0)), 0)) as total_sell_return_discount"),'transaction_date'
                );

                $transaction_totals_query_previous->addSelect(
                    DB::raw("(IF(transactions.type='sell_return', IF(discount_type = 'percentage', COALESCE(discount_amount, 0)*total_before_tax/100, COALESCE(discount_amount, 0)), 0)) as total_sell_return_discount"),'transaction_date'
                );

                $transaction_totals_query_location->groupBy('location_id')->addSelect(
                    DB::raw("SUM(IF(transactions.type='sell_return', IF(discount_type = 'percentage', COALESCE(discount_amount, 0)*total_before_tax/100, COALESCE(discount_amount, 0)), 0)) as total_sell_return_discount")
                );
            }

            if (in_array('sell_transfer', $transaction_types)) {
                $transaction_totals_query->addSelect(
                    DB::raw("(IF(transactions.type='sell_transfer', shipping_charges, 0)) as total_transfer_shipping_charges"),'transaction_date'

                );

                $transaction_totals_query_previous->addSelect(
                    DB::raw("(IF(transactions.type='sell_transfer', shipping_charges, 0)) as total_transfer_shipping_charges"),'transaction_date'

                );

                $transaction_totals_query_location->groupBy('location_id')->addSelect(
                    DB::raw("SUM(IF(transactions.type='sell_transfer', shipping_charges, 0)) as total_transfer_shipping_charges")
                );
            }

            if (in_array('expense', $transaction_types)) {
                $transaction_totals_query->addSelect(
                    DB::raw("(IF(transactions.type='expense', final_total, 0)) - (IF(transactions.type='expense_refund', final_total, 0)) as total_expense"),'transaction_date'
                );

                $transaction_totals_query_previous->addSelect(
                    DB::raw("(IF(transactions.type='expense', final_total, 0)) - (IF(transactions.type='expense_refund', final_total, 0)) as total_expense"),'transaction_date'
                );

                $transaction_totals_query_location->groupBy('location_id')->addSelect(
                    DB::raw("SUM(IF(transactions.type='expense', final_total, 0)) - SUM(IF(transactions.type='expense_refund', final_total, 0)) as total_expense")
                );
            }

            if (in_array('stock_adjustment', $transaction_types)) {
                $transaction_totals_query->addSelect(
                    DB::raw("(IF(transactions.type='stock_adjustment', final_total, 0)) as total_adjustment"),
                    DB::raw("(IF(transactions.type='stock_adjustment', total_amount_recovered, 0)) as total_recovered"),'transaction_date'
                );

                $transaction_totals_query_previous->addSelect(
                    DB::raw("(IF(transactions.type='stock_adjustment', final_total, 0)) as total_adjustment"),
                    DB::raw("(IF(transactions.type='stock_adjustment', total_amount_recovered, 0)) as total_recovered"),'transaction_date'
                );

                $transaction_totals_query_location->groupBy('location_id')->addSelect(
                    DB::raw("SUM(IF(transactions.type='stock_adjustment', final_total, 0)) as total_adjustment"),
                    DB::raw("SUM(IF(transactions.type='stock_adjustment', total_amount_recovered, 0)) as total_recovered")
                );
            }

            if (in_array('purchase', $transaction_types)) {
                $transaction_totals_query->addSelect(
                    DB::raw("(IF(transactions.type='purchase', IF(discount_type = 'percentage', COALESCE(discount_amount, 0)*total_before_tax/100, COALESCE(discount_amount, 0)), 0)) as total_purchase_discount"),'transaction_date'
                );
                
                $transaction_totals_query_previous->addSelect(
                    DB::raw("(IF(transactions.type='purchase', IF(discount_type = 'percentage', COALESCE(discount_amount, 0)*total_before_tax/100, COALESCE(discount_amount, 0)), 0)) as total_purchase_discount"),'transaction_date'
                );
                
                $transaction_totals_query_location->groupBy('location_id')->addSelect(
                    DB::raw("SUM(IF(transactions.type='purchase', IF(discount_type = 'percentage', COALESCE(discount_amount, 0)*total_before_tax/100, COALESCE(discount_amount, 0)), 0)) as total_purchase_discount")
                );
            }

            if (in_array('sell', $transaction_types)) {
                $transaction_totals_query->addSelect(
                    DB::raw("(IF(transactions.type='sell' AND transactions.status='final', IF(discount_type = 'percentage', COALESCE(discount_amount, 0)*total_before_tax/100, COALESCE(discount_amount, 0)), 0)) as total_sell_discount"),
                    DB::raw("(IF(transactions.type='sell' AND transactions.status='final', rp_redeemed_amount, 0)) as total_reward_amount"),
                    DB::raw("(IF(transactions.type='sell' AND transactions.status='final', round_off_amount, 0)) as total_sell_round_off"),'transaction_date'
                );
                
                $transaction_totals_query_previous->addSelect(
                    DB::raw("(IF(transactions.type='sell' AND transactions.status='final', IF(discount_type = 'percentage', COALESCE(discount_amount, 0)*total_before_tax/100, COALESCE(discount_amount, 0)), 0)) as total_sell_discount"),
                    DB::raw("(IF(transactions.type='sell' AND transactions.status='final', rp_redeemed_amount, 0)) as total_reward_amount"),
                    DB::raw("(IF(transactions.type='sell' AND transactions.status='final', round_off_amount, 0)) as total_sell_round_off"),'transaction_date'
                );
                
                $transaction_totals_query_location->groupBy('location_id')->addSelect(
                    DB::raw("SUM(IF(transactions.type='sell' AND transactions.status='final', IF(discount_type = 'percentage', COALESCE(discount_amount, 0)*total_before_tax/100, COALESCE(discount_amount, 0)), 0)) as total_sell_discount"),
                    DB::raw("SUM(IF(transactions.type='sell' AND transactions.status='final', rp_redeemed_amount, 0)) as total_reward_amount"),
                    DB::raw("SUM(IF(transactions.type='sell' AND transactions.status='final', round_off_amount, 0)) as total_sell_round_off")
                );
            }

            $transaction_totals = $transaction_totals_query->get();
            $transaction_totals_previous = $transaction_totals_query_previous->get();
            $transaction_totals_location = $transaction_totals_query_location->get();

    
            // Return a response (you can customize this based on your needs)
            return response()->json([
                'success' => true, 'message' => 'Value received successfully', 
                'value'=> $selectedLocation, 
                'sales_sum'=> $sales_sum, 
                'sales_sum_previous'=> $sales_sum_previous, 
                'purchases'=> $purchases, 
                'expenses_sum'=> $expenses_sum, 
                'expenses_sum_previous'=> $expenses_sum_previous, 
                'sells'=> $sells, 
                'sells_previous'=> $sells_previous, 
                'expenses' => $expenses,
                'expenses_previous' => $expenses_previous,
                'gross_profits' => $gross_profits ,
                'gross_profits_previous' => $gross_profits_previous ,
                'sell_details' => $sell_details,
                'sell_details_previous' => $sell_details_previous,
                'purchase_details' => $purchase_details,
                'purchase_details_previous' => $purchase_details_previous,
                'transaction_totals' => $transaction_totals, 
                'transaction_totals_previous' => $transaction_totals_previous, 
                'sells_by_location' => $sells_by_location,
                'expenses_by_location' => $expenses_by_location,
                'gross_profits_location' => $gross_profits_location,
                'sell_details_location' => $sell_details_location,
                'purchase_details_location'=> $purchase_details_location,
                'purchase_details_location'=> $purchase_details_location,
                'transaction_totals_location'=> $transaction_totals_location
        ]);
        }
        
        return view('financial.index')
        ->with(compact('all_locations'));
    }
}


//total net profit

        // $total = (float)$gross_profit+
        //          ($total_sell_round_off+
        //          $total_recovered+
        //          $total_sell_shipping_charge+
        //          $total_purchase_discount+
        //          $total_sell_additional_expense+
        //          $total_sell_return_discount)-
        //          ($total_reward_amount+
        //          $total_expense+
        //          $total_adjustment+
        //          $total_transfer_shipping_charges+
        //          $total_purchase_shipping_charge+
        //          $total_purchase_additional_expense+
        //          $total_sell_discount);

        // echo $total;
        // // dd($query);
        // // dd($sell_details);
        // // dd($purchase_details);
        // // dd($gross_profit);


// $homeDue=Transaction::where('transactions.business_id', $business_id)
//                     ->where('transactions.type', 'sell')
//                     ->where('transactions.status', 'final')
//                     ->select(
//                         DB::raw('SUM(final_total) as total_sell'),
//                         DB::raw('SUM(final_total - tax_amount) as total_exc_tax'),
//                         DB::raw('SUM(final_total - (SELECT COALESCE(SUM(IF(tp.is_return = 1, -1*tp.amount, tp.amount)), 0) FROM transaction_payments as tp WHERE tp.transaction_id = transactions.id) )  as total_due'),
//                         DB::raw('SUM(total_before_tax) as total_before_tax'),
//                         DB::raw('SUM(shipping_charges) as total_shipping_charges'),
//                         DB::raw('SUM(additional_expense_value_1 + additional_expense_value_2 + additional_expense_value_3 + additional_expense_value_4) as total_expense')
//                     )
//                     ->get();
//         echo $homeDue;

// $year = 2023;

//         $result = Transaction::where('business_id',$business_id)
//                 ->where('type','sell')
//                 ->where('status', 'final')
//                 ->select(DB::raw('SUM(final_total) as total_revenue'), DB::raw('MONTH(transaction_date) as month'))
//                     ->whereBetween('transaction_date', ["{$previousYear}-01-01 00:00:00", "{$currentYear}-12-31 23:59:59"])
//                     ->groupBy(DB::raw('MONTH(transaction_date)'))
//                     ->groupBy(DB::raw('YEAR(transaction_date)'))
//                     ->get();
//         echo $result;