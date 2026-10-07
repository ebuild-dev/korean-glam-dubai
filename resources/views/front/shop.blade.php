@extends('front.fixe')
@section('titre', 'Shop Korean Skincare in Tunisia')

@section('meta')
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ Request::url() }}">

    <!-- SEO SHOP LIST -->
    <meta name="description"
          content="Discover authentic Korean skincare in Tunisia including Centella Asiatica serums, creams, masks, and Medicube products. High-quality K-Beauty with fast local delivery.">

    <meta name="keywords"
          content="korean skincare tunisia, korean cosmetics wholesale, k beauty tunisia, korean beauty shop, skincare products tunisia">

    <!-- OPEN GRAPH SHOP -->
    <meta property="og:title" content="Shop Korean Skincare in Tunisia">
    <meta property="og:description"
          content="Explore a curated selection of authentic Korean skincare products available in Tunisia. Trusted brands, fast delivery.">
    <meta property="og:url" content="{{ Request::url() }}">
    <meta property="og:image" content="{{ asset('images/shop-cover.jpg') }}">
    <meta property="og:type" content="website">

    <!-- TWITTER CARD SHOP -->
    <meta name="twitter:title" content="Shop Korean Skincare in Tunisia">
    <meta name="twitter:description"
          content="Authentic Korean skincare products available in Tunisia. Discover top Korean beauty brands.">

@endsection
@section('schema')
    <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "CollectionPage",
          "name": "Korean Skincare Shop in Tunisia",
          "description": "Online shop offering authentic Korean skincare products in Tunisia. Explore cleansers, serums, creams and treatments from trusted Korean brands.",
          "url": "{{ Request::url() }}",
  "mainEntity": {
    "@type": "ItemList",
    "itemListOrder": "https://schema.org/ItemListOrderAscending",
    "numberOfItems": "{{ $produits->count() ?? 0 }}"
  }
}
    </script>
@endsection

