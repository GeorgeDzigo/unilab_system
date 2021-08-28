@extends(backpack_view('blank'))
<script src={{ URL::asset('js/jquery.js') }}></script>  
<title>Person Event</title>

@section('content')

    <nav aria-label="breadcrumb" class="d-none d-lg-block">
        <ol class="breadcrumb bg-transparent p-0 justify-content-end">
            <li class="breadcrumb-item text-capitalize"><a href="{{ backpack_url('dashboard') }}">@lang('Admin')</a></li>
            <li class="breadcrumb-item text-capitalize"><a href="{{ backpack_url('person') }}">@lang('Person')</a></li>
            <li class="breadcrumb-item text-capitalize active" aria-current="page">@lang('Event')</li>
        </ol>
    </nav>
    
    <div class="row">
        <section class="container-fluid">
            <h2>
                <span class="text-capitalize">{{App\Models\Person::personFullName($personId)}}</span>
                <small>@lang('Event history')</small>
                <small>
                    <a href="{{ backpack_url('person') }}" class="hidden-print font-sm">
                        @lang('Back to all person list')
                    </a>
                </small>
            </h2>
        </section>  
    
        <form action="" method="GET" id="form1" >
            <div class="card-body" style="margin-bottom: -20px;">
                <input placeholder="-დან" name="from" type="text" onfocus="(this.type='date')" onblur="(this.type='text')" id="date" style="border: none; border-radius: 20px;"/>
                <input placeholder="-მდე" name="till" type="text" onfocus="(this.type='date')" onblur="(this.type='text')" id="date1" style="margin-left: 10px; border: none; border-radius: 20px;"/>
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
                                            @lang('თარიღი')
                                        </th>
                                        <th data-orderable="true" data-priority="1" data-visible-in-table="false" data-visible="true" data-can-be-visible-in-table="true" data-visible-in-modal="true" data-visible-in-export="true" data-force-export="false" class="sorting" tabindex="0" aria-controls="crudTable" rowspan="1" colspan="1" aria-label="activate to sort column ascending">
                                            @lang('შემოსვლის დრო')
                                        </th>
                                        <th data-orderable="true" data-priority="1" data-visible-in-table="false" data-visible="true" data-can-be-visible-in-table="true" data-visible-in-modal="true" data-visible-in-export="true" data-force-export="false" class="sorting" tabindex="0" aria-controls="crudTable" rowspan="1" colspan="1" aria-label="activate to sort column ascending">
                                            @lang('გასვლის დრო')
                                        </th>
                                        <th data-orderable="true" data-priority="1" data-visible-in-table="false" data-visible="true" data-can-be-visible-in-table="true" data-visible-in-modal="true" data-visible-in-export="true" data-force-export="false" class="sorting" tabindex="0" aria-controls="crudTable" rowspan="1" colspan="1" aria-label="activate to sort column ascending">
                                            @lang('დაყოვნების დრო')
                                        </th>
                                    </tr>
                                </thead>

                                <tbody>
                                    @forelse($events  as $event)
                                        <tr role="row" class="odd">
                                            <td>{{ $event[0] == "----" ? $event[1]->format('Y-m-d') : $event[0]->format('Y-m-d') }}
                                            </td>
                                            <td>{{ $event[0] == "----" ? $event[0] : $event[0]->format('H:i:s') }}</td>
                                            <td>{{ $event[1] == "----" ? $event[1] : $event[1]->format('H:i:s') }}</td>
                                            <td>{{ $event[2] }}</td>
                                        </tr>
                                    @empty
                                        <td colspan=4><center>@lang('Empty')</center></td>
                                    @endforelse 
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th rowspan="1" colspan="1">
                                            @lang('თარიღი')
                                        </th>
                                        <th rowspan="1" colspan="1">
                                            @lang('შემოსვლის დრო')
                                        </th>
                                        <th rowspan="1" colspan="1">
                                            @lang('გასვლის დრო')
                                        </th>
                                        <th rowspan="1" colspan="1">
                                            @lang('დაყოვნების დრო')
                                        </th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

{{ $events->links() }}

<script>$('#date1').change(function(){
    console.log('Submiting form');                
    $('#form1').submit();
});</script>
@endsection

