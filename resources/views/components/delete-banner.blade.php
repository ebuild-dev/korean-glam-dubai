 <!-- Center modal content -->
 <div class="modal fade" id="modal-delete-banner-{{ $banner->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="myCenterModalLabel">
                    <b class="text-danger">
                        Supprimer une bannière
                    </b>
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('banners.destroy',$banner) }}" method="post">
                    @method('DELETE')
                    @csrf
                    <input type="hidden" value="{{ $banner->id }}" id="id_banner" name="id_banner">
                    <p>
                        vous etes sur le point de faire la suppression d'une bannière .
                        Voulez-vous vraimaent le faire ?
                        <p>
                            <b>{{ $banner->titre }}</b>
                        </p>
                    </p>
                    <button type="submit" class="btn btn-danger">
                        Supprimer
                    </button>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
