<div class="row justify-content-between">
    <div class="col-lg-6">

        <div id="carouseltest" class="carousel slide">
            <div class="carousel-inner">
                <img src="{{ $produit->FirstImage() }}" alt="" class="w-100" srcset="">
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="product-content px-md-0">
            <div class="card border-0 h-600">
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between">
                        <h2 class="text-dark product-title product-line">
                            {{ $produit->nom }}
                        </h2>
                    </div>
                    <!-- rating star section Start -->
                    <p class="pt-2">
                        @if ($produit->inPromotion())
                            <span class="product-price text-truncate">
                                {{ $produit->getPrice() }} <x-devise></x-devise>
                            </span>
                            <del class="text-danger fw-500 fs-8 fw-normal">
                                <del class="text-danger fs-7 product-original-price">
                                    {{ $produit->getPrixVente() }} <x-devise></x-devise>
                                </del>
                            </del>
                        @else
                            <span class="product-price text-truncate">
                                {{ $produit->getPrixVente() }} <x-devise></x-devise>
                            </span>
                        @endif



                    </p>

                    <div class="row mt-2">
                        <p class="text-primary fw-semibold mb-1">
                            Product details
                        </p>
                        <div class="col-lg-12 line-5">
                            {{ $produit->description }}
                        </div>
                    </div>
                    @if ($produit->categorie)
                    <div class="row mt-2">
                        <p class="text-primary fw-semibold mb-1">
                            Category:
                        </p>
                        <div class="col-lg-12 line-5">
                            {{ $produit->categorie->nom }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            <div class="card-footer">
                <div class="row align-items-center justify-content-between">
                    <div class="col-lg-6 col-md-6 col-12 mb-3 mb-md-0">
                        <a href="javascript:void(0)" onclick="calladdtocart({{ $produit->id }})"
                            class="btn btn-lg btn-fashion w-100"
                            id="modal-addtocart">Add to cart</a>
                    </div>
                    <div class="col-lg-6 col-md-6 col-12">
                        <a href="{{ route('produit', ['id' => $produit->id, 'slug' => Str::slug($produit->nom)]) }}"
                            class="btn btn-lg btn-primary rounded-0 w-100 fs-6"
                            id="full_view">View details</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>