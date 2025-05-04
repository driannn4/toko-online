<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>tokoonline</title>
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('frontend/image/icon_univ_bsi.png') }}">
    
    <!-- Google font -->
    <link href="https://fonts.googleapis.com/css?family=Hind:400,700" rel="stylesheet">

    <!-- CSS Libraries -->
    <link rel="stylesheet" href="{{ asset('frontend/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/slick.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/slick-theme.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/nouislider.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/font-awesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/css/style.css') }}">

    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<!-- HEADER -->
<header>
    <!-- Top Header -->
    <div id="top-header">
        <div class="container">
            <div class="pull-left">
                <span>Selamat datang di toko online</span>
            </div>
        </div>
    </div>

    <!-- Main Header -->
    <div id="header">
        <div class="container">
            <div class="pull-left">
                <!-- Logo -->
                <div class="header-logo">
                    <a class="logo" href="#">
                        <img src="{{ asset('frontend/image/icon_univ_bsi.png') }}" alt="">
                    </a>
                </div>
            </div>

            <div class="pull-right">
                <ul class="header-btns">
                    <!-- Cart -->
                    <li class="header-cart dropdown default-dropdown">
                        <a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                            <div class="header-btns-icon">
                                <i class="fa fa-shopping-cart"></i>
                            </div>
                            <strong class="text-uppercase">Keranjang</strong>
                        </a>
                    </li>
<!-- Account -->
<li class="header-account dropdown default-dropdown">
    <div class="dropdown-toggle" role="button" data-toggle="dropdown" aria-expanded="true">
        <div class="header-btns-icon">
            <i class="fa fa-user-o"></i>
        </div>
        <strong class="text-uppercase">Akun Saya <i class="fa fa-caret-down"></i></strong>
    </div>

    <ul class="custom-menu">
        @auth
            <!-- Menampilkan Nama Pengguna yang Sedang Login -->
            <li><a href="#"><i class="fa fa-user-o"></i> {{ Auth::user()->nama }}</a></li>
            <li><a href="#"><i class="fa fa-envelope"></i> {{ Auth::user()->email }}</a></li>
            <li><a href="{{ route('customer.logout') }}"><i class="fa fa-sign-out"></i> Logout</a></li>
        @else
            <!-- Menampilkan Login jika Pengguna Belum Login -->
            <li><a href="{{ route('auth.redirect') }}"><i class="fa fa-google"></i> Login dengan Google</a></li>
        @endauth

        <li><a href="#"><i class="fa fa-heart-o"></i> My Wishlist</a></li>
        <li><a href="#"><i class="fa fa-exchange"></i> Compare</a></li>
        <li><a href="#"><i class="fa fa-check"></i> Checkout</a></li>

        {{-- Registrasi akun bisa diarahkan ke halaman form jika kamu buat nanti --}}
        <li><a href="#"><i class="fa fa-user-plus"></i> Create An Account</a></li>
    </ul>
</li>


        {{-- Logout customer jika sudah login --}}
        @auth
        <li>
            <form method="POST" action="{{ route('customer.logout') }}">
                @csrf
                <button type="submit" class="btn btn-link" style="padding: 0; margin-left: 10px;">
                    <i class="fa fa-sign-out"></i> Logout
                </button>
            </form>
        </li>
        @endauth
    </ul>
</li>


                    <!-- Mobile nav toggle -->
                    <li class="nav-toggle">
                        <button class="nav-toggle-btn main-btn icon-btn"><i class="fa fa-bars"></i></button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>

<!-- NAVIGATION -->
<div id="navigation">
    <div class="container">
        <div id="responsive-nav">
            @if (request()->segment(1) == '' || request()->segment(1) == 'beranda')
                <div class="category-nav">
                    <span class="category-header">Kategori <i class="fa fa-list"></i></span>
                    <ul class="category-list">
                        @php
                            $kategori = DB::table('kategori')->orderBy('nama_kategori', 'asc')->get();
                        @endphp
                        @foreach ($kategori as $row)
                            <li><a href="{{ route('produk.kategori', $row->id) }}">{{ $row->nama_kategori }}</a></li>
                        @endforeach
                    </ul>
                </div>
            @else
                <div class="category-nav show-on-click">
                    <span class="category-header">Kategori <i class="fa fa-list"></i></span>
                    <ul class="category-list">
                        @foreach ($kategori as $row)
                            <li><a href="{{ route('produk.kategori', $row->id) }}">{{ $row->nama_kategori }}</a></li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="menu-nav">
                <span class="menu-header">Menu <i class="fa fa-bars"></i></span>
                <ul class="menu-list">
                    <li><a href="{{ route('beranda') }}">Beranda</a></li>
                    <li><a href="{{ route('produk.all') }}">Produk</a></li>
                    <li><a href="#">Lokasi</a></li>
                    <li><a href="#">Hubungi Kami</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

