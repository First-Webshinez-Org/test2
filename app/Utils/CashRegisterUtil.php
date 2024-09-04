<?php

namespace App\Utils;

use App\CashRegister;
use App\Contact;
use App\CashRegisterTransaction;
use App\Utils\TransactionUtil;
use App\Transaction;
use DB;

class CashRegisterUtil extends Util
{
    /**
     * Returns number of opened Cash Registers for the
     * current logged in user
     *
     * @return int
     */
    public function countOpenedRegister()
    {
        $user_id = auth()->user()->id;
        $count = CashRegister::where('user_id', $user_id)
                                ->where('status', 'open')
                                ->count();

        return $count;
    }

    /**
     * Adds sell payments to currently opened cash register
     *
     * @param object/int $transaction
     * @param  array  $payments
     * @return bool
     */
    public function addSellPayments($transaction, $payments)
    {
        $user_id = auth()->user()->id;
        $register = CashRegister::where('user_id', $user_id)
                                ->where('status', 'open')
                                ->first();
        $payments_formatted = [];
        foreach ($payments as $payment) {
            $payment_amount = (isset($payment['is_return']) && $payment['is_return'] == 1) ? (-1 * $this->num_uf($payment['amount'])) : $this->num_uf($payment['amount']);
            if ($payment_amount != 0) {
                $type = 'credit';
                if ($transaction->type == 'expense') {
                    $type = 'debit';
                }
                if ($transaction->type == 'purchase') {
                    $type = 'debit';
                }

                $payments_formatted[] = new CashRegisterTransaction([
                    'amount' => $payment_amount,
                    'pay_method' => $payment['method'],
                    'type' => $type,
                    'transaction_type' => $transaction->type,
                    'transaction_id' => $transaction->id,
                ]);
            }
        }

        if (! empty($payments_formatted)) {
            $register->cash_register_transactions()->saveMany($payments_formatted);
        }

        return true;
    }

    /**
     * Adds sell payments to currently opened cash register
     *
     * @param object/int $transaction
     * @param  array  $payments
     * @return bool
     */
    public function updateSellPayments($status_before, $transaction, $payments)
    {
        $user_id = auth()->user()->id;
        $register = CashRegister::where('user_id', $user_id)
                                ->where('status', 'open')
                                ->first();
        //If draft -> final then add all
        //If final -> draft then refund all
        //If final -> final then update payments
        if ($status_before == 'draft' && $transaction->status == 'final') {
            $this->addSellPayments($transaction, $payments);
        } elseif ($status_before == 'final' && $transaction->status == 'draft') {
            $this->refundSell($transaction);
        } elseif ($status_before == 'final' && $transaction->status == 'final') {
            $prev_payments = CashRegisterTransaction::where('transaction_id', $transaction->id)
                            ->select(
                                DB::raw("SUM(IF(pay_method='cash', IF(type='credit', amount, -1 * amount), 0)) as total_cash"),
                                DB::raw("SUM(IF(pay_method='card', IF(type='credit', amount, -1 * amount), 0)) as total_card"),
                                DB::raw("SUM(IF(pay_method='cheque', IF(type='credit', amount, -1 * amount), 0)) as total_cheque"),
                                DB::raw("SUM(IF(pay_method='bank_transfer', IF(type='credit', amount, -1 * amount), 0)) as total_bank_transfer"),
                                DB::raw("SUM(IF(pay_method='other', IF(type='credit', amount, -1 * amount), 0)) as total_other"),
                                DB::raw("SUM(IF(pay_method='custom_pay_1', IF(type='credit', amount, -1 * amount), 0)) as total_custom_pay_1"),
                                DB::raw("SUM(IF(pay_method='custom_pay_2', IF(type='credit', amount, -1 * amount), 0)) as total_custom_pay_2"),
                                DB::raw("SUM(IF(pay_method='custom_pay_3', IF(type='credit', amount, -1 * amount), 0)) as total_custom_pay_3"),
                                DB::raw("SUM(IF(pay_method='custom_pay_4', IF(type='credit', amount, -1 * amount), 0)) as total_custom_pay_4"),
                                DB::raw("SUM(IF(pay_method='custom_pay_5', IF(type='credit', amount, -1 * amount), 0)) as total_custom_pay_5"),
                                DB::raw("SUM(IF(pay_method='custom_pay_6', IF(type='credit', amount, -1 * amount), 0)) as total_custom_pay_6"),
                                DB::raw("SUM(IF(pay_method='custom_pay_7', IF(type='credit', amount, -1 * amount), 0)) as total_custom_pay_7"),
                                DB::raw("SUM(IF(pay_method='advance', IF(type='credit', amount, -1 * amount), 0)) as total_advance")
                            )->first();
            if (! empty($prev_payments)) {
                $payment_diffs = [
                    'cash' => $prev_payments->total_cash,
                    'card' => $prev_payments->total_card,
                    'cheque' => $prev_payments->total_cheque,
                    'bank_transfer' => $prev_payments->total_bank_transfer,
                    'other' => $prev_payments->total_other,
                    'custom_pay_1' => $prev_payments->total_custom_pay_1,
                    'custom_pay_2' => $prev_payments->total_custom_pay_2,
                    'custom_pay_3' => $prev_payments->total_custom_pay_3,
                    'custom_pay_4' => $prev_payments->total_custom_pay_4,
                    'custom_pay_5' => $prev_payments->total_custom_pay_5,
                    'custom_pay_6' => $prev_payments->total_custom_pay_6,
                    'custom_pay_7' => $prev_payments->total_custom_pay_7,
                    'advance' => $prev_payments->total_advance,
                ];

                foreach ($payments as $payment) {
                    if (isset($payment['is_return']) && $payment['is_return'] == 1) {
                        $payment_diffs[$payment['method']] += $this->num_uf($payment['amount']);
                    } else {
                        $payment_diffs[$payment['method']] -= $this->num_uf($payment['amount']);
                    }
                }
                $payments_formatted = [];
                foreach ($payment_diffs as $key => $value) {
                    if ($value > 0) {
                        $payments_formatted[] = new CashRegisterTransaction([
                            'amount' => $value,
                            'pay_method' => $key,
                            'type' => 'debit',
                            'transaction_type' => 'refund',
                            'transaction_id' => $transaction->id,
                        ]);
                    } elseif ($value < 0) {
                        $payments_formatted[] = new CashRegisterTransaction([
                            'amount' => -1 * $value,
                            'pay_method' => $key,
                            'type' => 'credit',
                            'transaction_type' => 'sell',
                            'transaction_id' => $transaction->id,
                        ]);
                    }
                }
                if (! empty($payments_formatted)) {
                    $register->cash_register_transactions()->saveMany($payments_formatted);
                }
            }
        }

        return true;
    }

