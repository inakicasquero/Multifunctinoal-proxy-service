<table id="table_1" class="mdl-data-table" cellspacing="0" width="100%">
    <thead>
        <thead>
            <tr>
                {foreach $table_config['total_column'] as $key => $value}
                    <th class="{$key}">{$value}</th>
                {/foreach}
            </tr>
        </thead>
    </thead>
    <tbody>
        <tr>
            {foreach $table_config['total_column'] as $key => $value}
                <th class="{$key}">{$value}</th>
            {/foreach}
        </tr>
    </tbody>
</table>