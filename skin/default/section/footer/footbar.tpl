<div id="footbar" data-spy="affix" data-offset-top="0">
    <div class="wrapper">
        <div class="dropup pull-left">
            <button class="btn btn-flat btn-box dropdown-toggle" type="button" id="menu-share" data-toggle="dropdown">
                <i class="material-icons">share</i> {#share#|ucfirst}
            </button>
        </div>
        <ul class="list-unstyled share-nav" aria-labelledby="menu-share">
            {include file="section/loop/share.tpl" data=$shareUrls config=$shareConfig}
        </ul>
        <div class="align-right toTop affix-top float-btn">
            <a class="btn btn-flat btn-box" href="#" title="{#back_to_top#|ucfirst}">
                <i class="material-icons">keyboard_arrow_up</i>
            </a>
        </div>
    </div>
</div>