    /**
     * Refunds all payments of a sell
     *
     * @param object/int $transaction
     * @return bool
     */
    public function refundSell($transaction)
    {
        $user_id = auth()->user()->id;
        $register = CashRegister::where('user_id', $user_id)
                                ->where('status', 'open')
                                ->first();

        $total_payment = CashRegisterTransaction::where('transaction_id', $transaction->id)
                            ->select(
                                DB::raw("SUM(IF(pay_method='cash', IF(type='credit', amount, -1 * amount), 0)) as total_cash"),
                                DB::raw("SUM(IF(pay_method='card', IF(type='credit', amount, -1 * amount), 0)) as total_card"),
                                DB::raw("SUM(IF(pay_method='cheque', IF(type='credit', amount, -1 * amount), 0)) as total_cheque"),
                                DB::raw("SUM(IF(pay_method='bank_transfer', IF(type='credit', amount, -1 * amount), 0)) as total_bank_transfer"),
                                DB::raw("SUM(IF(pay_method='other', IF(type='credit', amount, -1 * amount), 0)) as total_other"),
                                DB::raw("SUM(IF(pay_method='custom_pay_1', IF(type='credit', amount, -1 * amount), 0)) as total_custom_pay_1"),
                                DB::raw("SUM(IF(pay_method='custom_pay_2', IF(type='credit', amount, -1 * amount), 0)) as total_custom_pay_2"),
                                DB::raw("SUM(IF(pay_method='custom_pay_3', IF(type='credit', amount, -1 * amount), 0)) as total_custom_pay_3"),
                                DB::raw("SUM(IF(pay_method='custom_pay_4', IF(type='credit', amount, -1 * amount), 0)) as total_custom_pay_4"),
                                DB::raw("SUM(IF(pay_method='custom_pay_5', IF(type='credit', amount, -1 * amount), 0)) as total_custom_pay_5"),
                                DB::raw("SUM(IF(pay_method='custom_pay_6', IF(type='credit', amount, -1 * amount), 0)) as total_custom_pay_6"),
                                DB::raw("SUM(IF(pay_method='custom_pay_7', IF(type='credit', amount, -1 * amount), 0)) as total_custom_pay_7")
                            )->first();
        $refunds = [
            'cash' => $total_payment->total_cash,
            'card' => $total_payment->total_card,
            'cheque' => $total_payment->total_cheque,
            'bank_transfer' => $total_payment->total_bank_transfer,
            'other' => $total_payment->total_other,
            'custom_pay_1' => $total_payment->total_custom_pay_1,
            'custom_pay_2' => $total_payment->total_custom_pay_2,
            'custom_pay_3' => $total_payment->total_custom_pay_3,
            'custom_pay_4' => $total_payment->total_custom_pay_4,
            'custom_pay_5' => $total_payment->total_custom_pay_5,
            'custom_pay_6' => $total_payment->total_custom_pay_6,
            'custom_pay_7' => $total_payment->total_custom_pay_7,
        ];
        $refund_formatted = [];
        foreach ($refunds as $key => $val) {
            if ($val > 0) {
                $refund_formatted[] = new CashRegisterTransaction([
                    'amount' => $val,
                    'pay_method' => $key,
                    'type' => 'debit',
                    'transaction_type' => 'refund',
                    'transaction_id' => $transaction->id,
                ]);
            }
        }

        if (! empty($refund_formatted)) {
            $register->cash_register_transactions()->saveMany($refund_formatted);
        }

        return true;
    }

