{*<div id="footbar" data-spy="affix" data-offset-top="0">
    <div class="wrapper">
        <div class="dropup pull-left">
            <button class="btn btn-flat btn-box btn-main-theme dropdown-toggle" type="button" id="menu-share" data-toggle="dropdown" *}{*aria-haspopup="true" aria-expanded="true"*}{*>
                <span class="fa fa-share-alt"></span>
                {#share#|ucfirst}
            </button>
        </div>
        <ul class="list-unstyled share-nav" aria-labelledby="menu-share">
            {include file="section/loop/share.tpl" data=$shareData}
        </ul>
        <div class="align-right toTop affix-top float-btn">
            <a class="btn btn-flat btn-box btn-main-theme" href="#" title="{#back_to_top#|ucfirst}">
                <span class="fa fa-angle-up"></span>
            </a>
        </div>
    </div>
</div>*}
<div id="footbar">
    <amp-accordion disable-session-states>
        <section>
            <header>
                <div>
                    <button class="btn btn-flat btn-box btn-main-theme" type="button" id="menu-share">
                        <i class="material-icons">share</i>
                        {#share#|ucfirst}
                    </button>
                    <a href="#top" class="btn btn-flat btn-box btn-main-theme"><i class="material-icons">keyboard_arrow_up</i></a>
                </div>
            </header>
            <div>
                <ul class="list-unstyled share-nav" aria-labelledby="menu-share">
                    {include file="amp/section/loop/share.tpl" data=$shareData}
                </ul>
            </div>
        </section>
    </amp-accordion>
</div>