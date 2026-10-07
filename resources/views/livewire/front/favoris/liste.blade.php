<div >
    <table class="table">
        <tbody>
            @forelse ($favoris as $favori)
                <tr>
                    <td>
                        <a
                            href="{{ route('produit', ['id' => $favori->produit->id, 'slug' => Str::slug($favori->produit->nom)]) }}">
                            <img src="{{ $favori->produit->FirstImage() }}" height="60" alt="" srcset="">
                        </a>
                    </td>
                    <td class="my-auto">
                        <a
                            href="{{ route('produit', ['id' => $favori->produit->id, 'slug' => Str::slug($favori->produit->nom)]) }}">
                        <b>
                            {{ Str::limit($favori->produit->nom, 40) }}
                        </b>
                        </a>
                    </td>
                    <td>
                        @if ($favori->produit->inPromotion())
                            <span class="arrow-label-wrap">
                                <span class="arrow-label bg-theme-sun">
                                    -{{ $favori->produit->inPromotion()->pourcentage }}%
                                </span>
                            </span>
                        @endif
                    </td>
                    <td>
                        @if ($favori->produit->categorie)
                            {{ $favori->produit->categorie->nom }}
                        @else
                            <i class="text-muted">
                                No category
                            </i>
                        @endif
                    </td>
                    <td>
                        @if ($favori->produit->inPromotion())
                            {{ $favori->produit->getPrice() }} <sup>AED</sup>
                            <del class="text-danger fw-500 fs-8 fw-normal">
                                {{ $favori->produit->prix }} <sup>AED</sup>
                            </del>
                        @else
                            {{ $favori->produit->prix }} <sup>AED</sup>
                        @endif
                    </td>
                    <td>
                        <a class="circle-round wishlist-btn text-danger" wire:click="delete({{ $favori->id }})">
                            <i class="fa fa-minus-circle" aria-hidden="true"></i>
                            retirer
                        </a>
                    </td>
                </tr>
            @empty
                <div class="text-center p-3 col-12">
                    <h5 class="text-muted">Your wishlist is empty</h5>
                </div>
            @endforelse

        </tbody>
    </table>

</div>
