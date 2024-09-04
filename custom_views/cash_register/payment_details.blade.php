<div class="row">
  <div class="col-sm-12">
    <table class="table table-condensed"  id="payments_details_div">
      <tr>
        <th>@lang('lang_v1.payment_method')</th>
        <th>@lang('sale.sale')</th>
        <th>@lang('lang_v1.expense')</th>
        <th>@lang('lang_v1.purchase')</th>
      </tr>
      <!-- <tr>
        <td>
          @lang('cash_register.cash_in_hand'):
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $bal_before_start_date ?? '৳ 0.00' }}</span>
        </td>
        <td>--</td>
        <td>--</td>
      </tr> -->
      <tr>
        <td>
          @lang('cash_register.cash_payment'):
        </th>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $details['sales_details']->total_cash ?? '৳ 0.00' }}</span>
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $details['expense_details']->total_cash ?? '৳ 0.00' }}</span>
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $details['purchase_details']->total_cash ?? '৳ 0.00'}}</span>
        </td>
      </tr>
      <tr>
        <td>
          @lang('cash_register.checque_payment'):
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $details['sales_details']->total_cheque ?? '৳ 0.00' }}</span>
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $details['expense_details']->total_cheque ?? '৳ 0.00' }}</span>
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $details['purchase_details']->total_cheque ?? '৳ 0.00'}}</span>
        </td>
      </tr>
      <tr>
        <td>
          @lang('cash_register.card_payment'):
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $details['sales_details']->total_card ?? '৳ 0.00' }}</span>
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $details['expense_details']->total_card ?? '৳ 0.00' }}</span>
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $details['purchase_details']->total_card ?? '৳ 0.00'}}</span>
        </td>
      </tr>
      <tr>
        <td>
          @lang('cash_register.bank_transfer'):
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $details['sales_details']->total_bank_transfer ?? '৳ 0.00' }}</span>
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $details['expense_details']->total_bank_transfer ?? '৳ 0.00' }}</span>
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $details['purchase_details']->total_bank_transfer ?? '৳ 0.00'}}</span>
        </td>
      </tr>
      <tr>
        <td>
          @lang('lang_v1.advance_payment'):
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $details['sales_details']->total_advance ?? '৳ 0.00' }}</span>
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $details['expense_details']->total_advance ?? '৳ 0.00' }}</span>
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $details['purchase_details']->total_advance ?? '৳ 0.00'}}</span>
        </td>
      </tr>
      @if(array_key_exists('custom_pay_1', $payment_types))
        <tr>
          <td>
            {{$payment_types['custom_pay_1']}}:
          </td>
          <td>
            <span class="display_currency" data-currency_symbol="true">{{ $details['sales_details']->total_custom_pay_1 ?? '৳ 0.00' }}</span>
          </td>
          <td>
            <span class="display_currency" data-currency_symbol="true">{{ $details['expense_details']->total_custom_pay_1 ?? '৳ 0.00' }}</span>
          </td>
          <td>
            <span class="display_currency" data-currency_symbol="true">{{ $details['purchase_details']->total_custom_pay_1 ?? '৳ 0.00'}}</span>
          </td>
        </tr>
      @endif
      @if(array_key_exists('custom_pay_2', $payment_types))
        <tr>
          <td>
            {{$payment_types['custom_pay_2']}}:
          </td>
          <td>
            <span class="display_currency" data-currency_symbol="true">{{ $details['sales_details']->total_custom_pay_2 ?? '৳ 0.00' }}</span>
          </td>
          <td>
            <span class="display_currency" data-currency_symbol="true">{{ $details['expense_details']->total_custom_pay_2 ?? '৳ 0.00' }}</span>
          </td>
          <td>
            <span class="display_currency" data-currency_symbol="true">{{ $details['purchase_details']->total_custom_pay_2 ?? '৳ 0.00'}}</span>
          </td>
        </tr>
      @endif
      @if(array_key_exists('custom_pay_3', $payment_types))
        <tr>
          <td>
            {{$payment_types['custom_pay_3']}}:
          </td>
          <td>
            <span class="display_currency" data-currency_symbol="true">{{ $details['sales_details']->total_custom_pay_3 ?? '৳ 0.00' }}</span>
          </td>
          <td>
            <span class="display_currency" data-currency_symbol="true">{{ $details['expense_details']->total_custom_pay_3 ?? '৳ 0.00' }}</span>
          </td>
          <td>
            <span class="display_currency" data-currency_symbol="true">{{ $details['purchase_details']->total_custom_pay_3 ?? '৳ 0.00'}}</span>
          </td>
        </tr>
      @endif
      @if(array_key_exists('custom_pay_4', $payment_types))
        <tr>
          <td>
            {{$payment_types['custom_pay_4']}}:
          </td>
          <td>
            <span class="display_currency" data-currency_symbol="true">{{ $details['sales_details']->total_custom_pay_4 ?? '৳ 0.00' }}</span>
          </td>
          <td>
            <span class="display_currency" data-currency_symbol="true">{{ $details['expense_details']->total_custom_pay_4 ?? '৳ 0.00' }}</span>
          </td>
          <td>
            <span class="display_currency" data-currency_symbol="true">{{ $details['purchase_details']->total_custom_pay_4 ?? '৳ 0.00'}}</span>
          </td>
        </tr>
      @endif
      @if(array_key_exists('custom_pay_5', $payment_types))
        <tr>
          <td>
            {{$payment_types['custom_pay_5']}}:
          </td>
          <td>
            <span class="display_currency" data-currency_symbol="true">{{ $details['sales_details']->total_custom_pay_5 ?? '৳ 0.00' }}</span>
          </td>
          <td>
            <span class="display_currency" data-currency_symbol="true">{{ $details['expense_details']->total_custom_pay_5 ?? '৳ 0.00' }}</span>
          </td>
          <td>
            <span class="display_currency" data-currency_symbol="true">{{ $details['purchase_details']->total_custom_pay_5 ?? '৳ 0.00'}}</span>
          </td>
        </tr>
      @endif
      @if(array_key_exists('custom_pay_6', $payment_types))
        <tr>
          <td>
            {{$payment_types['custom_pay_6']}}:
          </td>
          <td>
            <span class="display_currency" data-currency_symbol="true">{{ $details['sales_details']->total_custom_pay_6 ?? '৳ 0.00' }}</span>
          </td>
          <td>
            <span class="display_currency" data-currency_symbol="true">{{ $details['expense_details']->total_custom_pay_6 ?? '৳ 0.00' }}</span>
          </td>
          <td>
            <span class="display_currency" data-currency_symbol="true">{{ $details['purchase_details']->total_custom_pay_6 ?? '৳ 0.00'}}</span>
          </td>
        </tr>
      @endif
      @if(array_key_exists('custom_pay_7', $payment_types))
        <tr>
          <td>
            {{$payment_types['custom_pay_7']}}:
          </td>
          <td>
            <span class="display_currency" data-currency_symbol="true">{{ $details['sales_details']->total_custom_pay_7 ?? '৳ 0.00' }}</span>
          </td>
          <td>
            <span class="display_currency" data-currency_symbol="true">{{ $details['expense_details']->total_custom_pay_7 ?? '৳ 0.00' }}</span>
          </td>
          <td>
            <span class="display_currency" data-currency_symbol="true">{{ $details['purchase_details']->total_custom_pay_7 ?? '৳ 0.00'}}</span>
          </td>
        </tr>
      @endif
      <tr>
        <td>
          @lang('cash_register.other_payments'):
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $details['sales_details']->total_other ?? '৳ 0.00' }}</span>
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $details['expense_details']->total_other ?? '৳ 0.00' }}</span>
        </td>
          <td>
            <span class="display_currency" data-currency_symbol="true">{{ $details['purchase_details']->total_other ?? '৳ 0.00'}}</span>
          </td>
      </tr>
      <tr class="success">
        <td>
          @lang('cash_register.grand_total'):
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">
            {{ (
                ($details['sales_details']->total_cash ?? 0) + 
                ($details['sales_details']->total_cheque ?? 0) +  
                ($details['sales_details']->total_card ?? 0) +
                ($details['sales_details']->total_bank_transfer ?? 0) +
                ($details['sales_details']->total_advance ?? 0) +
                (array_key_exists('custom_pay_1', $payment_types) ? $details['sales_details']->total_custom_pay_1 : 0) +
                (array_key_exists('custom_pay_2', $payment_types) ? $details['sales_details']->total_custom_pay_2 : 0) +
                (array_key_exists('custom_pay_3', $payment_types) ? $details['sales_details']->total_custom_pay_3 : 0) +
                (array_key_exists('custom_pay_4', $payment_types) ? $details['sales_details']->total_custom_pay_4 : 0) +
                (array_key_exists('custom_pay_5', $payment_types) ? $details['sales_details']->total_custom_pay_5 : 0) +
                (array_key_exists('custom_pay_6', $payment_types) ? $details['sales_details']->total_custom_pay_6 : 0) +
                (array_key_exists('custom_pay_7', $payment_types) ? $details['sales_details']->total_custom_pay_7 : 0) +
                ($details['sales_details']->total_other ?? 0)
            ) }}

          </span>
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">
         {{ (
                ($details['expense_details']->total_cash ?? 0) + 
                ($details['expense_details']->total_cheque ?? 0) +  
                ($details['expense_details']->total_card ?? 0) +
                ($details['expense_details']->total_bank_transfer ?? 0) +
                ($details['expense_details']->total_advance ?? 0) +
                (array_key_exists('custom_pay_1', $payment_types) ? $details['expense_details']->total_custom_pay_1 : 0) +
                (array_key_exists('custom_pay_2', $payment_types) ? $details['expense_details']->total_custom_pay_2 : 0) +
                (array_key_exists('custom_pay_3', $payment_types) ? $details['expense_details']->total_custom_pay_3 : 0) +
                (array_key_exists('custom_pay_4', $payment_types) ? $details['expense_details']->total_custom_pay_4 : 0) +
                (array_key_exists('custom_pay_5', $payment_types) ? $details['expense_details']->total_custom_pay_5 : 0) +
                (array_key_exists('custom_pay_6', $payment_types) ? $details['expense_details']->total_custom_pay_6 : 0) +
                (array_key_exists('custom_pay_7', $payment_types) ? $details['expense_details']->total_custom_pay_7 : 0) +
                ($details['expense_details']->total_other ?? 0)
            ) }}</span>
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">
            {{ (
                ($details['purchase_details']->total_cash ?? 0) + 
                ($details['purchase_details']->total_cheque ?? 0) +  
                ($details['purchase_details']->total_card ?? 0) +
                ($details['purchase_details']->total_bank_transfer ?? 0) +
                ($details['purchase_details']->total_advance ?? 0) +
                (array_key_exists('custom_pay_1', $payment_types) ? $details['purchase_details']->total_custom_pay_1 : 0) +
                (array_key_exists('custom_pay_2', $payment_types) ? $details['purchase_details']->total_custom_pay_2 : 0) +
                (array_key_exists('custom_pay_3', $payment_types) ? $details['purchase_details']->total_custom_pay_3 : 0) +
                (array_key_exists('custom_pay_4', $payment_types) ? $details['purchase_details']->total_custom_pay_4 : 0) +
                (array_key_exists('custom_pay_5', $payment_types) ? $details['purchase_details']->total_custom_pay_5 : 0) +
                (array_key_exists('custom_pay_6', $payment_types) ? $details['purchase_details']->total_custom_pay_6 : 0) +
                (array_key_exists('custom_pay_7', $payment_types) ? $details['purchase_details']->total_custom_pay_7 : 0) +
                ($details['purchase_details']->total_other ?? 0)
            ) }}
            <!-- {{ ($details['purchase_transaction_details']->total_purchase + $register_details->total_expense) == 0 ? '৳ 0.00' : ($details['purchase_transaction_details']->total_purchase + $register_details->total_expense) }} -->
          </span>
        </td>
      </tr>
    </table>
    <hr>
    <!-- purchase -->
    <table class="table table-condensed">
      <tr>
        <td>
          <!-- @lang('cash_register.total_sales'): -->
          Total Purchase :
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $details['purchase_transaction_details']->total_purchase ?? '৳ 0.00' }}</span>
        </td>
      </tr>
      <!-- <tr class="danger">
        <th>
          @lang('cash_register.total_purchase_refund')
        </th>
        <td>
          <b><span class="display_currency" data-currency_symbol="true">{{ $register_details->total_refund ?? '৳ 0.00' }}</span></b><br>
          <small>
          @if($register_details->total_cash_refund != 0)
            Cash: <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_cash_refund ?? '৳ 0.00' }}</span><br>
          @endif
          @if($register_details->total_cheque_refund != 0) 
            Cheque: <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_cheque_refund ?? '৳ 0.00' }}</span><br>
          @endif
          @if($register_details->total_card_refund != 0) 
            Card: <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_card_refund ?? '৳ 0.00' }}</span><br> 
          @endif
          @if($register_details->total_bank_transfer_refund != 0)
            Bank Transfer: <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_bank_transfer_refund ?? '৳ 0.00' }}</span><br>
          @endif
          @if(array_key_exists('custom_pay_1', $payment_types) && $register_details->total_custom_pay_1_refund != 0)
              {{$payment_types['custom_pay_1']}}: <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_custom_pay_1_refund ?? '৳ 0.00' }}</span>
          @endif
          @if(array_key_exists('custom_pay_2', $payment_types) && $register_details->total_custom_pay_2_refund != 0)
              {{$payment_types['custom_pay_2']}}: <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_custom_pay_2_refund ?? '৳ 0.00' }}</span>
          @endif
          @if(array_key_exists('custom_pay_3', $payment_types) && $register_details->total_custom_pay_3_refund != 0)
              {{$payment_types['custom_pay_3']}}: <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_custom_pay_3_refund ?? '৳ 0.00' }}</span>
          @endif
          @if($register_details->total_other_refund != 0)
            Other: <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_other_refund ?? '৳ 0.00' }}</span>
          @endif
          </small>
        </td>
      </tr> -->
      <tr class="danger">
        <th>
          @lang('cash_register.total_purchase_return')
        </th>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $details['purchase_return']->total_purchase_return ?? '৳ 0.00' }}</span>
        </td>
      </tr>
      <tr class="success">
        <th>
          @lang('lang_v1.total_payment')
        </th>
        <td>
          <b><span class="display_currency" data-currency_symbol="true">{{ $details['credit_purchase_details']->amount_paid ?? '৳ 0.00'}}</span></b>
        </td>
      </tr>
      <tr class="success">
        <th>
          @lang('lang_v1.supplier_opening_payment')
        </th>
        <td>
          <b><span class="display_currency" data-currency_symbol="true">{{ $details['supplier_opening_payment']->opening_balance_paid ?? '৳ 0.00'}}</span></b>
        </td>
      </tr>
      <tr class="success">
        <th>
          @lang('lang_v1.credit_purchase'):
        </th>
        <td>
          <b><span class="display_currency" data-currency_symbol="true">{{ ($details['purchase_transaction_details']->total_purchase - $details['credit_purchase_details']->amount_paid) }}</span></b>
        </td>
      </tr>
      <tr class="success">
        <th>
          @lang('cash_register.total_purchase_discount'):
        </th>
        <td>
          <b><span class="display_currency" data-currency_symbol="true">{{ $details['purchase_transaction_details']->total_discount  ?? '৳ 0.00'}}</span></b>
        </td>
      </tr>
      <tr class="danger">
        <th>
          @lang('cash_register.total_purchase_vat'):
        </th>
        <td>
          <b><span class="display_currency" data-currency_symbol="true">{{ $details['purchase_transaction_details']->total_tax ?? '৳ 0.00' }}</span></b>
        </td>
      </tr>
    </table>
    
    <!-- purchase table details -->
    <div class="row" id="product_purchase_details_div">
      <div class="col-md-12">
        <hr>
        <h3>@lang('lang_v1.product_purchase_details_register')</h3>
        <table class="table table-condensed">
          <tr>
            <th>#</th>
            <th>@lang('product.sku')</th>
            <th>@lang('sale.product')</th>
            <th>@lang('sale.qty')</th>
            <th>@lang('sale.total_amount')</th>
          </tr>
          @php
            $total_amount = 0;
            $total_quantity = 0;
          @endphp
          @foreach($details['product_purchase_details'] as $detail)
            <tr>
              <td>
                {{$loop->iteration}}.
              </td>
              <td>
                {{$detail->sku}}
              </td>
              <td>
                {{$detail->product_name}}
                @if($detail->type == 'variable')
                {{$detail->product_variation_name}} - {{$detail->variation_name}}
                @endif
              </td>
              <td>
                {{@format_quantity($detail->total_quantity)}}
                @php
                  $total_quantity += $detail->total_quantity;
                @endphp
              </td>
              <td>
                <span class="display_currency" data-currency_symbol="true">
                  {{$detail->total_amount}}
                </span>
                @php
                  $total_amount += $detail->total_amount;
                @endphp
              </td>
            </tr>
          @endforeach

          
          @php
            $total_amount += ($details['purchase_transaction_details']->total_tax - $details['purchase_transaction_details']->total_discount);

            $total_amount += $details['purchase_transaction_details']->total_shipping_charges;
          @endphp

          <!-- Final details -->
          <tr class="success">
            <th>#</th>
            <th></th>
            <th></th>
            <th>{{$total_quantity}}</th>
            <th>

              @if($details['purchase_transaction_details']->total_tax != 0)
                @lang('sale.order_tax'): (+)
                <span class="display_currency" data-currency_symbol="true">
                  {{$details['purchase_transaction_details']->total_tax}}
                </span>
                <br/>
              @endif

              @if($details['purchase_transaction_details']->total_discount != 0)
                @lang('sale.discount'): (-)
                <span class="display_currency" data-currency_symbol="true">
                  {{$details['purchase_transaction_details']->total_discount}}
                </span>
                <br/>
              @endif
              @if($details['purchase_transaction_details']->total_shipping_charges != 0)
                @lang('lang_v1.total_shipping_charges'): (+)
                <span class="display_currency" data-currency_symbol="true">
                  {{$details['purchase_transaction_details']->total_shipping_charges}}
                </span>
                <br/>
              @endif

              @lang('lang_v1.grand_total'):
              <span class="display_currency" data-currency_symbol="true">
                {{$total_amount}}
              </span>
            </th>
          </tr>

        </table>
      </div>
    </div>
    <hr>

    <!-- sales -->
    <table class="table table-condensed">
      <tr>
        <td>
          @lang('cash_register.total_sales'):
        </td>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $details['transaction_details']->total_sales ?? '৳ 0.00' }}</span>
        </td>
      </tr>
      <!-- <tr class="danger">
        <th>
          @lang('cash_register.total_refund')
        </th>
        <td>
          <b><span class="display_currency" data-currency_symbol="true">{{ $register_details->total_refund ?? '৳ 0.00' }}</span></b><br>
          <small>
          @if($register_details->total_cash_refund != 0)
            Cash: <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_cash_refund ?? '৳ 0.00' }}</span><br>
          @endif
          @if($register_details->total_cheque_refund != 0) 
            Cheque: <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_cheque_refund ?? '৳ 0.00' }}</span><br>
          @endif
          @if($register_details->total_card_refund != 0) 
            Card: <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_card_refund ?? '৳ 0.00' }}</span><br> 
          @endif
          @if($register_details->total_bank_transfer_refund != 0)
            Bank Transfer: <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_bank_transfer_refund ?? '৳ 0.00' }}</span><br>
          @endif
          @if(array_key_exists('custom_pay_1', $payment_types) && $register_details->total_custom_pay_1_refund != 0)
              {{$payment_types['custom_pay_1']}}: <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_custom_pay_1_refund  ?? '৳ 0.00'}}</span>
          @endif
          @if(array_key_exists('custom_pay_2', $payment_types) && $register_details->total_custom_pay_2_refund != 0)
              {{$payment_types['custom_pay_2']}}: <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_custom_pay_2_refund ?? '৳ 0.00' }}</span>
          @endif
          @if(array_key_exists('custom_pay_3', $payment_types) && $register_details->total_custom_pay_3_refund != 0)
              {{$payment_types['custom_pay_3']}}: <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_custom_pay_3_refund ?? '৳ 0.00' }}</span>
          @endif
          @if($register_details->total_other_refund != 0)
            Other: <span class="display_currency" data-currency_symbol="true">{{ $register_details->total_other_refund ?? '৳ 0.00' }}</span>
          @endif
          </small>
        </td>
      </tr> -->
      <tr class="danger">
        <th>
          @lang('cash_register.total_sell_return')
        </th>
        <td>
          <span class="display_currency" data-currency_symbol="true">{{ $details['sell_return']->total_sell_return ?? '৳ 0.00' }}</span>
        </td>
      </tr>
      <tr class="success">
        <th>
          @lang('lang_v1.opening_payment_received')
        </th>
        <td>
          <b><span class="display_currency" data-currency_symbol="true">{{ $details['customer_opening_payment']->opening_balance_paid ?? '৳ 0.00'}}</span></b>
        </td>
      </tr>
      <tr class="success">
        <th>
          @lang('lang_v1.total_payment_received')
        </th>
        <td>
          <!-- <b><span class="display_currency" data-currency_symbol="true">{{ $register_details->cash_in_hand + $register_details->total_cash - $register_details->total_cash_refund == 0 ? '৳ 0.00' : $register_details->cash_in_hand + $register_details->total_cash - $register_details->total_cash_refund  }}</span></b> -->
          <b><span class="display_currency" data-currency_symbol="true">{{$details['amount_paid']->amount_paid }}</span></b>
        </td>
      </tr>
      <tr class="success">
        <th>
          @lang('lang_v1.credit_sales'):
        </th>
        <td>
          <!-- <b><span class="display_currency" data-currency_symbol="true">{{ ($details['transaction_details']->total_sales - $register_details->total_sale) == 0 ? '৳ 0.00' : ($details['transaction_details']->total_sales - $register_details->total_sale) }}</span></b> -->
          <b><span class="display_currency" data-currency_symbol="true">{{ ($details['transaction_details']->total_sales - $details['amount_paid']->amount_paid  ) }}</span></b>
        </td>
      </tr>
      <tr class="success">
        <th>
          @lang('cash_register.total_discount'):
        </th>
        <td>
          <b><span class="display_currency" data-currency_symbol="true">{{ $details['transaction_details']->total_discount  ?? '৳ 0.00'}}</span></b>
        </td>
      </tr>
      <tr class="success">
        <th>
          @lang('cash_register.total_vat'):
        </th>
        <td>
          <b><span class="display_currency" data-currency_symbol="true">{{ $details['transaction_details']->total_tax ?? '৳ 0.00' }}</span></b>
        </td>
      </tr>
      <tr class="danger">
        <th>
          @lang('report.total_expense'):
        </th>
        <td>
          <b><span class="display_currency" data-currency_symbol="true">{{ $details['expense_transaction_details']->total_expense ?? '৳ 0.00' }}</span></b>
        </td>
      </tr>
    </table>
  </div>
</div>

@include('cash_register.register_product_details')