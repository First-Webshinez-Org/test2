<div class="modal-dialog" role="document">
  <div class="modal-content">

    {!! Form::open(['url' => action([\App\Http\Controllers\MotherUnitController::class, 'update'], [$mother_unit->id]), 'method' => 'PUT', 'id' => 'mother_unit_edit_form' ]) !!}

    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">@lang( 'unit.edit_mother_unit' )</h4>
    </div>

    <div class="modal-body">
      <div class="row">
        <div class="form-group col-sm-12">
          {!! Form::label('mother_unit_name', __( 'unit.name' ) . ':*') !!}
            {!! Form::text('mother_unit_name', $mother_unit->name, ['class' => 'form-control', 'required', 'placeholder' => __( 'mother_unit.name' )]); !!}
        </div>
      </div>
    </div>

    <div class="modal-footer">
      <button type="submit" class="btn btn-primary">@lang( 'messages.update' )</button>
      <button type="button" class="btn btn-default" data-dismiss="modal">@lang( 'messages.close' )</button>
    </div>

    {!! Form::close() !!}

  </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->