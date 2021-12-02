<div class="bread-crumbs" style="cursor: pointer;">
	@foreach ($arrayCrumbs as $key => $linkCrumbs)
	<a href="@if($linkCrumbs) {{ $linkCrumbs }} @else javascript:; @endif">{{ $key }}</a>
	<i>&middot;</i>
	@endforeach
</div>