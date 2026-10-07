<div>

    <form wire:submit="filtrer">
        <div class="row">
            <div class="col-sm-6">
                <span>
                    <b>{{ $clients->count() }}</b> Résultats sur {{ $total }}.
                </span>
            </div>
            <div class="col-sm-6">
                <div class="input-group mb-3">
                    <input type="text" class="form-control btn-sm" wire:model="key" placeholder="Nom, email, phone">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            Filtrer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </form>


    @include('components.alert')

    <div class="table-responsive-sm">
        <table id="basic-datatable" class="table table-striped dt-responsive nowrap w-100">
            <thead class="table-dark cusor">
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Zipcode</th>
                    <th>City</th>
                    <th>Inscription</th>
                    <th>Points</th>
                    <th style="text-align: right;">
                        <span wire:loading>
                            <img src="https://i.gifer.com/ZKZg.gif" width="20" height="20" class="rounded shadow"
                                alt="">
                        </span>
                    </th>
                </tr>
            </thead>


            <tbody>
            @forelse ($clients as $client)
                <tr wire:key="client-{{ $client->id }}">
                    <td>{{ $client->id }}</td>
                    <td>{{ $client->nom }}</td>
                    <td>{{ $client->email }}</td>
                    <td>{{ $client->phone ?? '-' }}</td>
                    <td>{{ $client->zipcode ?? '-' }}</td>
                    <td>{{ $client->city ?? '-' }}</td>
                    <td>{{ $client->created_at->format('d/m/Y') }}</td>
                    <td>{{ $client->points ?? '-' }}</td>
                    <td class="text-end">

                        @if($client->guides && $client->guides->count() > 0)

                            @php
                                $guide = $client->guides->first();
                            @endphp

                            <button
                                type="button"
                                class="btn btn-warning btn-sm"
                                onclick="window.location.href='{{ route('guides.edit', $guide->id) }}'"
                            >
                                <i class="ri-edit-line"></i> Edit Guide
                            </button>

                        @else

                            <button
                                type="button"
                                class="btn btn-primary btn-sm"
                                onclick="window.location.href='{{ route('guides.create', ['client' => $client->id]) }}'"
                            >
                                <i class="ri-magic-line"></i> Create Guide
                            </button>

                        @endif


                        {{-- Commandes --}}
                        <button
                            type="button"
                            class="btn btn-sm btn-info"
                            data-bs-toggle="collapse"
                            data-bs-target="#cmd{{ $client->id }}"
                            aria-expanded="false"
                            aria-controls="cmd{{ $client->id }}"
                        >
                            <i class="ri-eye-line"></i> Commandes
                        </button>


                        {{-- Suppression --}}
                        @if ($confirmingDeleteId === $client->id)

                            <div class="d-inline-flex align-items-center gap-1">

            <span class="text-danger small fw-bold">
                Confirmer ?
            </span>

                                <button
                                    type="button"
                                    class="btn btn-sm btn-danger"
                                    wire:click="delete({{ $client->id }})"
                                    wire:loading.attr="disabled"
                                    wire:target="delete({{ $client->id }})"
                                >
                                    Oui
                                </button>

                                <button
                                    type="button"
                                    class="btn btn-sm btn-secondary"
                                    wire:click="cancelDelete"
                                    wire:loading.attr="disabled"
                                >
                                    Non
                                </button>

                            </div>

                        @else

                            <button
                                type="button"
                                class="btn btn-sm btn-danger"
                                wire:click="confirmDelete({{ $client->id }})"
                            >
                                <i class="ri-delete-bin-6-line"></i>
                            </button>

                        @endif

                    </td>
                </tr>

                <!-- ✅ Sous-table : historique des commandes -->
                <tr class="collapse bg-light" id="cmd{{ $client->id }}">
                    <td colspan="9">
                        @if($client->commandes->count() > 0)
                            <table class="table table-bordered table-sm">
                                <thead class="table-secondary">
                                <tr>
                                    <th>ID</th>
                                    <th>Statut</th>
                                    <th>Date</th>
                                    <th>Produits</th>
                                    <th>Paiement with points</th>
                                    <th>Montant total (approx)</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($client->commandes as $cmd)
                                    <tr>
                                        <td>{{ $cmd->id }}</td>
                                        <td>{{ ucfirst($cmd->statut) }}</td>
                                        <td>{{ $cmd->created_at->format('d/m/Y H:i') }}</td>
                                        <td>
                                            @foreach ($cmd->contenus as $item)
                                                - {{ $item->produit->nom ?? 'Produit supprimé' }} (x{{ $item->quantite }})<br>
                                            @endforeach
                                        </td>
                                        <td>
                                            @if($cmd->is_point)
                                                <span class="text-success fw-bold">✔️ Oui</span>
                                            @else
                                                <span class="text-danger fw-bold">❌ Non</span>
                                            @endif
                                        </td>

                                        <td>
                                            @if($cmd->is_point)
                                                {{  $config->frais}}
                                            @else
                                            @php
                                                $total = 0;
                                                foreach ($cmd->contenus as $item) {
                                                    $total += ($item->produit->prix ?? 0) * $item->quantite;
                                                }

                                            @endphp
                                            {{ $total+= $config->frais}}
                                            {{ number_format($total, 2) }} AED
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        @else
                            <p class="text-muted m-2">Aucune commande trouvée pour ce client.</p>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">Aucun client trouvé</td>
                </tr>
            @endforelse
            </tbody>




        </table>
    </div>
    {{ $clients->links('pagination::bootstrap-4') }}
    <br>
    @if ($clients->count() > 0)
        <div>
            <button class="btn btn-sm btn-danger" wire:click="delete_client">
                Supprimer tous les clients
            </button>
            @if ($deleted_confirmed)
                <div class="alert alert-warning mt-2">
                    <p>
                        Pour éviter tout problème lié à la suppression de tous les clients et des commandes, le système
                        se
                        chargera d'effectuer une suppression silencieuse des données tout en rendant les adresses e-mail
                        disponibles pour une nouvelle création de compte.
                    </p>
                    <div class="text-end">
                        <button class="btn btn-sm btn-success" wire:click="delete_all_client">
                            Confirmer la suppression
                        </button>
                    </div>
                </div>
            @endif
        </div>
    @endif


</div>

