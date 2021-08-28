@extends(backpack_view('blank'))

@php

@endphp

@section('content')
<div class="row">
        <div class="col-sm-6 col-lg-4" >
            <div class="card text-white bg-primary">
                <div class="card-body pb-0"  style="margin-bottom: 2rem;">
                    <div class="text-value">{{ App\Models\Item::where('action', 1)->count() }}</div>
                    <div><i class="nav-icon las la-database"></i> გაცემული მოწყობილობები</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4" >
            <div class="card text-white bg-info">
                <div class="card-body pb-0"  style="margin-bottom: 2rem;">
                    <div class="text-value">{{ App\Models\Person::where('status', 1)->count() }}</div>
                    <div><i class="nav-icon las la-user-tie"></i> აქტიური პირები</div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4"  style="margin-bottom: 2rem;">
            <div class="card text-white bg-success">
                <div class="card-body pb-0"  style="margin-bottom: 2rem;">
                    <div class="text-value">{{ App\Models\Item::where('status', 1)->count() }}</div>
                    <div><i class="nav-icon las la-laptop"></i> აქტიური მოწყობილობები</div>
                </div>
            </div>
        </div>
        @if(backpack_user()->hasPermissionTo(config('permissions.statistics.view.key')))
            <div class="col-6 col-sm-4 col-md mb-3 mb-xl-0 text-center">
                <a class="btn btn-lg btn-primary" href="{{ url('admin/statistic/persons') }}">
                    სტატისტიკის გვერდი
                </a>
            </div>
        @endif
        <!-- /.col-->
    </div>
@endsection
