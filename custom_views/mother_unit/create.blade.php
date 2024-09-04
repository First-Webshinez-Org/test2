<div class="modal-dialog" role="document">
    <div class="modal-content">

        {!! Form::open([
            'url' => action([\App\Http\Controllers\MotherUnitController::class, 'store']),
            'method' => 'post',
            'id' => $quick_add ? 'quick_add_mother_unit_form' : 'mother_unit_add_form',
        ]) !!}

        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">@lang('unit.add_mother_unit')</h4>
        </div>

        <div class="modal-body">
            <div class="row">
                <div class="form-group col-sm-12">
                    {!! Form::label('mother_unit_name', __('unit.name') . ':*') !!}
                    {!! Form::text('mother_unit_name', null, [
                        'class' => 'form-control',
                        'required',
                        'placeholder' => __('Mother Unit Name'),
                    ]) !!}
                </div>
            </div>

        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-primary">@lang('messages.save')</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">@lang('messages.close')</button>
        </div>

        {!! Form::close() !!}

    </div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->
