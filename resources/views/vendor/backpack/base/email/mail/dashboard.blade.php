<title>@lang('Email Dashboard')</title>
@extends('vendor.backpack.base.email.mail.send.main')

@section('head')
    <nav aria-label="breadcrumb" class="d-none d-lg-block">
        <ol class="breadcrumb bg-transparent p-0 justify-content-end">
            <li class="breadcrumb-item text-capitalize"><a href="{{ backpack_url('dashboard') }}">@lang('Admin')</a></li>
            <li class="breadcrumb-item text-capitalize"><a href="{{ route('mail.dashboard') }}">@lang('Mail')</a></li>
            <li class="breadcrumb-item text-capitalize active" aria-current="page">@lang('Dashboard')</li>
        </ol>
    </nav>

    <section>
        <h2><span class="text-capitalize">@lang('Email Group')</span></h2>
        <div class="row mb-0">
            <div class="col-6">
                <div class="hidden-print with-border">
                    <a href="{{ route('mail.send') }}" class="btn btn-primary" data-style="zoom-in"><span class="ladda-label"><i class="fa fa-plus"></i>@lang('Send Email')</span></a>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('main_section')
    <div class="alert-block">
        @if(session()->has('success'))
            <div class="alert alert-success" id="message" role="alert" style="display:none">
                {{session()->get("success")}}
            </div>
        @elseif(session()->has('error'))
            <div class="alert alert-danger" id="message" role="alert" style="display:none">
                {{session()->get("error")}}
            </div>
        @endif
    </div>

    <div class="row">
        <div class="col-sm-12">
            <div class="overflow-hidden mt-2">
                <div id="crudTable_wrapper" class="dataTables_wrapper dt-bootstrap4">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="crudTable" class="bg-white table table-striped table-hover nowrap rounded shadow-xs border-xs dataTable dtr-inline" cellspacing="0" aria-describedby="crudTable_info" role="grid">
                                {{-- columlns --}}
                                <thead>
                                    <tr role="row">
                                        <th data-orderable="true" data-priority="1" data-visible-in-table="false" data-visible="true" data-can-be-visible-in-table="true" data-visible-in-modal="true" data-visible-in-export="true" data-force-export="false" class="sorting" tabindex="0" aria-controls="crudTable" rowspan="1" colspan="1" aria-label="activate to sort column ascending">
                                            @lang('სახელი')
                                        </th>
                                        <th data-orderable="true" data-priority="1" data-visible-in-table="false" data-visible="true" data-can-be-visible-in-table="true" data-visible-in-modal="true" data-visible-in-export="true" data-force-export="false" class="sorting" tabindex="0" aria-controls="crudTable" rowspan="1" colspan="1" aria-label="activate to sort column ascending">
                                            @lang('გამგზავნი')
                                        </th>
                                        <th data-orderable="true" data-priority="1" data-visible-in-table="false" data-visible="true" data-can-be-visible-in-table="true" data-visible-in-modal="true" data-visible-in-export="true" data-force-export="false" class="sorting" tabindex="0" aria-controls="crudTable" rowspan="1" colspan="1" aria-label="activate to sort column ascending">
                                            @lang('გაგზავნის დრო')
                                        </th>
                                        <th data-orderable="true" data-priority="1" data-visible-in-table="false" data-visible="true" data-can-be-visible-in-table="true" data-visible-in-modal="true" data-visible-in-export="true" data-force-export="false" class="sorting" tabindex="0" aria-controls="crudTable" rowspan="1" colspan="1" aria-label="activate to sort column ascending">
                                            @lang('Email რაოდებობა')
                                        </th>
                                        <th data-orderable="true" data-priority="1" data-visible-in-table="false" data-visible="true" data-can-be-visible-in-table="true" data-visible-in-modal="true" data-visible-in-export="true" data-force-export="false" class="sorting" tabindex="0" aria-controls="crudTable" rowspan="1" colspan="1" aria-label="activate to sort column ascending">
                                            <center>@lang('Action')</center>
                                        </th>
                                    </tr>
                                </thead>   
                                {{-- items --}}
                                <tbody>
                                    @forelse ($emailGroups as $emailGroup)
                                        <tr role="row" class="odd">
                                            @php
                                            @endphp
                                            <td>{{ $emailGroup->name }}</td>
                                            <td>{{ $emailGroup->user->name }}</td>
                                            <td>{{ $emailGroup->updated_at }}</td>
                                            <td>{{ $emailGroup->emailCount()->count() }} @lang('mail')</td>
                                            <td><center><a class="btn btn-sm btn-link" href="{{ route('mail.list', $emailGroup->id) }}">@lang('Email List')</a></center></td>
                                        </tr>
                                    @empty
                                        <td colspan=5><center>@lang('Empty')</center></td>
                                    @endforelse
                                </tbody>
                                {{-- footer columlns --}}
                                <tfoot>
                                    <tr>
                                        <th rowspan="1" colspan="1">
                                            @lang('სახელი')
                                        </th>
                                        <th rowspan="1" colspan="1">
                                            @lang('გამგზავნი')
                                        </th>
                                        <th rowspan="1" colspan="1">
                                            @lang('გაგზავნის დრო')
                                        </th>
                                        <th rowspan="1" colspan="1">
                                            @lang('Email რაოდებობა')
                                        </th>
                                        <th rowspan="1" colspan="1">
                                            <center>@lang('Action')</center>
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
{{ $emailGroups->render() }}
@endsection

