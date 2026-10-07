@extends('front.fixe')
@section('titre', $produit->nom)


@section('meta')
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="{{ Request::url() }}">

    <!-- SEO PRODUIT -->
    <meta name="description" content="{{ strip_tags(Str::limit($produit->description, 300)) }}">
    <meta name="keywords"
          content="{{ $produit->nom }}, korean skincare tunisia, {{ strtolower($produit->marque) }}@if($produit->categorie), {{ strtolower($produit->categorie->nom) }}@endif">

    <!-- OPEN GRAPH PRODUIT -->
    <meta property="og:title" content="{{ $produit->nom }} – Korean Skincare Tunisia">
    <meta property="og:description" content="{{ strip_tags($produit->description) }}">
    <meta property="og:url" content="{{ Request::url() }}">
    <meta property="og:image" content="{{ $produit->FirstImage() }}">
    <meta property="og:type" content="product">

    <!-- TWITTER CARD PRODUIT -->
    <meta name="twitter:title" content="{{ $produit->nom }}">
    <meta name="twitter:description" content="{{ strip_tags($produit->description) }}">
    <meta name="twitter:image" content="{{ $produit->FirstImage() }}">
@endsection
@section('schema')
    <script type="application/ld+json">
        {
          "@context": "https://schema.org/",
          "@type": "Product",
          "name": "{{ $produit->nom }}",
  "image": [
    "{{ $produit->FirstImage() }}"
  ],
  "description": "{{ strip_tags(Str::limit($produit->description, 300)) }}",
  "sku": "{{ $produit->id }}",
  "brand": {
    "@type": "Brand",
    "name": "{{ $produit->marque }}"
  },
  "offers": {
    "@type": "Offer",
    "url": "{{ Request::url() }}",
    "priceCurrency": "AED",
    "price": " {{ $produit->getPrice() }} ",
    "availability": "{{ $produit->stock > 0 ? 'https://schema.org/InStock' : 'https://schema.org/OutOfStock' }}",
    "itemCondition": "https://schema.org/NewCondition"
  }
}
    </script>
@endsection


