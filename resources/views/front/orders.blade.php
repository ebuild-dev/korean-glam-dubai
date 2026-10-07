@extends('front.fixe')
@section('titre', 'My orders')
@section('body')

    <br><br>
    <section >
        <div class="container">
            <h4 class="breadcrumb-title mb-2">Orders</h4>
        </div>
    </section>
    <section>
        <div class="container py-4">
            <div class="row">
                <div class="col-lg-3 col-xxl-3 mb-4 mb-lg-0 ">
                    <x-ProfileOption></x-ProfileOption>
                </div>
                <div class="col-lg-9 col-xxl-9">
                    <div class="border px-3 rounded table-box">
                        <div class='d-md-flex align-items-center py-4 table-top-box'>
                            <a href="orders?type=processing" class="w-100">
                                <button type='button'
                                    class="btn border rounded p-3 w-100 border-warning text-warning bg-white">
                                    <span class='warning-icon d-flex d-flex align-items-center'>
                                        <i
                                            class="fa-regular fa-hourglass  d-flex justify-content-center align-items-center"></i>
                                        <div class='px-3'>
                                            <p class='m-0 p-0'>Preparing your order</p>
                                            <p class='text-start-pro m-0 p-0 text-warning'>
                                                {{ $commandes->where('statut', 'créé')->count() }}
                                            </p>
                                        </div>
                                    </span>
                                </button>
                            </a>
                            <a href="orders?type=completed" class="w-100 mx-3 delivered-box">
                                <button type='button'
                                    class="btn border rounded p-3 w-100 border-success text-success bg-white my-2 my-md-">
                                    <span class='success-icon d-flex d-flex align-items-center'>
                                        <i
                                            class='fa-regular fa-circle-check d-flex justify-content-center align-items-center'>
                                        </i>
                                        <div class='px-3'>
                                            <p class='m-0 p-0'>Delivered</p>
                                            <p class='text-start-pro m-0 p-0 text-success'>
                                                {{ $commandes->where('statut', 'livré')->count() }}
                                            </p>
                                        </div>
                                    </span>
                                </button>
                            </a>
                            <a href="orders?type=rejected" class="w-100 rejected-box">
                                <button type='button'
                                    class="btn border rounded p-3 w-100 border-danger text-danger bg-white">
                                    <span class='danger-icon d-flex d-flex align-items-center'>
                                        <i class='fa-solid fa-xmark d-flex justify-content-center align-items-center'></i>
                                        <div class='px-3'>
                                            <p class='m-0 p-0'>Rejected</p>
                                            <p class='text-start-pro m-0 p-0 text-danger'>
                                                {{ $commandes->where('statut', 'retournée')->count() }}
                                            </p>
                                        </div>
                                    </span>
                                </button>
                            </a>
                        </div>
                        <x-FrontAlert></x-FrontAlert>
                        <div class="table-responsive py-4">
                            <table class="table table-striped table-bordered py-3 zero-configuration w-100">
                                <thead>
                                    <tr class="text-uppercase fs-7">
                                        <td>#</td>
                                        <td>Order number</td>
                                        <td>Date</td>
                                        <td>Grand total</td>
                                        <td>Payment method</td>
                                        <td>Statut</td>
                                        <td></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($commandes as $commande)
                                        @php
                                            $show_facture = true;
                                        @endphp
                                        <tr>
                                            <td></td>
                                            <td>
                                                {{ $commande->id }}
                                            </td>
                                            <td>
                                                {{ $commande->created_at }}
                                            </td>
                                            <td>
                                                {{ $commande->montant() }} <x-devise></x-devise>
                                            </td>
                                            <td>
                                                @if ($commande->mode_paiement == 'online')
                                                    En ligne
                                                    @if ($commande->paymentRef)
                                                        <img width="15" height="15"
                                                            src="https://img.icons8.com/material-sharp/15/40C057/approval.png"
                                                            alt="approval" />
                                                    @else
                                                        <img width="15" height="15"
                                                            src="https://img.icons8.com/fluency/24/delete-sign.png"
                                                            alt="delete-sign" />
                                                        @php
                                                            $show_facture = false;
                                                        @endphp
                                                    @endif
                                                @else
                                                    Cash on delivery
                                                @endif
                                            </td>
                                            <td>
                                                {{ ['créé' => 'Pending', 'confirmée' => 'Confirmed', 'confirmé' => 'Confirmed', 'en cours' => 'In progress', 'expédiée' => 'Shipped', 'expédié' => 'Shipped', 'livré' => 'Delivered', 'livrée' => 'Delivered', 'retournée' => 'Returned', 'annulée' => 'Cancelled', 'annulé' => 'Cancelled', 'rejetée' => 'Rejected'][$commande->statut] ?? $commande->statut }}
                                            </td>
                                            <td>
                                                @if ($show_facture)
                                                    <a href="{{ route('print_commande', ['id' => $commande->id]) }}"
                                                        class="btn btn-dark btn-sm text-white">
                                                        Facture
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="7">
                                                <div class="text-center p-3">
                                                    No orders found.
                                                </div>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


@endsection
