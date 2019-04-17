{$open_days = array()}
{$open = ''}
{$close = ''}
{foreach $companyData.specifications as $day => $specific}
    {if $specific.open_day}
        {$open_days[] = $day}

        {if $open == '' || $specific.open_time < $open}
            {$open = $specific.open_time}
        {/if}

        {if $close == '' || $specific.close_time > $close}
            {$close = $specific.close_time}
        {/if}
    {/if}
{/foreach}
{$open_days = ','|implode:$open_days}
<table class="table">
    <thead>
    <tr>
        <th class="text-center" colspan="3"><i class="material-icons ico ico-schedule">{*access_time*}</i></th>
        {*<th>{#between#}</th>
        <th>{#and_between#}</th>*}
    </tr>
    </thead>
    <tbody>
    {foreach $companyData.specifications as $day => $specific}
        {$dayOfWeek = 'opening_'|cat:$day}
        {if $specific.open_day}
            <tr>
                <td>{#$dayOfWeek#}</td>
                {if $specific.noon_time}
                    <td>
                        <span>{$specific.open_time}</span> -
                        <span>{$specific.noon_start}</span>
                    </td>
                    <td>
                        <span>{$specific.noon_end}</span> -
                        <span>{$specific.close_time}</span>
                    </td>
                {else}
                    <td colspan="2">
                        <span>{$specific.open_time}</span> -
                        <span>{$specific.close_time}</span>
                    </td>
                    {*<td>
                        -
                    </td>*}
                {/if}
            </tr>
        {else}
            <tr>
                <td>{#$dayOfWeek#}</td>
                <td colspan="2"><strong>{if !empty($specific.close_txt[$lang])}{$specific.close_txt[$lang]}{else}{#closed#}{/if}</strong></td>
            </tr>
        {/if}
    {/foreach}
    </tbody>
</table>