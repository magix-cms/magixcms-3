<div id="footbar">
    <amp-accordion disable-session-states>
        <section>
            <header>
                <div>
                    <button class="btn btn-flat btn-box btn-main-theme" type="button" id="menu-share">
                        <i class="material-icons">share</i>
                        {#share#|ucfirst}
                    </button>
                    <a href="#{$smarty.capture.bodyId}" class="btn btn-flat btn-box btn-main-theme"><i class="material-icons">keyboard_arrow_up</i></a>
                </div>
            </header>
            <div>
                <ul class="list-unstyled share-nav" aria-labelledby="menu-share">
                    {include file="section/loop/share.tpl" data=$shareUrls config=$shareConfig}
                </ul>
            </div>
        </section>
    </amp-accordion>
</div>