@extends(backpack_view('layouts.top_left'))


@section('header')
    <section class="container-fluid">
        <h2>
            <span class="text-capitalize">{!! $crud->getHeading() ?? $crud->entity_name_plural !!}</span>
            <small>{!! $crud->getSubheading() ?? trans('backpack::crud.add').' '.$crud->entity_name !!}.</small>
            @if ($crud->hasAccess('list'))
                <small><a href="{{ url($crud->route) }}" class="hidden-print font-sm"><i class="la la-angle-double-left"></i> {{ trans('backpack::crud.back_to_all') }} <span>{{ $crud->entity_name_plural }}</span></a></small>
            @endif
        </h2>
    </section>
@endsection

@section('content')

{{--    <div class="row">--}}
{{--        <div class="{{ $crud->getCreateContentClass() }}">--}}
            <!-- Default box -->

            @include('crud::inc.grouped_errors')

            <div class="container-fluid animated fadeIn" id="app">

                <items-manage-component
                   :get-data-route="'{{ route('item_log.get_data') }}'"
                ></items-manage-component>

            </div>

{{--        </div>--}}
{{--    </div>--}}


@endsection


@section('after_scripts')

    <script src="{{ mix('js/app.js') }}"></script>

@endsection