@section('body')
    <div class="container-fluid">
        <div class="container">
            <br><br><br>
            <div class="row">
                <div class="col-md-4">
                    <div class="card-produit-img-details">
                        <img src="{{ $produit->FirstImage() }}" alt="{{ $produit->nom }}" id="MainImage">
                    </div>
                    <div class="card-produit-img-details-list">
                        @foreach (json_decode($produit->photos) ?? []  as $image)
                            <div class="card-produit-img-details-list-item">
                                <img src="{{ Storage::url($image) }}" alt="{{ $produit->nom }}"
                                     onclick="ChangeImage(this)">
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="col-md-8">
                    <h3>{{ $produit->nom }}</h3>
                    <hr>
                    <div>
                        <div class="mb-2">
                            Availability:
                            @if($produit->stock > 0)
                                <i class="fa-solid fa-square-check text-success"></i> In stock ({{ $produit->stock }})
                            @else
                                <i class="fa-solid fa-triangle-exclamation text-danger"></i> Out of stock
                            @endif
                        </div>

                        <div class="mb-2">
                            <span class="color"><b>{{ $produit->stock }}</b></span> products left in stock!
                        </div>
                        <div class="mb-2 mb-2">
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
                            {{-- 🔥 Section des points avec nouveau design --}}

                        </div>
                    </div>
                    <p class="text-muted">{{ $produit->description }}</p>
                    <!-- Remplacer la section des points existante par ce code -->
                    <div class="points-container">
                        @if(!empty($produit->points_achat) && $produit->points_achat > 0)

                            <div class="points-card purchase-points">
                                <div class="points-icon purchase-icon">
                                    <i class="fa-solid fa-coins"></i>
                                </div>
                                <div class="points-content">
                                    <div class="points-title">Points requis pour acheter ce produit</div>
                                    <div class="points-value">{{ $produit->points_achat }} <span class="points-badge">Points</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @if(!empty($produit->points_vente) && $produit->points_vente > 0)

                            <div class="points-card redeem-points">
                                <div class="points-icon redeem-icon">
                                    <i class="fa-solid fa-star"></i>
                                </div>
                                <div class="points-content">
                                    <div class="points-title"> Points earned with this purchase</div>
                                    <div class="points-value">{{ $produit->points_vente }} <span class="points-badge">Points</span>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <br>
                    <div class="d-flex align-items-center gap-2">
                        <div>
                            <b>Quantity :</b>
                        </div>
                        <div class="d-flex align-items-center qte-produit">
                            <button type="button" class="btn-qte-produit btn-qte-produit-1"
                                    onclick="DecrementQte('{{ $produit->id }}')">
                                <b>-</b>
                            </button>
                            <input type="number" class="input-qte-produit" value="1" id="qte-produit-{{ $produit->id }}"
                                   min="1">
                            <button type="button" class="btn-qte-produit btn-qte-produit-2"
                                    onclick="IncrementQte('{{ $produit->id }}')">
                                <b>+</b>
                            </button>
                        </div>
                    </div>
                    <br>
                    <ul class="ul-option-produit">
                        <li data-id="{{ $produit->id }}" class="btn-add-to-favoris">
                            <i class="fa-solid fa-heart"></i>
                        </li>
                        @if(!$produit->pris_order)
                            <li class="btn-add-to-cart" data-id="{{ $produit->id }}">
                                <i class="fa-solid fa-cart-shopping"></i> Add to cart
                            </li>
                        @else
                            <li class="btn-preorder" data-id="{{ $produit->id }}">
                                <i class="fa-solid fa-clock"></i> Make as Pre-order
                            </li>
                        @endif

                        <li>
                            <a href="javascript:void(0)" class="btn-checkout-direct" data-id="{{ $produit->id }}"
                               style="color: white !important;">
                                Checkout
                            </a>
                        </li>
                    </ul>
                    <div class="alert alert-light d-none" id="alert-write-review">
                        <b>Reviews are only available to users.</b>
                    </div>


                </div>
                <div class="pt-4">
                    <b>Customer reviews:</b>
                    <div class="text-muted" id="reviews-list">
                        @forelse($produit->comments()->where('valide', true)->get() as $comment)
                            <div class="mb-2 border-bottom pb-2">
                                <img
                                    src="{{ $comment->user->avatar ? Storage::url($comment->user->avatar) : asset('icons/default-no-profile-pic.webp') }}"
                                    alt="avatar" class="rounded-circle"
                                    style="width:40px; height:40px; object-fit:cover;">
                                <b>{{ $comment->user->nom }}</b> :
                                <p>{{ $comment->commentaire }}</p>
                                @if($comment->photo)
                                    <img src="{{ Storage::url($comment->photo) }}" alt="Comment photo"
                                         style="max-width: 150px;">
                                @endif
                            </div>
                        @empty
                            <div class="text-muted">
                                This product has no reviews.
                            </div>
                        @endforelse
                    </div>

                    @auth
                        <div class="card mt-3 p-3 shadow-sm rounded">
                            <div class="d-flex gap-2">
                                <!-- Avatar utilisateur -->
                                <img
                                    src="{{ Auth::user()->avatar ? Storage::url(Auth::user()->avatar) : asset('icons/default-no-profile-pic.webp') }}"
                                    alt="avatar" class="rounded-circle"
                                    style="width:40px; height:40px; object-fit:cover;">


                                <!-- Formulaire -->
                                <form action="{{ route('comments.store') }}" method="POST" enctype="multipart/form-data"
                                      class="flex-grow-1 d-flex align-items-center gap-2">
                                    @csrf
                                    <textarea name="commentaire" class="form-control flex-grow-1 mb-0" rows="1"
                                              placeholder="Write a comment..."
                                              oninput="this.style.height = '';this.style.height = this.scrollHeight + 'px'"
                                              required></textarea>

                                    <!-- Icon photo (Font Awesome) -->
                                    <label class="btn btn-light p-1 mb-0" style="cursor:pointer;">
                                        <i class="fa fa-image fs-5"></i>
                                        <input type="file" name="photo" class="d-none" onchange="previewImage(event)">
                                    </label>

                                    <!-- Preview photo -->
                                    <img id="preview-comment-photo" src="" class="rounded"
                                         style="max-width:40px; display:none;">

                                    <!-- Icon send (Font Awesome) -->
                                    <button type="submit" class="btn btn-primary p-1 mb-0"
                                            style="width:40px; height:40px; display:flex; align-items:center; justify-content:center; background-color: var(--primary-color) ;border-color: var(--primary-color)">
                                        <i class="fa fa-paper-plane"></i>
                                    </button>

                                    <input type="hidden" name="product_id" value="{{ $produit->id }}">
                                </form>
                            </div>
                        </div>

                        <script>
                            function previewImage(event) {
                                const output = document.getElementById('preview-comment-photo');
                                output.src = URL.createObjectURL(event.target.files[0]);
                                output.style.display = 'inline-block';
                            }
                        </script>
                    @else
                        <div class="text-muted mt-2">
                            Please <a href="{{ route('login') }}">login</a> to write a comment.
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @if(session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: "{{ session('success') }}",
                timer: 2500,
                showConfirmButton: false
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Erreur',
                text: "{{ session('error') }}",
                timer: 3500,
                showConfirmButton: true
            });
        </script>
    @endif
    <script>
        $(document).on('click', '.btn-checkout-direct', function (e) {
            e.preventDefault();

            let id_produit = $(this).data('id');

            $.ajax({
                url: '/client/ajouter_au_panier',
                type: 'POST',
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    id_produit: id_produit,
                    quantite: 1
                },
                success: function (response) {
                    if (response.statut) {
                        // Produit ajouté au panier → on redirige vers checkout
                        window.location.href = '/cart';
                    } else {
                        alert(response.message);
                    }
                },
                error: function () {
                    alert("Unable to add this item to your cart!");
                }
            });
        });

    </script>
    <style>
        .points-container {
            margin-bottom: 20px;
        }

        .points-card {
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            display: flex;
            align-items: center;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .points-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.12);
        }

        .points-icon {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            flex-shrink: 0;
            font-size: 18px;
        }

        .points-content {
            flex-grow: 1;
        }

        .points-title {
            font-weight: 600;
            font-size: 15px;
            margin-bottom: 3px;
        }

        .points-value {
            font-weight: 700;
            font-size: 18px;
        }

        .purchase-points {
            background: linear-gradient(135deg, #ee8da5, #d87a92);
            color: white;
        }

        .purchase-icon {
            background-color: rgba(255, 255, 255, 0.25);
        }

        .redeem-points {
            background: linear-gradient(135deg, #f9b4c6, var(--primary-color));
            color: white;
        }

        .redeem-icon {
            background-color: rgba(255, 255, 255, 0.25);
        }

        .points-badge {
            display: inline-block;
            padding: 4px 10px;
            border-radius: 20px;
            font-weight: 600;
            font-size: 14px;
            margin-left: 8px;
            background: rgba(255, 255, 255, 0.3);
        }

    </style>
@endsection
