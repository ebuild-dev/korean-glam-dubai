@section('titre', $produit->nom )
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
                                    {{ $produit->nom }}
                                </li>
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
                                modification du produit
                            </h5>
                        </div>
                        <div class="card-body">
                            @livewire('Produits.AddProduit', [
                            'produit' => $produit,
                            'page' => request()->get('page')
                            ])
                        </div> <!-- end card body-->



                    </div> <!-- end card -->
                </div><!-- end col-->
            </div> <!-- end row-->
        </div> <!-- container -->
    </div>
@endsection
@push('scripts')


    <script>
        (function initTagifyAfterLivewireReady() {
            if (typeof window.Livewire === 'undefined') {
                console.log('⏳ En attente de Livewire...');
                return setTimeout(initTagifyAfterLivewireReady, 300);
            }

         //   console.log('🔥 Livewire détecté');

            const input = document.querySelector('#sousSousCategorieTagify');
            if (!input) {
                console.warn('⚠️ Input Tagify introuvable');
                return;
            }

            let tagify = input._tagify;
            let currentTags = []; // 🧠 garde les tags visibles même après re-render

            // 🔹 Initialisation Tagify (une seule fois)
            if (!tagify) {
               // console.log('✅ Initialisation Tagify');
                tagify = new Tagify(input, {
                    enforceWhitelist: false, // ✅ permet d’afficher des tags qui ne sont pas dans la whitelist
                    whitelist: [], // optionnel, on peut la remplir plus tard
                    dropdown: { maxItems: 10, enabled: 1, closeOnSelect: false }
                });
                input._tagify = tagify;

                // 🔁 Quand l’utilisateur modifie les tags → on sauvegarde + on envoie à Livewire
                tagify.on('change', e => {
                    currentTags = tagify.value; // garde la valeur actuelle
                    Livewire.dispatch('updateSousSousCategories', { data: e.detail.value });
                });
                tagify.on('input', function (e) {
                    const value = e.detail.value.trim();
                   // console.log('🔎 Recherche saisie Tagify :', value);

                    if (value.length >= 2) {
                        // 🔁 Envoi vers Livewire pour recherche dynamique
                        Livewire.dispatch('searchSousSousCategories', { query: value });
                    } else {
                        // vide la liste si champ effacé
                        tagify.settings.whitelist = [];
                    }
                });
                // 🧩 Quand Livewire renvoie les résultats de recherche
                Livewire.on('updateTagifySuggestions', data => {
                    const results = data.results || [];
                   // console.log('📩 Suggestions reçues depuis Livewire :', results);

                    // 🧠 Met à jour la whitelist et ouvre le dropdown
                    tagify.settings.whitelist = results;
                    tagify.dropdown.show.call(tagify, value = '');
                });
            }

            // 🔹 Quand Livewire envoie la liste depuis le back-end
            Livewire.on('loadSousSousCategories', data => {
                const tags = data.sousSousCats || [];
                console.log('📦 Tags reçus depuis Livewire :', tags);

                // 🧩 ne remplace pas si même contenu
                const same =
                    JSON.stringify(tags.map(t => t.value)) ===
                    JSON.stringify((tagify.value || []).map(t => t.value));

                if (!same) {
                    currentTags = tags;
                    tagify.removeAllTags();
                    tagify.addTags(tags);
                }
            });

            // 🔹 Après chaque re-render Livewire
            Livewire.hook('message.processed', () => {
                const newInput = document.querySelector('#sousSousCategorieTagify');

                // 🧠 Si Livewire a recréé le champ, on réattache l'ancien Tagify sans perdre les tags
                if (newInput && !newInput._tagify) {
                 //   console.log('♻️ Réattachement Tagify après re-render Livewire');

                    // Réutilise le même objet Tagify (évite la réinit)
                    newInput._tagify = tagify;
                    tagify.DOM.input = newInput;
                    tagify.input = newInput;
                    tagify.addTags(currentTags, true, true); // réinjecte les tags actuels
                }
            });

            // 🚀 Premier chargement des tags depuis Livewire
            Livewire.dispatch('refreshSousSousCats');
        })();
    </script>

@endpush





