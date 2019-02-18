<form id="table-image" action="">
    {include file="catalog/product/loop/img.tpl" controller="product" uploadDir="catalog/p" data=$images}
    <div class="actions">
        <div class="pull-right">
            {*<a href="#" class="btn btn-lg btn-default"></a>*}
            {*<a href="#" class="btn btn-lg btn-main-theme"></a>*}
            <button class="btn btn-lg btn-default update-checkbox" value="check-all" data-table="image">
                <span class="fa fa-check-square"></span> <span class="hidden-sm hidden-md">Sélectionner toutes les images</span>
            </button>
            <button class="btn btn-lg btn-default update-checkbox" value="uncheck-all" data-table="image">
                <span class="fa fa-square-o"></span> <span class="hidden-sm hidden-md">Désélectionner toutes les images</span>
            </button>
            <button class="btn btn-lg btn-main-theme modal_action" data-target="#delete_modal" data-controller="product" data-sub="image">
                <span class="fa fa-trash"></span> <span class="hidden-sm hidden-md">Supprimer les images sélectionnées</span>
            </button>
        </div>
    </div>
</form>