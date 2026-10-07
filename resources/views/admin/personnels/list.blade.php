@section('titre', 'Liste des personnels')
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
                                    <a href="{{ route('personnels') }}">personnels</a>
                                </li>
                                <li class="breadcrumb-item active">Liste</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->


            <div class="card radius-15">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div class="card-title">
                            <h5 class="mb-0 my-auto">
                                Liste des personnels
                            </h5>
                        </div>
                        <div>
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                data-bs-target="#add">
                                <i class="ri-user-add-line"></i>
                                Ajouter un personnel
                            </button>
                        </div>
                    </div>
                    <hr />
                    @include('components.alert')

                    <div class="table-responsive-sm">
                        <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
                            <thead class="table-dark cusor">
                                <tr>
                                    <th>Photo</th>
                                    <th>Nom</th>
                                    <th>Prénom</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>création</th>
                                    <th style="text-align: right;">
                                        <span wire:loading>
                                            <img src="https://i.gifer.com/ZKZg.gif" width="20" height="20"
                                                class="rounded shadow" alt="">
                                        </span>
                                    </th>
                                </tr>
                            </thead>


                            <tbody>
                                @forelse ($personnels as $personnel)
                                    <tr>
                                        <td>
                                            <img src="{{ $personnel->avatar() }}" width="40 " height="40 "
                                                class="rounded " alt="">
                                        </td>
                                        <td>
                                            {{ $personnel->nom }}
                                        </td>
                                        <td>
                                            {{ $personnel->prenom }}
                                        </td>
                                        <td>{{ $personnel->email }} </td>
                                        <td>{{ $personnel->phone }}</td>
                                        <td>{{ $personnel->created_at }} </td>
                                        <td style="text-align: right;">
                                            <div class="btn-group">
                                                <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#personnel-{{ $personnel->id }}">
                                                    <i class="ri-settings-5-line"></i> Permissions
                                                </button>
                                                <button class="btn btn-sm btn-danger"
                                                    onclick="toggle_confirmation({{ $personnel->id }})">
                                                    <i class="ri-delete-bin-6-line"></i>
                                                </button>
                                            </div>
                                            <button class="btn btn-sm btn-success d-none" type="button"
                                                id="confirmBtn{{ $personnel->id }}" onclick="url('/admin/personnel/delete/{{ $personnel->id }}')" >
                                                <i class="bi bi-check-circle"></i>
                                                <span class="hide-tablete">
                                                    Confirmer
                                                </span>
                                            </button>


                                            @include('admin.personnels.modal-permissions', ['personnel'=>$personnel])
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center">Aucun personnel trouvé</td>
                                    </tr>
                                @endforelse

                            </tbody>


                        </table>
                    </div>
                   

                </div>
            </div>
        </div>
    </div>



    <!-- Center modal content -->
    <div class="modal fade" id="add" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="myCenterModalLabel">
                        Ajouter un personnel.
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                @livewire('AddPersonnels')
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->


@endsection
