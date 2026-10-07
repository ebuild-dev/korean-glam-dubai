<section class="cart">

    <div class="container">

        <div class="row">

            <div class="col-lg-8 mb-3">

                <div class="card border-0">

                    <div class="card-body p-0">
                        <table class="table cart-table m-md-0">
                            <thead>
                                <tr>
                                    <th class="d-none d-sm-table-cell bg-light">Product</th>
                                    <th class="d-none d-sm-table-cell bg-light">Price</th>
                                    <th class="d-none d-md-table-cell bg-light">Quantity</th>
                                    <th class="d-none d-md-table-cell bg-light">Total</th>
                                    <th class="d-none d-md-table-cell bg-light">Remove</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($panier as $item)
                                    <tr>
                                        <td class="px-0 px-sm-2">
                                            <div class="product-detail">
                                                <img class="pr-img" src="{{ $item['photo'] }}"
                                                    alt="{{ $item['nom'] }}">
                                                <div class="details">
                                                    <a class="cart_title"
                                                        href="{{ route('produit', ['id' => $item['id_produit'], 'slug' => Str::slug($item['nom'])]) }}">
                                                        <p class="text-dark">
                                                            {{ $item['nom'] }}
                                                        </p>

                                                    </a>
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <span class="size gap-2 d-flex d-sm-none">
                                                            {{ $item['prix'] }} <x-devise></x-devise>
                                                        </span>
                                                        <div class="item-quantity d-lg-none">

                                                            <a class="btn border-0 minus"
                                                                wire:click="update({{ $item['id'] }},{{ $item['quantite'] - 1 }})"
                                                                href="#"><i class="fa-regular fa-minus"></i></a>
                                                            <input type="text" value="{{ $item['quantite'] }}"
                                                                readonly>
                                                            <a class="btn border-0 plus"
                                                                wire:click="update({{ $item['id'] }},{{ $item['quantite'] + 1 }})"
                                                                href="#"><i class="fa-regular fa-plus"></i></a>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="price d-none d-sm-table-cell">
                                            {{ $item['total'] }} <x-devise></x-devise>
                                        </td>
                                        <td class="d-none d-md-table-cell">
                                            <div class="item-quantity">

                                                <a class="btn border-0 minus"
                                                    wire:click="update({{ $item['id'] }},{{ $item['quantite'] - 1 }})"
                                                    href="#"><i class="fa-regular fa-minus"></i></a>

                                                <input type="text" value="{{ $item['quantite'] }}" readonly>

                                                <a class="btn border-0 plus" href="#"
                                                    wire:click="update({{ $item['id'] }},{{ $item['quantite'] + 1 }})"><i
                                                        class="fa-regular fa-plus"></i></a>

                                            </div>
                                        </td>
                                        <td class="total d-none d-md-table-cell">
                                            {{ $item['total'] }} <x-devise></x-devise>
                                        </td>
                                        <td class="total d-none d-md-table-cell">
                                            <a class="text-primary" tooltip="Remove"
                                                wire:click="delete( {{ $item['id'] }})"><i
                                                    class="fa-light fa-trash text-danger"></i></a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5">
                                            <div class="text-center p-3">
                                                Your cart is empty!
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 mb-3">
                <div class="card border-0 mb-3 rounded-0 bg-light">

                    <div class="card-body">

                        <p class="fs-5 text-dark fw-600 border-bottom">Summary</p>
                        @if ($panier)
                            <ul class="list-group">

                                <li
                                    class="list-group-item d-flex bg-light px-0 border-0 align-items-center justify-content-between">

                                    <span>Subtotal</span>

                                    <span>{{ $montant }} <x-devise></x-devise></span>

                                </li>

                                <li
                                    class="list-group-item d-flex bg-light px-0 border-0 align-items-center justify-content-between">

                                    <span>Shipping</span>

                                    <span> {{ $frais ?? '0.000' }} <x-devise></x-devise></span>
                                </li>
                                <li
                                    class="list-group-item d-flex bg-light px-0 border-0 border-top-dashed align-items-center justify-content-between">
                                    <strong class="text-success fw-semibold">
                                        Grand total
                                    </strong>
                                    <strong class="text-success fw-semibold">{{ $montant_final }}
                                        <x-devise></x-devise></strong>
                                </li>
                            </ul>
                            <a type="button" class="btn btn-fashion w-100 mt-3" href="{{ route('checkout') }}">
                                Proceed to checkout
                            </a>
                        @endif
                        <a class="btn btn-primary rounded-0 py-2 w-100 mt-3" href="{{ route('shop') }}">
                            <span>Back to shop</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>

    </div>
