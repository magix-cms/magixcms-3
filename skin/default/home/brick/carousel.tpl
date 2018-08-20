{if $viewport !== 'mobile'}
<div id="home-slidehow" class="carousel slide hidden-xs" data-ride="carousel">
    <ol class="carousel-indicators">
        <li data-target="#home-slidehow" data-slide-to="0" class="active"></li>
        <li data-target="#home-slidehow" data-slide-to="1"></li>
        <li data-target="#home-slidehow" data-slide-to="2"></li>
    </ol>
    <div class="carousel-inner" role="listbox">
        <div class="item active">
            <img src="/skin/{$theme}/img/carousel/carousel-urbex.jpg" width="1920" height="500" alt="">
        </div>
        <div class="item">
            <img src="/skin/{$theme}/img/carousel/carousel-mountain-walker.jpg" width="1920" height="500" alt="">
        </div>
        <div class="item">
            <img src="/skin/{$theme}/img/carousel/carousel-skyscraper.jpg" width="1920" height="500" alt="">
        </div>
    </div>
    <a class="left carousel-control" href="#home-slidehow" role="button" data-slide="prev">
        <span class="icon-prev" aria-hidden="true"></span>
        <span class="sr-only">Previous</span>
    </a>
    <a class="right carousel-control" href="#home-slidehow" role="button" data-slide="next">
        <span class="icon-next" aria-hidden="true"></span>
        <span class="sr-only">Next</span>
    </a>
</div>
{else}
    <div id="banner">
        <div class="banner-inner">
            <h1 class="text-center" itemprop="name">{$home.name}</h1>
        </div>
    </div>
{/if}