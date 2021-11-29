<div class="bread-crumbs">
	@foreach ($arrayCrumbs as $key => $linkCrumbs)
	    <a href="@if($linkCrumbs) {{ $linkCrumbs }} @else javascript:; @endif">{{ $key }}</a>
	    <i>&middot;</i>
	@endforeach
</div>