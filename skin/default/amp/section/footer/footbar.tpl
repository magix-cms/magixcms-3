{strip}{widget_share_data assign="shareData"}{/strip}
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