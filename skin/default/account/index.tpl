{extends file="layout.tpl"}
{block name="title"}{seo_rewrite conf=['level'=>'root','type'=>'title','default'=>#seo_account_title#]}{/block}
{block name="description"}{seo_rewrite conf=['level'=>'root','type'=>'description','default'=>#seo_account_desc#]}{/block}
{block name='body:id'}private{/block}

{block name="article:content"}
    <header>
        <h1 class="text-center">{#my_account#|ucfirst}</h1>
    </header>

    <div class="row row-center">
        <div class="col-ph-12 col-sm-8 col-md-6 col-lg-4">
            <ul class="list-unstyled tab-list">
                <li><a href="{$smarty.server.REQUEST_URI}infos/" class="btn btn-default btn-box"><span class="fa fa-user"></span> {#global#}</a></li>
                <li><a href="{$smarty.server.REQUEST_URI}config/" class="btn btn-default btn-box"><span class="fa fa-cog"></span> {#account_config#}</a></li>
                <li><a href="{$smarty.server.REQUEST_URI}logout/" class="btn btn-default btn-box"><span class="fa fa-power-off"></span> {#logout#}</a></li>
            </ul>
        </div>
    </div>
{/block}