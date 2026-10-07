@section('titre', 'Gestion des bannières')
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
                                <li class="breadcrumb-item active">
                                    Gestion des bannières
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->


            <div class="card radius-15">
                <div class="card-body ">
                    <div class="card-title">
                        <div class="d-flex justify-content-between">
                            <h5 class="mb-0 my-auto">
                                <i class='bx bxs-coupon'></i>
                                Gestion des bannières
                            </h5>
                            <button type="button" data-bs-toggle="modal" data-bs-target="#modal-add-coupon"
                                class="btn btn-sm btn-primary">
                                Ajouter une bannière
                            </button>
                        </div>
                    </div>
                    <hr>
                    @include('components.alert')
                    <div class="alert alert-info">
                        <i class='bx bxs-hand' ></i>
                        Pour gérer les bannières, vous pouvez utiliser le tableau ci-dessous et modifier les informations de chaque bannière et deplacer l'ordre.
                    </div>
                    <table class="table sortable-list" id="basic-datatable">
                        <thead class="table-dark cusor">
                            <tr>
                                <td>Image pc </td>
                                <td>Image mobile </td>
                                <td>Date de création</td>
                                <td>Bouton</td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($banners as $banner)
                                <tr data-id="{{ $banner->id }}" class="cusor">
                                    <td>
                                        <a href="javascript:;">
                                            <img src="{{ Storage::url($banner->image) }}" width="30" height="30"
                                                class="rounded-circle shadow" alt="">
                                        </a>
                                    </td>
                                    <td>
                                        <a href="javascript:;">
                                            <img src="{{ Storage::url($banner->image_mobile) }}" width="30" height="30"
                                                class="rounded-circle shadow" alt="">
                                        </a>
                                    </td>
                                    <td>{{ $banner->created_at->format('d/m/Y H:m') }}</td>
                                    <td>
                                        @if ($banner->show_btn)
                                            <b class="text-success">Visible</b>
                                        @else
                                            <b class="text-danger">Masqué</b>
                                        @endif
                                        <x-DeleteBanner :id="$banner->id"></x-DeleteBanner>
                                        <x-UpdateBanner :id="$banner->id"></x-UpdateBanner>
                                    </td>

                                    <td class="text-end">
                                        <div style="width: 100px !important; ">
                                            <button type="button" data-bs-toggle="modal"
                                                data-bs-target="#modal-update-banner-{{ $banner->id }}"
                                                class="btn btn-sm btn-dark">
                                                <i class='bx bxs-edit'></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal"
                                                data-bs-target="#modal-delete-banner-{{ $banner->id }}">
                                                <i class='bx bx-trash'></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5">
                                        <div class="text-center p-3">
                                            Aucune bannière trouvé.
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




    <!-- Center modal content -->
    <div class="modal fade" id="modal-add-coupon" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="myCenterModalLabel">
                        Ajouter une bannière
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('banners.store') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-3">
                            <label for="code">Photo version pc</label>
                            <input type="file" class="form-control" name="image" required>
                            @error('image')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="code">Photo version mobile</label>
                            <input type="file" class="form-control" name="image_mobile" required>
                            @error('image_mobile')
                                <div class="alert alert-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="row">
                            <div class="col">
                                <label for="code">Visibilité</label>
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" name="show_btn" checked value="true"
                                        id="flexSwitchCheckChecked">
                                    <label class="form-check-label" for="flexSwitchCheckChecked">Affichier le bouton</label>
                                </div>
                            </div>
                        </div>
                        <br>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary">Ajouter</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->




    @section('header')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
    @endsection

    <script>
        new Sortable(document.getElementById('basic-datatable').querySelector('tbody'), {
            animation: 150,
            onEnd: function(event) {
                let data = Array.from(event.to.children).map((item, index) => {
                    return item.getAttribute('data-id');
                }).join(',');

                let idsArray = data.split(',');

                fetch('{{ route('banners.reorder') }}?ids=' + idsArray.join(','), {
                        method: 'GET',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                    })
                    .then(response => {
                        console.log('Ordre mis à jour avec succès.');
                    })
                    .catch(error => {
                        console.error('Erreur lors de la mise à jour de l\'ordre : ', error);
                    });
            }
        });
    </script>

@endsection
