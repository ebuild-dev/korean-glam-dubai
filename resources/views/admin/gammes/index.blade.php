@section('titre', 'Liste des gammes')
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
                                    <a href="{{ route('gammes.index') }}">Gammes</a>
                                </li>
                                <li class="breadcrumb-item active">Liste</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            {{-- ✅ Livewire Composant principal --}}
            @livewire('Gammes')

        </div>
    </div>

    <!-- ✅ Modal pour modification -->
    <div class="modal fade" id="modalUpdateGamme" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="myCenterModalLabel">
                        Modifier la gamme
                    </h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @livewire('Gammes.Update')
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <script>
        function OpenModelUpdateGamme(id) {
            Livewire.dispatch('openUpdateGamme', { id: id });
            $('#modalUpdateGamme').modal('show');
        }
    </script>

@endsection

@section('header')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const table = document.getElementById('basic-datatable');
        if (!table) return;

        const tbody = table.querySelector('tbody');
        if (!tbody) return;

        new Sortable(tbody, {
            animation: 150,
            onEnd: function (event) {
                let data = Array.from(event.to.children).map((item, index) => {
                    return item.getAttribute('data-id');
                }).join(',');

                let idsArray = data.split(',');

                fetch('{{ route('gammes.reorder') }}?ids=' + idsArray.join(','), {
                    method: 'GET',
                    headers: { 'Content-Type': 'application/json' },
                })
                    .then(() => console.log('Ordre des gammes mis à jour avec succès ✅'))
                    .catch(error => console.error('Erreur de mise à jour :', error));
            }
        });
    });
</script>
