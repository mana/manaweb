
<h3><?=lang('guild_members')?></h3>
<table class="datatable">
    <tr>
        <th>Character</th>
        <th>Level</th>
        <th>Gender</th>
        <th>Map</th>
        <th>Player</th>
    </tr>
    <? foreach ($guild->getMembers() as $member) {
            $char = $member['character'];

            if ($char->getOwnerId() == $user->getId())
            {
                $isOwn = true;
            }
            else
            {
                $isOwn = false;
            }
        ?>
        <tr>
            <td>
                <? if ($isOwn) { ?><a href='<?= site_url(array('charcontroller',$char->getId() )) ?>'><? } ?>
                <?= $char->getName() ?>
                <? if ($isOwn) { ?></a><? } ?>

                <?= $char->isOnline('image') ?>
            </td>
            <td align="right"><?= $char->getLevel() ?></td>
            <td align="center"><?= $char->getGender('image') ?></td>
            <td><?= $char->getMap()->getDescription() ?></td>
            <td>
                <? if ($isOwn) { ?><a href='<?= site_url('myaccount') ?>'><? } ?>
                <?= $char->getUsername() ?>
                <? if ($isOwn) { ?></a><? } ?>
            </td>
        </tr>
    <? } ?>

</table>
