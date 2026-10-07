 <!-- Center modal content -->
 <div class="modal fade" id="modal-delete-coupon-{{ $coupon->id }}" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="myCenterModalLabel">
                    <b class="text-danger">
                        Supprimer un coupon
                    </b>
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('coupons.destroy',$coupon) }}" method="post">
                    @method('DELETE')
                    @csrf
                    <input type="hidden" value="{{ $coupon->id }}" id="id_coupon" name="id_coupon">
                    <p>
                        vous etes sur le point de faire la suppression d'un coupon .
                        Voulez-vous vraimaent le faire ?
                    </p>
                    <button type="submit" class="btn btn-danger">
                        Supprimer
                    </button>
                </form>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
