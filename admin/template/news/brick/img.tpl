{*{$setImgData = ['id'   =>  $page.id_news,'imgSrc' =>  $page.imgSrc]}
{include file="section/loop/img.tpl" controller="news" data=$setImgData}*}
<form id="table-image" action="">
    {include file="news/loop/img.tpl" controller="news" uploadDir="news" data=$images}
    <div class="actions">
        <div class="pull-right">
            {*<a href="#" class="btn btn-lg btn-default"></a>*}
            {*<a href="#" class="btn btn-lg btn-main-theme"></a>*}
            <button class="btn btn-lg btn-default update-checkbox" value="check-all" data-table="images">
                <span class="fa fa-check-square"></span> <span class="hidden-sm hidden-md">Sélectionner toutes les images</span>
            </button>
            <button class="btn btn-lg btn-default update-checkbox" value="uncheck-all" data-table="images">
                <span class="fa fa-square-o"></span> <span class="hidden-sm hidden-md">Désélectionner toutes les images</span>
            </button>
            <button class="btn btn-lg btn-main-theme modal_action" data-target="#delete_modal" data-controller="news" data-sub="images">
                <span class="fa fa-trash"></span> <span class="hidden-sm hidden-md">Supprimer les images sélectionnées</span>
            </button>
        </div>
    </div>
</form>