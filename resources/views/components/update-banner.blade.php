  <!-- Center modal content -->
  <div class="modal fade" id="modal-update-banner-{{ $banner->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="myCenterModalLabel">
                    <b>
                        Modifier la  bannière
                    </b>
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('banners.update',$banner) }}" enctype="multipart/form-data" method="post">
                    @method('PUT')
                    @csrf
                    <input type="hidden" name="id" value="{{ $banner->id }}">
                    <div class="mb-3">
                        <label for="code">Photo version pc</label>
                        <input type="file" class="form-control" name="image">
                        @error('image')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label for="code">Photo version mobile</label>
                        <input type="file" class="form-control" name="image_mobile">
                        @error('image_mobile')
                            <div class="alert alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="row">
                        <div class="col">
                            <label for="code">Visibilité</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="show_btn" @checked( $banner->show_btn) value="true"
                                    id="flexSwitchCheckChecked">
                                <label class="form-check-label" for="flexSwitchCheckChecked">Affichier le bouton</label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                        <button type="submit" class="btn btn-primary">
                            Enregistrer
                        </button>
                    </div>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
