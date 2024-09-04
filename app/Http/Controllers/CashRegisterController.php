<?php

namespace App\Http\Controllers;

use App\Account;
use App\AccountTransaction;
use App\BusinessLocation;
use App\CashRegister;
use App\CashRegisterTransaction;
use App\Utils\ContactUtil;
use App\Utils\BusinessUtil;
use App\Utils\CashRegisterUtil;
use App\Utils\ModuleUtil;
use Illuminate\Http\Request;
use DB;
use Yajra\DataTables\Facades\DataTables;

class CashRegisterController extends Controller
{
    /**
     * All Utils instance.
     */
    protected $cashRegisterUtil;

    protected $moduleUtil;

    protected $contactUtil;

    protected $businessUtil;
    /**
     * Constructor
     *
     * @param  CashRegisterUtil  $cashRegisterUtil
     * @return void
     */
    public function __construct( 
        BusinessUtil $businessUtil,
        CashRegisterUtil $cashRegisterUtil, 
        ModuleUtil $moduleUtil, 
        ContactUtil $contactUtil
    )
    {
        $this->cashRegisterUtil = $cashRegisterUtil;
        $this->businessUtil = $businessUtil;
        $this->moduleUtil = $moduleUtil;
        $this->contactUtil = $contactUtil;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('cash_register.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //like:repair
        $sub_type = request()->get('sub_type');

        //Check if there is a open register, if yes then redirect to POS screen.
        if ($this->cashRegisterUtil->countOpenedRegister() != 0) {
            return redirect()->action([\App\Http\Controllers\SellPosController::class, 'create'], ['sub_type' => $sub_type]);
        }
        $business_id = request()->session()->get('user.business_id');
        $business_locations = BusinessLocation::forDropdown($business_id);

        return view('cash_register.create')->with(compact('business_locations', 'sub_type'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //like:repair
        $sub_type = request()->get('sub_type');

        try {
            $initial_amount = 0;
            if (! empty($request->input('amount'))) {
                $initial_amount = $this->cashRegisterUtil->num_uf($request->input('amount'));
            }
            $user_id = $request->session()->get('user.id');
            $business_id = $request->session()->get('user.business_id');

            $register = CashRegister::create([
                'business_id' => $business_id,
                'user_id' => $user_id,
                'status' => 'open',
                'location_id' => $request->input('location_id'),
                'created_at' => \Carbon::now()->format('Y-m-d H:i:00'),
            ]);
            if (! empty($initial_amount)) {
                $register->cash_register_transactions()->create([
                    'amount' => $initial_amount,
                    'pay_method' => 'cash',
                    'type' => 'credit',
                    'transaction_type' => 'initial',
                ]);
            }
        } catch (\Exception $e) {
            \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());
        }

        return redirect()->action([\App\Http\Controllers\SellPosController::class, 'create'], ['sub_type' => $sub_type]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\CashRegister  $cashRegister
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (! auth()->user()->can('view_cash_register')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');

        $register_details = $this->cashRegisterUtil->getRegisterDetails($id);
        $user_id = $register_details->user_id;
        $open_time = $register_details['open_time'];
        $close_time = ! empty($register_details['closed_at']) ? $register_details['closed_at'] : \Carbon::now()->toDateTimeString();
        $details = $this->cashRegisterUtil->getRegisterTransactionDetails($user_id, $open_time, $close_time);

        $payment_types = $this->cashRegisterUtil->payment_types(null, false, $business_id);

        return view('cash_register.register_details')
                    ->with(compact('register_details', 'details', 'payment_types', 'close_time'));
    }

    /**
     * Shows register details modal.
     *
     * @param  void
     * @return \Illuminate\Http\Response
     */
    public function getRegisterDetails(Request $request)
    {
        if (! auth()->user()->can('view_cash_register')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');
        $business_locations = BusinessLocation::forDropdown($business_id, true);

        $is_admin = $this->businessUtil->is_admin(auth()->user());

        $register_details = $this->cashRegisterUtil->getRegisterDetails();

        $todayStart = \Carbon\Carbon::now()->startOfDay()->format('Y-m-d H:i:s');
        $todayEnd = \Carbon\Carbon::now()->endOfDay()->format('Y-m-d H:i:s');

        $user_id = auth()->user()->id;
        $open_time = $todayStart;
        $close_time = $todayEnd;
        // $open_time = $register_details['open_time'];
        // $close_time = \Carbon::now()->toDateTimeString();

        $is_types_of_service_enabled = $this->moduleUtil->isModuleEnabled('types_of_service');

        $details = $this->cashRegisterUtil->getRegisterTransactionDetails($user_id, $open_time, $close_time, $is_types_of_service_enabled);

        $payment_types = $this->cashRegisterUtil->payment_types($register_details->location_id, true, $business_id);

        // cash in hand
        $before_bal_query = AccountTransaction::join(
            'accounts as A',
            'account_transactions.account_id',
            '=',
            'A.id'
        )
        ->where('A.business_id', $business_id)
        ->where('A.id', 1)
        ->select([
            DB::raw('SUM(IF(account_transactions.type="credit", account_transactions.amount, -1 * account_transactions.amount)) as prev_bal'), ])
        ->where('account_transactions.operation_date', '<', $close_time)
        ->whereNull('account_transactions.deleted_at');
    
        $bal_before_start_date = $before_bal_query->first()->prev_bal;

        $user_details = [
            'user_name' => auth()->user()->surname . ' ' . auth()->user()->first_name . ' ' . auth()->user()->last_name,
            'email' => auth()->user()->email,
            'location_name' => 'All' ,
        ];

        // Return the details in ajax call
        if ($request->ajax()) {
            $start_date = $request->get('start_date');
            $end_date = $request->get('end_date');
            $location = $request->get('location');

            $register_details = $this->cashRegisterUtil->getRegisterDetails(null,$start_date, $end_date);

            $user_details = [
                'user_name' => auth()->user()->surname . ' ' . auth()->user()->first_name . ' ' . auth()->user()->last_name,
                'email' => auth()->user()->email,
                'location_name' => $location ? $business_locations[$location] : 'All' ,
            ];
            
            $user_id = auth()->user()->id;
            $open_time = $start_date ?? $todayStart;
            $close_time = $end_date ?? $todayEnd;

            $is_types_of_service_enabled = $this->moduleUtil->isModuleEnabled('types_of_service');

            $details = $this->cashRegisterUtil->getRegisterTransactionDetails($user_id, $open_time, $close_time, $is_types_of_service_enabled, $location, $is_admin);

            $payment_types = $this->cashRegisterUtil->payment_types($location ??$register_details->location_id, true, $business_id);
            
            // cash in hand
            $before_bal_query = AccountTransaction::join(
                'accounts as A',
                'account_transactions.account_id',
                '=',
                'A.id'
                )
                ->where('A.business_id', $business_id)
                ->where('A.id', 1)
                ->select([
                    DB::raw('SUM(IF(account_transactions.type="credit", account_transactions.amount, -1 * account_transactions.amount)) as prev_bal'), ])
                ->where('account_transactions.operation_date', '<', $end_date ?? $close_time)
                ->whereNull('account_transactions.deleted_at');
        
            $bal_before_start_date = $before_bal_query->first()->prev_bal;

            return view('cash_register.register_details')
                ->with(compact('bal_before_start_date','open_time','business_locations','register_details', 'details', 'payment_types', 'close_time','user_details'));
        }
        
        return view('cash_register.register_details')
                ->with(compact('bal_before_start_date','business_locations','register_details', 'details', 'payment_types', 'open_time', 'close_time','user_details'));
    }

    /**
     * Shows close register form.
     *
     * @param  void
     * @return \Illuminate\Http\Response
     */
    public function getCloseRegister($id = null)
    {
        if (! auth()->user()->can('close_cash_register')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');
        $register_details = $this->cashRegisterUtil->getRegisterDetails($id);

        $user_id = $register_details->user_id;
        $open_time = $register_details['open_time'];
        $close_time = \Carbon::now()->toDateTimeString();

        $is_types_of_service_enabled = $this->moduleUtil->isModuleEnabled('types_of_service');

        $details = $this->cashRegisterUtil->getRegisterTransactionDetails($user_id, $open_time, $close_time, $is_types_of_service_enabled);

        $payment_types = $this->cashRegisterUtil->payment_types($register_details->location_id, true, $business_id);

        $pos_settings = ! empty(request()->session()->get('business.pos_settings')) ? json_decode(request()->session()->get('business.pos_settings'), true) : [];

        return view('cash_register.close_register_modal')
                    ->with(compact('register_details', 'details', 'payment_types', 'pos_settings'));
    }

    /**
     * Closes currently opened register.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function postCloseRegister(Request $request)
    {
        if (! auth()->user()->can('close_cash_register')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            //Disable in demo
            if (config('app.env') == 'demo') {
                $output = ['success' => 0,
                    'msg' => 'Feature disabled in demo!!',
                ];

                return redirect()->action([\App\Http\Controllers\HomeController::class, 'index'])->with('status', $output);
            }

            $input = $request->only(['closing_amount', 'total_card_slips', 'total_cheques', 'closing_note']);
            $input['closing_amount'] = $this->cashRegisterUtil->num_uf($input['closing_amount']);
            $user_id = $request->input('user_id');
            $input['closed_at'] = \Carbon::now()->format('Y-m-d H:i:s');
            $input['status'] = 'close';
            $input['denominations'] = ! empty(request()->input('denominations')) ? json_encode(request()->input('denominations')) : null;

            CashRegister::where('user_id', $user_id)
                                ->where('status', 'open')
                                ->update($input);
            $output = ['success' => 1,
                'msg' => __('cash_register.close_success'),
            ];
        } catch (\Exception $e) {
            \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());
            $output = ['success' => 0,
                'msg' => __('messages.something_went_wrong'),
            ];
        }

        return redirect()->back()->with('status', $output);
    }
}
