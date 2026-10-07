@section('titre', 'Gestion des blogs')
@extends('admin.fixe')

@section('body')
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
                                Gestion des blogs
                            </h5>
                            <button type="button" data-bs-toggle="modal"
                                    class="btn btn-sm btn-primary">
                                <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary ">Créer un nouveau blog</a>

                            </button>
                        </div>
                    </div>
                    <hr>
                    @include('components.alert')

                    <table class="table sortable-list" id="basic-datatable">
                        <thead class="table-dark cusor">
                        <tr>
                            <td>Titre  </td>
                            <td>Description </td>
                            <td>Photo</td>
                            <td>Date de création</td>
                            <td></td>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($blogs as $blog)
                            <tr>
                                <td>{{ $blog->titre }}</td>
                                <td>{!!   Str::limit($blog->description, 50) !!}</td>
                                <td>
                                    @if($blog->photo)
                                        <img src="{{ $blog->photo }}" alt="{{ $blog->titre }}" width="50">
                                    @endif
                                </td>
                                <td>{{ $blog->date ? $blog->date->format('d/m/Y') : 'Non spécifiée' }}</td>
                                <td>
                                    <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="btn btn-warning btn-sm">Éditer</a>

                                    <button
                                        type="button"
                                        class="btn btn-danger btn-sm"
                                        data-bs-toggle="modal"
                                        data-bs-target="#deleteBlogModal{{ $blog->id }}">
                                        Supprimer
                                    </button>

                                </td>
                            </tr>
                            <div class="modal fade" id="deleteBlogModal{{ $blog->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">

                                        <div class="modal-header">
                                            <h5 class="modal-title">Confirmation de suppression</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>

                                        <div class="modal-body">
                                            <p>
                                                Voulez-vous vraiment supprimer le blog
                                                <strong>{{ $blog->titre }}</strong> ?
                                            </p>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                Annuler
                                            </button>

                                            <form action="{{ route('admin.blogs.destroy', $blog->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-danger">
                                                    Oui, supprimer
                                                </button>
                                            </form>
                                        </div>

                                    </div>
                                </div>
                            </div>

                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


@endsection
