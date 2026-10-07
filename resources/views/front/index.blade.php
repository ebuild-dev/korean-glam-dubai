@extends('front.fixe')

@section('titre', 'Korean Skincare Tunisia | Authentic K-Beauty')

@section('meta')
    <!-- SEO META TAGS -->
    <meta name="description"
          content="Korean Skincare Tunisia: discover authentic K-Beauty products in Tunisia. Korean serums, creams, masks and skincare with fast local delivery.">

    <meta name="keywords"
          content="korean skincare tunisia, korean skin care tunisia, k-beauty tunisia, korean beauty store tunisia,first korean beauty store tunisia, korean face care, authentic korean skincare, korean glam ameni">

    <meta name="author" content="Korean Glam By Ameni">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ url()->current() }}">

    <!-- OPEN GRAPH -->
    <meta property="og:title" content="Korean Skincare Tunisia – Authentic K-Beauty | Korean Glam By Ameni">
    <meta property="og:description"
          content="Your reference for Korean Skincare in Tunisia. Authentic K-Beauty products, professional advice, and Korean face care.">
    <meta property="og:image" content="{{ $infos->getLogo() }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="website">
    <meta property="og:locale" content="en_TN">

    <!-- TWITTER -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Korean Skincare Tunisia – Korean Glam By Ameni">
    <meta name="twitter:description"
          content="Authentic Korean skincare and K-Beauty products available in Tunisia.">
    <meta name="twitter:image" content="{{ $infos->getLogo() }}">

    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
@endsection
@section('schema')
    <script type="application/ld+json">
        {
          "@context": "https://schema.org",
          "@type": "WebSite",
          "name": "Korean Glam By Ameni",
          "alternateName": "Korean Skincare Tunisia",
          "url": "{{ url('/') }}",
  "potentialAction": {
    "@type": "SearchAction",
    "target": "{{ url('/shop') }}?q={search_term_string}",
    "query-input": "required name=search_term_string"
  }
}
    </script>
@endsection

