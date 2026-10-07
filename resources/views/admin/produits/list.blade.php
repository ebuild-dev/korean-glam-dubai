@section('titre', 'Liste des produits')
@extends('admin.fixe')

@section('body')
    <!--page-content-wrapper-->
    <div class="page-content-wrapper">
        <div class="page-content">

            <!-- start page title -->
            <div class="row mb-3">
                <div class="col-12">
                    <div class="page-title-box">
                        <div class="page-title-right">
                            <ol class="breadcrumb m-0">
                                <li class="breadcrumb-item">
                                    <a href="javascript: void(0);">{{ config('app.name') }}</a>
                                </li>
                                <li class="breadcrumb-item">
                                    <a href="{{ route('produits') }}">Produits</a>
                                </li>
                                <li class="breadcrumb-item active">Liste</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->


            <div class="card radius-15 header-option-produits">
                <div class="card-body text-center">
                    <br>
                    <button class="btn btn-warning btn-sm  px-5" onclick="url('{{ route('promotions') }}')">
                        <i class="ri-discount-percent-line"></i>
                        Promotions
                    </button>
                    @can('gestion_stock')
                        <button class="btn btn-dark btn-sm  px-5" data-bs-toggle="modal" data-bs-target="#modal-add-stock">
                            <i class="ri-database-2-fill"></i>
                            Ajouter du stock
                        </button>
                    @endcan
                    @can('product_add')
                        <button class="btn btn-primary btn-sm  px-5" onclick="url('{{ route('produit.add') }}')">
                            <i class="ri-add-line"></i>
                            Ajouter un produit
                        </button>
                    @endcan
                </div>
            </div>
            <div class="card radius-15">
                <div class="card-body">
                    <div class="card-title">
                        <h5 class="mb-0 my-auto">
                            Liste des produits
                        </h5>
                    </div>
                    <br>
                    @livewire('Produits.ListProduit')
                </div>
            </div>
        </div>
    </div>


    @can('gestion_stock')
        <!-- Center modal content -->
        <div class="modal fade" id="modal-add-stock" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="myCenterModalLabel">
                            Ajout du stock
                        </h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        @livewire('Produits.AddStock')
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div><!-- /.modal -->
    @endcan
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const tbody = document.getElementById('produits-sortable');
        if (!tbody) return;

        new Sortable(tbody, {
            animation: 150,
            handle: 'tr',
            draggable: 'tr',
            onEnd: function () {
                const idsArray = Array.from(tbody.querySelectorAll('tr[data-id]'))
                    .map(tr => tr.getAttribute('data-id'));

                fetch("{{ route('produits.reorder') }}", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                    body: JSON.stringify({ ids: idsArray })
                })
                    .then(r => r.json())
                    .then(res => console.log('Ordre mis à jour', res))
                    .catch(err => console.error('Erreur reorder:', err));
            }
        });
    });
</script>
<script>
    function openEdit(id) {
        let page = new URLSearchParams(window.location.search).get('page') || 1;

        window.location.href = `/admin/produit/${id}/update?page=${page}`;
    }
</script>
