<!-- This file is used to store sidebar items, starting with Backpack\Base 0.9.0 -->
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('elfinder') }}\"><i class="nav-icon las la-file"></i> <span>{{ trans('backpack::crud.file_manager') }}</span></a></li>

@if (backpack_user()->hasPermissionTo(config('permissions.test_options.view.key')))
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('testsquestions') }}'><i class='nav-icon las la-university'></i> @lang('ტესტების კითხვები') </a></li>
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('tests') }}'><i class='nav-icon las la-scroll'></i> @lang('ტესტები') </a></li>
@endif
@if (backpack_user()->hasPermissionTo(config('permissions.test_attach.view.key')))
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('announcedcompetitions') }}'><i class='nav-icon las la-bullhorn'></i> @lang('გამოცხადებული კონკურსები') </a></li>
@endif

@if (backpack_user()->hasPermissionTo(config('permissions.submitted_test_access.view.key')))
    <li class='nav-item'><a class='nav-link' href='{{ route('submittedTests.competitions') }}'><i class='nav-icon las la-bullhorn'></i> @lang('შევსებული ტესტები') </a></li>
@endif

@if(backpack_user()->hasPermissionTo(config('permissions.event.view.key')) )
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('events') }}'><i class='nav-icon las la-calendar'></i> @lang('შემოსვლა/გასვლა')</a></li>
@endif

@if(backpack_user()->hasPermissionTo(config('permissions.item_log.list.key')) )
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('log-item') }}'><i class='nav-icon las la-bars'></i> @lang('გაცემა/მიღება')</a></li>
@endif

@if(backpack_user()->hasPermissionTo(config('permissions.competition.view.key')) )
<li class='nav-item'><a class='nav-link' href='{{ backpack_url('competition') }}'><i class='nav-icon las la-bars'></i>@lang('კონკურსი')</a></li>
@endif

@if(backpack_user()->hasPermissionTo(config('permissions.competition_type.view.key')) )
    <li class='nav-item'><a class='nav-link' href='{{ backpack_url('competitiontype') }}'><i class='nav-icon  fa fa-tag'></i> @lang('კონკურსის ტიპი')</a></li>
@endif



@if(backpack_user()->hasPermissionTo(config('permissions.template.view.key')) ||
backpack_user()->hasPermissionTo(config('permissions.email_send.view.key')))
    <li class='nav-item'>
        <li class="nav-item nav-dropdown">
            <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon fa fa-group"></i> @lang('მეილის გაგზავნა')</a>
            <ul class="nav-dropdown-items">
                @if(backpack_user()->hasPermissionTo(config('permissions.template.view.key')) )
                    <li class='nav-item'>
                        <a class='nav-link' href='{{ route('template.list') }}'> @lang('Template')</a></li>
                @endif

                @if( backpack_user()->hasPermissionTo(config('permissions.email_send.view.key')))
                    <li class='nav-item'><a class='nav-link' href='{{ route('mail.dashboard') }}'> @lang('Email')</a></li>
                @endif
            </ul>
        </li>
    </li>
@endif




@if(backpack_user()->hasPermissionTo(config('permissions.person.manage.key')) ||
backpack_user()->hasPermissionTo(config('permissions.position.manage.key')) ||
backpack_user()->hasPermissionTo(config('permissions.department.manage.key')) )
    <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon fa fa-group"></i> @lang('პირები')</a>
        <ul class="nav-dropdown-items">
            @if(backpack_user()->hasPermissionTo(config('permissions.person.manage.key')) )
                <li class='nav-item'><a class='nav-link' href='{{ backpack_url('person') }}'><i class='nav-icon las la-user-tie'></i> @lang('პირები')</a></li>
            @endif

            @if( backpack_user()->hasPermissionTo(config('permissions.position.manage.key')))
                <li class='nav-item'><a class='nav-link' href='{{ backpack_url('position') }}'><i class='nav-icon las la-building'></i> @lang('პოზიციები')</a></li>
            @endif

            @if(backpack_user()->hasPermissionTo(config('permissions.department.manage.key')) )
                <li class='nav-item'><a class='nav-link' href='{{ backpack_url('department') }}'><i class='nav-icon las la-address-card'></i> @lang('მიმართულებები')</a></li>
            @endif
        </ul>
    </li>
@endif




@if(backpack_user()->hasPermissionTo(config('permissions.item_type.manage.key')) || backpack_user()->hasPermissionTo(config('permissions.item.manage.key')))
    <li class="nav-item nav-dropdown">
        <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon fa fa-group"></i>@lang('მოწყობილობები')</a>
        <ul class="nav-dropdown-items">
            @if(backpack_user()->hasPermissionTo(config('permissions.item_type.manage.key')) )
                <li class='nav-item'><a class='nav-link' href='{{ backpack_url('item-type') }}'><i class='nav-icon las la-database'></i> @lang('ტიპები')</a></li>
            @endif
            @if(backpack_user()->hasPermissionTo(config('permissions.item.manage.key')) )
                <li class='nav-item'><a class='nav-link' href='{{ backpack_url('item') }}'><i class='nav-icon las la-laptop'></i> @lang('მოწყობილობები')</a></li>
            @endif
        </ul>
    </li>
@endif

@if(backpack_user()->hasPermissionTo(config('permissions.user_role.role_manage.key')) || backpack_user()->hasPermissionTo(config('permissions.user_role.user_manage.key')) )

<li class="nav-item nav-dropdown">
    <a class="nav-link nav-dropdown-toggle" href="#"><i class="nav-icon fa fa-group"></i> Authentication</a>
    <ul class="nav-dropdown-items">
        @if(backpack_user()->hasPermissionTo(config('permissions.user_role.role_manage.key')))
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('role') }}"><i class="nav-icon lab la-critical-role"></i> <span>Roles</span></a></li>
        @endif

        @if(backpack_user()->hasPermissionTo(config('permissions.user_role.user_manage.key')))
        <li class="nav-item"><a class="nav-link" href="{{ backpack_url('user') }}"><i class="nav-icon las la-user"></i> <span>Users</span></a></li>
        @endif
    </ul>
</li>

@endif

<li class='nav-item'><a class='nav-link' href='{{ backpack_url('log') }}'><i class='nav-icon las la-server'></i> Logs</a></li>
