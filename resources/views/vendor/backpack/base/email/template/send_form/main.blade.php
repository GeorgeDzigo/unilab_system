@extends(backpack_view('blank'))
<script src={{ asset('js/jquery.js') }}></script>  
<script src="//cdn.ckeditor.com/4.15.0/full/ckeditor.js"></script>


<style>
    .action{
        display: flex;
        width: 147px;
        margin-right: -35px;
        position: relative;
        top: 10px;
    }
    .copy{
        width: 40px;
        height: 27px;
        position: relative;
    }
    #update{
        width: 44px;
        height: 22px;
        padding: 0px;
        position: relative;
        left: 10px;
        top: 4px;
    }
    .remove{
        width: 50px;
        position: relative;
        left: 14px;
    }
</style>

@section('content')

<main class="main pt-2">
    @yield('head')
    <div class="row">
        <div class="col-md-12">
            @yield('main_section')
        </div>
    </div>
</main>

<script>
    CKEDITOR.replace( 'exampleFormControlTextarea6' );
    // Add the following code if you want the name of the file appear on select
    $(".custom-file-input").on("change", function() {
      var fileName = $(this).val().split("\\").pop();
      $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
    });
</script>

<script type="text/javascript">
    var elems = document.getElementsByClassName('confirmation');
    var confirmIt = function (e) {
        if (!confirm('Are you sure?')) e.preventDefault();
    };
    for (var i = 0, l = elems.length; i < l; i++) {
        elems[i].addEventListener('click', confirmIt, false);
    }
</script>

<script>
    var timeleft = 5;
    var downloadTimer = setInterval(function(){
    if(timeleft <= 0){
    clearInterval(downloadTimer);
    $('#message').hide()

    } else {
    $('#message').show()
    }
    timeleft -= 1;
    }, 500);
</script>

@endsection