@section('body')


    <link rel="stylesheet" href="/front/styles/home.css?v={{ time() }}" >



    <body>
    <div class="carousel" id="bannerCarousel">
        <div class="carousel-inner">
            @foreach ($banners as $key => $bitem)
                <div class="carousel-item {{ $key == 0 ? 'active' : '' }}">
                    <picture>
                        <source media="(max-width: 767px)" srcset="{{ Storage::url($bitem->image_mobile) }}" >
                        <img src="{{ Storage::url($bitem->image) }}" alt="Authentic Korean skincare product available in Tunisia" loading="lazy">
                    </picture>

                    @if ($bitem->show_btn)
                        <a href="{{ route('shop') }}" class="btn-banner-home text-white">
                            <b>Buy it now !</b>
                        </a>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="progress-bar">
            <div class="progress"></div>
        </div>
    </div>


    <div class="container">

        <div class="container">
            <div class="text-center mt-5 mb-3">
                <h2>Trending Korean Skincare Products</h2>

            </div>
            <div id="produitsCarousel2" class="carousel slide" data-bs-ride="carousel">
                <!-- Boutons de défilement -->
                <button class="carousel-control-prev" aria-label="carousel-control-prev" type="button" data-bs-target="#produitsCarousel2"
                        data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Previous</span>
                </button>
                <button class="carousel-control-next" aria-label="carousel-control-next" type="button" data-bs-target="#produitsCarousel2"
                        data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="visually-hidden">Suivant</span>
                </button>

                <!-- Contenu du carousel -->
                <div class="carousel-inner">
                    @foreach ($produits->chunk($itemsPerSlide) as $chunk)
                    <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                            <div class="row">
                                @foreach ($chunk as $produit)
                                    <div class="col-12 col-sm-6 col-lg-3">
                                        <div class="card-produit m-2">
                                            <a title="View details and buy {{ $produit->nom }}" href="{{ route('produit', ['id' => $produit->id]) }}">
                                                <div class="card-produit-img">
                                                    @if ($produit->exclusive)
                                                        <span class="badge-exclusive">
            <i class="ri-vip-crown-fill"></i> Exclusive
        </span>
                                                    @endif

                                                    @if ($produit->inPromotion())
                                                        <div class="badge-promotion">
                                                            - {{ $produit->inPromotion()->pourcentage }} %
                                                        </div>
                                                    @endif
                                                    <img src="{{ $produit->FirstImage() }}"  alt="Buy {{ $produit->nom }} – Korean Skincare Tunisia"
                                                         title="{{ $produit->nom }} – Korean Skincare" loading="lazy"/>
                                                    <button data-id="{{ $produit->id }}" type="button" aria-label="add {{$produit->nom}} to favoris"
                                                            class="btn-add-to-favoris btn-ajouter-favoris">
                                                        <i class="fa-solid fa-heart"></i>
                                                    </button>
                                                </div>
                                                <p class="product-title" title="{{ $produit->nom }}">{{ $produit->nom }}</p>

                                            </a>
                                            @if ($produit->inPromotion())
                                                <b>
                                                    {{ $produit->getPrice() }} <sup>AED</sup>
                                                </b>
                                                <strike>
                                                    {{ $produit->prix }}<sup>AED</sup>
                                                </strike>
                                            @else
                                                <b>
                                                    {{ $produit->getPrice() }} <sup>AED</sup>
                                                </b>
                                            @endif
                                            <label for="qte-produit-{{ $produit->id }}" class="sr-only">Quantity</label>

                                            <input type="number" value="1" class="form-control mt-2"
                                                   id="qte-produit-{{ $produit->id }}" name="quantity" max="10">
                                            <button type="button"
                                                    aria-label="Add {{ $produit->nom }} to cart"
                                                    class="btn btn-primary-color w-100 mt-2 btn-add-to-cart"
                                                    data-id="{{ $produit->id }}">
                                                Add to cart
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <br><br><br>


        <div class="container">
            <div class="row">
                <div class="col-sm-6 mb-3 mb-sm-0">
                    <div class="img-product-categorie">
                        <div class="info">
                            <h3>{{ $infos->home_text_1 }}</h3>
                            <a href="{{ $infos->home_lien_1 }}" class="btn btn-primary-color">Buy now</a>
                        </div>
                        <img src="{{ Storage::url($infos->home_photo_1) }}" alt="Authentic Korean skincare product available in Tunisia"
                             class="img-fluid product-image">
                    </div>
                </div>

                <div class="col-sm-6">
                    <div class="img-product-categorie">
                        <div class="info">
                            <h3>{{ $infos->home_text_2 }}</h3>
                            <a href="{{ $infos->home_lien_2 }}" class="btn btn-primary-color">Buy now</a>
                        </div>
                        <img src="{{ Storage::url($infos->home_photo_2) }}" alt="Authentic Korean skincare product available in Tunisia"
                             class="img-fluid product-image">
                    </div>
                </div>
            </div>

        </div>


        <div class="container selected-type pt-lg-5">
            <div class="text-center mt-5 mb-3">
                <h2>Our Korean Skincare Products</h2>
            </div>
            <div>
                <ul class="option-ours-products-home">
                    <li class="btn-change-type active" data-type="special">SPECIAL PRODUCTS</li>
                    <li class="btn-change-type" data-type="new">NEW PRODUCTS</li>
                    <li class="btn-change-type" data-type="best">BEST SELLERS</li>
                </ul>
                <br>
                <div class="tab active special">
                    <div class="row">
                        @foreach ($OtherProduits as $produit)
                            <script type="application/ld+json">
                                {
                                  "@context": "https://schema.org/",
                                  "@type": "Product",
                                  "name": "{{ $produit->nom }}",
  "image": "{{ $produit->FirstImage() }}",
  "description": "{{ Str::limit($produit->description ?? $produit->nom, 160) }}",
  "brand": {
    "@type": "Brand",
    "name": "{{ $produit->brand->brandname ?? 'Korean Glam' }}"
  },
  "offers": {
    "@type": "Offer",
    "url": "{{ route('produit', ['id' => $produit->id, 'slug' => Str::slug($produit->nom)]) }}",
    "priceCurrency": "AED",
    "price": "{{ $produit->getPrice() }}",
    "availability": "https://schema.org/InStock"
  }
}
                            </script>

                            <div class="col-sm-3">
                                <div class="card-produit m-2">
                                    <a
                                        href="{{ route('produit', ['id' => $produit->id, 'slug' => Str::slug($produit->nom)]) }}"  title="View details and buy {{ $produit->nom }}">
                                        <div class="card-produit-img">
                                            @if ($produit->inPromotion())
                                                <div class="badge-promotion">
                                                    - {{ $produit->inPromotion()->pourcentage }} %
                                                </div>
                                            @endif
                                            <img src="{{ $produit->FirstImage() }}" loading="lazy"  alt="Buy {{ $produit->nom }} – Korean Skincare Tunisia"
                                                 title="{{ $produit->nom }} – Korean Skincare" />
                                            @if ($produit->exclusive)
                                                <span class="badge-exclusive">
            <i class="ri-vip-crown-fill"></i> Exclusive
        </span>
                                            @endif
                                            <button data-id="{{ $produit->id }}" type="button" aria-label="add {{ $produit->nom }} to favoris"
                                                    class="btn-add-to-favoris btn-ajouter-favoris">
                                                <i class="fa-solid fa-heart"></i>
                                            </button>
                                        </div>
                                        <p class="product-title" title="{{ $produit->nom }}">{{ $produit->nom }}</p>

                                    </a>
                                    @if ($produit->inPromotion())
                                        <b>
                                            {{ $produit->getPrice() }} <sup>AED</sup>
                                        </b>
                                        <strike>
                                            {{ $produit->prix }}<sup>AED</sup>
                                        </strike>
                                    @else
                                        <b>
                                            {{ $produit->getPrice() }} <sup>AED</sup>
                                        </b>
                                    @endif
                                    <label for="qte-produit-{{ $produit->id }}" class="sr-only">Quantity</label>

                                    <input type="number" value="1" class="form-control mt-2"
                                           id="qte-produit-{{ $produit->id }}" name="quantity" max="10">
                                    <button type="button" class="btn btn-primary-color w-100 mt-2 btn-add-to-cart"
                                            aria-label="Add {{ $produit->nom }} to cart"
                                            data-id="{{ $produit->id }}">
                                        Add to cart
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="tab new">
                    <div class="row">
                        @foreach ($NewProduits as $produit)
                            <script type="application/ld+json">
                                {
                                  "@context": "https://schema.org/",
                                  "@type": "Product",
                                  "name": "{{ $produit->nom }}",
  "image": "{{ $produit->FirstImage() }}",
  "description": "{{ Str::limit($produit->description ?? $produit->nom, 160) }}",
  "brand": {
    "@type": "Brand",
    "name": "{{ $produit->brand->brandname ?? 'Korean Glam' }}"
  },
  "offers": {
    "@type": "Offer",
    "url": "{{ route('produit', ['id' => $produit->id, 'slug' => Str::slug($produit->nom)]) }}",
    "priceCurrency": "AED",
    "price": "{{ $produit->getPrice() }}",
    "availability": "https://schema.org/InStock"
  }
}
                            </script>

                            <div class="col-sm-3">
                                <div class="card-produit m-2">
                                    <a    title="View details and buy {{ $produit->nom }}"
                                        href="{{ route('produit', ['id' => $produit->id, 'slug' => Str::slug($produit->nom)]) }}">
                                        <div class="card-produit-img">
                                            @if ($produit->exclusive)
                                                <span class="badge-exclusive">
                                                <i class="ri-vip-crown-fill"></i> Exclusive
                                            </span>
                                            @endif
                                            @if ($produit->inPromotion())
                                                <div class="badge-promotion">
                                                    - {{ $produit->inPromotion()->pourcentage }} %
                                                </div>
                                            @endif
                                            <img
                                                src="{{ $produit->FirstImage() }}" loading="lazy"
                                                alt="Buy {{ $produit->nom }} – Korean Skincare Tunisia"
                                                title="{{ $produit->nom }} – Korean Skincare"/>
                                            <button data-id="{{ $produit->id }}" type="button" aria-label="add {{ $produit->nom }} to favoris"
                                                    class="btn-add-to-favoris btn-ajouter-favoris">
                                                <i class="fa-solid fa-heart"></i>
                                            </button>
                                        </div>
                                        <p class="product-title" title="{{ $produit->nom }}">{{ $produit->nom }}</p>

                                    </a>
                                    <b>
                                        {{ $produit->getPrice() }} <sup>AED</sup>
                                    </b>
                                    <label for="qte-produit-{{ $produit->id }}" class="sr-only">Quantity</label>

                                    <input type="number" value="1" class="form-control mt-2"
                                           id="qte-produit-{{ $produit->id }}" name="quantity" max="10">
                                    <button type="button" class="btn btn-primary-color w-100 mt-2 btn-add-to-cart"
                                            aria-label="Add {{ $produit->nom }} to cart"
                                            data-id="{{ $produit->id }}">
                                        Add to cart
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="tab best">
                    <div class="row">
                        @foreach ($topProduits as $item)
                            <script type="application/ld+json">
                                {
                                  "@context": "https://schema.org/",
                                  "@type": "Product",
                                  "name": "{{ $produit->nom }}",
  "image": "{{ $produit->FirstImage() }}",
  "description": "{{ Str::limit($produit->description ?? $produit->nom, 160) }}",
  "brand": {
    "@type": "Brand",
    "name": "{{ $produit->brand->brandname ?? 'Korean Glam' }}"
  },
  "offers": {
    "@type": "Offer",
    "url": "{{ route('produit', ['id' => $produit->id, 'slug' => Str::slug($produit->nom)]) }}",
    "priceCurrency": "AED",
    "price": "{{ $produit->getPrice() }}",
    "availability": "https://schema.org/InStock"
  }
}
                            </script>

                            <div class="col-sm-3">
                                <div class="card-produit m-2">
                                    <a  title="View details and buy {{ $item->produit->nom }}"
                                        href="{{ route('produit', ['id' => $item->produit->id, 'slug' => Str::slug($item->produit->nom)]) }}">
                                        <div class="card-produit-img">
                                            @if ($item->produit->exclusive)
                                                <span class="badge-exclusive">
                                                <i class="ri-vip-crown-fill"></i> Exclusive
                                            </span>
                                            @endif
                                            @if ($item->produit->inPromotion())
                                                <div class="badge-promotion">
                                                    - {{ $item->produit->inPromotion()->pourcentage }} %
                                                </div>
                                            @endif
                                            <img
                                                src="{{ $item->produit->FirstImage() }}" loading="lazy"
                                                alt="Buy {{ $item->produit->nom }} – Korean Skincare Tunisia"
                                                title="{{ $item->produit->nom }} – Korean Skincare"/>
                                            <button data-id="{{ $produit->id }}" type="button" aria-label="add {{ $produit->nom }} to favoris"
                                                    class="btn-add-to-favoris btn-ajouter-favoris">
                                                <i class="fa-solid fa-heart"></i>
                                            </button>
                                        </div>
                                        <p class="product-title" title="{{ $item->produit->nom }}">{{ $item->produit->nom }}</p>

                                    </a>
                                    <b>
                                        {{ $item->produit->getPrice() }} <sup>AED</sup>
                                    </b>
                                    <label for="qte-produit-{{ $produit->id }}" class="sr-only">Quantity</label>

                                    <input type="number" value="1" class="form-control mt-2"
                                           id="qte-produit-{{ $item->produit->id }}" name="quantity" max="10">
                                    <button type="button" class="btn btn-primary-color w-100 mt-2 btn-add-to-cart"
                                            aria-label="Add {{ $item->produit->nom }} to cart"
                                            data-id="{{ $item->produit->id }}">
                                        Add to cart
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <br><br><br>

        <div class="container">
            <div class="text-center mt-5 mb-3">
                <h2>Our Trusted Korean Beauty Brands</h2>
            </div>
            <div class="brands-slide-homer-home">
                <div class="slide-homer-track-home">
                    @foreach ($brands as $brand)
                        <script type="application/ld+json">
                            {
                              "@context": "https://schema.org",
                              "@type": "Brand",
                              "name": "{{ $brand->brandname }}",
  "logo": "{{ Storage::url($brand->photourl) }}",
  "url": "{{ route('shop', ['idSelectedBrand' => $brand->id]) }}"
}
                        </script>
                        <div class="slide-home">
                            <a href="{{ route('shop', ['idSelectedBrand' => $brand->id]) }}"  title="View {{ $brand->brandname }} products">
                                <img src="{{ Storage::url($brand->photourl) }}"  loading="lazy" alt="Official {{ $brand->brandname }} Korean Skincare Brand in Tunisia"
                                     title="Shop {{ $brand->brandname }} products in Tunisia">
                            </a>
                        </div>
                    @endforeach
                    <!-- Clones pour l'effet infini -->
                    @foreach ($brands as $brand)
                            <script type="application/ld+json">
                                {
                                  "@context": "https://schema.org",
                                  "@type": "Brand",
                                  "name": "{{ $brand->brandname }}",
  "logo": "{{ Storage::url($brand->photourl) }}",
  "url": "{{ route('shop', ['idSelectedBrand' => $brand->id]) }}"
}
                            </script>
                        <div class="slide-home">
                            <a href="{{ route('shop', ['idSelectedBrand' => $brand->id]) }}"  title="View {{ $brand->brandname }} products">
                                <img src="{{ Storage::url($brand->photourl) }}" loading="lazy" alt="Official {{ $brand->brandname }} Korean Skincare Brand in Tunisia"
                                     title="Shop {{ $brand->brandname }} products in Tunisia">
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        @if($blogs->count())
            <section class="container mt-5 mb-5">

                <div class="text-center mb-4">
                    <h2>Korean Skincare in Tunisia: Tips & Trends</h2>
                    <p class="text-muted">
                        Explore the best <strong>Korean skincare products in Tunisia</strong>, discover routines, tips, and secrets for glowing, healthy skin.
                    </p>
                </div>


                <div class="row">
                    @foreach($blogs as $blog)
                        <div class="col-md-4 mb-4">

                            <article class="card h-100 shadow-sm">

                                <a href="{{ route('blogs.show', $blog->id) }}">
                                    <img
                                        src="{{ $blog->photo ?? asset('images/blog-placeholder.jpg') }}"
                                        class="card-img-top"
                                        alt="{{ $blog->titre }} - Korean Beauty Blog"
                                        loading="lazy">
                                </a>

                                <div class="card-body d-flex flex-column">

                                    <small class="text-muted mb-2">
                                        {{ $blog->date ? $blog->date->format('d M Y') : '' }}
                                    </small>

                                    <h5 class="card-title">
                                        <a href="{{ route('blogs.show', $blog->id) }}" class="text-dark">
                                            {{ $blog->titre }}
                                        </a>
                                    </h5>

                                    <p class="card-text text-muted">
                                        {{ Str::limit(strip_tags($blog->description), 120) }}
                                    </p>

                                    <a href="{{ route('blogs.show', $blog->id) }}"
                                       class="mt-auto btn btn-outline-primary btn-sm">
                                        Read more
                                    </a>

                                </div>
                            </article>

                            {{-- ✅ Schema.org BlogPosting --}}
                            <script type="application/ld+json">
                {
                  "@context": "https://schema.org",
                  "@type": "BlogPosting",
                  "headline": "{{ $blog->titre }}",
                  "image": "{{ $blog->photo }}",
                  "datePublished": "{{ optional($blog->date)->toDateString() }}",
                  "author": {
                    "@type": "Organization",
                    "name": "Korean Glam"
                  }
                }
                </script>

                        </div>
                    @endforeach
                </div>

                <div class="text-center mt-4">
                    <a href="{{ route('front.blogs.index') }}" class="btn btn-primary-color w-25">
                        View All Blog Posts
                    </a>
                </div>

            </section>
        @endif

        <div id="insta-feed" class="insta-grid"></div>

    </div>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const banner = document.getElementById("bannerCarousel");
            const items = banner.querySelectorAll(".carousel-item");
            const progress = banner.querySelector(".progress");

            let index = 0;
            const duration = 5000;

            function showSlide(i) {
                items.forEach(el => el.classList.remove("active"));
                items[i].classList.add("active");

                progress.style.transition = "none";
                progress.style.width = "0%";

                setTimeout(() => {
                    progress.style.transition = `width ${duration}ms linear`;
                    progress.style.width = "100%";
                }, 50);
            }

            function nextSlide() {
                index = (index + 1) % items.length;
                showSlide(index);
            }

            showSlide(index);
            setInterval(nextSlide, duration);
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const carousel = document.getElementById("produitsCarousel2");
            const carouselItems = carousel.querySelectorAll(".carousel-item");
            let currentIndex = 0;

            function showNextSlide() {
                carouselItems[currentIndex].classList.remove("active");
                currentIndex = (currentIndex + 1) % carouselItems.length;
                carouselItems[currentIndex].classList.add("active");
            }

            // Remplacez cette partie du code par l'événement de défilement de la flèche.
            document.querySelector(".carousel-control-next").addEventListener("click", function () {
                showNextSlide();
            });

            document.querySelector(".carousel-control-prev").addEventListener("click", function () {
                carouselItems[currentIndex].classList.remove("active");
                currentIndex = (currentIndex - 1 + carouselItems.length) % carouselItems.length;
                carouselItems[currentIndex].classList.add("active");
            });
        });

    </script>

    @endsection

