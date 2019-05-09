<div class="text-center">
    {include file="brick/spinner.tpl" label="{#installation#}..."}
    <div id="database">
        <span class="fas fa-database fa-5x text-success"></span>
        <h2 class="h3">{#installation_success#}</h2>
        <p>{#Installation_txt#}</p>
        <p>
            <a href="#configuration" class="btn btn-box btn-invert btn-main-theme" data-toggle="tab">{#previous#}</a>
            <a id="goto_setting" href="#setting" class="btn btn-box btn-invert btn-success-theme disabled">{#goto_setting#}</a>
            <a href="#setting" class="btn btn-box btn-invert btn-main-theme hide" data-toggle="tab">{#next#}</a>
        </p>
    </div>
</div>