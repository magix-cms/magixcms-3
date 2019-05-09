<div class="text-center">
    <h1>{#welcome_to_magix#}</h1>
    <p>{#welcome_txt_1#}</p>
    <p>{#welcome_txt_2#}</p>
    <p>{#welcome_txt_3#}</p>
    <p>
        <a id="start" href="#analysis" class="btn btn-box btn-invert btn-success-theme">{#goto_analysis#}</a>
        <a href="#analysis" class="btn btn-box btn-invert btn-main-theme hide" data-toggle="tab">{#next#}</a>
    </p>
</div>
<p class="text-right">
    {if $lang !== 'fr'}<a href="{$url}/fr/{$install_folder}/" class="btn btn-xs btn-box btn-link">Français</a>{/if}
    {if $lang !== 'en'}<a href="{$url}/en/{$install_folder}/" class="btn btn-xs btn-box btn-link">English</a>{/if}
</p>