@section('body')


    <div class="container">
        <br><br>
        <!-- SEO INTRO SHOP -->
        <article class="text-center">
            <header>
                <h2>Korean skincare in Tunisia</h2>
            </header>
            <section>
                <p>At Korean Glam, we offer a curated selection of <strong>authentic Korean skincare products</strong> available in Tunisia.</p>
                <p>We ship across Tunisia, ensuring fast and reliable delivery. Explore our collection of <strong>Korean skincare products in Tunisia</strong> today and experience the best in K-beauty.</p>
            </section>
        </article>

        <br><br>
        <!-- Bouton Filtre (visible seulement en mobile) -->
        <div class="d-md-none mb-3 text-end">
            <button class="btn btn-outline-primary"
                    aria-label="Filter"
                    type="button"
                    data-bs-toggle="offcanvas"
                    data-bs-target="#offcanvasFilter"
                    aria-controls="offcanvasFilter">
                <i class="fa fa-filter"></i>
                Filter
            </button>

        </div>

        <!-- Sidebar Filtres en Offcanvas -->
        <div class="offcanvas offcanvas-start d-md-none"
             tabindex="-1"
             id="offcanvasFilter"
             aria-labelledby="offcanvasFilterLabel"
             data-bs-scroll="true"
             data-bs-backdrop="true">
            <div class="offcanvas-header">
                <h5 id="offcanvasFilterLabel">Filtres</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <!-- Ton code filtre ici -->
                <div>
                    <div class="mb-3">
                        <h6><b>Filter by price</b></h6>
                        <div class="price-range">
                            <div id="slider-1" class="mb-2 mt-3"></div>
                            <div class="price-values d-flex justify-content-between">
                                <div><span id="min-price-1">0</span><sup>AED</sup></div>
                                <div><span id="max-price-1">{{ $max_price * 2 }}</span><sup>AED</sup></div>
                            </div>
                        </div>
                    </div>

                    <!-- Marques -->
                    <div class="mb-3">
                        <h6><b>Marques</b></h6>
                        @foreach ($marques as $marque)
                            <div class="d-flex align-items-start gap-2">
                                <input type="checkbox" name="marque" class="selectShopmarque"
                                       data-id="{{ $marque->id }}" value="{{ $marque->id }}"
                                    {{ $idSelectedBrand == $marque->id ? 'checked' : '' }}>
                                <span>{{ $marque->brandname }}
                            <small class="text-muted">({{ $marque->produits_count }})</small>
                        </span>
                            </div>
                        @endforeach
                    </div>

                    <!-- Categories -->
                    <div class="mb-3">
                        <h6><b>Categories</b></h6>
                        @foreach ($categories as $categorie)
                            @if($categorie->souscategories->isNotEmpty())
                                <div class="mb-2">
                                    <b>{{ $categorie->nom }}</b>
                                    <div class="ms-3">
                                        @foreach ($categorie->souscategories as $souscategorie)
                                            <div class="d-flex align-items-start gap-2">
                                                <input type="checkbox" name="souscategorie" class="selectShopSousCategorie"
                                                       data-id="{{ $souscategorie->id }}" value="{{ $souscategorie->id }}"
                                                    {{ $IDsouscategorie == $souscategorie->id ? 'checked' : '' }}>
                                                <span>{{ $souscategorie->nom }}
                                            <small class="text-muted">({{ $souscategorie->produits_count }})</small>
                                        </span>
                                            </div>
                                            <!-- Sous-sous-catégories -->
                                            @if($souscategorie->soussouscategories->isNotEmpty())
                                                <div class="ms-4">
                                                    @foreach ($souscategorie->soussouscategories as $soussous)
                                                        <div class="d-flex align-items-start gap-2">
                                                            <input type="checkbox" name="soussouscategorie" class="selectShopSousSousCategorie"
                                                                   data-id="{{ $soussous->id }}" data-parent-id="{{ $souscategorie->id }}" value="{{ $soussous->id }}"
                                                                {{ $IDsoussouscategorie == $soussous->id ? 'checked' : '' }}>
                                                            <span>{{ $soussous->nom }}
                                                        <small class="text-muted">({{ $soussous->produits_count }})</small>
                                                    </span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>


        <div class="row">
            <div class="col-md-3 d-none d-md-block">
                <div>
                    <div class="mb-3 ">
                        <h6><b>Filter by price</b></h6>
                        <div class="price-range">
                            <div id="slider" class="mb-2 mt-3"></div>
                            <div class="price-values">
                                <div>
                                    <span id="min-price">0</span>
                                    <sup>AED</sup>
                                </div>
                                <div>
                                    <span id="max-price">{{ $max_price * 2 }}</span>
                                    <sup>AED</sup>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <h6><b>Marques</b></h6>
                        @foreach ($marques as $marque)
                            <div class="d-flex align-items-start gap-2">
                                <div>
                                    <input type="checkbox" name="marque" class="selectShopmarque"
                                        data-id="{{ $marque->id }}" value="{{ $marque->id }}" {{ $idSelectedBrand == $marque->id ? 'checked' : '' }}>
                                </div>
                                <div>
                                    {{ $marque->brandname }}
                                    <small class="text-muted">
                                        (  {{ $marque->produits_count }} )
                                    </small>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <div class="mb-3">
                        <h6><b>Categories</b></h6>
                        @foreach ($categories as $categorie)
                            @if($categorie->souscategories->isNotEmpty())
                                <div class="mb-2">
                                    <b>{{ $categorie->nom }}</b>
                                    <div class="ms-3">
                                        @foreach ($categorie->souscategories as $souscategorie)
                                            <div class="d-flex align-items-start gap-2">
                                                <div>
                                                    <input type="checkbox"
                                                           name="souscategorie"
                                                           class="selectShopSousCategorie"
                                                           data-id="{{ $souscategorie->id }}"
                                                           value="{{ $souscategorie->id }}"
                                                        {{ $IDsouscategorie == $souscategorie->id ? 'checked' : '' }}>
                                                </div>
                                                <div>
                                                    {{ $souscategorie->nom }}
                                                    <small class="text-muted">
                                                        ({{ $souscategorie->produits_count }})
                                                    </small>
                                                </div>
                                            </div>

                                            {{-- Sous-sous-catégories --}}
                                            @if($souscategorie->soussouscategories->isNotEmpty())
                                                <div class="ms-4">
                                                    @foreach ($souscategorie->soussouscategories as $soussous)
                                                        <div class="d-flex align-items-start gap-2">
                                                            <div>
                                                                <input type="checkbox"
                                                                       name="soussouscategorie"
                                                                       class="selectShopSousSousCategorie"
                                                                       data-id="{{ $soussous->id }}"
                                                                       data-parent-id="{{ $souscategorie->id }}"
                                                                       value="{{ $soussous->id }}"
                                                                    {{ $IDsoussouscategorie == $soussous->id ? 'checked' : '' }}>
                                                            </div>
                                                            <div>
                                                                {{ $soussous->nom }}
                                                                <small class="text-muted">
                                                                    ({{ $soussous->produits_count }})
                                                                </small>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                </div>
            </div>
            <div class="col-md-9">
                <div class="card flex-row justify-content-between p-2">
                    <div class="my-auto">
                        @if ($type == 'best-sellers')
                            <h6>Best sellers</h6>
                        @else
                            <h6>All products</h6>
                        @endif
                    </div>
                    <div>
                        <i class="fa-solid fa-basket-shopping"></i>
                        <b><span class="color" id="totalProduit">0</span></b> / {{ DB::table('produits')->count() }}
                        products.
                    </div>
                </div>
                <br>
                <div id="ProduitsList">
                    <div class="p-5 text-center">
                        <img src="/icons/ZKZg.gif" height="60" width="60" alt="Loading" srcset="">
                    </div>
                </div>
{{--                <div class="d-flex justify-content-center mt-4">--}}
{{--                    {{$produits->links('pagination::bootstrap-4')  }}--}}
{{--                </div>--}}

            </div>
        </div>
    </div>

    <!-- Lien vers le CSS de noUiSlider -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="{{ asset('front/styles/shop.css') }}?v={{ filemtime(public_path('front/styles/shop.css')) }}">


