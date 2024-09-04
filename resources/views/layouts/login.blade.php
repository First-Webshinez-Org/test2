<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('app.name', 'POS') }}</title>

    @include('layouts.partials.css')

    <!-- login css -->
    <link rel="stylesheet" href="{{ asset('css/login.css?v='.$asset_v) }}">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>
    <section class="form_bg_trasnparennt">
        <div class="container_form" class="container">
            <div class="brand_details_div">
                <div class="brand_details text-center">
                    <span class="logo_container">
                        @if(file_exists(public_path('/images/LogoBizzSelfWhite.png')))
                            <img src="/images/LogoBizzSelfWhite.png" class="img-rounded" alt="Logo" width="150" class="bizzself_logo">
                        @else
                            {{ config('app.name', 'ultimatePOS') }}
                        @endif
                    </span>
                    <div class="brand_content">
                        <p>BizzSelf is a dynamic and intuitive Business Management ERP (Enterprise Resource Planning) software designed to revolutionize the way organizations operate. Offering an all-encompassing solution, BizzSelf seamlessly integrates various facets of business management, from finance and human resources to supply chain and customer relations. <br><br>
                        With its user-friendly interface, BizzSelf simplifies complex workflows, automates routine tasks, and centralizes data, providing businesses with a holistic view of their operations. This comprehensive ERP software empowers decision-makers with real-time insights, fostering data-driven strategies that drive efficiency and innovation.
                        <br>
                        </p>
                    </div>
                    <span class="webshine_link_container">A Product Of <a class="webshine_link" href="">Web Shinez Technology</a></span>
                </div>
                <div class="form_section">
                    <div class="form_section_inner">
                        @yield('content')
                    </div>
                </div>
            </div>
        </div>
    </section>

    @include('layouts.partials.javascripts')

    <!-- Scripts -->
    <script src="{{ asset('js/login.js?v=' . $asset_v) }}"></script>
    
    @yield('javascript')

    <script type="text/javascript">
        $(document).ready(function(){
            $('.select2_register').select2();
        });
    </script>
</body>
</html>
