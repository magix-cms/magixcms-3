{if !isset($activation)}
    {$activation = false}
{/if}
{if !isset($search)}
    {$search = true}
{/if}
{if !isset($readonly)}
    {$readonly = []}
{/if}
{if !isset($sortable)}
    {$sortable = false}
{/if}
{if isset($data) && is_array($data)}
    {*<pre>{$data[0]|print_r}</pre>*}
    {strip}
        {$table['rows'] = $data}
        {foreach $data[0] as $k => $v}
            {if $k != 'keyuniqid_admin'}
                {$col = []}

                {$pre = strstr($k, '_', true)}
                {$col['title'] = $pre}

                {if $pre == 'id'}
                    {if !isset($table['section'])}
                        {$table['section'] = $k}
                        {$col['class'] = 'fixed-td text-center'}
                    {else}
                        {$col['title'] = substr($k,strpos($k,'_')+1)}
                    {/if}
                {/if}

                {if $pre == 'type'}
                    {$typeof = substr($k,strpos($k,'_')+1)}
                    {if $typeof == 'funeral'}
                        {$col['enum'] = ''}
                        {$col['type'] = 'enum'}
                        {$col['input'] = ['type' => 'select','values' => [
                        ['v' => 'interment', 'name' => {#interment#}],
                        ['v' => 'cremation', 'name' => {#cremation#}]]
                        ]}
                    {/if}
                {/if}

                {if $pre == 'email'}
                    {$col['class'] = 'th-35'}
                {/if}

                {if $pre == 'role'}
                    {$col['class'] = 'th-15'}
                {/if}

                {if $pre == 'content'}
                    {$col['type'] = 'content'}
                    {$col['title'] = $k}
                {/if}

                {if $pre == 'price'}
                    {$col['type'] = 'price'}
                {/if}

                {if $pre == 'nbr'}
                    {$col['title'] = $k}
                {/if}

                {if $pre == 'default'}
                    {$col['title'] = $k}
                    {$col['enum'] = 'bin_'}
                    {$col['type'] = 'bin'}
                    {$col['input'] = ['type' => 'select', 'values' => [['v' => 1, 'name' => 'Oui'],['v' => 0, 'name' => 'Non']]]}
                {/if}

                {if $pre == 'title'}
                    {$col['class'] = 'fixed-td-md'}
                    {$col['enum'] = 'title_'}
                    {$col['type'] = 'enum'}
                    {$col['input'] = ['type' => 'select','values' => [['v' => 'm', 'name' => {#title_m#}],['v' => 'w', 'name' => {#title_w#}]]]}
                {/if}

                {if $pre == 'name'}
                    {$col['title'] = $k}
                {/if}

                {if $pre == 'parent'}
                    {$col['title'] = $k}
                {/if}

                {if $pre == 'iso'}
                    {$col['title'] = $k}
                {/if}

                {if $pre == 'url'}
                    {$col['title'] = $k}
                {/if}

                {if $pre == 'status'}
                    {$col['enum'] = 'status_'}
                    {$col['type'] = 'enum'}
                    {$col['input'] = ['type' => 'select','values' => [
                    ['v' => '0', 'name' => {#status_0#}],
                    ['v' => '1', 'name' => {#status_1#}]]
                    ]}
                {/if}

                {if $pre == 'module'}
                    {$col['enum'] = 'module_'}
                    {$col['type'] = 'enum'}
                    {$col['input'] = ['type' => 'select','values' => [
                    ['v' => 'pages', 'name' => {#module_0#}],
                    ['v' => 'news', 'name' => {#module_1#}],
                    ['v' => 'catalog', 'name' => {#module_2#}],
                    ['v' => 'plugins', 'name' => {#module_3#}]]
                    ]}
                {/if}

                {if $pre == 'active'}
                    {$col['class'] = 'fixed-td-md text-center'}
                    {$col['enum'] = 'bin_'}
                    {$col['type'] = 'bin'}
                    {$col['input'] = ['type' => 'select', 'values' => [['v' => 1, 'name' => 'Oui'],['v' => 0, 'name' => 'Non']]]}
                {/if}

                {if $pre == 'menu'}
                    {$col['class'] = 'fixed-td-md text-center'}
                    {$col['enum'] = 'bin_'}
                    {$col['type'] = 'bin'}
                    {$col['input'] = ['type' => 'select', 'values' => [['v' => 1, 'name' => 'Oui'],['v' => 0, 'name' => 'Non']]]}
                {/if}

                {if $pre == 'published'}
                    {$col['class'] = 'fixed-td-md text-center'}
                    {$col['enum'] = 'bin_'}
                    {$col['type'] = 'bin'}
                    {$col['input'] = ['type' => 'select', 'values' => [['v' => 1, 'name' => 'Oui'],['v' => 0, 'name' => 'Non']]]}
                {/if}

                {if $pre == 'preplan'}
                    {$col['class'] = 'fixed-td-md text-center'}
                    {$col['enum'] = 'bin_'}
                    {$col['type'] = 'bin'}
                    {$col['input'] = ['type' => 'select', 'values' => [['v' => 1, 'name' => 'Oui'],['v' => 0, 'name' => 'Non']]]}
                {/if}

                {if $pre == 'opt'}
                    {$col['enum'] = 'bin_'}
                    {$col['type'] = 'bin'}
                {/if}

                {if $pre == 'firstname' || $pre == 'lastname' || $pre == 'phone' || $pre == 'width' || $pre == 'height' || $pre == 'attribute' || $pre == 'resize'}
                    {$col['class'] = 'th-25'}
                {/if}

                {$table['cols'][$k] = $col}
            {/if}
        {/foreach}
    {/strip}
    <div class="table-responsive{if empty($data) || !count($data)} hide{/if}" id="table-{if $subcontroller}{$subcontroller}{else}{$controller}{/if}">
        <form action="#" method="get">
            <input type="hidden" name="controller" value="{$smarty.get.controller}" />
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th class="fixed-td-sm">&nbsp;</th>
                    {foreach $table.cols as $name => $col}
                        <th class="{$col.class}">{#$col['title']#|ucfirst}</th>
                    {/foreach}
                    <th class="fixed-td-lg text-center">{#actions#|ucfirst}</th>
                </tr>
                {if $search}
                <tr class="search-bar">
                    <th class="text-center">
                        <div class="checkbox">
                            <label for="check-all">
                                <input type="checkbox" id="check-all" name="check-all" class="check-all" data-table="{if $subcontroller}{$subcontroller}{else}{$controller}{/if}"/>
                            </label>
                        </div>
                    </th>
                    {foreach $table.cols as $name => $col}
                    <th>
                        <div class="form-group">
                            <label for="search[{$name}]" class="sr-only"></label>
                            {if $col.input.type == 'select'}
                                <select name="search[{$name}]" id="search[{$name}]" class="form-control" >
                                    <option value="" selected>--</option>
                                    {foreach $col.input.values as $val}
                                        <option value="{$val.v}"{if isset($smarty.get.search[$name]) && $smarty.get.search[$name] =='{$val.v}'} selected{/if}>{$val.name}</option>
                                    {/foreach}
                                </select>
                            {else}
                                <input type="text"
                                       id="search[{$name}]"
                                       name="search[{$name}]"
                                       class="form-control"
                                       {if isset($smarty.get.search[$name])}value="{$smarty.get.search[$name]}" {/if}/>
                            {/if}
                        </div>
                    </th>
                    {/foreach}
                    <th>
                        <div class="form-group">
                            <button type="submit" id="search" class="form-control">
                                <span class="fa fa-search"></span> {#search#|ucfirst}
                            </button>
                        </div>
                    </th>
                </tr>
                {/if}
                </thead>
                <tbody{if $sortable} class="ui-sortable"{/if}>
                {foreach $table.rows as $row}
                    {include file="section/form/loop/rows.tpl" data=$row section=$table['section'] controller=$controller subcontroller=$subcontroller readonly=$readonly}
                {/foreach}
                </tbody>
            </table>
            <div class="hidden-xs">
                <p>
                    <span class="fa fa-reply fa-rotate-180"></span>
                    <button class="btn btn-link update-checkbox" id="check-all" value="check-all" data-table="{$controller}">
                        <span class="fa fa-check-square"></span> <span class="hidden-sm hidden-md">{#check_all#|ucfirst}</span>
                    </button>
                    <button class="btn btn-link update-checkbox" id="uncheck-all" value="uncheck-all" data-table="{$controller}">
                        <span class="fa fa-square-o"></span> <span class="hidden-sm hidden-md">{#uncheck_all#|ucfirst}</span>
                    </button>
                    {if $activation}
                        &mdash;
                    <button class="btn btn-link" type="submit" name="action" value="active-selected">
                        <span class="fa fa-power-off text-success"></span> <span class="hidden-sm hidden-md">{#active_selected#|ucfirst}</span>
                    </button>
                    <button class="btn btn-link" type="submit" name="action" value="unactive-selected">
                        <span class="fa fa-power-off text-danger"></span> <span class="hidden-sm hidden-md">{#unactive_selected#|ucfirst}</span>
                    </button>
                    {/if}
                    {if {employee_access type="del" class_name=$cClass} eq 1}
                        &mdash;
                    <button class="btn btn-link modal_action" data-target="#delete_modal" data-controller="{$controller}"{if $subcontroller} data-sub="{$subcontroller}"{/if}>
                        <span class="fa fa-trash"></span> <span class="hidden-sm hidden-md">{#delete_selected#|ucfirst}</span>
                    </button>
                    {/if}
                </p>
            </div>
            <div class="dropup visible-xs">
                <button class="btn btn-default dropdown-toggle" type="button" id="dropdownMenu2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {#recursive_actions#|ucfirst}
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenu2">
                    <li>
                        <button class="btn btn-link update-checkbox" type="submit" value="check-all" data-table="{$controller}">
                            <span class="fa fa-check-square"></span> <span>{#check_all#|ucfirst}</span>
                        </button>
                    </li>
                    <li>
                        <button class="btn btn-link update-checkbox" type="submit" value="uncheck-all" data-table="{$controller}">
                            <span class="fa fa-square-o"></span> <span>{#uncheck_all#|ucfirst}</span>
                        </button>
                    </li>
                    <li role="separator" class="divider"></li>
                    {if $activation}
                    <li>
                        <button class="btn btn-link" type="submit" name="action" value="active-selected">
                            <span class="fa fa-power-off text-success"></span> <span>{#active_selected#|ucfirst}</span>
                        </button>
                    </li>
                    <li>
                        <button class="btn btn-link" type="submit" name="action" value="unactive-selected">
                            <span class="fa fa-power-off text-danger"></span> <span>{#unactive_selected#|ucfirst}</span>
                        </button>
                    </li>
                    <li role="separator" class="divider"></li>
                    {/if}
                    {if {employee_access type="del" class_name=$cClass} eq 1}
                    <li>
                        <button class="btn btn-link modal_action" data-target="#delete_modal" data-controller="{$controller}"{if $subcontroller} data-sub="{$subcontroller}"{/if}>
                            <span class="fa fa-trash"></span> <span>{#delete_selected#|ucfirst}</span>
                        </button>
                    </li>
                    {/if}
                </ul>
            </div>
        </form>
    </div>
    <p class="no-entry alert alert-warning{if !empty($data) || count($data)} hide{/if}">
        <span class="fa fa-info"></span> {#no_entry#|ucfirst}
    </p>
{/if}