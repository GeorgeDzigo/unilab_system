<title>@lang('Add Template')</title>
@extends('vendor.backpack.base.email.template.send_form.main')

@section('head')
    <nav aria-label="breadcrumb" class="d-none d-lg-block">
        <ol class="breadcrumb bg-transparent p-0 justify-content-end">
            <li class="breadcrumb-item text-capitalize"><a href="{{ backpack_url('dashboard') }}">Admin</a></li>
            <li class="breadcrumb-item text-capitalize"><a href="{{ route('template.list') }}">Template</a></li>
            <li class="breadcrumb-item text-capitalize active" aria-current="page">List</li>
        </ol>
    </nav>

    <section class="container-fluid">
        <h2>
            <span class="text-capitalize">@lang('Template')</span>
            <small>@lang('Update Template')</small>
            <small>
                <a href="{{ route('template.list') }}" class="hidden-print font-sm">
                    <i class="fa fa-angle-double-left"></i>
                    @lang('Back to all')
                    <span>@lang('Template')</span>
                </a>
            </small>
        </h2>
    </section>
@endsection

@section('main_section')

    <form class="was-validated" enctype="multipart/form-data" action="{{ route('template.update.store',$templateData->id) }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-body row">
                <div class="form-group col-sm-12 required">
                    <label for="validationTextarea">@lang('დასახელება')</label>
                    <input value="{{ $templateData->name }}" class="form-control is-invalid" type="text" class="form-control" minlength="2" id="inputEmail4" name="name" placeholder="@lang('დასახელება')" required style="width: 30%"> 
                    <small class="form-text text-muted">@lang('* Required')</small>
                </div> 

                <div class="form-group col-sm-10">
                    <div class="form-group col-sm-10">
                        @isset($templateData->header_picture_path)
                        <label class="custom-file-label" for="customFile">{{ $templateData->header_picture_path }}</label>
                        @else
                        <label class="custom-file-label" for="customFile">@lang('Photo For Header')</label>
                        @endisset
                        <input type="file" class="custom-file-input"  name="headerImage" id="customFile">
                    </div> 

                    <div class="form-group col-sm-10">
                        @isset($templateData->footer_picture_path)
                        <label class="custom-file-label" for="customFile">{{ $templateData->footer_picture_path }}</label>
                        @else
                        <label class="custom-file-label" for="customFile">@lang('Photo For Footer')</label>
                        @endisset
                        <input type="file" class="custom-file-input"  name="footerImage" id="customFile">
                    </div>  

                    <div class="form-group col-sm-12 required">
                        <div class="form-group shadow-textarea">
                            <label for="exampleFormControlTextarea6">@lang('Footer ტექსტი')</label>
                            <textarea class="form-control z-depth-1" name="footerText" id="exampleFormControlTextarea6" placeholder="Write something here...">{{ $templateData->footer_content }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div> 

        <div class="submitButton">
            <button type="submit" class="btn btn-success">
                <span>@lang('Update Template')</span>
            </button>
        </div> 
    </form>

@endsection
