<header class="header" role="banner">
    <nav class="header-inner">
        <div role="button" on="tap:sidebar1.toggle" tabindex="0" class="hamburger"><i class="material-icons">menu</i></div>
        <div class="site-name">
            <a href="{geturl}/{getlang}/amp/" title="{#logo_link_title#|ucfirst}">
                <amp-img src="/skin/{template}img/logo/webp/logo-magix_cms@229.webp" alt="Logo de Magix CMS" height="50" width="229"></amp-img>
            </a>
        </div>
    </nav>
</header>
<amp-sidebar id="sidebar1" layout="nodisplay" side="left" class="menu">
    {*<div role="button" aria-label="close sidebar" on="tap:sidebar1.toggle" tabindex="0" class="close-sidebar"><i class="material-icons">close</i></div>*}
    <ul class="sidebar">
        <li><a href="#">Example 1</a></li>
        <li><a href="#">Example 2</a></li>
        <li><a href="#">Example 3</a></li>
    </ul>
</amp-sidebar>