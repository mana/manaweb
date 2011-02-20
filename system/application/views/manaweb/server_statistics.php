<h3>Server Statistics</h3>
<p>
Currently there are <?= $stats[Server_statistics::CHARACTER_COUNT] ?>
individual Characters on <?= MANAWEB_GAMENAME ?>, played by
<?= $stats[Server_statistics::PLAYER_COUNT] ?> players.
The characters founded
<?= $stats[Server_statistics::GUILD_COUNT] ?> guilds.<br />
All characters form a purchasing power of
<?=
  number_format($stats[Server_statistics::ECONOMY_PURCHASE_POW], 0, ".", ",");
?> <?= MANAWEB_GPNAME ?>.
</p>

<table style="border-width: 0px; margin-bottom: 0px;">
    <tr>
        <td style="border-width: 0px;">
            <span class="label">Number of players: </span>
        </td>
        <td style="border-width: 0px;">
            <span class="input">
                <?= $stats[Server_statistics::PLAYER_COUNT] ?>
            </span>
        </td>
        <td rowspan="4" align="right" style="border-width: 0px;">
            <img src="<?= base_url() ?>/data/testchar2.png">
        </td>
    </tr>
    <tr>
        <td style="border-width: 0px;">
            <span class="label">Number of characters: </span>
        </td>
        <td style="border-width: 0px;">
            <span class="input">
                <?= $stats[Server_statistics::CHARACTER_COUNT] ?>
            </span>
        </td>
    </tr>
    <tr>
        <td style="border-width: 0px;">
            <span class="label">Number of guilds: </span>
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

<h3>The top 10 Guilds</h3>
<? if ($stats[Server_statistics::GUILD_TOPTEN] === false ) { ?>
    <p>Sorry, there are currently no guilds founded.</p>
<? } else { ?>
<table class="datatable">
    <tr>
        <th></th>
        <th>Guild</th>
        <th>Members</th>
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



<h3>The top 10 Characters</h3>
<? if ($stats[Server_statistics::CHARACTER_TOPTEN] === false ) { ?>
    <p>Sorry, there are currently no characters alive.</p>
<? } else { ?>
<table class="datatable">
    <tr>
        <th></th>
        <th>Character</th>
        <th>Player</th>
        <th>Level</th>
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
