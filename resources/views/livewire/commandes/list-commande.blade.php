<div>
    <form wire:submit="filtrer">
        <div class="row">
            <div class="col-sm-4">
                <span>
                    <b>{{ $commandes->count() }}</b> Résultats sur {{ $total }}
                </span>
            </div>
            <div class="col-sm-12">
                <div class="input-group mb-3">
                    @if ($selectedCommandes)
                        <button type="button" class="btn btn-sm btn-secondary" wire:click="getSelectedCommandes">
                            Exporter le bordereau
                            ({{ count($selectedCommandes) }})
                        </button>
                    @endif
                    <input type="text" wire:model.live="key" placeholder="Recherche par nom,prenom, nnuméro"
                        class="form-control">
                    <select class="form-control" wire:model="statut2">
                        <option value="">Etat de confirmation</option>
                        <option value="">Tous</option>
                        <option value="confirmer">Confirmé</option>
                        <option value="non_confirmer">Non confirmé</option>
                    </select>
                    <select class="form-control" wire:model="gouvernorat">
                        <option value="">Emirate</option>
                        @foreach ($gouvernoratsTunisie as $gouvernorat)
                            <option value="{{ $gouvernorat->id }}">
                                {{ $gouvernorat->nom }}
                            </option>
                        @endforeach
                    </select>
                    <select class="form-control" wire:model="statut">
                        <option value="">Etat</option>
                        <option value="créé">Créé</option>
                        <option value="traitement">Traitement</option>
                        <option value="Livraison">Livraison</option>
                        <option value="livré">Livré</option>
                        <option value="payée">Payée</option>
                        <option value="planification_retour">Planification de retour</option>
                        <option value="retournée">Retournée</option>
                    </select>
                        <select class="form-control" wire:model="preorder">
                            <option value="">Type commande</option>
                            <option value="1">Précommande</option>
                            <option value="0">Commande normale</option>
                        </select>

                        <input type="date" class="form-control" wire:model="date">
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
            <thead class="table-dark">
                <tr>
                    <th></th>
                    <td></td>
                    <th>ID</th>
                    <th>Ref light speed</th>
                    <th>Nom</th>
                    <th>Téléphone</th>
                    <th>City</th>
                    <th>Montant</th>
                    <th>Statut</th>

                    <th>Mode</th>
{{--                    <th>Paiement avec Points</th> --}}{{-- 🆕 nouvelle colonne --}}
                    <th>Preorder</th>

                    <th>Date</th>
                    <th class="text-end">
                        <span wire:loading>
                            <img src="https://i.gifer.com/ZKZg.gif" height="15" alt="" srcset="">
                        </span>
                    </th>
                </tr>
            </thead>


            <tbody>
                @forelse ($commandes as $commande)
                    <tr>
                        <td>
                            <input type="checkbox" wire:click="toggleCommandeSelection({{ $commande->id }})">
                        </td>
                        <td>
                            <button class="btn btn-sm" data-bs-toggle="modal"
                                data-bs-target="#qr-code-{{ $commande->id }}">
                                <i class="ri-qr-scan-2-line"></i>
                            </button>
                            <!-- Center modal content -->
                            <div class="modal fade" id="qr-code-{{ $commande->id }}" tabindex="-1" role="dialog"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="myCenterModalLabel">
                                                Commande #{{ $commande->id }}
                                            </h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <h6 class="text-muted">
                                                Veuillez scanner ce code Qr pour impprimer le Reçu de commande .
                                            </h6>
                                            <div class="text-center p-2">
                                                {!! QrCode::size(100)->generate(route('print_commande', ['id' => $commande->id])) !!}
                                            </div>
                                        </div>
                                    </div><!-- /.modal-content -->
                                </div><!-- /.modal-dialog -->
                            </div><!-- /.modal -->
                        </td>
                        <td>{{ $commande->id }}</td>
                        <td>{{ $commande->code_in_api ?? '-' }}</td>
                        <td>
                            {{ $commande->nom }}
                            @if ($commande->note)
                                <i class="ri-message-2-fill" title="Une note a été ajouté"></i>
                            @endif
                        </td>
                        <td>{{ $commande->phone }}</td>
                        <td>
                            {{ $commande->city ?? 'N/A' }}
                        </td>
                        @php
                            $config = \App\Models\config::first();
                        @endphp

                        <td>
                            @if($commande->is_point)
                                {{ $config->frais ?? 0 }} {{ $commande->devise === 'DT' ? 'AED' : $commande->devise }}
                            @else
                                {{ $commande->montant_with_coupon() }} {{ $commande->devise === 'DT' ? 'AED' : $commande->devise }}
                            @endif
                        </td>


                        <td>
                            @can('order_edit')
                                @if ($commande->etat === 'annulé')
                                <label for="etat-{{ $commande->id }}">État</label>
                                <select id="etat-{{ $commande->id }}" class="form-select form-select-sm w-auto mb-2"
                                        wire:change="updateEtat({{ $commande->id }}, $event.target.value)" wire:loading.attr="disabled">
                                    @foreach (['attente' => 'En attente', 'confirmé' => 'Confirmée', 'annulé' => 'Annulée'] as $value => $label)
                                        <option value="{{ $value }}" @selected($commande->etat === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @endif
                                <label for="statut-{{ $commande->id }}">Statut</label>
                                <select id="statut-{{ $commande->id }}" class="form-select form-select-sm w-auto"
                                        wire:change="updateStatut({{ $commande->id }}, $event.target.value)" wire:loading.attr="disabled">
                                    <option value="" disabled @selected(!$commande->statut)>-- Statut --</option>
                                    @foreach (['créé' => 'Créé', 'traitement' => 'Traitement', 'livraison' => 'Livraison', 'livré' => 'Livré', 'payée' => 'Payée', 'planification_retour' => 'Planification de retour', 'retournée' => 'Retournée'] as $value => $label)
                                        <option value="{{ $value }}" @selected($commande->statut === $value)>{{ $label }}</option>
                                    @endforeach
                                </select>
                                @if ($commande->etat === 'attente')
                                    @if ($commande->mode_paiement == 'online' && !$commande->paymentRef)
                                        <b class="text-danger">Paiement incomplet</b>
                                    @else
                                        <button type="button" class="btn btn-sm btn-primary mt-2"
                                                wire:click="confirmer({{ $commande->id }})">Valider </button>
                                        <button type="button" class="btn btn-sm btn-danger mt-2"
                                                wire:click="annuler({{ $commande->id }})"
                                                wire:loading.attr="disabled">Annuler</button>
                                    @endif
                                @endif
                            @endcan

                        </td>
                        <td>
    <span class="text-capitalize">

        {{-- Paiement en ligne / espèce --}}
        @if ($commande->mode_paiement == 'online')
            <span class="badge bg-primary">
                En ligne
            </span>

            @if ($commande->paymentRef)
                <span class="badge bg-warning text-dark">
                    Payé
                </span>
            @endif

        @else
            <span class="badge bg-dark">
                Espèce
            </span>
        @endif

        {{-- Points fidélité --}}
        @if ($commande->is_point)
            <span class="badge bg-success">
                Points fidélité
            </span>
        @endif

    </span>
                        </td>

                        {{--    <td>
                                @if($commande->is_point)
                                    <span class="badge bg-success">✅ Oui</span>
                                @else
                                    <span class="badge bg-secondary">❌ Non</span>
                                @endif
                            </td>--}}
                        <td>
                            @if ($commande->hasPreorder())
                                <span class="badge bg-warning text-dark">Pre-order</span>
                            @else
                                <span class="badge bg-success">Normal</span>
                            @endif
                        </td>

                        <td>{{ $commande->created_at }} </td>
                        <td style="text-align: right;">
                            <div class="row row-cols-2 g-1" style="width:130px; float:right;">

                                @can('order_edit')
                                    @if ($commande->modifiable())
                                        <div class="col">
                                            <button class="btn btn-sm btn-warning w-100"
                                                    onclick="url('{{ route('edit_commande', ['id' => $commande->id]) }}')">
                                                <i class="ri-edit-2-line"></i>
                                            </button>
                                        </div>
                                    @endif
                                @endcan

                                @can('order_edit')
                                    <div class="col">
                                        <button class="btn btn-sm btn-primary w-100"
                                                onclick="add_note({{ $commande->id }},'{{ $commande->nom }}')">
                                            Note
                                        </button>
                                    </div>
                                @endcan

                                <div class="col">
                                    <button class="btn btn-info btn-sm w-100" type="button"
                                            title="Imprimer la commande"
                                            onclick="url('{{ route('print_commande', ['id' => $commande->id]) }}')">
                                        <i class="ri-printer-line"></i>
                                    </button>
                                </div>

                                <div class="col">
                                    <button class="btn btn-sm btn-dark w-100"
                                            onclick="url('{{ route('details_commande', ['id' => $commande->id]) }}')">
                                        <i class="ri-eye-line"></i>
                                    </button>
                                </div>

                                @can('order_delete')
                                    <div class="col">
                                        <button class="btn btn-sm btn-danger w-100"
                                                onclick="toggle_confirmation({{ $commande->id }})">
                                            <i class="ri-delete-bin-6-line"></i>
                                        </button>
                                    </div>
                                @endcan

                            </div>

                            @can('order_delete')
                                <button class="btn btn-sm btn-success d-none mt-1 w-100" type="button"
                                        id="confirmBtn{{ $commande->id }}" wire:click="delete({{ $commande->id }})">
                                    Confirmer
                                </button>
                            @endcan
                        </td>

                    </tr>
                @empty
                    <tr>
                        <td colspan="12">
                            <div class="text-center">
                                <div>
                                    <img src="/icons/icons8-ticket-100.png" height="100" width="100"
                                        alt="" srcset="">
                                </div>
                                Aucune commande trouvé
                                @if ($key)
                                    <b> " {{ $key }} " </b>
                                @endif
                                .
                            </div>
                        </td>
                    </tr>
                @endforelse

            </tbody>


        </table>
    </div>

    {{ $commandes->links('pagination::bootstrap-4') }}





</div>
