<div>
    @include('components.alert')
    <div class="table-responsive-sm">
        <table class="table table-striped dt-responsive nowrap w-100">
            <thead class="table-dark">
                <tr>
                    <td></td>
                    <td>Nom</td>
                    <td>Référence</td>
                    <td>Prix</td>
                    <td>Articles</td>
                    <td>
                    </td>
                </tr>
            </thead>
            @forelse ($packs as $pack)
                <tr>
                    <td>
                        <img width="30" height="30" src="https://img.icons8.com/ios/30/027461/pack-luggage.png" alt="pack-luggage"/>
                    </td>
                    <td>
                        {{ $pack->nom }}
                    </td>
                    <td>
                        {{ $pack->reference }}
                    </td>
                    <td>
                        {{ $pack->prix }}
                        <x-devise></x-devise>
                    </td>
                    <td>
                        {{ $pack->contenus->count() }}
                    </td>
                    <td style="text-align: right;">
                        <div class="btn-group">
                            <button class="btn btn-sm btn-warning" >
                                <i class="ri-edit-box-line"></i> Modifier
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="toggle_confirmation({{ $pack->id }})">
                                <i class="ri-delete-bin-6-line"></i>
                            </button>
                        </div>
                        <button class="btn btn-sm btn-success d-none" type="button" id="confirmBtn{{ $pack->id }}"
                            wire:click="delete({{ $pack->id }})">
                            <span class="hide-tablete">
                                Confirmer
                            </span>
                        </button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">
                        <div class="text-center p-3">
                            <div>
                                <img src="/icons/icons8-ticket-100.png" height="100" width="100" alt=""
                                    srcset="">
                            </div>
                            Aucun pack
                        </div>
                    </td>
                </tr>
            @endforelse
        </table>
    </div>
</div>
