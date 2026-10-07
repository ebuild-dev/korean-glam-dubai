<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- SEO META TAGS GÉNÉRIQUES -->
    <meta name="description"
          content="Discover authentic Korean skincare in Tunisia. Shop Korean serums, creams, masks and K-Beauty products from trusted brands. Fast local delivery.">
    <meta name="keywords"
          content="korean store in tunisia, korean beauty, k-beauty tunisia, skincare tunisia, korean glam, ameni, beauty store tunisia, soins visage coréens, kbeauty shop, produits coréens tunisie, esthétique, korean skincare tunisia">
    <meta name="author" content="Korean Glam By Ameni">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">
    <link rel="shortcut icon" href="{{ $infos->getIcon() }}" type="image/x-icon">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- 🔥 MÉTA-TAGS SPÉCIFIQUES (PAR PAGE, PAR PRODUIT) -->
    @yield('meta')


    <title>@yield('titre') | Korean Glam By Ameni</title>

    <!-- styles / scripts -->
    <link rel="stylesheet"
          href="{{ asset('front/styles/style.css') }}?v={{ filemtime(public_path('front/styles/style.css')) }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function () {
            // Ouvrir menu
            $('#mobile-menu').click(function (e) {
                e.stopPropagation();
                $('.nav-links').addClass('active');
            });

            // Fermer via bouton close
            $('#close-menu-btn').click(function () {
                $('.nav-links').removeClass('active');
            });

            // Fermer si clic dehors
            $(document).click(function (event) {
                if (!$(event.target).closest('#mobile-menu, .nav-links').length) {
                    $('.nav-links').removeClass('active');
                }
            });


        });
    </script>
    @yield('schema')
    <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "Organization",
          "name": "Korean Glam By Ameni",
          "url": "{{ url('/') }}",
  "logo": "{{ $infos->getLogo() }}",
  "sameAs": [
    "https://www.instagram.com/tunisia_marka/",
    "https://www.facebook.com/TunisiaMarkaBy"
  ]
}


    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".toggle-header").forEach(header => {
                header.addEventListener("click", function () {
                    const submenu = this.nextElementSibling;
                    if (submenu && submenu.classList.contains("produits-list")) {
                        submenu.style.display = submenu.style.display === "none" ? "block" : "none";

                        // Toggle +/-
                        const icon = this.querySelector(".toggle-icon");
                        if (icon) icon.textContent = submenu.style.display === "block" ? "-" : "+";
                    }
                });
            });
        });
    </script>
    @livewireStyles

    @yield('schema')
</head>

