<title>@lang('Email Send')</title>
@extends('vendor.backpack.base.email.mail.send.main')

@section('head')
    <nav aria-label="breadcrumb" class="d-none d-lg-block">
        <ol class="breadcrumb bg-transparent p-0 justify-content-end">
            <li class="breadcrumb-item text-capitalize"><a href="{{ backpack_url('dashboard') }}">@lang('Admin')</a></li>
            <li class="breadcrumb-item text-capitalize"><a href="{{ route('mail.dashboard') }}">@lang('Mail')</a></li>
            <li class="breadcrumb-item text-capitalize active" aria-current="page">@lang('List')</li>
        </ol>
    </nav>

    <section class="container-fluid">
        <h2>
            <span class="text-capitalize">@lang('Email Group')</span>
            <small>@lang('Email List')</small>
            <small>
                <a href="{{ route('mail.dashboard') }}" class="hidden-print font-sm">
                    @lang('Back to all Email Dashboard')
                </a>
            </small>
        </h2>
    </section>
@endsection

@section('main_section')
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
                                        <th class="sorting_asc" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Username: activate to sort column descending">
                                            @lang('მიმღები')
                                        </th>
                                        <th class="sorting" tabindex="0" aria-controls="DataTables_Table_0" rowspan="1" colspan="1" aria-label="Date registered: activate to sort column ascending">
                                            @lang('სტატუსი')
                                        </th>
                                    </tr>
                                </thead>   
                                {{-- items --}}
                                <tbody>
                                    @forelse ($emails as $email)
                                        <tr role="row" class="odd">
                                            <td>{{ $email->getReciver() }}</td>
                                            <td>{{ $email->getStatusName() }}</td>
                                        </tr>
                                    @empty
                                        <tr role="row" class="odd">
                                            <td colspan=2><center>@lang('Empty')</center></td>
                                        </tr>
                                    @endforelse
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th rowspan="1" colspan="1">
                                            @lang('მიმღები')
                                        </th>
                                        <th rowspan="1" colspan="1">
                                            @lang('სტატუსი')
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
@endsection
