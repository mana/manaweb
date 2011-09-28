<?php
    // create some local variables for faster access
    $weight_inv = $char->getInventory()->getWeight('inventory');
    $weight_equ = $char->getInventory()->getWeight('equipment');
    $weight_all = $weight_inv +  $weight_equ;
    $weight_max = $char->getMaximumWeight();
    $weight_pct = intval(100/$weight_max*$weight_all);

    // tiny checks
    if ($weight_pct < 0)
    {
        $weight_pct = 0;
    }
    if ($weight_pct > 100)
    {
        $weight_pct = 100;
    }
?>
<div style="text-align: right;">
    Your current load: <?= $weight_all ?> of <?= $weight_max ?>
</div>

<h3><?= T_('character_equipment') ?></h3>

<p><?= T_('Sorry, this feature is not yet supported.') ?></p>

<h3><?= T_('character_inventory') ?></h3>

<table class="datatable">
    <tr>
        <th colspan="2"><?= T_('Item') ?></th>
        <th><?= T_('Description') ?></th>
        <th><?= T_('Effect') ?></th>
        <th><?= T_('Amount') ?></th>
        <th><?= T_('Weight') ?></th>
    </tr>

    <? foreach ($char->getInventory()->getInventory() as $item) { ?>
    <tr>
        <td width="35"><img src="<?= $imageprovider->getItemImage($item->id, $item->image, $item->dyestring) ?>" /></td>
        <td><?= $item->name ?></td>
        <td><?= $item->description ?></td>
        <td><?= $item->effect ?></td>
        <td align="right"><?= $item->amount ?></td>
        <td align="right"><?= intval($item->weight) * intval($item->amount) ?></td>
    </tr>
    <? } ?>

    <tr>
        <td colspan="5" style="border: 0px;" align="right">
        </td>
        <td align="right" style="border-bottom: 0px; border-top: 1px dotted;">
            <strong><?= $weight_inv ?></strong>
        </td>
    </tr>

    <tr>
        <td colspan="5" style="border: 0px;" align="right">
            <em><?= T_('Weight of your equipped items:') ?></em>
        </td>
        <td align="right" style="border-bottom: 0px;">
            <strong>+ <?= $weight_equ ?></strong>
        </td>
    </tr>
    <tr>
        <td colspan="5" style="border: 0px;" align="right">
            <em><?= T_('Total weight of all your items:') ?></em>
        </td>
        <td align="right" style="border-bottom: 0px; border-top: 1px dotted;">
            <strong><?= $weight_all ?></strong>
        </td>
    </tr>
</table>
