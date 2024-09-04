<?php
// Full file added (dev_ma)
namespace App\Http\Controllers;

use App\Unit;
use App\MotherUnit;
use Illuminate\Http\Request;
use App\Utils\Util;
use Yajra\DataTables\Facades\DataTables;

class MotherUnitController extends Controller
{
     /**
     * All Utils instance.
     */
    protected $commonUtil;

    public function __construct(Util $commonUtil)
    {
        $this->commonUtil = $commonUtil;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (! auth()->user()->can('mother_unit.view') && ! auth()->user()->can('mother_unit.create')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');

            $mother_unit = MotherUnit::where('business_id', $business_id)
                        ->select(['name', 'id']);

            return Datatables::of($mother_unit)
                ->addColumn(
                    'action',
                    '@can("mother_unit.update")
                    <button data-href="{{action(\'App\Http\Controllers\MotherUnitController@edit\', [$id])}}" class="btn btn-xs btn-primary edit_mother_unit_button"><i class="glyphicon glyphicon-edit"></i> @lang("messages.edit")</button>
                        &nbsp;
                    @endcan
                    @can("mother_unit.delete")
                        <button data-href="{{action(\'App\Http\Controllers\MotherUnitController@destroy\', [$id])}}" class="btn btn-xs btn-danger delete_mother_unit_button"><i class="glyphicon glyphicon-trash"></i> @lang("messages.delete")</button>
                    @endcan'
                )
                ->removeColumn('id')
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('mother_unit.index');
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function create(){
        if (! auth()->user()->can('mother_unit.create')) {
            abort(403, 'Unauthorized action.');
        }

        $business_id = request()->session()->get('user.business_id');

        $quick_add = false;
        if (! empty(request()->input('quick_add'))) {
            $quick_add = true;
        }

        return view('mother_unit.create')
                ->with(compact('quick_add'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request){
        if (! auth()->user()->can('mother_unit.create')) {
            abort(403, 'Unauthorized action.');
        }

        try {
            $input = $request->only(['mother_unit_name']);
            $input['business_id'] = $request->session()->get('user.business_id');
            $input['created_by'] = $request->session()->get('user.id');

            //dev-nu
            $input['name'] = $request->input('mother_unit_name');

            //dev-nu
            $mother_unit = MotherUnit::create($input);
            $output = ['success' => true,
                'data' => $mother_unit,
                'msg' => __('unit.added_success'),
            ];
        } catch (\Exception $e) {
            \Log::emergency('File:'.$e->getFile().'Line:'.$e->getLine().'Message:'.$e->getMessage());

            $output = ['success' => false,
                'msg' => __('messages.something_went_wrong'),
            ];
        }

        //dev-nu
        return $output;
    }

    
    // dev-nu start

    /**
    * Show the form for editing the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function edit($id){
        if (! auth()->user()->can('unit.update')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            $business_id = request()->session()->get('user.business_id');
            $mother_unit = MotherUnit::where('business_id', $business_id)->find($id);

            return view('mother_unit.edit')
                ->with(compact('mother_unit'));
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if (! auth()->user()->can('unit.update')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $input = $request->only(['mother_unit_name']); // modified (dev_ma)
                $business_id = $request->session()->get('user.business_id');

                $mother_unit = MotherUnit::where('business_id', $business_id)->findOrFail($id);
                $mother_unit->name = $input['mother_unit_name'];

                $mother_unit->save();

                $output = ['success' => true,
                    'msg' => __('unit.updated_success'),
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
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){
        if (! auth()->user()->can('unit.delete')) {
            abort(403, 'Unauthorized action.');
        }

        if (request()->ajax()) {
            try {
                $business_id = request()->user()->business_id;

                $mother_unit = MotherUnit::where('business_id', $business_id)->findOrFail($id);

                //check if any product associated with the unit
                $exists = Unit::where('mother_unit_id', $mother_unit->id)
                                ->exists();

                if (! $exists) {
                    $mother_unit->delete();
                    $output = ['success' => true,
                        'msg' => __('unit.deleted_success'),
                    ];
                } else {
                    $output = ['success' => false,
                        'msg' => __('lang_v1.unit_cannot_be_deleted'),
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
    
    // dev-nu end

}
