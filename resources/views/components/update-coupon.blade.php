  <!-- Center modal content -->
  <div class="modal fade" id="modal-update-coupon-{{ $coupon->id }}" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
              <div class="modal-header">
                  <h6 class="modal-title" id="myCenterModalLabel">
                      <b>
                          Modifier le coupon : <b> {{ $coupon->code }} </b>
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
                  <div class="alert alert-info">
                      La modification d'un coupon entrainera la modification des commandes dans les quelles le code de
                      ce coupon a été utiliser.
                  </div>
                  <form action="{{ route('coupons.update',$coupon) }}" method="post">
                      @method('PUT')
                      @csrf

                      <input type="hidden" name="id" value="{{ $coupon->id }}">
                      <div class="mb-3">
                          <label for="code">Code du coupon *</label>
                          <input type="text" class="form-control" placeholder="XXXXXX" id="code"
                              value="{{ $coupon->code }}" name="code" required>
                      </div>
                      <div class="mb-3">
                          <label for="pourcentage">Valeur du coupon ( Pourcentage %) *</label>
                          <input type="number" class="form-control" id="pourcentage" name="pourcentage"
                              value="{{ $coupon->pourcentage }}" required>
                      </div>
                      <div class="mb-3">
                          <label for="valid_until">Date d'expiration *</label>
                          <input type="date" class="form-control" id="valid_until" name="valid_until"
                              value="{{ date('Y-m-d', strtotime($coupon->valid_until)) }}" required>
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
