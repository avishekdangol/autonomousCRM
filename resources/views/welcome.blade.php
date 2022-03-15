<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>CRM</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('css/index.css') }}">

        <link rel="icon" href="{{ Config::get('constants.info.logo') }}">

        {{-- Scripts --}}
        <script src="{{ asset('js/index.js') }}" defer></script>

    </head>
    <body onload="load()">
        <div class="pre-loader">
            <div>
                @if(tenant())
                    <img class="loader-img" src="{{ asset(Config::get('constants.tenantInfo.gif_logo')) }}" alt="">
                @else 
                    <img class="loader-img" src="{{ asset(Config::get('constants.info.gif_logo')) }}" alt="">
                @endif
            </div>
        </div>

        {{-- Main Content --}}
        <div id="index">
            {{-- Top Slider or Banner  --}}
            <div class="slider">
                <div class="slider-content">

                    {{-- Slider or Banner Image --}}
                    <div class="brand d-flex align-items-center mb-4">
                        <img src="{{ Config::get('constants.info.logo') }}" alt="CRM banner">
                    </div>

                    {{-- Contents --}}
                    <h5 class="fw-bold">Manage your customer relationships with our CRM</h5>

                    <div class="d-flex my-3">
                        <a href="#" class="btn btn-primary me-2"><i class="fa fa-book"></i> DOCS</a>
                        <a href="http://demo.localhost:8000" class="btn btn-primary me-2" target="_blank"><i class="fa fa-laptop"></i> DEMO</a>
                    </div>
                    
                </div>
            </div>

            <div class="content container my-4">

                {{-- Introduction --}}
                <section class="introduction">
                    <h4 class="fw-bold">CRM</h4>
                    <p class="lead">CRM is a customer relationship management web application where you can store your leads and know their status. It provides personalized leads management for your employees where each of your employees can access and work on their leads without having to worry about others. It also provides an administrator's account which can access to every single leads in your company like a supervisor.</p>
                </section>

                {{-- Features --}}
                <section class="features">
                    <h4 class="fw-bold">Why Choose our CRM?</h4>
                    <div class="row">
                        <div class="col-md-4 col-sm-12 my-2">
                            <div class="card shadow shadow-sm">
                                <div class="card-header">
                                    <h5 class="fw-bold card-title my-1"><i class="fa fa-user-group"></i> User Friendly</h5>
                                </div>
    
                                <div class="card-body">
                                    <p class="card-text">Our CRM is a user-friendly web application; it is simple and easy to use.</p>
                                    <p class="card-text">Adding new leads, updating or even deleting has never been so easy.</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4 col-sm-12 my-2">
                            <div class="card shadow shadow-sm">
                                <div class="card-header">
                                    <h5 class="fw-bold card-title my-1"><i class="fa fa-chart-line"></i> Chart Representation</h5>
                                </div>

                                <div class="card-body">
                                    <p class="card-text">Our CRM demonstrates all your leads and their status in charts. We provide you your daily, weekly and yearly leads status which helps you to understand how well your company is performing.</p>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 col-sm-12 my-2">
                            <div class="card shadow shadow-sm">
                                <div class="card-header">
                                    <h5 class="fw-bold card-title my-1"><i class="fa fa-list-check"></i> Personalized Tasks</h5>
                                </div>

                                <div class="card-body">
                                    <p class="card-text">Our CRM provides personalized tasks to your employees, which means, each of your employees can track their own leads without having to worry about other's. You can switch the employee for the leads if necessary.</p>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                </section>

                <section>
                    <h4 class="fw-bold">Graphical Representation</h4>
                    <p class="lead">We provide you visually pleasing graphical representations about the status of your leads. You can trace your daily, weekly or yearly progress with our simple graphs which rebuilds itself as the progress are made in the CRM.</p>
                    <img class="full-width border" src="{{ asset('assets/homepage/CRM dashboard.png') }}" alt="Dashboard Screenshot">
                </section>
            </div>

            {{-- Footer --}}
            <footer>
                <hr>
                <div class="content container">
                    <div class="row justify-content-between">
                        <p class="col-md-6 col-sm-12">Created and Managed by Autonomous Technology Pvt. Ltd.</p>
                        <p class="right col-md-6 col-sm-12">Copyright &copy; <span id="year"></span>. All Rights Reserved.</p>
                    </div>
                </div>
            </footer>
            
        </div>
        
        {{-- Script for the pre-loader --}}
        <script>
            // loader to show at the beginning of the page load
            const loader = document.querySelector('.pre-loader');
            function load() {
                loader.style.display = 'none';
            }

            // current year for the copyright footer
            const year = document.getElementById('year');
            const y = new Date().getFullYear();

            year.textContent = y;
        </script>
    </body>
</html>
