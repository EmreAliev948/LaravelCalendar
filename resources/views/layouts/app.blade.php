<!DOCTYPE html>

<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default" data-assets-path="../assets/"
    data-template="vertical-menu-template-free">

    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
        <link rel="icon" type="image/x-icon" href="{{ URL::asset('assets/img/favicon/favicon.ico') }}" />
        <link rel="stylesheet" href="{{ URL::asset('assets/vendor/fonts/boxicons.css') }}" />
        <link rel="stylesheet" href="{{ URL::asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
        <link rel="stylesheet" href="{{ URL::asset('assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
        <link rel="stylesheet" href="{{ URL::asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
        <script src="{{ URL::asset('assets/vendor/js/helpers.js') }}"></script>
        <script src="{{ URL::asset('assets/js/config.js') }}"></script>
        <style>
            .notifyjs-corner {
                position: fixed;
                margin: 5px;
                z-index: 999999 !important;
            }

            .youtube-icon {
                width: 30px;
                /* Adjust the width as needed */
                height: auto;
                /* Maintain aspect ratio */
                margin-right: 5px;
                /* Add some spacing between the icon and the text */
            }

            .app-brand-logo img,
            .app-brand-logo svg {
                display: inline;
            }
        </style>
        @yield('head')
    </head>

    <body>
        
            <div class="layout-container">
                <div class="layout-page">
                    
                    <div class="content-wrapper">
                        @yield('content')
                        <div class="content-backdrop fade"></div>
                    </div>
                </div>
            </div>
            <div class="layout-overlay layout-menu-toggle"></div>
        
        <script src="{{ URL::asset('assets/js/jquery-3.7.1.min.js') }}"></script>
        <script src="{{ URL::asset('assets/bootstrap-5.3.2-dist/js/bootstrap.min.js') }}"></script>
        <script src="{{ URL::asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
        <script src="{{ URL::asset('assets/vendor/js/menu.js') }}"></script>
        <script src="{{ URL::asset('assets/js/main.js') }}"></script>
        <script src="{{ URL::asset('assets/notify.js') }}"></script>
        <script async defer src="https://buttons.github.io/buttons.js"></script>
    </body>
    <script>
        @auth
        var source = new EventSource("{{ URL('/sse-updates') }}");

        source.onmessage = function(event) {
            let ac = JSON.parse(event.data);
            $.notify(ac.message, 'success');
        }
        @endauth
    </script>
    @yield('script')

</html>