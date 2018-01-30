<div class="modal fade" id="modal-cart" tabindex="-1" role="dialog" aria-labelledby="{#shopping_cart#|ucfirst}" aria-hidden="true">>
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">{#shopping_cart#|ucfirst}</h4>
            </div>
            <div class="modal-body">
                <p class="alert alert-info">{#login_modal_alert#|ucfirst}</p>
                <p>
                    <a class="btn btn-primary btn-block" href="/{getlang}/account/login_redirect/">
                        <span class="fa fa-user fa-lg"></span>
                        {#login_title#|ucfirst}
                    </a>
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{#close#|ucfirst}</button>
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->