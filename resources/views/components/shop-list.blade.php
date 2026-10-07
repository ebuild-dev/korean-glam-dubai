<div>
    <div class="row">
        @forelse ($produits->where('masquer_produit', false) as $produit)
            <div class="col-sm-3 col-6">
                <div class="card-produit mb-2">
                    <a  title="View details and buy {{ $produit->nom }}"
                        href="{{ route('produit', ['id' => $produit->id, 'slug' => Str::slug($produit->nom)]) }}">
                        <div class="card-produit-img">
                            @if ($produit->exclusive)
                                <span class="badge-exclusive">
            <i class="ri-vip-crown-fill"></i> Exclusive
        </span>
                            @endif
                            @if ($produit->inPromotion())
                                <div class="badge-promotion"> - {{ $produit->inPromotion()->pourcentage }} % </div>
                            @endif
                            <img src="{{ $produit->FirstImage() }}"   alt="Buy {{ $produit->nom }} – Korean Skincare Tunisia"
                                 title="{{ $produit->nom }} – Korean Skincare" loading="lazy" />
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
                    <input type="number" value="1" class="form-control mt-2" id="qte-produit-{{ $produit->id }}"
                        name="quantity" max="10">

                    @if(!$produit->pris_order)
                        <button aria-label="Add {{ $produit->nom }} to cart" type="button" class="btn btn-primary-color w-100 mt-2 btn-add-to-cart"
                        data-id="{{ $produit->id }}">
                        Add to cart
                    </button>
                    @else
                        <li class="btn-preorder btn btn-primary-color w-100 mt-2" data-id="{{ $produit->id }}">
                            <i class="fa-solid fa-clock"></i> Make as Pre-order
                        </li>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-sm-12 col-12 text-center p-3">
                <h5>There are no items within that specification.</h5>
            </div>
        @endforelse
        <div>
            {{ $produits->onEachSide(2)->links('pagination::bootstrap-4') }}
        </div>

    </div>
</div>
