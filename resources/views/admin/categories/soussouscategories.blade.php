@section('titre', 'Liste des sous-sous-catégories')
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
                                    <a href="{{ route('soussouscategories') }}">Sous-sous-catégories</a>
                                </li>
                                <li class="breadcrumb-item active">Liste</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->


            @livewire('Categories.SousSousCategories')

        </div>
    </div>


    <!-- Center modal content -->
    <div class="modal fade" id="modalUpdateSousSousCategorie" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="myCenterModalLabel">
                        Modifier la sous-sous-catégorie
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @livewire('Categories.UpdateSousSousCategorie')
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->


    <script>
        function OpenModelUpdateSousSousCategorie(id) {
            Livewire.dispatch('openUpdateSousSousCategorie', {id : id});
            $('#modalUpdateSousSousCategorie').modal('show');
        }
    </script>

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

                fetch('{{ route('soussouscategories.reorder') }}?ids=' + idsArray.join(','), {
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
