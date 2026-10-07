@php
    $config = DB::table('configs')->select('icon', 'logo')->first();
@endphp
<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title> @yield('titre') - (Admin) {{ config('app.name') }}</title>
    <!--favicon-->
    <link rel="icon" href="{{ Storage::url($config->icon) }}" type="image/png" />
    <!--plugins-->
    <link href="/admin/assets/plugins/simplebar/css/simplebar.css" rel="stylesheet" />
    <link href="/admin/assets/plugins/perfect-scrollbar/css/perfect-scrollbar.css" rel="stylesheet" />
    <link href="/admin/assets/plugins/metismenu/css/metisMenu.min.css" rel="stylesheet" />
    <!-- loader-->
    <link href="/admin/assets/css/pace.min.css" rel="stylesheet" />
    <script src="/admin/assets/js/pace.min.js"></script>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="/admin/assets/css/bootstrap.min.css" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600&family=Roboto&display=swap" />
    <!-- Icons CSS -->
    <link rel="stylesheet" href="/admin/assets/css/icons.css" />
    <!-- App CSS -->
    <link rel="stylesheet" href="/admin/assets/css/app.css" />
    <link rel="stylesheet" href="/admin/assets/css/dark-sidebar.css" />
    <link rel="stylesheet" href="/admin/assets/css/dark-theme.css" />
    <link rel="stylesheet" href="{{ asset('admin-css.css?v=') . time() }}">
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">
    @livewireStyles
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@yaireo/tagify/dist/tagify.css">


    <script>
        !function(f,b,e,v,n,t,s) {
            if(f.fbq)return;n=f.fbq=function(){n.callMethod?
            n.callMethod.apply(n,arguments):n.queue.push(arguments)};
            if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';
            n.queue=[];t=b.createElement(e);t.async=!0;
            t.src=v;s=b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t,s)
        }(window, document,'script',
        'https://connect.facebook.net/en_US/fbevents.js');
        fbq('init', 'YOUR_PIXEL_ID');
        fbq('track', 'PageView');
    </script>
    <noscript>
        <img height="1" width="1" style="display:none"
            src="https://www.facebook.com/tr?id=YOUR_PIXEL_ID&ev=PageView&noscript=1"/>
    </noscript>


    @yield('header')



    <script>
        function url(url) {
            document.location.href = url;
        }

        function url2(url) {
            window.open(url, '_blank');
        }

        function toggle_confirmation(productId) {
            const confirmBtn = document.getElementById('confirmBtn' + productId);
            if (!confirmBtn.classList.contains('d-none')) {
                confirmBtn.classList.add('d-none');
            } else {
                // Masquer tous les autres boutons de confirmation s'ils sont visibles
                document.querySelectorAll('.confirm-btn').forEach(btn => {
                    if (!btn.classList.contains('d-none')) {
                        btn.classList.add('d-none');
                    }
                });
                confirmBtn.classList.remove('d-none');
            }
        }



        function preview_illustration(key) {
            const fileInput = document.getElementById('file-input-' + key);
            fileInput.click();
        }



        var old_total = 0;
        var count = 0;

        function fetchNotificationsAndUpdateComponent() {
            fetch('{{ route('live_notifications') }}')
                .then(response => response.json())
                .then(data => {
                    const total = data.total;
                    document.querySelector('.msg-count').textContent = total;
                    if (total > old_total) {
                        if (count > 0) {
                            const audio = new Audio('/icons/system-notification-199277.wav');
                            audio.play();
                            Livewire.dispatch('notificationUpdated');
                        }
                        old_total = total;
                        count++;
                    }
                })
                .catch(error => {
                    console.error('Erreur lors de la récupération des notifications :', error);
                });
        }
        setInterval(fetchNotificationsAndUpdateComponent, 5000);
    </script>
    @livewireStyles

    @stack('styles')


</head>

