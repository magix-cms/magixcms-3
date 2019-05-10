{if $install_detected}
    <div class="alert alert-warning">
        <h3 class="text-center"><i class="fas fa-exclamation-triangle"></i>&nbsp;{#welcome_warning#}&nbsp;<i class="fas fa-exclamation-triangle"></i></h3>
        <p class="text-left">{#welcome_warning_txt_1#}</p>
        <p class="text-left">{#welcome_warning_txt_2#}</p>
    </div>
{/if}
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
<ul class="link-bar">
    {if $lang !== 'fr'}<li><a href="{$url}/fr/{$install_folder}/" class="btn btn-xs btn-box btn-link">Français</a></li>{/if}
    {if $lang !== 'en'}<li><a href="{$url}/en/{$install_folder}/" class="btn btn-xs btn-box btn-link">English</a></li>{/if}
</ul>