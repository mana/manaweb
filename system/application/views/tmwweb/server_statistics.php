<h3>Server Statistics</h3>
<p>
Currently there are <?= $stats[Server_statistics::CHARACTER_COUNT] ?> 
individual Characters on the Mana World, played by 
<?= $stats[Server_statistics::PLAYER_COUNT] ?> players. The characters founded
<?= $stats[Server_statistics::GUILD_COUNT] ?> guilds.
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
</table>

<h3>The top 10 Guilds on the mana world</h3>

<table class="datatable">
    <tr>
        <th></th>
        <th>Guild</th>
        <th>Founder</th>
        <th>Members</th>
    </tr>
<?php for ($i=1; $i<=10; $i++)
{ ?>
    <tr>
        <td width="10" align="right"><?= $i . "." ?></td>
        <td>Guildname</td>
        <td>Charactername</td>
        <td align="right"><?= 100 - $i ?></td>
    </tr>
<?php } ?>    
</table>

<h3>The top 10 Characters on the mana world</h3>

<table class="datatable">
    <tr>
        <th></th>
        <th>Character</th>
        <th>Player</th>
        <th>Level</th>
    </tr>
<?php for ($i=1; $i<=10; $i++)
{ ?>
    <tr>
        <td width="10" align="right"><?= $i . "." ?></td>
        <td>Character</td>
        <td>Player</td>
        <td align="right"><?= 100 - $i ?></td>
    </tr>
<?php } ?>    
</table>