<body>
<header>
    <h1 class="hidden-seo">Korean Skincare in Tunisia – Authentic K-Beauty Products</h1>

    <button aria-label="go-top" class="go-top" onclick="window.scrollTo({top: 0, behavior: 'smooth'})">
        <i class="fa-solid fa-angles-up"></i>
    </button>
    <div class="container-fluid header-annonce py-2">
        <div class="container d-flex justify-content-between align-items-center text-center flex-wrap">

            {{-- Texte gauche (déjà existant) --}}
            <div class="flex-fill text-start">
            </div>

            {{-- ✅ Nouveau texte au centre --}}
            <div class="flex-fill text-center">
                <span class="fw-bold">{{ $infos->text_header_center ?? '' }}</span>
            </div>

            {{-- (Optionnel) Si tu as un texte à droite plus tard --}}
            <div class="flex-fill text-end">
                <span class="clignotant">{{ $infos->text_header ?? '' }}</span>
            </div>
        </div>
    </div>

    <div class="container-fluid">
        <div class="container">
            <div class="row">
                <div class="col-md-4 mx-auto my-auto">
                    <form action="{{ route('shop') }}" class="hide-mobile-version" method="get" autocomplete="off">
                        <div class="search-home position-relative">
                            <input type="text" id="search-input-desktop" placeholder="Search product...." name="key"
                                   value="{{ session('key_recherche') }}" required>
                            <button type="submit">
                                <i class="fa-solid fa-magnifying-glass"></i>
                            </button>

                            <!-- Suggestions -->
                            <div id="suggestions-box-desktop" class="list-group position-absolute w-100"
                            >
                            </div>
                        </div>
                    </form>
                </div>


                <div class="col-md-4 text-center">
                    <a href="{{ route('home') }}">
                        <img src="{{ $infos->getLogo() }}" alt="korean glam logo" height="100">
                    </a>
                </div>


                <div class="col-md-4 my-auto d-flex justify-content-end">
                    <div class="d-flex justify-content-start small">
                        <div class="my-auto">
                            {{-- Visible only on mobile (smaller than md) --}}
                            <a href="{{ route('login') }}" class="block">
                                <img width="35" height="35" class="user-icon-nav"
                                     src="https://img.icons8.com/ios/50/user--v1.png" alt="user--v1"/>
                            </a>
                        </div>

                        @guest
                            <div class="hide-mobile-version">
                                <div class="small">
                                    <b class="color ">ACCOUNT</b> <br>
                                    <a href="{{ route('register') }}">Register</a> |
                                    <a href="{{ route('login') }}">Login</a>
                                </div>
                            </div>
                        @endguest
                        @auth
                            <div class="small">
                                <b class="color">ACCOUNT</b> <br>
                                <a href="{{ route('profile') }}">My account</a> |
                                <a href="{{ route('logout') }}">Log out</a>
                                <br>
                                <span class="badge bg-success mt-1">
                    ⭐ {{ Auth::user()->points ?? 0 }} points
                </span>
                            </div>
                        @endauth

                        {{-- Panier --}}
                        <div class="my-auto" style="position: relative;" id="btn-open-cart">
                            <img width="30" height="30" class="bag-icon-nav"
                                 src="https://img.icons8.com/ios/50/shopping-bag--v1.png" alt="shopping-bag--v1"/>
                            <span class="badge-cart" id="badge-cart-count">0</span>
                        </div>

                        {{-- Bouton Pro --}}
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <nav class="d-flex justify-content-between">
                <!-- Bouton de menu pour mobile -->
                <div class="menu-toggle" id="mobile-menu">
                    <span class="bar"></span>
                    <span class="bar"></span>
                    <span class="bar"></span>

                </div>

                <!-- Menu principal -->
                <ul class="nav-links">
                    <li class="close-menu d-lg-none text-end ">
                        <button id="close-menu-btn" class="btn btn-link text-dark fs-3">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </li>
                    <li class="d-lg-none">
                    </li>
                    <li class="d-lg-none">
                    </li>
                    {{-- Accueil --}}
                    <li>
                        <a href="{{ route('home') }}">Home</a>
                    </li>


                    {{-- Marques --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle"
                           href="#"
                           role="button"
                           data-bs-toggle="dropdown"
                           data-bs-auto-close="outside"
                           aria-expanded="false">
                            Brands
                        </a>

                        <ul class="dropdown-menu brand-dropdown p-2">
                            <li>
                                <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-2">
                                    @foreach ($brands as $brand)
                                        <div class="col text-center">
                                            <a href="{{ route('shop', ['marque[]' => $brand->id]) }}">
                                                <img src="{{ Storage::url($brand->photourl) }}"
                                                     alt="{{ $brand->brandname }}"
                                                     class="img-fluid rounded image-brand">
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </li>
                        </ul>
                    </li>



                    {{-- Categories dynamiques --}}
                    {{-- Categories dynamiques --}}
                    @foreach($categories as $categorie)
                        <li class="nav-item dropdown d-none d-lg-inline-block {{ $categorie->souscategories->isNotEmpty() ? 'mega-dropdown' : '' }}">
                            <div class="d-flex align-items-center">
                                <a class="nav-link"
                                   href="{{ route('shop', ['categorie[]' => $categorie->id]) }}">
                                    {{ $categorie->nom }}
                                </a>

                                @if($categorie->souscategories->isNotEmpty())
                                    <a class="nav-link dropdown-toggle px-1"
                                       href="#"
                                       id="dropdownCat{{ $categorie->id }}"
                                       role="button"
                                       data-bs-toggle="dropdown"
                                       aria-expanded="false">
                                    </a>
                                @endif
                            </div>


                            {{-- Mega menu si sous-catégories --}}
                            @if($categorie->souscategories->isNotEmpty())
                                <div class="dropdown-menu mega-menu p-4"
                                     aria-labelledby="dropdownCat{{ $categorie->id }}"
                                     style="min-width: 800px;">
                                    <div class="row">
                                        @foreach($categorie->souscategories as $souscategorie)
                                            <div class="col-md-3 text-center mb-3">
                                                <h6 class="fw-bold mb-2">
                                                    <a href="{{ route('shop', [
    'categorie[]'    => $categorie->id,
    'souscategorie[]' => $souscategorie->id
]) }}"
                                                       class="text-dark text-decoration-none">
                                                        {{ $souscategorie->nom }}
                                                    </a>
                                                </h6>
                                                {{-- Image --}}
                                                @if($souscategorie->photo)
                                                    <img src="{{ asset('storage/' . $souscategorie->photo) }}"
                                                         alt="{{ $souscategorie->nom }}"
                                                         class="img-fluid mb-2 rounded cat-image"
                                                    >
                                                @endif

                                                {{-- Sous-sous-catégories --}}
                                                @if($souscategorie->soussouscategories->isNotEmpty())
                                                    <ul class="list-unstyled soussous-list">
                                                        @foreach($souscategorie->soussouscategories as $soussous)
                                                            <li>
                                                                <a href="{{ route('shop', [
    'categorie[]'       => $categorie->id,
    'souscategorie[]'   => $souscategorie->id,
    'soussouscategorie[]' => $soussous->id
]) }}"
                                                                   class="dropdown-item small">
                                                                    {{ $soussous->nom }}
                                                                </a>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </li>
                    @endforeach


                    <li class="d-lg-none">
                        <ul class="submenu list-unstyled text-center">
                            @foreach($categories as $categorie)
                                <li class="">

                                    <div class="toggle-header d-flex align-items-center  gap-2"
                                         style="cursor: pointer;">
                                        <a class="categorie-name text-decoration-none"
                                           href="{{ route('shop', ['idSelectedCategory' => $categorie->id]) }}">
                                            {{ $categorie->nom }}
                                        </a>

                                        @if($categorie->souscategories->isNotEmpty())
                                            <span class="toggle-icon">+</span>
                                        @endif
                                    </div>


                                    {{-- Sous-catégories --}}
                                    @if($categorie->souscategories->isNotEmpty())
                                        <ul class="list-unstyled produits-list text-center"
                                            style="display: none;">
                                            @foreach($categorie->souscategories as $souscategorie)
                                                <li class="mb-2">

                                                    <div class="toggle-header d-flex align-items-center  gap-2"
                                                         style="cursor: pointer;">
                                                        <a class="fw-bold text-decoration-none"
                                                           href="{{ route('shop', [
           'IDcategorie' => $categorie->id,
           'IDsouscategorie' => $souscategorie->id
       ]) }}">
                                                            {{ $souscategorie->nom }}
                                                        </a>

                                                        @if($souscategorie->soussouscategories->isNotEmpty())
                                                            <span class="toggle-icon">+</span>
                                                        @endif
                                                    </div>


                                                    {{-- Sous-sous-catégories --}}
                                                    @if($souscategorie->soussouscategories->isNotEmpty())
                                                        <ul class="list-unstyled produits-list "
                                                            style="display: none;">
                                                            @foreach($souscategorie->soussouscategories as $soussous)
                                                                <li>
                                                                    <a class="produit-name d-block"
                                                                       href="{{ route('shop', [
                                                       'IDcategorie' => $categorie->id,
                                                       'IDsouscategorie' => $souscategorie->id,
                                                       'IDsoussouscategorie' => $soussous->id
                                                   ]) }}">
                                                                        {{ $soussous->nom }}
                                                                    </a>
                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif

                                </li>
                            @endforeach
                        </ul>
                    </li>



                    {{-- Shop --}}
                    <li>
                        <a href="{{ route('shop') }}">Shop</a>
                    </li>
                    <li>
                        <a href="{{ route('appointment') }}">Make an Appointment</a>
                    </li>


                    {{-- Best Sellers --}}
                    <li>
                        <a href="{{ route('shop', ['type' => 'best-sellers']) }}">Best sellers</a>
                    </li>

                    {{-- Contact --}}
                    <li>
                        <a href="{{ route('contact') }}">Contact</a>
                    </li>
                    <li>
                        <form action="{{ route('shop') }}" class="d-block d-md-none" method="get" autocomplete="off">
                            <div class="search-home position-relative">
                                <input type="text" id="search-input-mobile" placeholder="Search product...." name="key"
                                       value="{{ session('key_recherche') }}" required>
                                <button type="submit">
                                    <i class="fa-solid fa-magnifying-glass"></i>
                                </button>

                                <!-- Suggestions -->
                                <div id="suggestions-box-mobile" class="list-group position-absolute w-100"
                                >
                                </div>
                            </div>
                        </form>
                    </li>
                </ul>

                <ul>

                    <li>
                        <a href="{{ route('contact') }}">
                            <i class="fa-solid fa-phone"></i>
                            Support
                        </a>
                    </li>
                </ul>

            </nav>
        </div>
    </div>
