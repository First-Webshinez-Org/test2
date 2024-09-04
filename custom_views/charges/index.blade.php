@extends('layouts.app')
@section('title', __('lang_v1.charges'))

@section('content')

<!-- Content Header (Page header) -->
<section class="content-header">
    <h1>@lang('lang_v1.charges')
    </h1>
</section>

<!-- Main content -->
<section class="content">
    @component('components.widget', ['class' => 'box-primary', 'title' => __( 'lang_v1.all_charges' )])
        @slot('tool')
            <div class="box-tools">
                <button type="button" class="btn btn-block btn-primary btn-modal" 
                    data-href="{{action([\App\Http\Controllers\ChargeController::class, 'create'])}}" 
                    data-container=".view_modal">
                    <i class="fa fa-plus"></i> @lang( 'messages.add' )</button>
            </div>
        @endslot
        <table class="table table-bordered table-striped" id="charge_table">
            <thead>
                <tr>
                    <th>@lang( 'lang_v1.name' )</th>
                    <th>@lang( 'lang_v1.description' )</th>
                    <!-- <th>@lang( 'lang_v1.duration' )</th> -->
                    <th>@lang( 'messages.action' )</th>
                </tr>
            </thead>
        </table>
    @endcomponent

</section>
<!-- /.content -->
@stop

@section('javascript')
<script type="text/javascript">
    $(document).ready( function(){
        //Status table
        var charge_table = $('#charge_table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{action([\App\Http\Controllers\ChargeController::class, 'index'])}}",
                columnDefs: [ {
                    "targets": 2,
                    "orderable": false,
                    "searchable": false
                } ],
                columns: [
                    { data: 'name', name: 'name' },
                    { data: 'description', name: 'description' },
                    // { data: 'duration', name: 'duration' },
                    { data: 'action', name: 'action' },
                ]
            });

        $(document).on('submit', 'form#charge_form', function(e){
            e.preventDefault();
            $(this).find('button[type="submit"]').attr('disabled', true);
            var data = $(this).serialize();

            $.ajax({
                method: $(this).attr('method'),
                url: $(this).attr("action"),
                dataType: "json",
                data: data,
                success: function(result){
                    if(result.success == true){
                        $('div.view_modal').modal('hide');
                        toastr.success(result.msg);
                        charge_table.ajax.reload();
                    } else {
                        toastr.error(result.msg);
                    }
                }
            });
        });

        // charge delete
        $(document).on('click', 'button.delete_charge_button', function () {
            swal({
                title: LANG.sure,
                text: LANG.confirm_delete_charge,
                icon: 'warning',
                buttons: true,
                dangerMode: true,
            }).then((willDelete) => {
                if (willDelete) {
                    var href = $(this).data('href');
                    var data = $(this).serialize();

                    $.ajax({
                        method: 'DELETE',
                        url: href,
                        dataType: 'json',
                        data: data,
                        success: function (result) {
                            if (result.success == true) {
                                toastr.success(result.msg);
                                charge_table.ajax.reload();
                            } else {
                                toastr.error(result.msg);
                            }
                        },
                    });
                }
            });
        });
    });
</script>
@endsection