    /**
     * Retrieves details of given rigister id else currently opened register
     *
     * @param $register_id default null
     * @return object
     */
    public function getRegisterDetails($register_id = null, $start_date = null, $end_date = null, $location = null)
    {
        $query = CashRegister::leftjoin(
            'cash_register_transactions as ct',
            'ct.cash_register_id',
            '=',
            'cash_registers.id'
        )
        ->join(
            'users as u',
            'u.id',
            '=',
            'cash_registers.user_id'
        )
        ->leftJoin(
            'business_locations as bl',
            'bl.id',
            '=',
            'cash_registers.location_id'
        );

        if (empty($register_id)) {
            $user_id = auth()->user()->id;
            $query->where('user_id', $user_id)
                ->where('cash_registers.status', 'open');
        } else {
            $query->where('cash_registers.id', $register_id);
        }

        if (!empty($start_date) && !empty($end_date)) {
            $query->whereBetween('ct.created_at', [$start_date, $end_date]);
        }

        if (!empty($location)) {
            $query->where('cash_registers.location_id', $location);
        }

        $register_details = $query->select(
            'cash_registers.created_at as open_time',
            'cash_registers.closed_at as closed_at',
            'cash_registers.user_id',
            'cash_registers.closing_note',
            'cash_registers.location_id',
            'cash_registers.denominations',
            DB::raw("SUM(IF(transaction_type='initial', amount, 0)) as cash_in_hand"),
            DB::raw("SUM(IF(transaction_type='sell', amount, IF(transaction_type='refund', -1 * amount, 0))) as total_sale"),
            DB::raw("SUM(IF(transaction_type='expense', IF(transaction_type='refund', -1 * amount, amount), 0)) as total_expense"),
            DB::raw("SUM(IF(pay_method='cash', IF(transaction_type='sell', amount, 0), 0)) as total_cash"),
            DB::raw("SUM(IF(pay_method='cash', IF(transaction_type='expense', amount, 0), 0)) as total_cash_expense"),
            DB::raw("SUM(IF(pay_method='cheque', IF(transaction_type='sell', amount, 0), 0)) as total_cheque"),
            DB::raw("SUM(IF(pay_method='cheque', IF(transaction_type='expense', amount, 0), 0)) as total_cheque_expense"),
            DB::raw("SUM(IF(pay_method='card', IF(transaction_type='sell', amount, 0), 0)) as total_card"),
            DB::raw("SUM(IF(pay_method='card', IF(transaction_type='expense', amount, 0), 0)) as total_card_expense"),
            DB::raw("SUM(IF(pay_method='bank_transfer', IF(transaction_type='sell', amount, 0), 0)) as total_bank_transfer"),
            DB::raw("SUM(IF(pay_method='bank_transfer', IF(transaction_type='expense', amount, 0), 0)) as total_bank_transfer_expense"),
            DB::raw("SUM(IF(pay_method='other', IF(transaction_type='sell', amount, 0), 0)) as total_other"),
            DB::raw("SUM(IF(pay_method='other', IF(transaction_type='expense', amount, 0), 0)) as total_other_expense"),
            DB::raw("SUM(IF(pay_method='advance', IF(transaction_type='sell', amount, 0), 0)) as total_advance"),
            DB::raw("SUM(IF(pay_method='advance', IF(transaction_type='expense', amount, 0), 0)) as total_advance_expense"),
            DB::raw("SUM(IF(pay_method='custom_pay_1', IF(transaction_type='sell', amount, 0), 0)) as total_custom_pay_1"),
            DB::raw("SUM(IF(pay_method='custom_pay_2', IF(transaction_type='sell', amount, 0), 0)) as total_custom_pay_2"),
            DB::raw("SUM(IF(pay_method='custom_pay_3', IF(transaction_type='sell', amount, 0), 0)) as total_custom_pay_3"),
            DB::raw("SUM(IF(pay_method='custom_pay_4', IF(transaction_type='sell', amount, 0), 0)) as total_custom_pay_4"),
            DB::raw("SUM(IF(pay_method='custom_pay_5', IF(transaction_type='sell', amount, 0), 0)) as total_custom_pay_5"),
            DB::raw("SUM(IF(pay_method='custom_pay_6', IF(transaction_type='sell', amount, 0), 0)) as total_custom_pay_6"),
            DB::raw("SUM(IF(pay_method='custom_pay_7', IF(transaction_type='sell', amount, 0), 0)) as total_custom_pay_7"),
            DB::raw("SUM(IF(pay_method='custom_pay_1', IF(transaction_type='expense', amount, 0), 0)) as total_custom_pay_1_expense"),
            DB::raw("SUM(IF(pay_method='custom_pay_2', IF(transaction_type='expense', amount, 0), 0)) as total_custom_pay_2_expense"),
            DB::raw("SUM(IF(pay_method='custom_pay_3', IF(transaction_type='expense', amount, 0), 0)) as total_custom_pay_3_expense"),
            DB::raw("SUM(IF(pay_method='custom_pay_4', IF(transaction_type='expense', amount, 0), 0)) as total_custom_pay_4_expense"),
            DB::raw("SUM(IF(pay_method='custom_pay_5', IF(transaction_type='expense', amount, 0), 0)) as total_custom_pay_5_expense"),
            DB::raw("SUM(IF(pay_method='custom_pay_6', IF(transaction_type='expense', amount, 0), 0)) as total_custom_pay_6_expense"),
            DB::raw("SUM(IF(pay_method='custom_pay_7', IF(transaction_type='expense', amount, 0), 0)) as total_custom_pay_7_expense"),
            DB::raw("SUM(IF(transaction_type='refund', amount, 0)) as total_refund"),
            DB::raw("SUM(IF(transaction_type='refund', IF(pay_method='cash', amount, 0), 0)) as total_cash_refund"),
            DB::raw("SUM(IF(transaction_type='refund', IF(pay_method='cheque', amount, 0), 0)) as total_cheque_refund"),
            DB::raw("SUM(IF(transaction_type='refund', IF(pay_method='card', amount, 0), 0)) as total_card_refund"),
            DB::raw("SUM(IF(transaction_type='refund', IF(pay_method='bank_transfer', amount, 0), 0)) as total_bank_transfer_refund"),
            DB::raw("SUM(IF(transaction_type='refund', IF(pay_method='other', amount, 0), 0)) as total_other_refund"),
            DB::raw("SUM(IF(transaction_type='refund', IF(pay_method='advance', amount, 0), 0)) as total_advance_refund"),
            DB::raw("SUM(IF(transaction_type='refund', IF(pay_method='custom_pay_1', amount, 0), 0)) as total_custom_pay_1_refund"),
            DB::raw("SUM(IF(transaction_type='refund', IF(pay_method='custom_pay_2', amount, 0), 0)) as total_custom_pay_2_refund"),
            DB::raw("SUM(IF(transaction_type='refund', IF(pay_method='custom_pay_3', amount, 0), 0)) as total_custom_pay_3_refund"),
            DB::raw("SUM(IF(transaction_type='refund', IF(pay_method='custom_pay_4', amount, 0), 0)) as total_custom_pay_4_refund"),
            DB::raw("SUM(IF(transaction_type='refund', IF(pay_method='custom_pay_5', amount, 0), 0)) as total_custom_pay_5_refund"),
            DB::raw("SUM(IF(transaction_type='refund', IF(pay_method='custom_pay_6', amount, 0), 0)) as total_custom_pay_6_refund"),
            DB::raw("SUM(IF(transaction_type='refund', IF(pay_method='custom_pay_7', amount, 0), 0)) as total_custom_pay_7_refund"),
            DB::raw("SUM(IF(pay_method='cheque', 1, 0)) as total_cheques"),
            DB::raw("SUM(IF(pay_method='card', 1, 0)) as total_card_slips"),
            DB::raw("CONCAT(COALESCE(surname, ''), ' ', COALESCE(first_name, ''), ' ', COALESCE(last_name, '')) as user_name"),
            'u.email',
            'bl.name as location_name'
        )->first();

        return $register_details;
    }

