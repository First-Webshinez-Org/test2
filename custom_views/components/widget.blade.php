<div class="box {{$class ?? 'box-solid'}}" @if(!empty($id)) id="{{$id}}" @endif>
    @if(empty($header))
        @if(!empty($title) || !empty($tool))
        <div class="box-header">
            {!!$icon ?? '' !!}
            <h3 class="box-title">{{ $title ?? '' }}</h3>
            {!!$tool ?? ''!!}
            {!!$tool2 ?? ''!!} {{-- dev_ma --}}

            @if(isset($help_text))
                <br/>
                <small>{!!$help_text!!}</small>
            @endif
        </div>
        @endif
    @else
        <div class="box-header">
            {!! $header !!}
        </div>
    @endif

    <div class="box-body">
        {{$slot}}
    </div>
    <!-- /.box-body -->
</div>