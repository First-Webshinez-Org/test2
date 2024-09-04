<?php

namespace App\Http\Controllers;

use App\Charge;
use App\TransactionSellLine;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ChargeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $business_id = request()->session()->get('user.business_id');

        if (request()->ajax()) {
            $charges = Charge::where('business_id', $business_id)
                         ->select(['id', 'name', 'description']);

            return Datatables::of($charges)
                ->addColumn(
                    'action',
                    '<button data-href="{{action(\'App\Http\Controllers\ChargeController@edit\', [$id])}}" class="btn btn-xs btn-primary btn-modal" data-container=".view_modal"><i class="glyphicon glyphicon-edit"></i> @lang("messages.edit")</button> <button data-href="{{action(\'App\Http\Controllers\ChargeController@destroy\', [$id])}}" class="btn btn-xs btn-danger delete_charge_button"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</button>'
                 )
                 ->removeColumn('id')
                 ->rawColumns(['action'])
                 ->make(true);
        }

        return view('charges.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('charges.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $business_id = request()->session()->get('user.business_id');

        try {
            $input = $request->only(['name', 'description']);
            $input['business_id'] = $business_id;

            $status = Charge::create($input);

            $output = ['success' => true,
                'msg' => __('lang_v1.added_success'),
            ];
        } catch (\Exception $e) {
            \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

            $output = ['success' => false,
                'msg' => __('messages.something_went_wrong'),
            ];
        }

        return $output;
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Charge  $Charge
     * @return \Illuminate\Http\Response
     */
    public function show(Charge $Charge)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Charge  $Charge
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $business_id = request()->session()->get('user.business_id');

        if (request()->ajax()) {
            $charge = Charge::where('business_id', $business_id)->find($id);

            return view('charges.edit')
                ->with(compact('charge'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Charge  $Charge
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $business_id = request()->session()->get('user.business_id');

        if (request()->ajax()) {
            try {
                $input = $request->only(['name', 'description']);

                $Charge = Charge::where('business_id', $business_id)->findOrFail($id);

                $Charge->update($input);

                $output = ['success' => true,
                    'msg' => __('lang_v1.updated_success'),
                ];
            } catch (\Exception $e) {
                \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

                $output = ['success' => false,
                    'msg' => __('messages.something_went_wrong'),
                ];
            }

            return $output;
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Charge  $Charge
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        if (! auth()->user()->can('unit.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $business_id = request()->user()->business_id;

                $charge = Charge::where('business_id', $business_id)->findOrFail($id);

                //check if any product associated with the unit
                // $exists = TransactionSellLine::where('charge_id', $charge->id)
                //                 ->exists();
                $exists = false;

                if (! $exists) {
                    $charge->delete();
                    $output = ['success' => true,
                        'msg' => __('unit.deleted_success'),
                    ];
                } else {
                    $output = ['success' => false,
                        'msg' => __('lang_v1.charge_cannot_be_deleted'),
                    ];
                }
            } catch (\Exception $e) {
                \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

                $output = ['success' => false,
                    'msg' => '__("messages.something_went_wrong")',
                ];
            }

            return $output;
        }
    }
}
