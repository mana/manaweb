<h3><?= T_('Server Statistics')?></h3>
<p>
<?
$format = T_('Currently there are %d individual Characters on %s , played by %d players.');
printf($format, $stats[Server_statistics::CHARACTER_COUNT], MANAWEB_GAMENAME, $stats[Server_statistics::PLAYER_COUNT]);
?>
<br/>
<?
$format = T_('The characters founded %d guilds.');
printf($format, $stats[Server_statistics::GUILD_COUNT]);
?>
<br/>
<?
$format = T_('All characters form a purchasing power of %d %s.');
printf($format, $stats[Server_statistics::ECONOMY_PURCHASE_POW], MANAWEB_GPNAME);
?>
</p>

<table style="border-width: 0px; margin-bottom: 0px;">
    <tr>
        <td style="border-width: 0px;">
            <span class="label"><?= T_('Number of players:')?></span>
        </td>
        <td style="border-width: 0px;">
            <span class="input">
                <?= $stats[Server_statistics::PLAYER_COUNT] ?>
            </span>
        </td>
        <td rowspan="4" align="right" style="border-width: 0px;">
            <img src="<?= base_url() ?>/data/gendergraph.png">
        </td>
    </tr>
    <tr>
        <td style="border-width: 0px;">
            <span class="label"><?= T_('Number of characters:')?> </span>
        </td>
        <td style="border-width: 0px;">
            <span class="input">
                <?= $stats[Server_statistics::CHARACTER_COUNT] ?>
            </span>
        </td>
    </tr>
    <tr>
        <td style="border-width: 0px;">
            <span class="label"><?= T_('Number of guilds:')?></span>
        </td>
        <td style="border-width: 0px;">
            <span class="input">
                <?= $stats[Server_statistics::GUILD_COUNT] ?>
            </span>
        </td>
    </tr>
    <tr>
        <td colspan="2" height="100" style="border-width: 0px;"></td>
    </tr>
</table>

<h3><?= T_('The top 10 Guilds')?></h3>
<? if ($stats[Server_statistics::GUILD_TOPTEN] === false ) { ?>
    <p><?= T_('Sorry, there are currently no guilds founded.')?></p>
<? } else { ?>
<table class="datatable">
    <tr>
        <th></th>
        <th><?= T_('Guild')?></th>
        <th><?= T_('Members')?></th>
    </tr>
    <?  $i = 1;
        foreach ($stats[Server_statistics::GUILD_TOPTEN] as $guild) { ?>
    <tr>
        <td width="10" align="right"><?= $i++ ?>.</td>
        <td><?= $guild->NAME ?></td>
        <td align="right"><?= $guild->MEMBERS ?></td>
    </tr>
    <? } ?>
</table>
<?php } ?>



<h3><?= T_('The top 10 Characters')?></h3>
<? if ($stats[Server_statistics::CHARACTER_TOPTEN] === false ) { ?>
    <p>Sorry, there are currently no characters alive.</p>
<? } else { ?>
<table class="datatable">
    <tr>
        <th></th>
        <th><?= T_('Character')?></th>
        <th><?= T_('Player')?></th>
        <th><?= T_('Level')?></th>
    </tr>
    <?  $i = 1;
        foreach ($stats[Server_statistics::CHARACTER_TOPTEN] as $char) { ?>
    <tr>
        <td width="10" align="right"><?= $i++ . "." ?></td>
        <td><?= $char->NAME ?></td>
        <td><?= $char->USERNAME ?></td>
        <td align="right"><?= $char->LEVEL ?></td>
    </tr>
    <?php } ?>
</table>
<?php } ?>
