<title>@lang('Email Send')</title>
@extends('vendor.backpack.base.email.mail.send.main')

@section('head')
    <nav aria-label="breadcrumb" class="d-none d-lg-block">
        <ol class="breadcrumb bg-transparent p-0 justify-content-end">
            <li class="breadcrumb-item text-capitalize"><a href="{{ backpack_url('dashboard') }}">@lang('Admin')</a></li>
            <li class="breadcrumb-item text-capitalize"><a href="{{ route('mail.dashboard') }}">@lang('Mail')</a></li>
            <li class="breadcrumb-item text-capitalize active" aria-current="page">@lang('Send')</li>
        </ol>
    </nav>

    <section class="container-fluid">
        <h2>
            <span class="text-capitalize">@lang('Email Group')</span>
            <small>@lang('Send Email')</small>
            <small>
                <a href="{{ route('mail.dashboard') }}" class="hidden-print font-sm">
                    @lang('Back to all Email Dashboard')
                </a>
            </small>
        </h2>
    </section>
@endsection

@section('main_section')

@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form enctype="multipart/form-data" action="{{ route('mail.store') }}" method="POST">
    @csrf
    <div class="card">
        <div class="card-body">
            <div class="form-group">
                <label for="name">@lang('სახელი:')</label>
                <input class="form-control" type="text" name="name" id="name" value="{{old('name')}}" placeholder=@lang('Name') required>
            </div>

            <div class="form-group">
                <label for="subject">@lang('Subject:')</label>
                <input class="form-control" type="text" name="subject" id="subject" value="{{old('subject')}}" placeholder=@lang('Subject')>
            </div>

            <div class="form-group">
                <label for="template">@lang('Template:')</label>
                <select class="form-control" id="template" name="template">
                    <option value="">@lang('Select Template')</option>
                    @foreach($tempaltes as $name)
                        <option value="{{ $name->id }}">{{ $name->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="user_status" id="activeUser" value="1" checked>
                    <label class="form-check-label" for="activeUser">@lang('აქტიური მომხმარებლები')</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="user_status" id="inactiveUser" value="0">
                    <label class="form-check-label" for="inactiveUser">@lang('არააქტიური მომხმარებლები')</label>
                </div>
            </div>
            <div class="form-group">
                <label for="group">@lang('მომხმარებლების არჩევა:')</label>
                <select class="selectpicker show-tick form-control" id="group" name="group[]" multiple data-actions-box="true">
                    @foreach($departaments as $row)
                        <option value="{{ $row->id }}">{!! $row->name !!}</option>
                    @endforeach
                    @foreach($positions as $row)
                        <option value="%{{ $row->id }}">{!! $row->name !!}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <div class="section">
                  <label for="choices-text-email-filter">Email addresses only</label>
                    <select class="show-tick form-control" id="choices-text-email-filter" name="emails[]" multiple data-actions-box="true">
                    </select>
{{--                  <input--}}
{{--                    class="form-control"--}}
{{--                    id="choices-text-email-filter"--}}
{{--                    type="text"--}}
{{--                    name="emails"--}}
{{--                    value="{{old('emails')}}"--}}
{{--                    placeholder="This is a placeholder"--}}
{{--                  />--}}
                </div>
            </div>

            <div class="form-group">
                <label for="body">@lang('Body ტექსტი:')</label>
                <textarea class="form-control" name="body" id="body" required>{{old('body')}}</textarea>
            </div>

            {{-- scheduler --}}
            {{--
            <div class="form-group row">
                <label for="scheduling" class="col-2 col-form-label">@lang('Scheduling')</label>
                <div class="col-10">
                  <input class="form-control" type="datetime-local" name="scheduling" id="scheduling">
                </div>
            </div>
            --}}
            <button class="btn btn-primary confirmation">@lang('გაგზავნა')</button>
        </div>
    </div>

</form>

@endsection

@section('after_scripts')

    <script>
        // $( document ).ready(function() {
        //     $("#choices-text-email-filter").select2({
        //         tokenSeparators: [',', ', ', ' ']
        //     });
        // })
    </script>

@endsection