@if (request()->segment(1) == '' || request()->segment(1) == 'beranda')
<!-- HOME -->
<div id="home">
    <div class="container">
        <div class="home-wrap">
            <div id="home-slick">
                <div class="banner banner-1">
                    <img src="{{ asset('frontend/img/img_slide01.jpg') }}" alt="">
                    <div class="banner-caption text-center">
                        <h1>Jajanan Tradisional</h1>
                        <h3 class="font-weak" style="color: #30323a;">Khas Makanan Indonesia</h3>
                        <button class="primary-btn">Pesan Sekarang</button>
                    </div>
                </div>
                <div class="banner banner-1">
                    <img src="{{ asset('frontend/img/img_slide02.jpg') }}" alt="">
                    <div class="banner-caption">
                        <h1 class="primary-color">Khas Makanan Indonesia<br><span class="white-color font-weak">Jajanan Tradisional</span></h1>
                        <button class="primary-btn">Pesan Sekarang</button>
                    </div>
                </div>
                <div class="banner banner-1">
                    <img src="{{ asset('frontend/img/img_slide03.jpg') }}" alt="">
                    <div class="banner-caption">
                        <h1 style="color: #f8694a;">Khas Makanan <span>Indonesia</span></h1>
                        <button class="primary-btn">Pesan Sekarang</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

<!-- SECTION -->
<div class="section">
    <div class="container">
        <div class="row">
            <!-- ASIDE -->
            <div id="aside" class="col-md-3">
                <div class="aside">
                    <h3 class="aside-title">Top Rated Product</h3>

                    <div class="product product-widget">
                        <div class="product-thumb">
                            <img src="{{ asset('frontend/img/thumb-product01.jpg') }}" alt="">
                        </div>
                        <div class="product-body">
                            <h2 class="product-name"><a href="#">Product Name Goes Here</a></h2>
                            <h3 class="product-price">$32.50 <del class="product-old-price">$45.00</del></h3>
                            <div class="product-rating">
                                <i class="fa fa-star"></i><i class="fa fa-star"></i>
                                <i class="fa fa-star"></i><i class="fa fa-star"></i>
                                <i class="fa fa-star-o empty"></i>
                            </div>
                        </div>
                    </div>

                    <div class="product product-widget">
                        <div class="product-thumb">
                            <img src="{{ asset('frontend/img/thumb-product01.jpg') }}" alt="">
                        </div>
                        <div class="product-body">
                            <h2 class="product-name"><a href="#">Product Name Goes Here</a></h2>
                            <h3 class="product-price">$32.50</h3>
                            <div class="product-rating">
                                <i class="fa fa-star"></i><i class="fa fa-star"></i>
                                <i class="fa fa-star"></i><i class="fa fa-star"></i>
                                <i class="fa fa-star-o empty"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="aside">
                    <h3 class="aside-title">Filter Kategori</h3>
                    <ul class="list-links">
                        @foreach ($kategori as $row)
                            <li><a href="{{ route('produk.kategori', $row->id) }}">{{ $row->nama_kategori }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- MAIN -->
            <div id="main" class="col-md-9">
                @yield('content')
            </div>
        </div>
    </div>
</div>

<!-- FOOTER -->
<footer id="footer" class="section section-grey">
    <div class="container">
        <div class="row">
            <!-- Footer Widgets -->
            <div class="col-md-3 col-sm-6">
                <div class="footer">
                    <div class="footer-logo">
                        <a class="logo" href="#"><img src="./img/logo.png" alt=""></a>
                    </div>
                    <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna.</p>
                    <ul class="footer-social">
                        <li><a href="#"><i class="fa fa-facebook"></i></a></li>
                        <li><a href="#"><i class="fa fa-twitter"></i></a></li>
                        <li><a href="#"><i class="fa fa-instagram"></i></a></li>
                        <li><a href="#"><i class="fa fa-google-plus"></i></a></li>
                        <li><a href="#"><i class="fa fa-pinterest"></i></a></li>
                    </ul>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="footer">
                    <h3 class="footer-header">My Account</h3>
                    <ul class="list-links">
                        <li><a href="#">My Account</a></li>
                        <li><a href="#">My Wishlist</a></li>
                        <li><a href="#">Compare</a></li>
                        <li><a href="#">Checkout</a></li>
                        <li><a href="#">Login</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="footer">
                    <h3 class="footer-header">Customer Service</h3>
                    <ul class="list-links">
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Shipping & Return</a></li>
                        <li><a href="#">Shipping Guide</a></li>
                        <li><a href="#">FAQ</a></li>
                    </ul>
                </div>
            </div>

            <div class="col-md-3 col-sm-6">
                <div class="footer">
                    <h3 class="footer-header">Stay Connected</h3>
                    <p>Join newsletter for updates</p>
                    <form>
                        <div class="form-group">
                            <input class="input" placeholder="Enter Email Address">
                        </div>
                        <button class="primary-btn">Join Newsletter</button>
                    </form>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-8 col-md-offset-2 text-center">
                <div class="footer-copyright">
                    &copy;<script>document.write(new Date().getFullYear());</script> All rights reserved | This template is made with <i class="fa fa-heart-o"></i> by <a href="https://colorlib.com" target="_blank">Colorlib</a>
                </div>
            </div>
        </div>
    </div>
</footer>

<!-- jQuery Plugins -->
<script src="{{ asset('frontend/js/jquery.min.js') }}"></script>
<script src="{{ asset('frontend/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('frontend/js/slick.min.js') }}"></script>
<script src="{{ asset('frontend/js/nouislider.min.js') }}"></script>
<script src="{{ asset('frontend/js/jquery.zoom.min.js') }}"></script>
<script src="{{ asset('frontend/js/main.js') }}"></script>

</body>
</html>
