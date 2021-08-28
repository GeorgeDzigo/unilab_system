<title>@lang('Template Dashboard')</title>
@extends('vendor.backpack.base.email.template.send_form.main')


@section('head')
    <nav aria-label="breadcrumb" class="d-none d-lg-block">
        <ol class="breadcrumb bg-transparent p-0 justify-content-end">
            <li class="breadcrumb-item text-capitalize"><a href="{{ backpack_url('dashboard') }}">Admin</a></li>
            <li class="breadcrumb-item text-capitalize"><a href="{{ route('template.list') }}">Template</a></li>
            <li class="breadcrumb-item text-capitalize active" aria-current="page">List</li>
        </ol>
    </nav>

    <section>
        
        <h2><span class="text-capitalize">@lang('Template')</span></h2>
        <div class="row mb-0">
            <div class="col-6">
                <div class="hidden-print with-border">
                    <a href="{{ route('template.add-form') }}" class="btn btn-primary" data-style="zoom-in">
                        <span class="ladda-label">
                            <i class="fa fa-plus"></i>
                            @lang('Add Template')
                        </span>
                    </a>
                </div>
            </div>
        </div>
    </section>

@endsection

@section('main_section')

    
    @if(session()->has('success'))
        <div class="alert alert-success" id="message" role="alert" style="display:none">
            {{session()->get("success")}}
        </div>
    @elseif(session()->has('error'))
        <div class="alert alert-danger" id="message" role="alert" style="display:none">
            {{session()->get("error")}}
        </div>
    @endif

    <div class="row">
        <div class="col-sm-12">
            <div class="overflow-hidden mt-2">
                <div id="crudTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="crudTable" class="bg-white table table-striped table-hover nowrap rounded shadow-xs border-xs dataTable dtr-inline" cellspacing="0" aria-describedby="crudTable_info" role="grid">
                                <thead>
                                    <tr role="row">
                                        <th data-orderable="true" data-priority="1" data-visible-in-table="false" data-visible="true" data-can-be-visible-in-table="true" data-visible-in-modal="true" data-visible-in-export="true" data-force-export="false" class="sorting" tabindex="0" aria-controls="crudTable" rowspan="1" colspan="1" aria-label="activate to sort column ascending">
                                            @lang('სახელი')
                                        </th>
                                        <th data-orderable="true" data-priority="1" data-visible-in-table="false" data-visible="true" data-can-be-visible-in-table="true" data-visible-in-modal="true" data-visible-in-export="true" data-force-export="false" class="sorting" tabindex="0" aria-controls="crudTable" rowspan="1" colspan="1" aria-label="activate to sort column ascending">
                                            @lang('Header სურათი')
                                        </th>
                                        <th data-orderable="true" data-priority="1" data-visible-in-table="false" data-visible="true" data-can-be-visible-in-table="true" data-visible-in-modal="true" data-visible-in-export="true" data-force-export="false" class="sorting" tabindex="0" aria-controls="crudTable" rowspan="1" colspan="1" aria-label="activate to sort column ascending">
                                            @lang('Footer სურათი')
                                        </th>
                                        <th data-orderable="true" data-priority="1" data-visible-in-table="false" data-visible="true" data-can-be-visible-in-table="true" data-visible-in-modal="true" data-visible-in-export="true" data-force-export="false" class="sorting" tabindex="0" aria-controls="crudTable" rowspan="1" colspan="1" aria-label="activate to sort column ascending">
                                            @lang('კონტექსტი')
                                        </th>
                                        <th data-orderable="true" data-priority="1" data-visible-in-table="false" data-visible="true" data-can-be-visible-in-table="true" data-visible-in-modal="true" data-visible-in-export="true" data-force-export="false" class="sorting" tabindex="0" aria-controls="crudTable" rowspan="1" colspan="1" aria-label="activate to sort column ascending">
                                            @lang('Action')
                                        </th>
                                    </tr>
                                </thead>   
                                {{-- items --}}
                                <tbody>
                                    @forelse($templateDatas as $record)
                                        <tr role="row" class="odd">
                                            <td>{{ $record->name }}</td>

                                            <td>
                                                @isset($record->header_picture_path)
                                                    <img src="{{ asset('/storage/template/'.$record->header_picture_path) }}" width="50" height="50">
                                                @else
                                                    @lang('სურათი ვერ მოიძებნა')
                                                @endisset
                                            </td>

                                            <td>
                                                @isset($record->footer_picture_path)
                                                    <img src="{{ asset('/storage/template/'.$record->footer_picture_path) }}" width="50" height="50">
                                                @else
                                                    @lang('სურათი ვერ მოიძებნა')
                                                @endisset
                                            </td>

                                            <td>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#context{{$record->id}}">
                                                    @lang('კონტექსტი')
                                                </button>
                                            </td>

                                            <td>
                                                <div class="action">
                                                    <form class="copy" enctype="multipart/form-data" action="{{ route('template.copy')}}" method="POST">   
                                                        {{ csrf_field() }}                                            
                                                        <button class="btn btn-sm btn-link" value="{{ $record->id }}" name="id">@lang('copy')</button>
                                                    </form>

                                                    <a class="btn btn-sm btn-link" id="update" href="{{ route('template.update', $record->id) }}">@lang('update')</a>
                                                                
                                                    <form class="remove" enctype="multipart/form-data" action="{{ route('template.remove')}}" method="POST">   
                                                        {{ csrf_field() }} 
                                                        <button class="btn btn-sm btn-link" value="{{ $record->id }}" name="id">@lang('delete')</button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @empty
                                        <td colspan=5><center>@lang('Empty')</center></td>
                                    @endforelse 
                                </tbody>

                                <tfoot>
                                    <tr>
                                        <th rowspan="1" colspan="1">
                                            @lang('სახელი')
                                        </th>
                                        <th rowspan="1" colspan="1">
                                            @lang('Header სურათი')
                                        </th>
                                        <th rowspan="1" colspan="1">
                                            @lang('Footer სურათი')
                                        </th>
                                        <th rowspan="1" colspan="1">
                                            @lang('კონტექსტი')
                                        </th>
                                        <th rowspan="1" colspan="1">
                                            @lang('Action')
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

{{-- paginate --}}
{{ $templateDatas->render() }}

@endsection

@foreach($templateDatas as $record)
<!-- Modal -->
    <div class="modal fade show" id="context{{$record->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true"> --}}
         <div class="modal-dialog modal-dialog-scrollable modal-xl" style="margin-top: 0%" >
            <div class="modal-content">           
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ $record->name }} @lang('კონტექსტი')</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    @isset($record->footer_content)
                    {!! $record->footer_content !!}
                    @else
                        @lang('კონტექსტი ვერ მოიძებნა')
                    @endisset
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endforeach
