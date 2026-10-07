<div>

    {{-- ========================================================= --}}
    {{-- LOADING --}}
    {{-- ========================================================= --}}

    <div class="d-flex justify-content-end mb-2">

        <span wire:loading wire:target="create,delete">
            <img
                src="https://i.gifer.com/ZKZg.gif"
                height="15"
                alt="Chargement"
            >
        </span>

    </div>


    {{-- ========================================================= --}}
    {{-- ALERTS --}}
    {{-- ========================================================= --}}

    @include('components.alert')


    {{-- ========================================================= --}}
    {{-- ERREUR SELECTION --}}
    {{-- ========================================================= --}}

    @error('selection')

    <div class="alert alert-danger">
        {{ $message }}
    </div>

    @enderror



    <div class="row">


        {{-- ===================================================== --}}
        {{-- LISTE DES PROMOTIONS --}}
        {{-- ===================================================== --}}

        <div class="col-sm-8 table-responsive-sm">

            <table
                id="basic-datatable"
                class="table table-striped dt-responsive nowrap w-100"
            >

                <thead class="table-dark">

                <tr>

                    <th>
                        Titre
                    </th>

                    <th>
                        Valeur(%)
                    </th>

                    <th>
                        Début
                    </th>

                    <th>
                        Fin
                    </th>

                    <th>
                        Statut
                    </th>

                    <th>
                    </th>

                </tr>

                </thead>


                <tbody>

                @forelse ($promotions as $promotion)

                    <tr>

                        <td>
                            {{ $promotion->titre }}
                        </td>


                        <td>
                            {{ $promotion->pourcentage }} %
                        </td>


                        <td>
                            {{ $promotion->debut }}
                        </td>


                        <td>
                            {{ $promotion->fin }}
                        </td>


                        <td>

                            @if ($promotion->statut === 'En cours')

                                <span class="badge bg-success">
                                        En cours
                                    </span>

                            @elseif ($promotion->statut === 'Programmer')

                                <span class="badge bg-warning text-dark">
                                        Programmé
                                    </span>

                            @elseif ($promotion->statut === 'Terminer')

                                <span class="badge bg-secondary">
                                        Terminé
                                    </span>

                            @else

                                <span class="badge bg-secondary">
                                        {{ $promotion->statut }}
                                    </span>

                            @endif

                        </td>


                        <td>

                            <button
                                type="button"
                                class="btn btn-sm btn-danger"
                                wire:confirm="Voulez-vous supprimer cette promotion ?"
                                wire:click="delete({{ $promotion->id }})"
                            >

                                <i class="ri-delete-bin-6-line"></i>

                            </button>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="6">

                            <div class="alert text-center">

                                <div>

                                    <img
                                        src="/icons/icons8-ticket-100.png"
                                        width="100"
                                        height="100"
                                        alt="Aucune promotion"
                                    >

                                </div>

                                <b>
                                    Aucune promotion disponible.
                                </b>

                            </div>

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>



        {{-- ===================================================== --}}
        {{-- FORMULAIRE --}}
        {{-- ===================================================== --}}

        <div class="col-sm-4">

            <form wire:submit="create">


                {{-- ================================================= --}}
                {{-- TITRE --}}
                {{-- ================================================= --}}

                <h6>
                    <b>
                        Nouvelle Promotion
                    </b>
                </h6>

                <hr>


                <div class="mb-3">

                    <label class="form-label">
                        Titre de la promotion
                    </label>

                    <input
                        type="text"
                        class="form-control"
                        wire:model="titre"
                        placeholder="Ex : Promotion été"
                    >

                    @error('titre')

                    <span class="text-danger small">
                            {{ $message }}
                        </span>

                    @enderror

                </div>



                {{-- ================================================= --}}
                {{-- TYPE DE SELECTION --}}
                {{-- ================================================= --}}

                @if (!$produit)

                    <div class="mb-3">

                        <label class="form-label">

                            <strong>
                                Type de sélection
                            </strong>

                        </label>


                        <select
                            class="form-control"
                            wire:model.live="selection_type"
                        >

                            <option value="">
                                -- Sélectionner --
                            </option>

                            <option value="categorie">
                                Par catégorie
                            </option>

                            <option value="sous_categorie">
                                Par sous-catégorie
                            </option>

                            <option value="sous_sous_categorie">
                                Par sous-sous-catégorie
                            </option>

                            <option value="marque">
                                Par marque
                            </option>

                            <option value="gamme">
                                Par gamme
                            </option>

                            <option value="produits">
                                Quelques produits
                            </option>

                            <option value="tous">
                                Tous les produits
                            </option>

                        </select>

                    </div>

                @endif



                {{-- ================================================= --}}
                {{-- PRODUIT DIRECT --}}
                {{-- ================================================= --}}

                @if ($produit)

                    <div class="mb-3 alert alert-warning p-2">

                        <label class="mb-1">

                            <strong>
                                Produit sélectionné
                            </strong>

                        </label>


                        <div class="d-flex align-items-center">

                            <img
                                src="{{ $produit->FirstImage() }}"
                                width="55"
                                height="55"
                                class="rounded border me-2"
                                style="object-fit:cover;"
                                alt="{{ $produit->nom }}"
                            >


                            <div>

                                <strong>
                                    {{ $produit->nom }}
                                </strong>

                                @if ($produit->reference)

                                    <br>

                                    <small class="text-muted">
                                        Réf :
                                        {{ $produit->reference }}
                                    </small>

                                @endif

                            </div>

                        </div>

                    </div>

                @endif



                {{-- ================================================= --}}
                {{-- CATEGORIE --}}
                {{-- ================================================= --}}

                @if (
                    !$produit &&
                    $selection_type === 'categorie'
                )

                    <div class="mb-3">

                        <label class="form-label">
                            Catégorie
                        </label>


                        <select
                            class="form-control"
                            wire:model.live="categorie_selected"
                        >

                            <option value="">
                                -- Sélectionner une catégorie --
                            </option>


                            @foreach ($categories as $categorie)

                                <option
                                    value="{{ $categorie->id }}"
                                >

                                    {{ $categorie->nom }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                @endif



                {{-- ================================================= --}}
                {{-- SOUS CATEGORIE --}}
                {{-- ================================================= --}}

                @if (
                    !$produit &&
                    $selection_type === 'sous_categorie'
                )

                    <div class="mb-3">

                        <label class="form-label">
                            Catégorie
                        </label>


                        <select
                            class="form-control mb-2"
                            wire:model.live="categorie_selected"
                        >

                            <option value="">
                                -- Catégorie --
                            </option>


                            @foreach ($categories as $categorie)

                                <option
                                    value="{{ $categorie->id }}"
                                >

                                    {{ $categorie->nom }}

                                </option>

                            @endforeach

                        </select>


                        @if ($categorie_selected)

                            <label class="form-label">
                                Sous-catégorie
                            </label>


                            <select
                                class="form-control"
                                wire:model.live="sous_categorie_selected"
                            >

                                <option value="">
                                    -- Sous-catégorie --
                                </option>


                                @foreach ($sousCategories as $sousCategorie)

                                    <option
                                        value="{{ $sousCategorie->id }}"
                                    >

                                        {{ $sousCategorie->nom }}

                                    </option>

                                @endforeach

                            </select>

                        @endif

                    </div>

                @endif



                {{-- ================================================= --}}
                {{-- SOUS SOUS CATEGORIE --}}
                {{-- ================================================= --}}

                @if (
                    !$produit &&
                    $selection_type === 'sous_sous_categorie'
                )

                    <div class="mb-3">

                        <label class="form-label">
                            Catégorie
                        </label>


                        <select
                            class="form-control mb-2"
                            wire:model.live="categorie_selected"
                        >

                            <option value="">
                                -- Catégorie --
                            </option>


                            @foreach ($categories as $categorie)

                                <option
                                    value="{{ $categorie->id }}"
                                >

                                    {{ $categorie->nom }}

                                </option>

                            @endforeach

                        </select>



                        @if ($categorie_selected)

                            <label class="form-label">
                                Sous-catégorie
                            </label>


                            <select
                                class="form-control mb-2"
                                wire:model.live="sous_categorie_selected"
                            >

                                <option value="">
                                    -- Sous-catégorie --
                                </option>


                                @foreach ($sousCategories as $sousCategorie)

                                    <option
                                        value="{{ $sousCategorie->id }}"
                                    >

                                        {{ $sousCategorie->nom }}

                                    </option>

                                @endforeach

                            </select>

                        @endif



                        @if ($sous_categorie_selected)

                            <label class="form-label">
                                Sous-sous-catégorie
                            </label>


                            <select
                                class="form-control"
                                wire:model.live="sous_sous_categorie_selected"
                            >

                                <option value="">
                                    -- Sous-sous-catégorie --
                                </option>


                                @foreach (
                                    $sousSousCategories
                                    as $sousSousCategorie
                                )

                                    <option
                                        value="{{ $sousSousCategorie->id }}"
                                    >

                                        {{ $sousSousCategorie->nom }}

                                    </option>

                                @endforeach

                            </select>

                        @endif

                    </div>

                @endif



                {{-- ================================================= --}}
                {{-- MARQUE --}}
                {{-- ================================================= --}}

                @if (
                    !$produit &&
                    $selection_type === 'marque'
                )

                    <div class="mb-3">

                        <label class="form-label">
                            Marque
                        </label>


                        <select
                            class="form-control"
                            wire:model.live="brand_selected"
                        >

                            <option value="">
                                -- Sélectionner une marque --
                            </option>


                            @foreach ($brands as $brand)

                                <option
                                    value="{{ $brand->id }}"
                                >

                                    {{ $brand->brandname }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                @endif



                {{-- ================================================= --}}
                {{-- GAMME --}}
                {{-- ================================================= --}}

                @if (
                    !$produit &&
                    $selection_type === 'gamme'
                )

                    <div class="mb-3">

                        <label class="form-label">
                            Gamme
                        </label>


                        <select
                            class="form-control"
                            wire:model.live="gamme_selected"
                        >

                            <option value="">
                                -- Sélectionner une gamme --
                            </option>


                            @foreach ($gammes as $gamme)

                                <option
                                    value="{{ $gamme->id }}"
                                >

                                    {{ $gamme->nom }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                @endif



                {{-- ================================================= --}}
                {{-- QUELQUES PRODUITS --}}
                {{-- ================================================= --}}

                @if (
                    !$produit &&
                    $selection_type === 'produits'
                )

                    <div class="mb-3">

                        <label class="form-label">

                            <strong>
                                Sélectionner des produits
                            </strong>

                        </label>


                        <input
                            type="text"
                            class="form-control"
                            wire:model.live.debounce.300ms="search_produit"
                            placeholder="Rechercher par nom ou référence..."
                        >

                    </div>

                @endif



                {{-- ================================================= --}}
                {{-- PRODUITS DU FILTRE --}}
                {{-- ================================================= --}}

                @if (
                    !$produit &&
                    $selection_type !== 'tous' &&
                    (
                        ($selection_type === 'categorie' && $categorie_selected)
                        ||
                        ($selection_type === 'sous_categorie' && $sous_categorie_selected)
                        ||
                        ($selection_type === 'sous_sous_categorie' && $sous_sous_categorie_selected)
                        ||
                        ($selection_type === 'marque' && $brand_selected)
                        ||
                        ($selection_type === 'gamme' && $gamme_selected)
                        ||
                        ($selection_type === 'produits' && $search_produit)
                    )
                )

                    <div class="mb-3">


                        {{-- ========================================= --}}
                        {{-- HEADER --}}
                        {{-- ========================================= --}}

                        <div class="d-flex justify-content-between align-items-center mb-2">

                            <label class="mb-0">

                                <strong>
                                    Produits
                                </strong>

                            </label>


                            <span class="badge bg-primary">

                                {{ count($produits_selected) }}

                            </span>

                        </div>



                        {{-- ========================================= --}}
                        {{-- BOUTONS --}}
                        {{-- ========================================= --}}

                        <div class="d-flex gap-2 mb-2">

                            <button
                                type="button"
                                class="btn btn-sm btn-outline-primary"
                                wire:click="selectAllProducts"
                            >

                                <i class="ri-checkbox-multiple-line"></i>

                                Tout sélectionner

                            </button>


                            <button
                                type="button"
                                class="btn btn-sm btn-outline-danger"
                                wire:click="unselectAllProducts"
                            >

                                <i class="ri-close-circle-line"></i>

                                Tout désélectionner

                            </button>

                        </div>



                        {{-- ========================================= --}}
                        {{-- LISTE PRODUITS --}}
                        {{-- ========================================= --}}

                        <div
                            class="border rounded"
                            style="
                                max-height:350px;
                                overflow-y:auto;
                            "
                        >

                            @forelse (
                                $produitsDisponibles
                                as $p
                            )

                                <div
                                    class="d-flex align-items-center border-bottom p-2"
                                >


                                    <input
                                        type="checkbox"
                                        class="form-check-input me-2"
                                        id="selected_product_{{ $p->id }}"
                                        value="{{ $p->id }}"
                                        wire:model.live="produits_selected"
                                    >



                                    <label
                                        for="selected_product_{{ $p->id }}"
                                        class="d-flex align-items-center flex-grow-1 mb-0"
                                        style="cursor:pointer;"
                                    >


                                        {{-- IMAGE --}}

                                        <img
                                            src="{{ $p->FirstImage() }}"
                                            width="50"
                                            height="50"
                                            class="rounded border me-2"
                                            style="object-fit:cover;"
                                            alt="{{ $p->nom }}"
                                        >


                                        {{-- INFORMATIONS --}}

                                        <div>

                                            <strong>
                                                {{ $p->nom }}
                                            </strong>


                                            @if ($p->reference)

                                                <br>

                                                <small class="text-muted">

                                                    Réf :
                                                    {{ $p->reference }}

                                                </small>

                                            @endif


                                            @if ($p->prix)

                                                <br>

                                                <small class="text-muted">

                                                    {{ $p->prix }}

                                                </small>

                                            @endif


                                            @if ($p->marque)

                                                <br>

                                                <small class="text-muted">

                                                    {{ $p->marque->brandname }}

                                                </small>

                                            @endif

                                        </div>

                                    </label>


                                    {{-- ETAT --}}

                                    @if (
                                        in_array(
                                            (string) $p->id,
                                            array_map(
                                                'strval',
                                                $produits_selected
                                            )
                                        )
                                    )

                                        <span class="badge bg-success">

                                            Sélectionné

                                        </span>

                                    @endif

                                </div>

                            @empty

                                <div class="text-center text-muted p-4">

                                    <i
                                        class="ri-inbox-line"
                                        style="font-size:30px;"
                                    ></i>

                                    <br>

                                    Aucun produit trouvé.

                                </div>

                            @endforelse

                        </div>

                    </div>

                @endif



                {{-- ================================================= --}}
                {{-- AJOUTER D'AUTRES PRODUITS --}}
                {{-- ================================================= --}}

                @if (
                    !$produit &&
                    $selection_type !== 'tous' &&
                    $selection_type !== 'produits'
                )

                    <div class="mb-3">

                        <div class="alert alert-info p-2">

                            <i class="ri-add-circle-line"></i>

                            <strong>
                                Ajouter d'autres produits
                            </strong>

                            <br>

                            <small>

                                Vous pouvez rechercher des produits
                                qui ne correspondent pas au filtre
                                sélectionné.

                            </small>

                        </div>


                        <input
                            type="text"
                            class="form-control"
                            wire:model.live.debounce.300ms="search_produit"
                            placeholder="Nom ou référence..."
                        >


                        @if ($search_produit)

                            <div
                                class="border rounded mt-2"
                                style="
                                    max-height:300px;
                                    overflow-y:auto;
                                "
                            >

                                @forelse (
                                    $produitsRecherche
                                    as $p
                                )

                                    <div
                                        class="d-flex align-items-center border-bottom p-2"
                                    >


                                        <input
                                            type="checkbox"
                                            class="form-check-input me-2"
                                            id="other_product_{{ $p->id }}"
                                            value="{{ $p->id }}"
                                            wire:model.live="produits_selected"
                                        >


                                        <label
                                            for="other_product_{{ $p->id }}"
                                            class="d-flex align-items-center flex-grow-1 mb-0"
                                            style="cursor:pointer;"
                                        >


                                            <img
                                                src="{{ $p->FirstImage() }}"
                                                width="50"
                                                height="50"
                                                class="rounded border me-2"
                                                style="object-fit:cover;"
                                                alt="{{ $p->nom }}"
                                            >


                                            <div>

                                                <strong>
                                                    {{ $p->nom }}
                                                </strong>


                                                @if ($p->reference)

                                                    <br>

                                                    <small class="text-muted">

                                                        Réf :
                                                        {{ $p->reference }}

                                                    </small>

                                                @endif


                                                @if ($p->marque)

                                                    <br>

                                                    <small class="text-muted">

                                                        Marque :
                                                        {{ $p->marque->brandname }}

                                                    </small>

                                                @endif

                                            </div>

                                        </label>


                                        @if (
                                            in_array(
                                                (string) $p->id,
                                                array_map(
                                                    'strval',
                                                    $produits_selected
                                                )
                                            )
                                        )

                                            <span class="badge bg-success">

                                                Sélectionné

                                            </span>

                                        @endif

                                    </div>

                                @empty

                                    <div class="text-center text-muted p-3">

                                        Aucun produit trouvé.

                                    </div>

                                @endforelse

                            </div>

                        @endif

                    </div>

                @endif



                {{-- ================================================= --}}
                {{-- QUELQUES PRODUITS : RECHERCHE --}}
                {{-- ================================================= --}}

                @if (
                    !$produit &&
                    $selection_type === 'produits'
                )

                    <div class="mb-3">

                        @if (count($produits_selected) > 0)

                            <div class="alert alert-success p-2">

                                <i class="ri-checkbox-multiple-line"></i>

                                <strong>
                                    {{ count($produits_selected) }}
                                </strong>

                                produit(s) sélectionné(s).

                            </div>

                        @endif


                        @if ($search_produit)

                            <div
                                class="border rounded"
                                style="
                                    max-height:350px;
                                    overflow-y:auto;
                                "
                            >

                                @forelse (
                                    $produitsRecherche
                                    as $p
                                )

                                    <div
                                        class="d-flex align-items-center border-bottom p-2"
                                    >

                                        <input
                                            type="checkbox"
                                            class="form-check-input me-2"
                                            id="search_product_{{ $p->id }}"
                                            value="{{ $p->id }}"
                                            wire:model.live="produits_selected"
                                        >


                                        <label
                                            for="search_product_{{ $p->id }}"
                                            class="d-flex align-items-center flex-grow-1 mb-0"
                                            style="cursor:pointer;"
                                        >

                                            <img
                                                src="{{ $p->FirstImage() }}"
                                                width="50"
                                                height="50"
                                                class="rounded border me-2"
                                                style="object-fit:cover;"
                                                alt="{{ $p->nom }}"
                                            >


                                            <div>

                                                <strong>
                                                    {{ $p->nom }}
                                                </strong>


                                                @if ($p->reference)

                                                    <br>

                                                    <small class="text-muted">

                                                        Réf :
                                                        {{ $p->reference }}

                                                    </small>

                                                @endif


                                                @if ($p->marque)

                                                    <br>

                                                    <small class="text-muted">

                                                        Marque :
                                                        {{ $p->marque->brandname }}

                                                    </small>

                                                @endif

                                            </div>

                                        </label>


                                        @if (
                                            in_array(
                                                (string) $p->id,
                                                array_map(
                                                    'strval',
                                                    $produits_selected
                                                )
                                            )
                                        )

                                            <span class="badge bg-success">

                                                Sélectionné

                                            </span>

                                        @endif

                                    </div>

                                @empty

                                    <div class="text-center text-muted p-3">

                                        Aucun produit trouvé.

                                    </div>

                                @endforelse

                            </div>

                        @else

                            <div class="alert alert-light border">

                                <i class="ri-search-line"></i>

                                Recherchez un produit par son nom
                                ou sa référence.

                            </div>

                        @endif

                    </div>

                @endif



                {{-- ================================================= --}}
                {{-- TOUS LES PRODUITS --}}
                {{-- ================================================= --}}

                @if (
                    !$produit &&
                    $selection_type === 'tous'
                )

                    <div class="mb-3">

                        <div class="alert alert-warning p-2">

                            <i class="ri-information-line"></i>

                            <strong>
                                Tous les produits
                            </strong>

                            <br>

                            <small>
                                Tous les produits seront concernés
                                par cette promotion.
                            </small>

                        </div>

                    </div>

                @endif



                {{-- ================================================= --}}
                {{-- RESUME SELECTION --}}
                {{-- ================================================= --}}

                @if (
                    !$produit &&
                    count($produits_selected) > 0
                )

                    <div class="alert alert-primary p-2">

                        <div class="d-flex justify-content-between">

                            <div>

                                <i class="ri-checkbox-multiple-line"></i>

                                <strong>
                                    {{ count($produits_selected) }}
                                </strong>

                                produit(s) sélectionné(s).

                            </div>


                            <button
                                type="button"
                                class="btn btn-sm btn-outline-danger"
                                wire:click="unselectAllProducts"
                            >

                                Vider

                            </button>

                        </div>

                    </div>

                @endif



                {{-- ================================================= --}}
                {{-- POURCENTAGE --}}
                {{-- ================================================= --}}

                <div class="mb-3">

                    <label class="form-label">

                        Pourcentage (%)

                    </label>


                    <input
                        type="number"
                        min="2"
                        max="100"
                        class="form-control"
                        wire:model="pourcentage"
                        placeholder="Entre 2 et 100"
                    >


                    @error('pourcentage')

                    <span class="text-danger small">
                            {{ $message }}
                        </span>

                    @enderror

                </div>



                {{-- ================================================= --}}
                {{-- DATES --}}
                {{-- ================================================= --}}

                <div class="row">


                    {{-- DATE DEBUT --}}

                    <div class="col">

                        <div class="mb-3">

                            <label class="form-label">
                                Date début
                            </label>


                            <input
                                type="date"
                                class="form-control"
                                wire:model="debut"
                            >


                            @error('debut')

                            <span class="text-danger small">
                                    {{ $message }}
                                </span>

                            @enderror

                        </div>

                    </div>



                    {{-- DATE FIN --}}

                    <div class="col">

                        <div class="mb-3">

                            <label class="form-label">
                                Date fin
                            </label>


                            <input
                                type="date"
                                class="form-control"
                                wire:model="fin"
                            >


                            @error('fin')

                            <span class="text-danger small">
                                    {{ $message }}
                                </span>

                            @enderror

                        </div>

                    </div>

                </div>



                {{-- ================================================= --}}
                {{-- BOUTON ENREGISTRER --}}
                {{-- ================================================= --}}

                <div class="modal-footer">

                    <button
                        type="submit"
                        class="btn btn-primary"
                        wire:loading.attr="disabled"
                        wire:target="create"
                    >

                        <span
                            wire:loading.remove
                            wire:target="create"
                        >

                            <i class="ri-save-line"></i>

                            Enregistrer la promotion.

                        </span>


                        <span
                            wire:loading
                            wire:target="create"
                        >

                            <i class="ri-loader-4-line"></i>

                            Enregistrement...

                        </span>

                    </button>

                </div>


            </form>

        </div>

    </div>

</div>
