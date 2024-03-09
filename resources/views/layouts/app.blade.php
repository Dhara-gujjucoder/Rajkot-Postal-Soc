<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        {{ (isset($page_title) ? $page_title : '') . ' | ' . Config::get('name', $setting->title ?? 'Rajkot Postal Soc.') }}
    </title>


    <link rel="shortcut icon" href="{{ asset(isset($setting) ? $setting->favicon : 'assets/images/favicon.png') }}"
        type="image/x-icon">



    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/app-dark.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/compiled/css/iconly.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/choices.js/public/assets/styles/choices.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/extensions/toastify-js/src/toastify.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/extensions/flatpickr/flatpickr.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/plugins/monthSelect/style.css">
    <link rel="stylesheet" href="{{ asset('assets/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <link rel="stylesheet" crossorigin href="{{ asset('assets/compiled/css/table-datatable-jquery.css') }}">
    <link href="{{ asset('assets/extensions/select2/select2.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/extensions/select2/select2totree.css') }}" rel="stylesheet" />
    <link href="{{ asset('assets/compiled/css/custom.css') }}" rel="stylesheet">

    @stack('style')
</head>

<body>

    <script src="{{ asset('assets/static/js/initTheme.js') }}"></script>
    @guest
        <div id="app">
            <div class="content-wrapper">

                @yield('content')
            </div>
        </div>
    @endguest
    @auth
        <div id="app">
            <div id="loader">
                <img src="data:image/svg+xml,%3csvg%20xmlns='http://www.w3.org/2000/svg'%20width='44'%20height='44'%20stroke='%235d79d3'%20viewBox='0%200%2044%2044'%3e%3cg%20fill='none'%20fill-rule='evenodd'%20stroke-width='2'%3e%3ccircle%20cx='22'%20cy='22'%20r='1'%3e%3canimate%20attributeName='r'%20begin='0s'%20calcMode='spline'%20dur='1.8s'%20keySplines='0.165,%200.84,%200.44,%201'%20keyTimes='0;%201'%20repeatCount='indefinite'%20values='1;%2020'/%3e%3canimate%20attributeName='stroke-opacity'%20begin='0s'%20calcMode='spline'%20dur='1.8s'%20keySplines='0.3,%200.61,%200.355,%201'%20keyTimes='0;%201'%20repeatCount='indefinite'%20values='1;%200'/%3e%3c/circle%3e%3ccircle%20cx='22'%20cy='22'%20r='1'%3e%3canimate%20attributeName='r'%20begin='-0.9s'%20calcMode='spline'%20dur='1.8s'%20keySplines='0.165,%200.84,%200.44,%201'%20keyTimes='0;%201'%20repeatCount='indefinite'%20values='1;%2020'/%3e%3canimate%20attributeName='stroke-opacity'%20begin='-0.9s'%20calcMode='spline'%20dur='1.8s'%20keySplines='0.3,%200.61,%200.355,%201'%20keyTimes='0;%201'%20repeatCount='indefinite'%20values='1;%200'/%3e%3c/circle%3e%3c/g%3e%3c/svg%3e"
                    class="me-4" style="width: 3rem" alt="audio">
            </div>
            @include('layouts.partials.sidebar')
            <div id="main" class="layout-horizontal">
                <header class="mb-3">
                    <a href="#" class="burger-btn d-block d-xl-none">
                        <i class="bi bi-justify fs-3"></i>
                    </a>
                    @include('layouts.partials.topbar')
                </header>
                <div class="page-heading" style="min-height:75vh;padding:25px;">
                    <div class="page-title pb-lg-5">
                        <div class="row">
                            <div class="col-12 col-md-6 order-md-1 order-last">
                                <h3> @yield('title')</h3>
                                {{-- <p class="text-subtitle text-muted">Bootstrap’s cards provide a flexible and extensible content
                                container with multiple variants and options.</p> --}}
                            </div>
                            <div class="col-12 col-md-6 order-md-2 order-first">
                                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                                    <ol class="breadcrumb">
                                        <li class="breadcrumb-item"><a
                                                href="{{ route('home') }}">{{ __('Dashboard') }}</a>
                                        </li>
                                        <li class="breadcrumb-item active" aria-current="page">@yield('title')</li>
                                    </ol>
                                </nav>
                            </div>
                        </div>
                    </div>

                    <div class="content-wrapper">

                        @yield('content')

                    </div>
                </div>

                @include('layouts.partials.footer')

            </div>
        </div>
    @endauth
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script src="{{ asset('assets/static/js/components/dark.js') }}"></script>
    <script src="{{ asset('assets/extensions/perfect-scrollbar/perfect-scrollbar.min.js') }}"></script>
    <script src="{{ asset('assets/compiled/js/app.js') }}"></script>
    <script src="{{ asset('assets/extensions/choices.js/public/assets/scripts/choices.js') }}"></script>
    <script src="{{ asset('assets/static/js/pages/form-element-select.js') }}"></script>
    <script src="{{ asset('assets/extensions/flatpickr/flatpickr.min.js') }}"></script>
    {{-- <script src="{{ asset('assets/static/js/pages/date-picker.js') }}"></script> --}}
    <script src="https://cdn.jsdelivr.net/npm/flatpickr@4.6.13/dist/plugins/monthSelect/index.js"></script>
    <script src="{{ asset('assets/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/toastify-js/src/toastify.js') }}"></script>
    <script src="{{ asset('assets/extensions/select2/select2.min.js') }}"></script>
    <script src="{{ asset('assets/extensions/select2/select2totree.js') }}"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js">
    </script>

    @if (session('success'))
        <script>
            Toastify({
                text: "{{ session('success') }}",
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: "#4fbe87",
            }).showToast();
        </script>
    @endif
    @if (session('error'))
        <script>
            Toastify({
                text: "{{ session('error') }}",
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: "#f27474",
            }).showToast();
        </script>
    @endif
    <script>
        function show_success(msg) {
            Toastify({
                text: msg,
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: "#4fbe87",
            }).showToast();
        }
        function show_error(msg) {
            Toastify({
                text: msg,
                duration: 3000,
                close: true,
                gravity: "top",
                position: "right",
                backgroundColor: "#f27474",
            }).showToast();
        }

    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl)
            })
        }, false);
    </script>

    @stack('script')

</body>

</html>
