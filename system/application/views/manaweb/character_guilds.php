<h3><?= T_('character_guild_member') ?></h3>
<?php
    if ($char->isGuildMember())
    { // the char is member in at least one guild...
?>

<table class="datatable">
    <tr>
        <th><?=T_('guild_id')?></th>
        <th><?=T_('guild_name')?></th>
        <th><?=T_('guild_members')?></th>
    </tr>

    <? foreach ($char->getGuilds() as $guild) { ?>
    <tr>
        <td><?=$guild->getId() ?></td>
        <td>
            <a href="<?= site_url(array('guildcontroller', 'index', $guild->getId())) ?>">
                <?=$guild->getName() ?>
            </a>
        </td>
        <td style="text-align:right;"><?=$guild->countMembers() ?></td>
    </tr>
    <? } ?>
</table>

<?php
    }
    else
    {  // the char is not member in any guild...
?>
    <p>
        <?= T_('character_not_guild_member') ?>
    </p>
<?php
    }
?>
