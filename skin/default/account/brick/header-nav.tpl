<div id="menu-user">
    <div class="dropdown" role="menu">
        <a class="{*btn btn-flat btn-main-theme *}dropdown-toggle {if $smarty.session.idaccount && $smarty.session.keyuniqid_ac}user-logged{/if}" type="button" data-toggle="dropdown"  aria-haspopup="true" aria-expanded="true" role="button">
            <span class="fa fa-user"></span>{*{if $smarty.session.idaccount && $smarty.session.keyuniqid_ac}{widget_account_data} {$dataAccount.pseudo}{else}<span class="hidden-xs">{#member_label#|ucfirst}</span>{/if}*}
        </a>
        <ul id="nav-user" class="dropdown-menu" aria-labelledby="menu-user" role="menu">
            {if $smarty.session.idaccount && $smarty.session.keyuniqid_ac}
                {widget_account_data}
                <li role="menuitem">
                    <a href="{$hashurl}" title="{#view_account_title#|ucfirst}" role="link"><span class="fa fa-user"></span> {#view_account_label#|ucfirst}</a>
                </li>
                <li role="menuitem">
                    <a href="{$hashurl}{#logout_account_url#}" title="{#logout_account_title#|ucfirst}" role="link"><span class="fa fa-power-off"></span> {#logout_account_label#|ucfirst}</a>
                </li>
            {else}
                <li role="menuitem">
                    <a href="{geturl}/{getlang}/{#connect_account_url#}" title="{#connect_account_title#|ucfirst}" role="link"><span class="fa fa-sign-in"></span> {#connect_account_label#|ucfirst}</a>
                </li>
                <li role="menuitem">
                    <a href="{geturl}/{getlang}/{#create_account_url#}" title="{#create_account_title#|ucfirst}" role="link"><span class="fa fa-user"></span> {#create_account_label#|ucfirst}</a>
                </li>
            {/if}
        </ul>
    </div>
</div>