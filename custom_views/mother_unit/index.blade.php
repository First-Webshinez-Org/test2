@extends('layouts.app')
@section('title', __( 'unit.mother_units' ))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang( 'unit.mother_units' )
        <small>@lang( 'unit.manage_your_mother_units' )</small>
    </h1>
</section>

<!-- Main content -->
<section class="content">
    @component('components.widget', ['class' => 'box-primary', 'title' => __( 'unit.all_your_mother_units' )])
        @can('mother_unit.create')
            @slot('tool')
                <div class="box-tools">
                    <button type="button" class="btn btn-block btn-primary btn-modal" 
                        data-href="{{action([\App\Http\Controllers\MotherUnitController::class, 'create'])}}" 
                        data-container=".mother_unit_modal">
                        <i class="fa fa-plus"></i> @lang( 'messages.add' )</button>
                </div>
            @endslot
        @endcan
        @can('mother_unit.view')
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="mother_unit_table">
                    <thead>
                        <tr>
                            <th>@lang( 'unit.name' )</th>
                            <th>@lang( 'messages.action' )</th>
                        </tr>
                    </thead>
                </table>
            </div>
        @endcan
    @endcomponent

    <div class="modal fade mother_unit_modal" tabindex="-1" role="dialog" 
    	aria-labelledby="gridSystemModalLabel">
    </div>

</section>
<!-- /.content -->

@endsection