</header>
<main>
    @yield('body')
</main>
<br><br>

<footer>
    <div class="container-fluid">
        <br><br>
        <hr class="hr">
        <div class="container">
            <div class="row first-footer">
                <div class="col-md-3">
                    <div class="d-flex justify-content-start">
                        <div class="icons">
                            <i class="fa-solid fa-car-side"></i>
                        </div>
                        <div class="titre my-auto">
                            Fast delivery
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex justify-content-start">
                        <div class="icons">
                            <i class="fa-solid fa-money-bill"></i>
                        </div>
                        <div class="titre my-auto">
                            Cash on delivery
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex justify-content-start">
                        <div class="icons">
                            <i class="fa-regular fa-thumbs-up"></i>
                        </div>
                        <div class="titre my-auto">
                            Quality product
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex justify-content-start">
                        <div class="icons">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <div class="titre my-auto">
                            Support by phone
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br>
        <hr class="hr">
        <br>
        <div class="container before-last">
            <div class="row">
                <div class="col-md-3 text-center">
                    <a href="{{ route('home') }}">
                        <img src="{{ $infos->getLogo() }}" alt="korean glam logo" class="img-fluid">
                    </a>
                </div>
                <div class="col-md-3">
                    <div class="d-flex justify-content-start">
                        <div class="icon-footer">
                            <i class="fa-solid fa-location-dot"></i>
                        </div>
                        <div>
                            <div class="titre">
                                Address
                            </div>
                            <p>
                                {{ $infos->adresse ?? ''   }}
                            </p>


                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex justify-content-start">
                        <div class="icon-footer">
                            <i class="fa-solid fa-phone"></i>
                        </div>
                        <div>
                            <div class="titre">
                                Contact
                            </div>
                            <p>
                                {{ $infos->telephone ?? '' }} <br>
                                {{ $infos->email ?? '' }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="d-flex justify-content-start">
                        <div class="icon-footer">
                            <i class="fa-regular fa-face-smile"></i>
                        </div>
                        <div>
                            <div class="titre">
                                Satisfaction
                            </div>
                            <p>
                                Our goal is to satisfy you in terms of
                                choice,
                                quality, price and service.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <br> <br>
        <div class="container before-last">
            <div class="row">

                <div class="col-md-6">
                    <div>
                        <p class="text-justify">
                            Korean Glam is a leading destination for <strong>Korean skincare in Tunisia</strong>,
                            offering a curated selection of authentic K-Beauty brands such as <strong>Medicube</strong>,
                            <strong>Some By Mi</strong>, and <strong>Beauty of Joseon</strong>. Explore our range of
                            Korean beauty essentials, including complete skincare routines, serums, moisturizers, and
                            facial care products designed for all skin types.
                        </p>

                    </div>

                </div>

                <div class="col-md-2">
                    <div class="titre">
                        Privacy & terms
                    </div>
                    <br>
                    <div>
                        <a href="{{ route('terms') }}">Terms & Conditions</a> <br>
                        <a href=" {{ route('return-policy') }} ">Returns and refunds</a>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="titre">
                        My account
                    </div>
                    <br>
                    <div>
                        <a href="{{ route('orders') }}">My orders</a> <br>
                        <a href="{{ route('cart') }} ">My cart</a>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="my-auto pro ">
                        <br>
                        <br>
                        <a href="{{ route('pro.page') }}" class="btn btn-outline-danger btn-sm">
                            Wholesaler
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br> <br>
    <div class="last">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    Copyright korean glam by <a class="lien_ebuild" target="_blank" href="https://ebuild.tn/">Ebuild</a>.
                </div>
                <div class="col-md-6 icon-footer">
                    <ul>
                        @if ($infos->facebook)
                            <li><a href="{{ $infos->facebook }}" target="_blank"><i
                                        class="fa-brands fa-facebook"></i></a></li>
                        @endif
                        @if ($infos->instagram)

                            <li><a href="{{ $infos->instagram }}" target="_blank"><i class="fa-brands fa-instagram"></i></a>
                            </li>
                        @endif

                        @if ($infos->tiktok)
                            <li><a href="{{ $infos->tiktok }}" target="_blank"><i class="fa-brands fa-tiktok"></i></a>
                            </li>
                        @endif

                        @if ($infos->youtube)
                            <li><a href="{{ $infos->youtube }}" target="_blank"><i class="fa-brands fa-youtube"></i></a>
                            </li>
                        @endif

                    </ul>
                </div>
            </div>
        </div>
    </div>
</footer>

<div class="overlay-cart">
    <div class="card-panier">
        <div>
            <div class="text-end">
                <i class="fa-solid fa-xmark" id="btn-close-cart"></i>
            </div>
            <div>
                <b>
                    There are <span class="color" id="count-total-cart">0</span> Products
                </b>
            </div>
        </div>
        <br>
        <div id="cart-content-modal">
            ok
        </div>
        <div class="footer-elemnt">
            <div>
                <table class="w-100">
                    <tr>
                        <td><b>Shipping:</b></td>
                        <td class="color  text-end">
                            <b>{{$infos->frais}} <sup>AED</sup></b>
                        </td>
                    </tr>
                    <tr>
                        <td><b>Sub total:</b></td>
                        <td class="color  text-end">
                            <b><span id="cart-total_prix">0.000 </span><sup>AED</sup></b>
                        </td>
                    </tr>
                </table>
            </div>
            <a href="{{ route('cart') }}" class="btn btn-dark w-100 mt-3 p-2 btn-border text-white">
                <b>View cart</b>
            </a>
            <button class="btn btn-dark w-100 mt-3 p-2 btn-border" id="btn-clear-cart">
                <i class="fa-regular fa-trash-can"></i>
                <b>Clear cart</b>
            </button>
        </div>
    </div>
</div>
<script src="{{ asset('front/js/cart.js') }}?v={{ filemtime(public_path('front/js/cart.js')) }}"></script>
<script src="{{ asset('front/js/home.js') }}?v={{ filemtime(public_path('front/js/home.js')) }}"></script>
<script>
    function initSearch(inputId, boxId) {
        const input = document.getElementById(inputId);
        const box = document.getElementById(boxId);

        if (!input || !box) return;

        input.addEventListener('input', function () {
            let query = this.value;

            if (query.length < 2) {
                box.style.display = "none";
                return;
            }

            fetch(`{{ route('search.suggestions') }}?q=${encodeURIComponent(query)}`)
                .then(res => res.json())
                .then(data => {
                    box.innerHTML = '';

                    if (data.length > 0) {
                        data.forEach(item => {
                            box.innerHTML += `
                            <a href="/produit/${item.id}" class="list-group-item list-group-item-action d-flex align-items-center">
                                <img src="/storage/${item.photo}" style="width:30px;height:30px;object-fit:cover;" class="me-2 rounded">
                                ${item.nom}
                            </a>
                        `;
                        });
                        box.style.display = "block";
                    } else {
                        box.style.display = "none";
                    }
                });
        });

        // fermer suggestions au clic extérieur
        document.addEventListener('click', e => {
            if (!input.contains(e.target) && !box.contains(e.target)) {
                box.style.display = "none";
            }
        });
    }

    document.addEventListener('DOMContentLoaded', function () {
        initSearch('search-input-desktop', 'suggestions-box-desktop');
        initSearch('search-input-mobile', 'suggestions-box-mobile');
    });
</script>

@yield('scripts')
@livewireScripts
</body>

</html>