<body>
    <!-- wrapper -->
    <div class="wrapper">
        <!--sidebar-wrapper-->
        <div class="sidebar-wrapper" data-simplebar="true">
            <div class="sidebar-header">
                <div class="">
                    <img src="{{ Storage::url($config->icon) }}" class="logo-icon-2" alt="" />
                </div>
                <div>
                    <h4 class="logo-text">
                        {{ config('app.name') }}
                    </h4>
                </div>
                <a href="javascript:;" class="toggle-btn ms-auto"> <i class="bx bx-menu"></i>
                </a>
            </div>
            <!--navigation-->
            <ul class="metismenu" id="menu">
                @can('dashboard')
                    <li>
                        <a href="{{ route('dashboard') }}">
                            <div class="parent-icon icon-color-1">
                                <img src="/icons/diagramme-circulaire.svg" height="20" width="20" alt="icon"
                                    srcset="">
                            </div>
                            <div class="menu-title">Dashboard</div>
                        </a>
                    </li>
                @endcan
                <li class="menu-label">Web Apps</li>
                @can('product_view')
                    <li>
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon icon-color-3"> <i class="bx bx-conversation"></i>
                            </div>
                            <div class="menu-title">
                                Produits
                            </div>
                        </a>
                        <ul>
                            <li>
                                <a href="{{ route('produits') }}">
                                    <i class="bx bx-right-arrow-alt"></i>
                                    Liste des produits
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('produit.add') }}">
                                    <i class="bx bx-right-arrow-alt"></i>
                                    Créer un produit
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('promotions') }}">
                                    <i class="bx bx-right-arrow-alt"></i>
                                    Gestion des promotions
                                </a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a href="{{ route('marques') }}">
                            <div class="parent-icon icon-color-3">
                                <i class="ri-building-3-line"></i>
                            </div>
                            <div class="menu-title">
                                Marques
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('coupons.index') }}">
                            <div class="parent-icon icon-color-33">
                                <i class="ri-coupon-line"></i>
                            </div>
                            <div class="menu-title">
                                Coupons
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('categories') }}">
                            <div class="parent-icon icon-color-33">
                                <i class="ri-layout-grid-2-line"></i>
                            </div>
                            <div class="menu-title">
                                Catégories
                            </div>
                        </a>

                        <a href="{{ route('souscategories') }}">
                            <div class="parent-icon icon-color-33">
                                <i class="ri-layout-grid-2-line"></i>
                            </div>
                            <div class="menu-title">
                                Sous-catégories
                            </div>
                        </a>
                        <a href="{{ route('soussouscategories') }}">
                            <div class="parent-icon icon-color-33">
                                <i class="ri-layout-grid-2-line"></i>
                            </div>
                            <div class="menu-title">
                               sous sous Catégories
                            </div>
                        </a>
                    </li>
                        <li>
                            <a href="{{ route('gammes.index') }}">
                                <div class="parent-icon icon-color-33">
                                    <i class="ri-stack-line"></i>
                                </div>
                                <div class="menu-title">
                                    Gammes
                                </div>
                            </a>
                        </li>


                        <li>
                            <a href="{{ route('admin.comments.list') }}">
                                <div class="parent-icon icon-color-33">
                                    <i class="ri-chat-3-line"></i> {{-- icône bulle de chat --}}
                                </div>
                                <div class="menu-title">
                                    Comments
                                </div>
                            </a>

                        </li>
                        <li>
                            <a href="{{ route('admin.appointments.index') }}">
                                <div class="parent-icon icon-color-33">
                                    <i class="ri-calendar-check-line"></i> {{-- icône calendrier rendez-vous --}}
                                </div>
                                <div class="menu-title">
                                    Appointments
                                </div>
                            </a>
                        </li>

                    @endcan
                @can('order_view')
                    <li>
                        <a href="{{ route('commandes') }}">
                            <div class="parent-icon icon-color-4">
                                <i class="bx bx-archive"></i>
                            </div>
                            <div class="menu-title">
                                Commandes
                            </div>
                        </a>
                    </li>
                @endcan
                @can('clients_view')
                    <li>
                        <a href="{{ route('clients') }}">
                            <div class="parent-icon icon-color-5">
                                <i class="bx bx-group"></i>
                            </div>
                            <div class="menu-title">
                                Clients
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('contacts-list') }}">
                            <div class="parent-icon icon-color-5">
                                <i class="bx bx-conversation"></i>
                            </div>
                            <div class="menu-title">
                                Contacts
                            </div>
                        </a>
                    </li>
                @endcan
                @can('setting_view')
                    <li class="menu-label">
                        Settings
                    </li>
                    <li>
                        <a href="{{ route('contact-admin') }}">
                            <div class="parent-icon icon-color-6">
                                <i class="ri-settings-2-line"></i>
                            </div>
                            <div class="menu-title">
                                Configurations
                            </div>
                        </a>
                    </li>
                @endcan
                    <li>
                        <a href="{{ route('admin.blogs.index') }}">
                            <div class="parent-icon icon-color-5">
                                <i class="bx bx-book"></i> <!-- Icone modifiée pour Blog -->
                            </div>
                            <div class="menu-title">
                                Blogs
                            </div>
                        </a>
                    </li>

                    @role('admin')
                    <li>
                        <a href="{{ route('personnels') }}">
                            <div class="parent-icon icon-color-6">
                                <i class="ri-user-settings-line"></i>
                            </div>
                            <div class="menu-title">
                                Gestion du personnel
                            </div>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('banners.index') }}">
                            <div class="parent-icon icon-color-6">
                                <i class='bx bx-image-add'></i>
                            </div>
                            <div class="menu-title">
                                Bannière
                            </div>
                        </a>
                    </li>
                @endrole

            </ul>
            <!--end navigation-->
        </div>
        <!--end sidebar-wrapper-->
        <!--header-->
        <header class="top-header">
            <nav class="navbar navbar-expand">
                <div class="left-topbar d-flex align-items-center">
                    <a href="javascript:;" class="toggle-btn"> <i class="bx bx-menu"></i>
                    </a>
                </div>
                <div class="flex-grow-1 search-bar">
                    <div class="input-group">
                        <button class="btn btn-search-back search-arrow-back" type="button"><i
                                class="bx bx-arrow-back"></i></button>
                        <input type="text" class="form-control" placeholder="search" />
                        <button class="btn btn-search" type="button"><i class="lni lni-search-alt"></i></button>
                    </div>
                </div>
                <div class="right-topbar ms-auto">
                    <ul class="navbar-nav">
                        <li class="nav-item search-btn-mobile">
                            <a class="nav-link position-relative" href="javascript:;"> <i
                                    class="bx bx-search vertical-align-middle"></i>
                            </a>
                        </li>
                        <li class="nav-item dropdown dropdown-lg">
                            <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative"
                                href="javascript:;" data-bs-toggle="dropdown"> <i
                                    class="bx bx-bell vertical-align-middle"></i>
                                <span class="msg-count">
                                    0
                                </span>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a href="javascript:;">
                                    <div class="msg-header">
                                        <h6 class="msg-header-title">
                                            Notifications
                                        </h6>
                                        <p class="msg-header-subtitle">
                                            Notifications sur {{ config('app.name') }}.
                                        </p>
                                    </div>
                                </a>
                                @livewire('AdminNotifications')

                            </div>
                        </li>


                        <li class="nav-item dropdown dropdown-user-profile">
                            <a class="nav-link dropdown-toggle dropdown-toggle-nocaret" href="javascript:;"
                                data-bs-toggle="dropdown">
                                <div class="d-flex user-box align-items-center">
                                    <div class="user-info">
                                        <p class="user-name mb-0">
                                            {{ Auth::user()->nom }}
                                        </p>
                                        <p class="designattion mb-0">
                                            Connecté
                                        </p>
                                    </div>
                                    <img src="{{ Auth::user()->avatar() }}" class="user-img" alt="user avatar">
                                </div>
                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="{{ route('parametres') }}">
                                    <i class="bx bx-cog"></i>
                                    <span>
                                        Paramètres
                                    </span>
                                </a>
                                <a class="dropdown-item" href="{{ route('dashboard') }}">
                                    <i class="bx bx-tachometer"></i>
                                    <span>Dashboard</span>
                                </a>
                                <div class="dropdown-divider mb-0"></div>
                                <a class="dropdown-item" href="{{ route('logout') }}">
                                    <i class="bx bx-power-off"></i>
                                    <span>
                                        Déconnexion
                                    </span>
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>
        <!--end header-->
        <!--page-wrapper-->
        <div class="page-wrapper">

            @yield('body')


        </div>
        <!-- END wrapper -->


        <!--end page-content-wrapper-->
    </div>
    <!--end page-wrapper-->
    <!--start overlay-->
    <div class="overlay toggle-btn-mobile"></div>
    <!--end overlay-->
    <!--Start Back To Top Button--> <a href="javaScript:;" class="back-to-top"><i
            class='bx bxs-up-arrow-alt'></i></a>
    <!--End Back To Top Button-->
    <!--footer -->
    <div class="footer">
        <p class="mb-0">@ {{ date('Y') }} | Developed By :
            <a href="{{ config('app.auteur_url') }}" target="_blank">
                <strong style="color: #ec0000 !important;">
                    {{ config('app.auteur') }}
                </strong>
            </a>
        </p>
    </div>
    <!-- end footer -->
    </div>

    <!-- end wrapper -->
    <!-- Bootstrap JS -->
    <script src="/admin/assets/js/bootstrap.bundle.min.js"></script>

    <!--plugins-->
    <script src="/admin/assets/js/jquery.min.js"></script>

    <script src="/admin/assets/js/jquery.min.js"></script>
    <script src="/admin/assets/plugins/simplebar/js/simplebar.min.js"></script>
    <script src="/admin/assets/plugins/metismenu/js/metisMenu.min.js"></script>
    <script src="/admin/assets/plugins/perfect-scrollbar/js/perfect-scrollbar.js"></script>
    <!-- App JS -->
    <script src="/admin/assets/js/app.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
    @livewireScripts
    @stack('scripts')

</body>

</html>
