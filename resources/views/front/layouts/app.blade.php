<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="{{asset($setting->favicon)}}">
    <title>{{$setting->title}}</title>
    <link href="{{ asset('front/css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('front/css/animate.css') }}" rel="stylesheet">
    <link href="{{ asset('front/css/fontawesome.css') }}" rel="stylesheet">
    <link href="{{ asset('front/css/cssmenu-styles.css') }}" rel="stylesheet">
    <link href="{{ asset('front/css/style.css') }}" rel="stylesheet">
    <link href="{{ asset('front/css/responsive.css') }}" rel="stylesheet">
</head>

<body>
    <div class="menu-overlay"></div>
    <div class="main-wrapper">
        <!-- <div class="cor-img-pos top-vec-img"></div> -->
        @include('front.layouts.topbar')
        <div class="main-middle-section inner-main-PTB">
            @yield('content')
        
        </div>
        <div class="cor-img-pos bottom-vec-img"></div>
    </div>
    <script src="{{ asset('front/js/jquery-3.7.0.min.js') }}"></script>
    <script src="{{ asset('front/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('front/js/cssmenu-script.js') }}"></script>
    <script src="{{ asset('front/js/wow.js') }}"></script>
    <script src="{{ asset('front/js/main.js') }}"></script>
    <script src="{{ asset('front/js/bootstrap-switch-button.min.js') }}"></script>
    <script>
        $('#switch_language').on('change',function(){

            console.log(($(this).is(":checked")));
            if($(this).is(":checked")){
                console.log('en');
                location.replace("{{ route('user.change.locale','en') }}");
            }else{
                console.log('hu');
                location.replace("{{ route('user.change.locale','gu') }}");
            }
        })
        let jquery_datatable = $("#table1").DataTable({
    responsive: true
})
    </script>
    
      @stack('script')
</body>
</html>
