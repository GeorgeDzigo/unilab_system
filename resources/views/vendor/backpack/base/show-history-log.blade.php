@extends(backpack_view('blank'))
<script src={{ URL::asset('js/jquery.js') }}></script>  
<title>Person Item History</title>

@section('content')

<nav aria-label="breadcrumb" class="d-none d-lg-block">
    <ol class="breadcrumb bg-transparent p-0 justify-content-end">
        <li class="breadcrumb-item text-capitalize"><a href="{{ backpack_url('dashboard') }}">@lang('Admin')</a></li>
        <li class="breadcrumb-item text-capitalize"><a href="{{ backpack_url('person') }}">@lang('Person')</a></li>
        <li class="breadcrumb-item text-capitalize active" aria-current="page">@lang('Technic')</li>
    </ol>
</nav>

<div class="row">
    <section class="container-fluid">
        <h2>
            <span class="text-capitalize">{{App\Models\Person::personFullName($personId)}}</span>
            <small>@lang('Product history')</small>
            <small>
                <a href="{{ backpack_url('person') }}" class="hidden-print font-sm">
                    @lang('Back to all person list')
                </a>
            </small>
        </h2>
    </section>

    <form action="{{ request()->fullUrl() }}" method="GET" id="form1" class="mx-sm-3 form-inline" style="margin-top: 12px; margin-bottom: 2px;">
        <div class="form-group mb-2">
            <input type="search" name="search" class="form-control form-control-sm" placeholder="Search" style="border-radius: 20px">
            <button type="submit" class="btn btn-secondary" style="margin-left:7px; height: 30px; font-size: 12px">search</button>
        </div>

        <div class="form-group mx-sm-2 mb-2">
            <div class="dropdown">
                <button class="btn btn-secondary dropdown-toggle" id="dropdownMenu2" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height: 30px; font-size: 12px">Action</button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenu2" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 34px, 0px); top: 0px; left: 0px; will-change: transform;">
                    <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['act' => 'active']) }} ">გატანილი ნივთები</a>
                    <a class="dropdown-item" href="{{ request()->fullUrlWithQuery(['act' => 'pasive']) }} ">დაბრუნებული ნივთები</a>
                </div>
            </div>
        </div>

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
                            <thead>
                            <tr role="row">
                                <th data-orderable="true" data-priority="1" data-visible-in-table="false" data-visible="true" data-can-be-visible-in-table="true" data-visible-in-modal="true" data-visible-in-export="true" data-force-export="false" class="sorting" tabindex="0" aria-controls="crudTable" rowspan="1" colspan="1" aria-label="activate to sort column ascending">
                                    @lang('ნივთის დასახელება')
                                </th>
                                <th data-orderable="true" data-priority="1" data-visible-in-table="false" data-visible="true" data-can-be-visible-in-table="true" data-visible-in-modal="true" data-visible-in-export="true" data-force-export="false" class="sorting" tabindex="0" aria-controls="crudTable" rowspan="1" colspan="1" aria-label="activate to sort column ascending">
                                    @lang('დაბრუნების დრო')
                                </th>
                                <th data-orderable="true" data-priority="1" data-visible-in-table="false" data-visible="true" data-can-be-visible-in-table="true" data-visible-in-modal="true" data-visible-in-export="true" data-force-export="false" class="sorting" tabindex="0" aria-controls="crudTable" rowspan="1" colspan="1" aria-label="activate to sort column ascending">
                                    @lang('გამოყენების დრო')
                                </th>
                                <th data-orderable="true" data-priority="1" data-visible-in-table="false" data-visible="true" data-can-be-visible-in-table="true" data-visible-in-modal="true" data-visible-in-export="true" data-force-export="false" class="sorting" tabindex="0" aria-controls="crudTable" rowspan="1" colspan="1" aria-label="activate to sort column ascending">
                                    @lang('გატანის თარიღი')
                                </th>
                                <th data-orderable="true" data-priority="1" data-visible-in-table="false" data-visible="true" data-can-be-visible-in-table="true" data-visible-in-modal="true" data-visible-in-export="true" data-force-export="false" class="sorting" tabindex="0" aria-controls="crudTable" rowspan="1" colspan="1" aria-label="activate to sort column ascending">
                                    @lang('ნივთის კატეგორია')
                                </th>
                                <th data-orderable="true" data-priority="1" data-visible-in-table="false" data-visible="true" data-can-be-visible-in-table="true" data-visible-in-modal="true" data-visible-in-export="true" data-force-export="false" class="sorting" tabindex="0" aria-controls="crudTable" rowspan="1" colspan="1" aria-label="activate to sort column ascending">
                                    @lang('გამტანის სახელი')
                                </th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($datas as $data)
                                <tr role="row" class="odd">
                                    <td class="sorting_1">{{$data['name']}}</td>
                                    <td>{{$data['date']}}</td>
                                    <td>{{$data['time']}}</td>
                                    <td>{{$data['create_date']}}
                                    <td><span class="badge">{{$data['categorical']}}</span></td>
                                    <td>{{$data['exporter']}}</td>
                                </tr>
                            @empty
                                <td colspan=6><center>@lang('Empty')</center></td>
                            @endforelse 
                        </tbody>

                        <tfoot>
                            <tr>
                                <th rowspan="1" colspan="1">
                                    @lang('ნივთის დასახელება')
                                </th>
                                <th rowspan="1" colspan="1">
                                    @lang('დაბრუნების დრო')
                                </th>
                                <th rowspan="1" colspan="1">
                                    @lang('გამოყენების დრო')
                                </th>
                                <th rowspan="1" colspan="1">
                                    @lang('გატანის თარიღი')
                                </th>
                                <th rowspan="1" colspan="1">
                                    @lang('ნივთის კატეგორია')
                                </th>
                                <th rowspan="1" colspan="1">
                                    @lang('გამტანის სახელი')
                                </th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{ $datas->render() }}

</div>

<script>$('#date1').change(function(){              
    $('#form1').submit();
});

</script>
@endsection




