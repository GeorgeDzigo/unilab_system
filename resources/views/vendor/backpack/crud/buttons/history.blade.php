@if ($crud->hasAccess('update'))
    <button class="btn btn-sm btn-link" id="dropdownMenu2" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">History</button>
    <div style="position: absolute; top: 0px; left: -30px;">
        <div class="dropdown-menu" aria-labelledby="dropdownMenu2" x-placement="bottom-start">
            <a class="dropdown-item" href="{{ url($crud->route.'/'.$entry->getKey().'/statistic') }}">შესვლა/გამოსვლა</a>
            <a class="dropdown-item" href="{{ url($crud->route.'/'.$entry->getKey().'/history') }}">ტექნიკა</a>
        </div>
    </div>
@endif