    /**
     * Get the transaction details for a particular register
     *
     * @param $user_id int
     * @param $open_time datetime
     * @param $close_time datetime
     * @return array
     */
    public function getRegisterTransactionDetails($user_id, $open_time, $close_time, $is_types_of_service_enabled = false, $location = null, $is_admin = false)
    {
        $business_id = request()->session()->get('user.business_id');

        $customer_opening_query = Contact::leftjoin('transactions AS t', 'contacts.id', '=', 't.contact_id')
                    ->leftjoin('customer_groups AS cg', 'contacts.customer_group_id', '=', 'cg.id')
                    ->leftjoin('transaction_payments AS tp', 't.id', '=', 'tp.transaction_id')
                    ->where('contacts.business_id', $business_id)
                    ->onlyCustomers()
                    ->where('t.type','opening_balance')
                    ->when(!empty($location), function ($query) use ($location) {
                        return $query->where('t.location_id', $location);
                    })
                    ->when(!empty($open_time) && !empty($close_time), function ($q) use ($open_time, $close_time) {
                        return $q->whereBetween('tp.paid_on', [$open_time, $close_time]);
                    });

        $customer_opening_payment = $customer_opening_query->select(
            DB::raw('SUM(IF(tp.is_return = 1, -1 * tp.amount, tp.amount)) as opening_balance_paid')
        )
        ->first();

        $supplier_opening_query = Contact::leftjoin('transactions AS t', 'contacts.id', '=', 't.contact_id')
                    ->leftjoin('customer_groups AS cg', 'contacts.customer_group_id', '=', 'cg.id')
                    ->leftjoin('transaction_payments AS tp', 't.id', '=', 'tp.transaction_id')
                    ->where('contacts.business_id', $business_id)
                    ->onlySuppliers()
                    ->where('t.type','opening_balance')
                    ->when(!empty($location), function ($query) use ($location) {
                        return $query->where('t.location_id', $location);
                    })
                    ->when(!empty($open_time) && !empty($close_time), function ($q) use ($open_time, $close_time) {
                        return $q->whereBetween('tp.paid_on', [$open_time, $close_time]);
                    });

        $supplier_opening_payment = $supplier_opening_query->select(
            DB::raw('SUM(IF(tp.is_return = 1, -1 * tp.amount, tp.amount)) as opening_balance_paid')
        )
        ->first();

        $product_details_by_brand = Transaction::when($is_admin, function ($q) use ($business_id) {
                    return $q->where('transactions.business_id', $business_id);
                }, function ($q) use ($user_id) {
                    return $q->where('transactions.created_by', $user_id);
                })
                ->when(!empty($open_time) && !empty($close_time), function ($q) use ($open_time, $close_time) {
                    return $q->whereBetween('transactions.transaction_date', [$open_time, $close_time]);
                })
                ->where('transactions.type', 'sell')
                ->where('transactions.status', 'final')
                // ->where('transactions.is_direct_sale', 0)
                ->when(!empty($location), function ($query) use ($location) {
                    return $query->where('transactions.location_id', $location);
                })
                ->join('transaction_sell_lines AS TSL', 'transactions.id', '=', 'TSL.transaction_id')
                ->join('products AS P', 'TSL.product_id', '=', 'P.id')
                ->where('TSL.children_type', '!=', 'combo')
                ->leftjoin('brands AS B', 'P.brand_id', '=', 'B.id')
                ->groupBy('B.id')
                ->select(
                    'B.name as brand_name',
                    DB::raw('SUM(TSL.quantity) as total_quantity'),
                    DB::raw('SUM(TSL.unit_price_inc_tax*TSL.quantity) as total_amount')
                )
                ->orderByRaw('CASE WHEN brand_name IS NULL THEN 2 ELSE 1 END, brand_name')
                ->get();

        $product_details = Transaction::when($is_admin, function ($q) use ($business_id) {
                    return $q->where('transactions.business_id', $business_id);
                }, function ($q) use ($user_id) {
                    return $q->where('transactions.created_by', $user_id);
                })
                ->when(!empty($open_time) && !empty($close_time), function ($q) use ($open_time, $close_time) {
                    return $q->whereBetween('transactions.transaction_date', [$open_time, $close_time]);
                })
                ->where('transactions.type', 'sell')
                ->where('transactions.status', 'final')
                // ->where('transactions.is_direct_sale', 0)
                ->when(!empty($location), function ($query) use ($location) {
                    return $query->where('transactions.location_id', $location);
                })
                ->join('transaction_sell_lines AS TSL', 'transactions.id', '=', 'TSL.transaction_id')
                ->join('variations AS v', 'TSL.variation_id', '=', 'v.id')
                ->join('product_variations AS pv', 'v.product_variation_id', '=', 'pv.id')
                ->join('products AS p', 'v.product_id', '=', 'p.id')
                ->where('TSL.children_type', '!=', 'combo')
                ->groupBy('v.id')
                ->select(
                    'p.name as product_name',
                    'p.type as product_type',
                    'v.name as variation_name',
                    'pv.name as product_variation_name',
                    'v.sub_sku as sku',
                    DB::raw('SUM(TSL.quantity) as total_quantity'),
                    DB::raw('SUM(TSL.unit_price_inc_tax*TSL.quantity) as total_amount')
                )
                ->get();

        // product purchase details
        $product_purchase_details = Transaction::when($is_admin, function ($q) use ($business_id) {
                    return $q->where('transactions.business_id', $business_id);
                }, function ($q) use ($user_id) {
                    return $q->where('transactions.created_by', $user_id);
                })
                ->when(!empty($open_time) && !empty($close_time), function ($q) use ($open_time, $close_time) {
                    return $q->whereBetween('transactions.transaction_date', [$open_time, $close_time]);
                })
                ->where('transactions.type', 'purchase')
                // ->where('transactions.status', 'final')
                // ->where('transactions.is_direct_sale', 0)
                ->when(!empty($location), function ($query) use ($location) {
                    return $query->where('transactions.location_id', $location);
                })
                ->join('purchase_lines AS PL', 'transactions.id', '=', 'PL.transaction_id')
                ->join('variations AS v', 'PL.variation_id', '=', 'v.id')
                ->join('product_variations AS pv', 'v.product_variation_id', '=', 'pv.id')
                ->join('products AS p', 'v.product_id', '=', 'p.id')
                // ->where('PL.children_type', '!=', 'combo')
                ->groupBy('v.id')
                ->select(
                    'p.name as product_name',
                    'p.type as product_type',
                    'v.name as variation_name',
                    'pv.name as product_variation_name',
                    'v.sub_sku as sku',
                    DB::raw('SUM(PL.quantity) as total_quantity'),
                    DB::raw('SUM(PL.purchase_price_inc_tax*PL.quantity) as total_amount')
                )
                ->get();

        //If types of service
        $types_of_service_details = null;
        if ($is_types_of_service_enabled) {
            $types_of_service_details = Transaction::when($is_admin, function ($q) use ($business_id) {
                    return $q->where('transactions.business_id', $business_id);
                }, function ($q) use ($user_id) {
                    return $q->where('transactions.created_by', $user_id);
                })
                ->whereBetween('transaction_date', [$open_time, $close_time])
                // ->where('transactions.is_direct_sale', 0)
                ->where('transactions.type', 'sell')
                ->where('transactions.status', 'final')
                ->when(!empty($location), function ($query) use ($location) {
                    return $query->where('transactions.location_id', $location);
                })
                ->leftjoin('types_of_services AS tos', 'tos.id', '=', 'transactions.types_of_service_id')
                ->groupBy('tos.id')
                ->select(
                    'tos.name as types_of_service_name',
                    DB::raw('SUM(final_total) as total_sales')
                )
                ->orderBy('total_sales', 'desc')
                ->get();
        }

        $transaction_details = Transaction::when($is_admin, function ($q) use ($business_id) {
                    return $q->where('transactions.business_id', $business_id);
                }, function ($q) use ($user_id) {
                    return $q->where('transactions.created_by', $user_id);
                })
                ->where('transactions.type', 'sell')
                // ->where('transactions.is_direct_sale', 0)
                ->where('transactions.status', 'final')
                ->when(!empty($open_time) && !empty($close_time), function ($q) use ($open_time, $close_time) {
                    return $q->whereBetween('transactions.transaction_date', [$open_time, $close_time]);
                })
                ->when(!empty($location), function ($query) use ($location) {
                    return $query->where('transactions.location_id', $location);
                })
                ->select(
                    DB::raw('SUM(tax_amount) as total_tax'),
                    DB::raw('SUM(IF(discount_type = "percentage", total_before_tax*discount_amount/100, discount_amount)) as total_discount'),
                    DB::raw('SUM(final_total) as total_sales'),
                    DB::raw('SUM(shipping_charges) as total_shipping_charges')
                )
                ->first();

        $amount_paid = Transaction::join(
                        'transaction_payments AS TP',
                        'transactions.id',
                        '=',
                        'TP.transaction_id'
                    )
                    ->when($is_admin, function ($q) use ($business_id) {
                    return $q->where('transactions.business_id', $business_id);
                }, function ($q) use ($user_id) {
                    return $q->where('transactions.created_by', $user_id);
                })
                    ->where('transactions.type', 'sell')
                    // ->where('transactions.is_direct_sale', 0)
                    ->where('transactions.status', 'final')
  
                ->when(!empty($open_time) && !empty($close_time), function ($q) use ($open_time, $close_time) {
                    return $q->whereBetween('transactions.transaction_date', [$open_time, $close_time]);
                })
                    ->when(!empty($location), function ($query) use ($location) {
                        return $query->where('transactions.location_id', $location);
                    })
                    ->select(
                        DB::raw('SUM(CASE WHEN TP.is_return = 1 THEN -TP.amount ELSE TP.amount END) as amount_paid')
                    )
                    ->first();

        $sell_return = Transaction::when($is_admin, function ($q) use ($business_id) {
                    return $q->where('transactions.business_id', $business_id);
                }, function ($q) use ($user_id) {
                    return $q->where('transactions.created_by', $user_id);
                })
                ->when(!empty($open_time) && !empty($close_time), function ($q) use ($open_time, $close_time) {
                    return $q->whereBetween('transactions.transaction_date', [$open_time, $close_time]);
                })
                ->where('transactions.type', 'sell_return')
                // ->where('transactions.is_direct_sale', 0)
                ->where('transactions.status', 'final')
                ->when(!empty($location), function ($query) use ($location) {
                    return $query->where('transactions.location_id', $location);
                })
                ->select(
                    DB::raw('SUM(final_total) as total_sell_return')
                )
                ->first();

        $purchase_transaction_details = Transaction::when($is_admin, function ($q) use ($business_id) {
                    return $q->where('transactions.business_id', $business_id);
                }, function ($q) use ($user_id) {
                    return $q->where('transactions.created_by', $user_id);
                })
                ->when(!empty($open_time) && !empty($close_time), function ($q) use ($open_time, $close_time) {
                    return $q->whereBetween('transactions.transaction_date', [$open_time, $close_time]);
                })
                ->where('transactions.type', 'purchase')
                // ->where('transactions.is_direct_sale', 0)
                // ->where('transactions.status', 'received')
                ->when(!empty($location), function ($query) use ($location) {
                    return $query->where('transactions.location_id', $location);
                })
                ->select(
                    DB::raw('SUM(tax_amount) as total_tax'),
                    DB::raw('SUM(IF(discount_type = "percentage", total_before_tax*discount_amount/100, discount_amount)) as total_discount'),
                    DB::raw('SUM(final_total) as total_purchase'),
                    DB::raw('SUM(shipping_charges) as total_shipping_charges')
                )
                ->first();

        $purchase_opening_transaction_details = Transaction::when($is_admin, function ($q) use ($business_id) {
                    return $q->where('transactions.business_id', $business_id);
                }, function ($q) use ($user_id) {
                    return $q->where('transactions.created_by', $user_id);
                })
                ->when(!empty($open_time) && !empty($close_time), function ($q) use ($open_time, $close_time) {
                    return $q->whereBetween('transactions.transaction_date', [$open_time, $close_time]);
                })
                ->where('transactions.type', 'opening_stock')
                // ->where('transactions.is_direct_sale', 0)
                // ->where('transactions.status', 'received')
                ->when(!empty($location), function ($query) use ($location) {
                    return $query->where('transactions.location_id', $location);
                })
                ->select(
                    DB::raw('SUM(final_total) as total_purchase_opening'),
                )
                ->first();

        $expense_transaction_details = Transaction::when($is_admin, function ($q) use ($business_id) {
                    return $q->where('transactions.business_id', $business_id);
                }, function ($q) use ($user_id) {
                    return $q->where('transactions.created_by', $user_id);
                })
                ->when(!empty($open_time) && !empty($close_time), function ($q) use ($open_time, $close_time) {
                    return $q->whereBetween('transactions.transaction_date', [$open_time, $close_time]);
                })
                ->whereIn('transactions.type', ['expense', 'expense_refund'])
                ->where('status', 'final')
                // ->where('transactions.is_direct_sale', 0)
                ->when(!empty($location), function ($query) use ($location) {
                    return $query->where('transactions.location_id', $location);
                })
                ->select(
                    // DB::raw('SUM(final_total) as total_purchase'),
                    DB::raw("SUM( IF(transactions.type='expense_refund', -1 * final_total, final_total) ) as total_expense")
                )
                ->first();

        $purchase_return = Transaction::when($is_admin, function ($q) use ($business_id) {
                    return $q->where('transactions.business_id', $business_id);
                }, function ($q) use ($user_id) {
                    return $q->where('transactions.created_by', $user_id);
                })
                ->when(!empty($open_time) && !empty($close_time), function ($q) use ($open_time, $close_time) {
                    return $q->whereBetween('transactions.transaction_date', [$open_time, $close_time]);
                })
                ->where('transactions.type', 'purchase_return')
                // ->where('transactions.is_direct_sale', 0)
                // ->where('transactions.status', 'final')
                ->when(!empty($location), function ($query) use ($location) {
                    return $query->where('transactions.location_id', $location);
                })
                ->select(
                    DB::raw('SUM(final_total) as total_purchase_return')
                )
                ->first();

        $credit_purchase_details = Transaction::join(
                        'transaction_payments AS TP',
                        'transactions.id',
                        '=',
                        'TP.transaction_id'
                    )
                    ->when($is_admin, function ($q) use ($business_id) {
                    return $q->where('transactions.business_id', $business_id);
                }, function ($q) use ($user_id) {
                    return $q->where('transactions.created_by', $user_id);
                })
                    ->where('transactions.type', 'purchase')
                    // ->where('transactions.is_direct_sale', 0)
                    // ->where('transactions.status', 'final')
                    ->when(!empty($open_time) && !empty($close_time), function ($q) use ($open_time, $close_time) {
                        return $q->whereBetween('transactions.transaction_date', [$open_time, $close_time]);
                    })
                    ->when(!empty($location), function ($query) use ($location) {
                        return $query->where('transactions.location_id', $location);
                    })
                    ->select(
                        DB::raw('SUM(CASE WHEN TP.is_return = 1 THEN -TP.amount ELSE TP.amount END) as amount_paid')
                    )
                    ->first();

        $purchase_details = Transaction::when($is_admin, function ($q) use ($business_id) {
                    return $q->where('transactions.business_id', $business_id);
                }, function ($q) use ($user_id) {
                    return $q->where('transactions.created_by', $user_id);
                })
            ->where('transactions.type', 'purchase')
            ->leftjoin(
            'transaction_payments as tp',
            'tp.transaction_id',
            '=',
            'transactions.id'
            )
            ->when(!empty($open_time) && !empty($close_time), function ($q) use ($open_time, $close_time) {
                return $q->whereBetween('transactions.transaction_date', [$open_time, $close_time]);
            })
            ->when(!empty($location), function ($query) use ($location) {
                return $query->where('transactions.location_id', $location);
            })
            ->select(
                DB::raw('SUM(CASE WHEN tp.is_return = 1 THEN -tp.amount ELSE tp.amount END) as total_paid'),
                DB::raw("SUM(CASE 
                        WHEN tp.method = 'cash' AND tp.is_return = 1 THEN -tp.amount
                        WHEN tp.method = 'cash' THEN tp.amount
                        ELSE 0 
                        END) as total_cash"),
                DB::raw("SUM(CASE 
                        WHEN tp.method = 'card' AND tp.is_return = 1 THEN -tp.amount
                        WHEN tp.method = 'card' THEN tp.amount
                        ELSE 0 
                        END) as total_card"),
                DB::raw("SUM(CASE 
                        WHEN tp.method = 'cheque' AND tp.is_return = 1 THEN -tp.amount
                        WHEN tp.method = 'cheque' THEN tp.amount
                        ELSE 0 
                        END) as total_cheque"),
                DB::raw("SUM(CASE 
                        WHEN tp.method = 'bank_transfer' AND tp.is_return = 1 THEN -tp.amount
                        WHEN tp.method = 'bank_transfer' THEN tp.amount
                        ELSE 0 
                        END) as total_bank_transfer"),
                DB::raw("SUM(CASE 
                        WHEN tp.method = 'other' AND tp.is_return = 1 THEN -tp.amount
                        WHEN tp.method = 'other' THEN tp.amount
                        ELSE 0 
                        END) as total_other"),
                DB::raw("SUM(CASE 
                        WHEN tp.method = 'advance' AND tp.is_return = 1 THEN -tp.amount
                        WHEN tp.method = 'advance' THEN tp.amount
                        ELSE 0 
                        END) as total_advance"),
                DB::raw("SUM(CASE 
                        WHEN tp.method = 'custom_pay_1' AND tp.is_return = 1 THEN -tp.amount
                        WHEN tp.method = 'custom_pay_1' THEN tp.amount
                        ELSE 0 
                        END) as total_custom_pay_1"),
                DB::raw("SUM(CASE 
                        WHEN tp.method = 'custom_pay_2' AND tp.is_return = 1 THEN -tp.amount
                        WHEN tp.method = 'custom_pay_2' THEN tp.amount
                        ELSE 0 
                        END) as total_custom_pay_2"),
                DB::raw("SUM(CASE 
                        WHEN tp.method = 'custom_pay_3' AND tp.is_return = 1 THEN -tp.amount
                        WHEN tp.method = 'custom_pay_3' THEN tp.amount
                        ELSE 0 
                        END) as total_custom_pay_3"),
                DB::raw("SUM(CASE 
                        WHEN tp.method = 'custom_pay_4' AND tp.is_return = 1 THEN -tp.amount
                        WHEN tp.method = 'custom_pay_4' THEN tp.amount
                        ELSE 0 
                        END) as total_custom_pay_4"),
                DB::raw("SUM(CASE 
                        WHEN tp.method = 'custom_pay_5' AND tp.is_return = 1 THEN -tp.amount
                        WHEN tp.method = 'custom_pay_5' THEN tp.amount
                        ELSE 0 
                        END) as total_custom_pay_5"),
                DB::raw("SUM(CASE 
                        WHEN tp.method = 'custom_pay_6' AND tp.is_return = 1 THEN -tp.amount
                        WHEN tp.method = 'custom_pay_6' THEN tp.amount
                        ELSE 0 
                        END) as total_custom_pay_6"),
                DB::raw("SUM(CASE 
                        WHEN tp.method = 'custom_pay_7' AND tp.is_return = 1 THEN -tp.amount
                        WHEN tp.method = 'custom_pay_7' THEN tp.amount
                        ELSE 0 
                        END) as total_custom_pay_7")
            )
            ->first();

        $sales_details = Transaction::when($is_admin, function ($q) use ($business_id) {
                    return $q->where('transactions.business_id', $business_id);
                }, function ($q) use ($user_id) {
                    return $q->where('transactions.created_by', $user_id);
                })
            ->where('transactions.type', 'sell')
            ->where('transactions.status', 'final')
            ->leftjoin(
            'transaction_payments as tp',
            'tp.transaction_id',
            '=',
            'transactions.id'
            )
            ->when(!empty($open_time) && !empty($close_time), function ($q) use ($open_time, $close_time) {
                return $q->whereBetween('transactions.transaction_date', [$open_time, $close_time]);
            })
            ->when(!empty($location), function ($query) use ($location) {
                return $query->where('transactions.location_id', $location);
            })
            ->select(
                DB::raw('SUM(CASE WHEN tp.is_return = 1 THEN -tp.amount ELSE tp.amount END) as total_paid'),
                DB::raw("SUM(CASE 
                        WHEN tp.method = 'cash' AND tp.is_return = 1 THEN -tp.amount
                        WHEN tp.method = 'cash' THEN tp.amount
                        ELSE 0 
                        END) as total_cash"),
                DB::raw("SUM(CASE 
                        WHEN tp.method = 'card' AND tp.is_return = 1 THEN -tp.amount
                        WHEN tp.method = 'card' THEN tp.amount
                        ELSE 0 
                        END) as total_card"),
                DB::raw("SUM(CASE 
                        WHEN tp.method = 'cheque' AND tp.is_return = 1 THEN -tp.amount
                        WHEN tp.method = 'cheque' THEN tp.amount
                        ELSE 0 
                        END) as total_cheque"),
                DB::raw("SUM(CASE 
                        WHEN tp.method = 'bank_transfer' AND tp.is_return = 1 THEN -tp.amount
                        WHEN tp.method = 'bank_transfer' THEN tp.amount
                        ELSE 0 
                        END) as total_bank_transfer"),
                DB::raw("SUM(CASE 
                        WHEN tp.method = 'other' AND tp.is_return = 1 THEN -tp.amount
                        WHEN tp.method = 'other' THEN tp.amount
                        ELSE 0 
                        END) as total_other"),
                DB::raw("SUM(CASE 
                        WHEN tp.method = 'advance' AND tp.is_return = 1 THEN -tp.amount
                        WHEN tp.method = 'advance' THEN tp.amount
                        ELSE 0 
                        END) as total_advance"),
                DB::raw("SUM(CASE 
                        WHEN tp.method = 'custom_pay_1' AND tp.is_return = 1 THEN -tp.amount
                        WHEN tp.method = 'custom_pay_1' THEN tp.amount
                        ELSE 0 
                        END) as total_custom_pay_1"),
                DB::raw("SUM(CASE 
                        WHEN tp.method = 'custom_pay_2' AND tp.is_return = 1 THEN -tp.amount
                        WHEN tp.method = 'custom_pay_2' THEN tp.amount
                        ELSE 0 
                        END) as total_custom_pay_2"),
                DB::raw("SUM(CASE 
                        WHEN tp.method = 'custom_pay_3' AND tp.is_return = 1 THEN -tp.amount
                        WHEN tp.method = 'custom_pay_3' THEN tp.amount
                        ELSE 0 
                        END) as total_custom_pay_3"),
                DB::raw("SUM(CASE 
                        WHEN tp.method = 'custom_pay_4' AND tp.is_return = 1 THEN -tp.amount
                        WHEN tp.method = 'custom_pay_4' THEN tp.amount
                        ELSE 0 
                        END) as total_custom_pay_4"),
                DB::raw("SUM(CASE 
                        WHEN tp.method = 'custom_pay_5' AND tp.is_return = 1 THEN -tp.amount
                        WHEN tp.method = 'custom_pay_5' THEN tp.amount
                        ELSE 0 
                        END) as total_custom_pay_5"),
                DB::raw("SUM(CASE 
                        WHEN tp.method = 'custom_pay_6' AND tp.is_return = 1 THEN -tp.amount
                        WHEN tp.method = 'custom_pay_6' THEN tp.amount
                        ELSE 0 
                        END) as total_custom_pay_6"),
                DB::raw("SUM(CASE 
                        WHEN tp.method = 'custom_pay_7' AND tp.is_return = 1 THEN -tp.amount
                        WHEN tp.method = 'custom_pay_7' THEN tp.amount
                        ELSE 0 
                        END) as total_custom_pay_7")
            )
            ->first();

        $expense_details = Transaction::when($is_admin, function ($q) use ($business_id) {
                    return $q->where('transactions.business_id', $business_id);
                }, function ($q) use ($user_id) {
                    return $q->where('transactions.created_by', $user_id);
                })
            ->where('transactions.type', 'expense')
            ->where('transactions.status', 'final')
            ->leftjoin(
            'transaction_payments as tp',
            'tp.transaction_id',
            '=',
            'transactions.id'
            )
            ->when(!empty($open_time) && !empty($close_time), function ($q) use ($open_time, $close_time) {
                return $q->whereBetween('transactions.transaction_date', [$open_time, $close_time]);
            })
            ->when(!empty($location), function ($query) use ($location) {
                return $query->where('transactions.location_id', $location);
            })
            ->select(
                DB::raw('SUM(CASE WHEN tp.is_return = 1 THEN -tp.amount ELSE tp.amount END) as total_paid'),
                DB::raw("SUM(CASE 
                        WHEN tp.method = 'cash' AND tp.is_return = 1 THEN -tp.amount
                        WHEN tp.method = 'cash' THEN tp.amount
                        ELSE 0 
                        END) as total_cash"),
                DB::raw("SUM(CASE 
                        WHEN tp.method = 'card' AND tp.is_return = 1 THEN -tp.amount
                        WHEN tp.method = 'card' THEN tp.amount
                        ELSE 0 
                        END) as total_card"),
                DB::raw("SUM(CASE 
                        WHEN tp.method = 'cheque' AND tp.is_return = 1 THEN -tp.amount
                        WHEN tp.method = 'cheque' THEN tp.amount
                        ELSE 0 
                        END) as total_cheque"),
                DB::raw("SUM(CASE 
                        WHEN tp.method = 'bank_transfer' AND tp.is_return = 1 THEN -tp.amount
                        WHEN tp.method = 'bank_transfer' THEN tp.amount
                        ELSE 0 
                        END) as total_bank_transfer"),
                DB::raw("SUM(CASE 
                        WHEN tp.method = 'other' AND tp.is_return = 1 THEN -tp.amount
                        WHEN tp.method = 'other' THEN tp.amount
                        ELSE 0 
                        END) as total_other"),
                DB::raw("SUM(CASE 
                        WHEN tp.method = 'advance' AND tp.is_return = 1 THEN -tp.amount
                        WHEN tp.method = 'advance' THEN tp.amount
                        ELSE 0 
                        END) as total_advance"),
                DB::raw("SUM(CASE 
                        WHEN tp.method = 'custom_pay_1' AND tp.is_return = 1 THEN -tp.amount
                        WHEN tp.method = 'custom_pay_1' THEN tp.amount
                        ELSE 0 
                        END) as total_custom_pay_1"),
                DB::raw("SUM(CASE 
                        WHEN tp.method = 'custom_pay_2' AND tp.is_return = 1 THEN -tp.amount
                        WHEN tp.method = 'custom_pay_2' THEN tp.amount
                        ELSE 0 
                        END) as total_custom_pay_2"),
                DB::raw("SUM(CASE 
                        WHEN tp.method = 'custom_pay_3' AND tp.is_return = 1 THEN -tp.amount
                        WHEN tp.method = 'custom_pay_3' THEN tp.amount
                        ELSE 0 
                        END) as total_custom_pay_3"),
                DB::raw("SUM(CASE 
                        WHEN tp.method = 'custom_pay_4' AND tp.is_return = 1 THEN -tp.amount
                        WHEN tp.method = 'custom_pay_4' THEN tp.amount
                        ELSE 0 
                        END) as total_custom_pay_4"),
                DB::raw("SUM(CASE 
                        WHEN tp.method = 'custom_pay_5' AND tp.is_return = 1 THEN -tp.amount
                        WHEN tp.method = 'custom_pay_5' THEN tp.amount
                        ELSE 0 
                        END) as total_custom_pay_5"),
                DB::raw("SUM(CASE 
                        WHEN tp.method = 'custom_pay_6' AND tp.is_return = 1 THEN -tp.amount
                        WHEN tp.method = 'custom_pay_6' THEN tp.amount
                        ELSE 0 
                        END) as total_custom_pay_6"),
                DB::raw("SUM(CASE 
                        WHEN tp.method = 'custom_pay_7' AND tp.is_return = 1 THEN -tp.amount
                        WHEN tp.method = 'custom_pay_7' THEN tp.amount
                        ELSE 0 
                        END) as total_custom_pay_7")
            )
            ->first();

        return ['product_details_by_brand' => $product_details_by_brand,
            'types_of_service_details' => $types_of_service_details,
            'product_details' => $product_details,
            'product_purchase_details' => $product_purchase_details,
            'transaction_details' => $transaction_details,
            'purchase_transaction_details' => $purchase_transaction_details,
            'expense_transaction_details' => $expense_transaction_details,
            'customer_opening_payment' => $customer_opening_payment,
            'supplier_opening_payment' => $supplier_opening_payment,
            'credit_purchase_details' => $credit_purchase_details,
            'purchase_opening_transaction_details' => $purchase_opening_transaction_details,
            'sell_return' => $sell_return,
            'purchase_return' => $purchase_return,
            'amount_paid' => $amount_paid,
            'expense_details' => $expense_details,
            'purchase_details' => $purchase_details,
            'sales_details' => $sales_details,
        ];
    }

    /**
     * Retrieves the currently opened cash register for the user
     *
     * @param $int user_id
     * @return obj
     */
    public function getCurrentCashRegister($user_id)
    {
        $register = CashRegister::where('user_id', $user_id)
                                ->where('status', 'open')
                                ->first();

        return $register;
    }
}
