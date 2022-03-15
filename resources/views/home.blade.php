@extends('layouts.app')

@section('content')
<div>
    @if(tenant())
        <tenant-layout />

    @else
        <layout />
    @endif
    
</div>

@endsection

