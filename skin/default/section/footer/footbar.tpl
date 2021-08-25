<div id="footbar" data-spy="affix" data-offset-top="0">
    <button type="button" class="btn toggle-menu navbar-toggle hidden-sm-up" data-toggle="collapse" data-target="#menu">
        <span class="icon">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </span>
        <span>Menu</span>
    </button>
    {if $dataLang != null && count($dataLang) > 1}
    <div class="select-lang hidden-sm-up">
        {include file="section/brick/lang.tpl" display='menu'}
    </div>
    {/if}
    <div class="dropup">
        <button class="btn btn-flat btn-box dropdown-toggle" type="button" id="menu-share" data-toggle="dropdown">
            <i class="material-icons ico ico-share"></i><span class="sr-ph">{#share#|ucfirst}</span>
        </button>
        <ul class="dropdown-menu list-unstyled share-nav" aria-labelledby="menu-share">
            {include file="section/loop/share.tpl" data=$shareUrls config=$shareConfig}
        </ul>
    </div>
    <div class="align-right toTop affix-top float-btn">
        <a class="btn btn-flat btn-box" href="#" title="{#back_to_top#|ucfirst}">
            <span class="sr-ph">{#back_to_top#|ucfirst}</span><i class="material-icons ico ico-keyboard_arrow_up"></i>
        </a>
    </div>
</div>