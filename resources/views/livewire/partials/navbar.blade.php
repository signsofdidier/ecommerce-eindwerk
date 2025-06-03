<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">


<!-- announcement bar start -->
<div class="announcement-bar bg-1 py-1 py-lg-2">
    <div class="container-fluid px-0"></div>
    <div class="container">
        <div class="row align-items-center justify-content-between">
            <div class="col-lg-3 d-lg-block d-none">
                <div class="announcement-call-wrapper">
                    <div class="announcement-call">
                        <a class="announcement-text text-white" href="tel:+1-078-2376">Call: +1 078 2376</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 col-12">
                <div class="announcement-text-wrapper d-flex align-items-center justify-content-center">
                    <p class="announcement-text text-white">Free Shipping on all orders over {{ Number::currency($free_shipping_threshold, 'EUR') }}.</p>
                </div>
            </div>
            <div class="col-lg-3 d-lg-block d-none">
                <div class="announcement-meta-wrapper d-flex align-items-center justify-content-end">
                    <div class="announcement-meta">

                        {{-- Login knop voor gasten --}}
                        @guest
                            <a class="d-flex align-items-center text-white text-decoration-none"
                               href="{{ url('/login') }}">
                                {{-- User-icon --}}
                                <svg class="me-1" width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                    <path fill-rule="evenodd" d="M8 9a5 5 0 0 0-5 5v1h10v-1a5 5 0 0 0-5-5z"/>
                                </svg>
                                <span class="fw-semibold">Login</span>
                            </a>

                            {{-- Dropdown voor ingelogde gebruikers --}}
                        @else
                            <div class="dropdown">
                                <a class="d-flex align-items-center text-white text-decoration-none dropdown-toggle"
                                   href="#"
                                   id="navbarUserDropdown"
                                   data-bs-toggle="dropdown"
                                   aria-expanded="false">
                                    {{-- Optioneel een avatar, anders deze icon --}}
                                    <img src="{{ Auth::user()->avatar_url ?? asset('assets/img/checkout/user.jpg') }}"
                                         alt="Avatar"
                                         class="rounded-circle me-2"
                                         width="32" height="32">
                                    <span class="fw-semibold">{{ Auth::user()->name }}</span>
                                </a>

                                <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg mt-2" aria-labelledby="navbarUserDropdown" style="min-width: 12rem;">
                                    <li class="px-3 py-2">
                                        <div class="d-flex align-items-center">
                                            <img src="{{ Auth::user()->avatar_url ?? asset('assets/img/checkout/user.jpg') }}"
                                                 alt="Avatar"
                                                 class="rounded-circle me-2"
                                                 width="40" height="40">
                                            <div>
                                                <div class="fw-bold">{{ Auth::user()->name }}</div>
                                            </div>
                                        </div>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>

                                    <li>
                                        <a class="dropdown-item d-flex align-items-center py-2"
                                           href="{{ url('/my-orders') }}">
                                            {{-- Profile-icon --}}
                                            <svg class="me-2" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3z"/>
                                                <path fill-rule="evenodd" d="M8 8a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                                            </svg>
                                            Profile Page
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item d-flex align-items-center py-2"
                                           href="{{ url('/my-orders') }}">
                                            {{-- Orders-icon --}}
                                            <svg class="me-2" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                                <path d="M0 1.5A.5.5 0 0 1 .5 1h15a.5.5 0 0 1 .5.5v11a.5.5 0 0 1-.5.5H.5a.5.5 0 0 1-.5-.5v-11zM1 2v10h14V2H1z"/>
                                                <path d="M3 4h10v2H3V4zm0 3h10v2H3V7z"/>
                                            </svg>
                                            My Orders
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>

                                    <li>
                                        <a class="dropdown-item d-flex align-items-center text-danger py-2"
                                           href="{{ url('/logout') }}">
                                            {{-- Logout-icon --}}
                                            <svg class="me-2 text-danger" width="18" height="18" fill="currentColor" viewBox="0 0 16 16">
                                                <path fill-rule="evenodd" d="M6 3.5a.5.5 0 0 1 .5-.5h5.793l-1.147-1.146a.5.5 0 1 1 .708-.708l2 2a.5.5 0 0 1 0 .708l-2 2a.5.5 0 0 1-.708-.708L12.293 4H6.5a.5.5 0 0 1-.5-.5z"/>
                                                <path fill-rule="evenodd" d="M13 8a.5.5 0 0 1-.5.5H2.707l1.147 1.146a.5.5 0 0 1-.708.708l-2-2a.5.5 0 0 1 0-.708l2-2a.5.5 0 1 1 .708.708L2.707 7.5H12.5A.5.5 0 0 1 13 8z"/>
                                            </svg>
                                            Logout
                                        </a>
                                        <form id="logout-form" action="/logout" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @endguest


                        {{-- <span class="separator-login d-flex px-3">
                                     <svg width="2" height="9" viewBox="0 0 2 9" fill="none"
                                          xmlns="http://www.w3.org/2000/svg">
                                         <path opacity="0.4" d="M1 0.5V8.5" stroke="#FEFEFE" stroke-linecap="round" />
                                     </svg>
                                 </span>--}}
                        {{--<div class="currency-wrapper">
                            <button type="button" class="currency-btn btn-reset text-white"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                <img class="flag" src="assets/img/flag/eur.jpg" alt="img">
                                <span>EUR</span>
                            </button>
                        </div>--}}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- announcement bar end -->

