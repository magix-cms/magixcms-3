<div{if !$main} id="menu"{if $mobile} class="collapse"{/if}{else} class="visible-md visible-lg"{/if}>
    {if !$main}
    <div id="menu-overlay" data-toggle="collapse" data-target="#menu"></div>
    <div id="sidebar">
        {strip}<header>
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#menu">
                <i class="material-icons">close</i>
                <span class="sr-only">{#closeNavigation#|ucfirst}</span>
            </button>
            {#navigation#}
        </header>{/strip}{/if}
        <nav id="{if $main}main{else}side{/if}-menu" class="menu menu-tabs-arrow menubar" itemprop="hasPart" itemscope itemtype="http://schema.org/SiteNavigationElement">
            <a href="#content" class="sr-only skip-menu">{#skipMenu#}</a>
            <ul{if $mobile} id="menul"{/if} class="list-unstyled">
                {include file="section/menu/loop/dropdown.tpl" menuData=$links mobile=$mobile}
            </ul>
        </nav>
        {if !$main}
        {if !empty($companyData.socials)}
            <footer>
                {include file="section/brick/sharebar.tpl"}
            </footer>
        {/if}
    </div>
    {/if}
</div>