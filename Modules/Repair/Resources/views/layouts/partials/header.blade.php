@if($__is_repair_enabled)
	@can("repair.create")
		<a href="{{ action([\App\Http\Controllers\SellPosController::class, 'create']). '?sub_type=repair'}}" title="{{ __('repair::lang.add_repair') }}" data-toggle="tooltip" data-placement="bottom">
			<i class="fa fa-wrench fa-lg" aria-hidden="true"></i> @lang('repair::lang.repair')
		</a>
	@endcan
@endif