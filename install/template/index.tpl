{extends file="layout.tpl"}
{block name='head:title'}{#welcome#}{/block}
{block name='body:id'}home{/block}
{block name='content'}
    <nav class="form-steps">
        <div class="form-steps__item form-steps__item--active">
            <a class="form-steps__item-content" href="#welcome" data-toggle="tab">
                <span class="form-steps__item-text"><span>{#welcome#}</span><span>&nbsp;</span></span>
                <span class="form-steps__item-icon"><span class="sr-only">1</span></span>
            </a>
        </div>
        <div class="form-steps__item">
            <a class="form-steps__item-content" href="#analysis" data-toggle="disabled">
                <span class="form-steps__item-text"><span>{#analysis#}</span><span>&nbsp;</span></span>
                <span class="form-steps__item-icon"><span class="sr-only">2</span></span>
            </a>
            <span class="form-steps__item-line"></span>
        </div>
        <div class="form-steps__item">
            <a class="form-steps__item-content" href="#configuration" data-toggle="disabled">
                <span class="form-steps__item-text"><span>{#configuration#}</span><span>&nbsp;</span></span>
                <span class="form-steps__item-icon"><span class="sr-only">3</span></span>
            </a>
            <span class="form-steps__item-line"></span>
        </div>
        <div class="form-steps__item">
            <a class="form-steps__item-content" href="#installation" data-toggle="disabled">
                <span class="form-steps__item-text"><span>{#installation#}</span><span>&nbsp;</span></span>
                <span class="form-steps__item-icon"><span class="sr-only">4</span></span>
            </a>
            <span class="form-steps__item-line"></span>
        </div>
        <div class="form-steps__item">
            <a class="form-steps__item-content" href="#setting" data-toggle="disabled">
                <span class="form-steps__item-text"><span>{#setting#}</span><span>&nbsp;</span></span>
                <span class="form-steps__item-icon"><span class="sr-only">5</span></span>
            </a>
            <span class="form-steps__item-line"></span>
        </div>
        <div class="form-steps__item">
            <a class="form-steps__item-content" href="#confirmation" data-toggle="disabled">
                <span class="form-steps__item-text"><span>{#confirmation#}</span><span>&nbsp;</span></span>
                <span class="form-steps__item-icon"><span class="sr-only">6</span></span>
            </a>
            <span class="form-steps__item-line"></span>
        </div>
    </nav>
    <div id="steps" class="tab-content">
        <div id="welcome" class="tab-pane fade in active">
            {include file="steps/welcome.tpl"}
        </div>
        <div id="analysis" class="tab-pane fade">
            {include file="steps/analysis.tpl"}
        </div>
        <div id="configuration" class="tab-pane fade">
            {include file="steps/configuration.tpl"}
        </div>
        <div id="installation" class="tab-pane fade">
            {include file="steps/installation.tpl"}
        </div>
        <div id="setting" class="tab-pane fade">
            {include file="steps/setting.tpl"}
        </div>
        <div id="confirmation" class="tab-pane fade">
            {include file="steps/confirmation.tpl"}
        </div>
    </div>
{/block}