@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            // Slider 1
            const slider1 = document.getElementById('slider-1');
            const minPrice1 = $('#min-price-1');
            const maxPrice1 = $('#max-price-1');

            noUiSlider.create(slider1, {
                start: [0, {{ $max_price * 2 }}],
                connect: true,
                range: { 'min': 0, 'max': {{ $max_price * 2 }} },
                step: 10,
            });

            slider1.noUiSlider.on('update', function(values) {
                minPrice1.text(Math.round(values[0]));
                maxPrice1.text(Math.round(values[1]));
            });


        });
    </script>
    <script>

        // Variables globales
        let idCategorieSelecteds = [];
        let idSousCategorieSelecteds = [];
        let idSousSousCategorieSelecteds = [];
        let idMarqueSelecteds = [];
        let minPrice, maxPrice, token, best_sellers;

        $(document).ready(function() {
            let pushUrl = true; // ou false selon le comportement que tu veux

            const slider1 = $('#slider-1')[0];
            const slider = $('#slider')[0];
            minPrice = $('#min-price');
            maxPrice = $('#max-price');
            const minPrice1 = $('#min-price-1');
            const maxPrice1 = $('#max-price-1');
            token = $("meta[name='csrf-token']").attr("content");
            best_sellers = "{{ $type ? 1 : 0 }}";

            const urlParams = new URLSearchParams(window.location.search);

            idCategorieSelecteds = urlParams.getAll("categorie[]");
            idSousCategorieSelecteds = urlParams.getAll("souscategorie[]");
            idSousSousCategorieSelecteds = urlParams.getAll("soussouscategorie[]");
            idMarqueSelecteds = urlParams.getAll("marque[]");
            if (!idCategorieSelecteds.length && @json($IDcategorie)) idCategorieSelecteds = [String(@json($IDcategorie))];
            if (!idSousCategorieSelecteds.length && @json($IDsouscategorie)) idSousCategorieSelecteds = [String(@json($IDsouscategorie))];
            if (!idSousSousCategorieSelecteds.length && @json($IDsoussouscategorie)) idSousSousCategorieSelecteds = [String(@json($IDsoussouscategorie))];
            if (!idMarqueSelecteds.length && @json($idSelectedBrand)) idMarqueSelecteds = [String(@json($idSelectedBrand))];

            $(".selectShopSousCategorie").each(function() {
                $(this).prop('checked', idSousCategorieSelecteds.includes(String($(this).data('id'))));
            });
            $(".selectShopSousSousCategorie").each(function() {
                $(this).prop('checked', idSousSousCategorieSelecteds.includes(String($(this).data('id'))));
            });
            $(".selectShopmarque").each(function() {
                $(this).prop('checked', idMarqueSelecteds.includes(String($(this).data('id'))));
            });

            var token = $("meta[name='csrf-token']").attr("content");
            // Fonction AJAX
            let productsRequest = null;
            let latestProductsRequest = 0;
            function fetchProduits() {
                if (productsRequest) productsRequest.abort();
                const requestNumber = ++latestProductsRequest;
                const desktopFilters = window.matchMedia('(min-width: 768px)').matches;
                const activeMinPrice = desktopFilters ? minPrice.text() : minPrice1.text();
                const activeMaxPrice = desktopFilters ? maxPrice.text() : maxPrice1.text();
                productsRequest = $.ajax({
                    url: "/shop/ajax",
                    type: "POST",
                    data: {
                        _token: token,
                        idCategorieSelecteds: idCategorieSelecteds,
                        idSousCategorieSelecteds: idSousCategorieSelecteds,
                        idSousSousCategorieSelecteds: idSousSousCategorieSelecteds,
                        minPrice: activeMinPrice,
                        maxPrice: activeMaxPrice,
                        key_recherche: '{{ $key_recherche }}',
                        idMarqueSelecteds: idMarqueSelecteds,
                        best_sellers: best_sellers
                    },
                    success: function(response) {
                        if (requestNumber !== latestProductsRequest) return;
                        $("#ProduitsList").html(response.html);
                        $("#totalProduit").text(response.total);
                        if(pushUrl){
                            // Mettre à jour l'URL si nécessaire
                            let params = new URLSearchParams();
                            idCategorieSelecteds.forEach(c => params.append("categorie[]", c));
                            idSousCategorieSelecteds.forEach(sc => params.append("souscategorie[]", sc));
                            idSousSousCategorieSelecteds.forEach(ssc => params.append("soussouscategorie[]", ssc));
                            idMarqueSelecteds.forEach(m => params.append("marque[]", m));
                            let newUrl = window.location.pathname + "?" + params.toString();
                            window.history.replaceState({}, '', newUrl);
                        }
                    },
                    error: function(xhr, status) {
                        if (status === 'abort' || requestNumber !== latestProductsRequest) return;
                        $("#ProduitsList").html('<p class="alert alert-warning" role="status">Unable to load products. Please try again.</p>');
                        $("#totalProduit").text('0');
                    }
                });
            }
            // ✅ Catégorie (single-select)
            $(document).on('change', ".selectShopCategorie", function() {
                let id = String($(this).data("id"));

                // Décocher toutes les autres cases du même groupe
                $(".selectShopCategorie").not(this).prop("checked", false);

                // Mettre à jour le tableau
                idCategorieSelecteds = $(this).is(':checked') ? [id] : [];

                fetchProduits(true);
            });

// ✅ Sous-catégorie (single-select)
            $(document).on('change', ".selectShopSousCategorie", function() {
                let id = String($(this).data("id"));

                $(".selectShopSousCategorie").not(this).prop("checked", false);
                $(".selectShopSousCategorie").not(this).filter(function() {
                    return String($(this).data('id')) === id;
                }).prop('checked', $(this).is(':checked'));
                idSousCategorieSelecteds = $(this).is(':checked') ? [id] : [];
                idSousSousCategorieSelecteds = [];
                $(".selectShopSousSousCategorie").prop('checked', false);

                fetchProduits(true);
            });

// ✅ Sous-sous-catégorie (single-select avec reset sous-catégorie)
            $(document).on('change', ".selectShopSousSousCategorie", function() {
                let id = String($(this).data("id"));
                let parentSousCategorieId = $(this).data("parent-id"); // Assure-toi d'avoir data-parent-id sur chaque sous-sous-catégorie

                if ($(this).is(':checked')) {
                    // Décocher toutes les autres sous-sous-catégories
                    $(".selectShopSousSousCategorie").not(this).prop("checked", false);
                    idSousSousCategorieSelecteds = [id];

                    // 🔹 Décocher la sous-catégorie précédente si différente de la parent de cette sous-sous-catégorie
                    $(".selectShopSousCategorie").each(function() {
                        if ($(this).data("id") != parentSousCategorieId) {
                            $(this).prop("checked", false);
                        }
                    });

                    // Mettre à jour le tableau de sous-catégories
                    idSousCategorieSelecteds = parentSousCategorieId ? [String(parentSousCategorieId)] : [];
                    $(".selectShopSousCategorie").filter(function() {
                        return String($(this).data('id')) === String(parentSousCategorieId);
                    }).prop('checked', true);

                } else {
                    idSousSousCategorieSelecteds = [];
                }
                $(".selectShopSousSousCategorie").not(this).filter(function() {
                    return String($(this).data('id')) === id;
                }).prop('checked', $(this).is(':checked'));

                fetchProduits(true);
            });

// ✅ Marques (multi-select)
            $(document).on('change', ".selectShopmarque", function() {
                let id = String($(this).data("id"));
                if ($(this).is(':checked')) {
                    if (!idMarqueSelecteds.includes(id)) idMarqueSelecteds.push(id);
                } else {
                    idMarqueSelecteds = idMarqueSelecteds.filter(item => String(item) !== id);
                }
                $(".selectShopmarque").not(this).filter(function() {
                    return String($(this).data('id')) === id;
                }).prop('checked', $(this).is(':checked'));
                fetchProduits(true);
            });



            // Premier appel → garder URL telle quelle
            fetchProduits(false);

            // Slider
            noUiSlider.create(slider, {
                start: [0, {{ $max_price * 2 }}],
                connect: true,
                range: { 'min': 0, 'max': {{ $max_price * 2 }} },
                step: 10,
            });

            slider.noUiSlider.on('update', function(values) {
                minPrice.text(Math.round(values[0]));
                maxPrice.text(Math.round(values[1]));
            });

            slider.noUiSlider.on('set', function() {
                fetchProduits();
            });
            slider1.noUiSlider.on('update', function(values) {
                minPrice1.text(Math.round(values[0]));
                maxPrice1.text(Math.round(values[1]));
            });

            slider1.noUiSlider.on('set', function() {
                fetchProduits();
            });
        });
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        // ✅ Pagination AJAX avec filtres
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            let page = $(this).attr('href').split('page=')[1];

            $.ajax({
                url: "/shop/ajax?page=" + page,
                type: "POST",
                data: {
                    idCategorieSelecteds: idCategorieSelecteds,
                    idSousCategorieSelecteds: idSousCategorieSelecteds,
                    idSousSousCategorieSelecteds: idSousSousCategorieSelecteds,
                    idMarqueSelecteds: idMarqueSelecteds,
                    minPrice: window.matchMedia('(min-width: 768px)').matches ? minPrice.text() : minPrice1.text(),
                    maxPrice: window.matchMedia('(min-width: 768px)').matches ? maxPrice.text() : maxPrice1.text(),
                    key_recherche: '{{ $key_recherche }}',
                    best_sellers: best_sellers
                },
                success: function(response) {
                    $("#ProduitsList").html(response.html);
                    $("#totalProduit").text(response.total);
                }
            });
        });



    </script>
@endsection
