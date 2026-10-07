@section('titre', 'Ajouter un produit')
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
                                <li class="breadcrumb-item active">Ajouter un produit</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <!-- end page title -->

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="header-title">
                                Formulaire d'ajout d'article
                            </h5>
                        </div>
                        <div class="card-body">
                            @livewire('Produits.AddProduit', ['produit'=> null] )
                        </div> <!-- end card body-->
                    </div> <!-- end card -->
                </div><!-- end col-->
            </div> <!-- end row-->
        </div> <!-- container -->
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/@yaireo/tagify"></script>
    <script> document.addEventListener('DOMContentLoaded', () => {
            console.log('✅ DOM prêt, attente de Livewire...');
            let isInitializing = false;

            function waitForLivewire() {
                if (window.Livewire) {
                    console.log('🔥 Livewire détecté → initialisation Tagify');
                    initTagifySousSousCat();
                    Livewire.hook('morph.updated', () => {
                       // console.log('🔁 DOM Livewire mis à jour — re-init Tagify');
                        initTagifySousSousCat(true);
                    });
                } else {
                    setTimeout(waitForLivewire, 200);
                }
            }

            waitForLivewire();

            function initTagifySousSousCat(force = false) {
                if (isInitializing) return;
                isInitializing = true;
                const input = document.querySelector('#sousSousCategorieTagify');
                if (!input) {
                    console.warn('⛔ Aucun champ Tagify trouvé.');
                    isInitializing = false;
                    return;
                } // 🧼 Supprimer l'ancienne instance proprement
                if (input._tagify) {
                    console.log('🗑️ Ancien Tagify détruit.');
                    try {
                        input._tagify.destroy();
                    } catch (e) {
                        console.warn('Erreur destruction Tagify:', e);
                    }
                    delete input._tagify;
                } // ⏳ Petit délai pour éviter la double init
                setTimeout(() => {
                    console.log('🎯 Création nouvelle instance Tagify');
                    const tagify = new Tagify(input, {
                        enforceWhitelist: true,
                        whitelist: [],
                        dropdown: {maxItems: 10, enabled:1, closeOnSelect: false}
                    }); // 🔍 Recherche côté Livewire
                    tagify.on('input', e => {
                        const value = e.detail.value.trim();
                        if (value.length > 0) {
                            console.log('🚀 Envoi à Livewire → searchSousSousCategories', {query: value});
                            Livewire.dispatch('searchSousSousCategories', {query: value});
                        }
                    }); // 📦 Réception correcte des suggestions
                    Livewire.on('updateTagifySuggestions', results => {
                        console.log('📬 Résultats reçus (raw):', results);
                        let list = Array.isArray(results)
                            ? results
                            : (Array.isArray(results.results) ? results.results : []);
                        if (list.length > 0) {
                            tagify.settings.whitelist = list;
                            tagify.dropdown.show.call(tagify); // 👈 force l'affichage sans clic
                        }
                    });// 💾 Sélection
                    tagify.on('change', () => {
                        const values = tagify.value.map(v => v.id || v.value);
                        console.log('💾 Sous-sous-catégories sélectionnées :', values);
                        Livewire.dispatch('updateSousSousCategories', { data: values });
                    });

                    isInitializing = false;
                    Livewire.on('resetSousSousCategories', () => {
                        const input = document.querySelector('#sousSousCategorieTagify');
                        if (input && input._tagify) {
                            console.log('🧼 Réinitialisation de Tagify après ajout du produit');
                            input._tagify.removeAllTags();
                            input._tagify.settings.whitelist = [];
                        }
                    });



                }, 150);
            }
        });
    </script>

@endpush


