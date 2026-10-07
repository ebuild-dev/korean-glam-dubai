@section('titre', 'Gestion des coupons')
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
                                    Gestion des coupons
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
                                Gestion des coupons
                            </h5>
                            <button type="button" data-bs-toggle="modal" data-bs-target="#modal-add-coupon"
                                class="btn btn-sm btn-primary">
                                Ajouter un coupon
                            </button>
                        </div>
                    </div>
                    <hr>
                    @include('components.alert')
                    <table class="table">
                        <thead class="table-dark cusor">
                            <tr>
                                <td>ID</td>
                                <td>Code</td>
                                <td>Date de création</td>
                                <td>Date de fin</td>
                                <td>Statut</td>
                                <td>Pourcentage</td>
                                <td>Créer par</td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($coupons as $coupon)
                                <tr>
                                    <td>{{ $coupon->id }}</td>
                                    <td>
                                       <span class="cusor" onclick="copy('{{ $coupon->code }}')">
                                        {{ $coupon->code }}
                                        <i class='bx bx-copy-alt' ></i>
                                       </span>
                                    </td>
                                    <td>{{ $coupon->created_at->format('d/m/Y H:m') }}</td>
                                    <td>{{ $coupon->valid_until }}</td>
                                    <td>
                                        @if ($coupon->estActif())
                                            <span class="badge bg-success">Actif</span>
                                        @else
                                            <span class="badge bg-danger">Expiré</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">
                                            <i class='bx bxs-coupon'></i>
                                            {{ $coupon->pourcentage }}%
                                        </span>
                                    </td>
                                    <td>
                                        @if ($coupon->Auteur)
                                            <span class="badge bg-dark">
                                                <i class='bx bx-user' ></i>
                                                {{ $coupon->Auteur->nom }}
                                            </span>
                                        @endif
                                        <x-UpdateCoupon :id="$coupon->id"></x-UpdateCoupon>
                                        <x-DeleteCoupon :id="$coupon->id"></x-DeleteCoupon>
                                    </td>
                                    <td class="text-end">
                                        <button type="button" 
                                        data-bs-toggle="modal" data-bs-target="#modal-update-coupon-{{ $coupon->id }}"
                                            class="btn btn-sm btn-dark">
                                            <i class='bx bxs-edit'></i>
                                        </button>
                                        <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#modal-delete-coupon-{{ $coupon->id }}">
                                            <i class='bx bx-trash'></i>
                                        </button>
                                    </td>
                                </tr>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8">
                                        <div class="text-center p-3">
                                            Aucun coupon trouvé.
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
                        Ajouter un coupon
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
                    <form action="{{ route('coupons.store') }}" method="post">
                        @csrf
                        <div class="mb-3">
                            <label for="code">Code du coupon *</label>
                            <input type="text" class="form-control" placeholder="XXXXXX" id="code" name="code"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="pourcentage">Valeur du coupon ( Pourcentage %) *</label>
                            <input type="number" class="form-control" id="pourcentage" name="pourcentage" required>
                        </div>
                        <div class="mb-3">
                            <label for="valid_until">Date d'expiration *</label>
                            <input type="date" class="form-control" id="valid_until" name="valid_until" required>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary">Ajouter</button>
                        </div>
                    </form>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    

    <script>
        function copy(text) {
            var input = document.createElement('textarea');
            input.value = text;
            document.body.appendChild(input);
            input.select();
            document.execCommand('copy');
            document.body.removeChild(input);
            alert('Code du coupon copié!');
        }
    </script>

@endsection