<!-- header start -->
<header class="sticky-header border-btm-black header-1">
    <div class="header-bottom">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-3 col-md-4 col-4">
                    <div class="header-logo">
                        <a href="/" class="logo-main">
                            <img src="{{ asset('assets/img/logo.png') }}" loading="lazy" alt="bisum">
                            {{--<h2 class="text-bold">K(L)ASSE</h2>--}}
                        </a>
                    </div>
                </div>

                {{-- NAVBAR LINKS --}}
                <div class="col-lg-6 d-lg-block d-none">
                    <nav class="site-navigation">
                        <ul class="main-menu list-unstyled justify-content-center">
                            <li class="menu-list-item nav-item {{ request()->is('/') ? 'active' : '' }}">
                                <a wire:navigate class="nav-link" href="{{ url('/') }}">
                                    Home
                                </a>
                            </li>
                            <li class="menu-list-item nav-item {{ request()->is('products') ? 'active' : '' }}">
                                <a wire:navigate class="nav-link" href="{{ url('/products') }}">
                                    Products
                                </a>
                            </li>
                            <li class="menu-list-item nav-item {{ request()->is('blog') ? 'active' : '' }}">
                                <a wire:navigate class="nav-link" href="{{ url('/blog') }}">
                                    Blog
                                </a>
                            </li>
                            <li class="menu-list-item nav-item {{ request()->is('about-us') ? 'active' : '' }}">
                                <a wire:navigate class="nav-link" href="{{ url('/about-us') }}">
                                    About Us
                                </a>
                            </li>
                            <li wire:navigate class="menu-list-item nav-item {{ request()->is('contact') ? 'active' : '' }}">
                                <a class="nav-link" href={{ url('/contact') }}">Contact</a>
                            </li>
                        </ul>
                    </nav>
                </div>


                <div class="col-lg-3 col-md-8 col-8">
                    <div class="header-action d-flex align-items-center justify-content-end">
                        <a class="header-action-item header-search" href="javascript:void(0)">
                            <svg class="icon icon-search" width="20" height="20" viewBox="0 0 20 20" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M7.75 0.250183C11.8838 0.250183 15.25 3.61639 15.25 7.75018C15.25 9.54608 14.6201 11.1926 13.5625 12.4846L19.5391 18.4611L18.4609 19.5392L12.4844 13.5627C11.1924 14.6203 9.5459 15.2502 7.75 15.2502C3.61621 15.2502 0.25 11.884 0.25 7.75018C0.25 3.61639 3.61621 0.250183 7.75 0.250183ZM7.75 1.75018C4.42773 1.75018 1.75 4.42792 1.75 7.75018C1.75 11.0724 4.42773 13.7502 7.75 13.7502C11.0723 13.7502 13.75 11.0724 13.75 7.75018C13.75 4.42792 11.0723 1.75018 7.75 1.75018Z"
                                    fill="black"/>
                            </svg>
                        </a>

                        {{-- WISHLIST --}}
                        <a class="header-action-item header-wishlist ms-4 d-none d-lg-block"
                           href="wishlist.html">
                            <svg class="icon icon-wishlist" width="26" height="22" viewBox="0 0 26 22"
                                 fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M6.96429 0.000183105C3.12305 0.000183105 0 3.10686 0 6.84843C0 8.15388 0.602121 9.28455 1.16071 10.1014C1.71931 10.9181 2.29241 11.4425 2.29241 11.4425L12.3326 21.3439L13 22.0002L13.6674 21.3439L23.7076 11.4425C23.7076 11.4425 26 9.45576 26 6.84843C26 3.10686 22.877 0.000183105 19.0357 0.000183105C15.8474 0.000183105 13.7944 1.88702 13 2.68241C12.2056 1.88702 10.1526 0.000183105 6.96429 0.000183105ZM6.96429 1.82638C9.73912 1.82638 12.3036 4.48008 12.3036 4.48008L13 5.25051L13.6964 4.48008C13.6964 4.48008 16.2609 1.82638 19.0357 1.82638C21.8613 1.82638 24.1429 4.10557 24.1429 6.84843C24.1429 8.25732 22.4018 10.1584 22.4018 10.1584L13 19.4036L3.59821 10.1584C3.59821 10.1584 3.14844 9.73397 2.69866 9.07411C2.24888 8.41426 1.85714 7.55466 1.85714 6.84843C1.85714 4.10557 4.13867 1.82638 6.96429 1.82638Z"
                                    fill="black"/>
                            </svg>
                        </a>

                        {{-- CART --}}
                        <a class="header-action-item header-cart ms-4 position-relative"
                           href="#drawer-cart" data-bs-toggle="offcanvas">
                            <svg class="icon icon-cart" width="24" height="26" viewBox="0 0 24 26" fill="none"
                                 xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M12 0.000183105C9.25391 0.000183105 7 2.25409 7 5.00018V6.00018H2.0625L2 6.93768L1 24.9377L0.9375 26.0002H23.0625L23 24.9377L22 6.93768L21.9375 6.00018H17V5.00018C17 2.25409 14.7461 0.000183105 12 0.000183105ZM12 2.00018C13.6562 2.00018 15 3.34393 15 5.00018V6.00018H9V5.00018C9 3.34393 10.3438 2.00018 12 2.00018ZM3.9375 8.00018H7V11.0002H9V8.00018H15V11.0002H17V8.00018H20.0625L20.9375 24.0002H3.0625L3.9375 8.00018Z"
                                    fill="black"/>
                            </svg>

                            {{--<div style="color: green; font-weight: bold">DEBUG: {{ $total_count }}</div>--}}
                            @if($total_count > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $total_count }}
                                    <span class="visually-hidden">items in cart</span>
                                </span>
                            @endif

                        </a>
                        <a class="header-action-item header-hamburger ms-4 d-lg-none" href="#drawer-menu"
                           data-bs-toggle="offcanvas">
                            <svg class="icon icon-hamburger" xmlns="http://www.w3.org/2000/svg" width="24"
                                 height="24" viewBox="0 0 24 24" fill="none" stroke="#000" stroke-width="2"
                                 stroke-linecap="round" stroke-linejoin="round">
                                <line x1="3" y1="12" x2="21" y2="12"></line>
                                <line x1="3" y1="6" x2="21" y2="6"></line>
                                <line x1="3" y1="18" x2="21" y2="18"></line>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <div class="search-wrapper">
            <div class="container">
                <form action="#" class="search-form d-flex align-items-center">
                    <button type="submit" class="search-submit bg-transparent pl-0 text-start">
                        <svg class="icon icon-search" width="20" height="20" viewBox="0 0 20 20" fill="none"
                             xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M7.75 0.250183C11.8838 0.250183 15.25 3.61639 15.25 7.75018C15.25 9.54608 14.6201 11.1926 13.5625 12.4846L19.5391 18.4611L18.4609 19.5392L12.4844 13.5627C11.1924 14.6203 9.5459 15.2502 7.75 15.2502C3.61621 15.2502 0.25 11.884 0.25 7.75018C0.25 3.61639 3.61621 0.250183 7.75 0.250183ZM7.75 1.75018C4.42773 1.75018 1.75 4.42792 1.75 7.75018C1.75 11.0724 4.42773 13.7502 7.75 13.7502C11.0723 13.7502 13.75 11.0724 13.75 7.75018C13.75 4.42792 11.0723 1.75018 7.75 1.75018Z"
                                fill="black"/>
                        </svg>
                    </button>
                    <div class="search-input mr-4">
                        <input type="text" placeholder="Search your products..." autocomplete="off">
                    </div>
                    <div class="search-close">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                             stroke-linejoin="round" class="icon icon-close">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </div>
                </form>
            </div>
        </div>
    </div>
</header>
<!-- header end -->
