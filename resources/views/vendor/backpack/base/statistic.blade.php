@extends(backpack_view('blank'))
<script src={{ URL::asset('js/jquery.js') }}></script>
<title>{{ $action }} Statistics</title>

@section('content')

<nav aria-label="breadcrumb" class="d-none d-lg-block">
    <ol class="breadcrumb bg-transparent p-0 justify-content-end">
        <li class="breadcrumb-item text-capitalize"><a href="{{ backpack_url('dashboard') }}">@lang('Admin')</a></li>
        <li class="breadcrumb-item text-capitalize"><a href="{{ backpack_url('person') }}">@lang('Statistic')</a></li>
        <li class="breadcrumb-item text-capitalize active" aria-current="page">{{ $action }}</li>
    </ol>
</nav>

<div class="row">
    <section class="container-fluid">
        <h2>
            <span class="text-capitalize">
                @lang("Most Active "){{$action}}
            </span>
            <small>@lang('Product history')</small>
            <small>
                    <a href="{{ backpack_url('dashboard') }}" class="hidden-print font-sm">
                        @lang('Back to Dashboart')
                    </a>
            </small>
        </h2>
    </section>

    <form action="{{ request()->fullUrl() }}" method="GET" id="form1" class="mx-sm-3 form-inline" style="margin-top: 12px; margin-bottom: 2px;">
        <div class="form-group mb-2">
            <input name="search" type="number" value="{{ request()->get('search') }}"min="1" class="custom-select custom-select-sm form-control form-control-sm" placeholder="Show" style="border-radius: 20px">
            <button type="submit" class="btn btn-secondary" style="margin-left:7px; height: 30px; font-size: 12px">search</button>
        </div>

        <div class="form-group mx-sm-2 mb-2">
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" id="dropdownMenu2" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 30px; font-size: 12px">Action</button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenu2" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 34px, 0px); top: 0px; left: 0px; will-change: transform;">
                    <a class="dropdown-item" href="{{ backpack_url('statistic/persons') }}">@lang("აქტიური მომხმარებლების")</a>
                    <a class="dropdown-item" href="{{ backpack_url('statistic/teq') }}">@lang("აქტიური ნივთების")</a>
                    <a class="dropdown-item" href="{{ backpack_url('statistic/delay') }}">@lang("მომხმარებლების აქტივობა")</a>
                </div>
            </div>
        </div>

        @if($action == 'Items')
            <div class="form-group mx-sm-1 mb-2">
                <div class="btn-group" role="group">
                    <button class="btn btn-secondary dropdown-toggle" id="btnGroupVerticalDrop1" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 30px; font-size: 12px">კატეგორია</button>
                    <div class="dropdown-menu" aria-labelledby="btnGroupVerticalDrop1">
                        @foreach(App\Models\ItemType::all() as $itemType)
                            <button class="dropdown-item" type="submit" name="teqType" value="{{$itemType->name}}">{{$itemType->name}}</button>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif

        <div class="form-group mb-2">
            <input placeholder="-დან" name="from" type="text" onfocus="(this.type='date')" onblur="(this.type='text')" id="date" style="border: none; border-radius: 20px"/>
            <input placeholder="-მდე" name="till" type="text" onfocus="(this.type='date')" onblur="(this.type='text')" id="date1" style="margin-left: 10px; border: none; border-radius: 20px"/>
        </div>
    </form>

    <div class="col-sm-12">
        <div class="overflow-hidden mt-2">
            <div id="crudTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                <div class="row">
                    <div class="col-sm-12">
                        <table id="crudTable" class="bg-white table table-striped table-hover nowrap rounded shadow-xs border-xs dataTable dtr-inline" cellspacing="0" aria-describedby="crudTable_info" role="grid">
                            {{-- columns --}}
                                <thead>
                                    <tr role="row">
                                        @foreach($columns as $column)
                                            <th data-orderable="true" data-priority="1" data-visible-in-table="false" data-visible="true" data-can-be-visible-in-table="true" data-visible-in-modal="true" data-visible-in-export="true" data-force-export="false" class="sorting" tabindex="0" aria-controls="crudTable" rowspan="1" colspan="1" aria-label="activate to sort column ascending">
                                                {{$column}}
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                            {{-- end-columns --}}

                            {{-- items --}}
                                <tbody>
                                    @forelse($statisticData as $data)
                                        @if($action == 'Users')
                                            <tr role="row" class="odd">
                                                <td>{{$data->person->getFullName()}}</td>
                                                <td>{{$data->person->additional_info['contact']['mobile']}}</td>
                                                <td>{{$data->person->personal_number}}</td>
                                                <td>{{$data->person->unilab_email}}</td>
                                                <td>{{$data->statistic}}</td>
                                            </tr>
                                        @elseif($action == 'Users Delay')
                                            <tr role="row" class="odd">
                                                <td>{{$data["fullname"]}}</td>
                                                <td>{{$data["mobile"]}}</td>
                                                <td>{{$data["passport"]}}</td>
                                                <td>{{$data["email"]}}</td>
                                                <td>{{$data["diff"]}}</td>
                                            </tr>
                                        @elseif($action == 'Items')
                                            <tr role="row" class="odd">
                                                <td>{{ $data->item_id }}</td>
                                                <td>{{ $data->item->name }}</td>
                                                <td>{{ $data->item->type->name }}</td>
                                                <td>{{ $data->item->getStatusName() }}</td>
                                                <td>{{ $data->item->created_at }}</td>
                                                <td>{{ $data->statistic }}</td>
                                            </tr>
                                        @endif
                                    @empty
                                        <td colspan={{ count($columns) }}>
                                            <center>@lang('Empty')</center>
                                        </td>
                                    @endforelse
                                </tbody>
                            {{-- end-items --}}

                            {{-- footer-columns --}}
                                <tfoot>
                                    <tr>
                                        @foreach($columns as $column)
                                            <th rowspan="1" colspan="1">
                                                {{$column}}
                                            </th>
                                        @endforeach
                                    </tr>
                                </tfoot>
                            {{-- end-footer-columns --}}
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{ $statisticData->render() }}
</div>

<script>$('#date1').change(function(){
    console.log('Submiting form');
    $('#form1').submit();
});</script>
@endsection

