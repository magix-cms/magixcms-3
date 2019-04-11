<amp-sidebar id="sidebar1" layout="nodisplay" side="left">
    <div class="sidebar">
        <header>
            <div role="button" aria-label="close sidebar" on="tap:sidebar1.toggle" tabindex="0" class="close-sidebar"><i class="material-icons">close</i></div>
            Navigation
        </header>
        <amp-accordion class="menu list-unstyled" animate expand-single-section disable-session-states>
            {include file="section/menu/loop/dropdown.tpl" menuData=$links mobile=$mobile amp=true}
        </amp-accordion>
        <footer>
            {include file="section/brick/sharebar.tpl"}
        </footer>
    </div>
</amp-sidebar>