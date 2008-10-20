<h3>Item dictionary</h3>
	<table style="border-width: 0px; margin-bottom: 0px;">
		<tr>
			<td valign="top" style="border-width: 0px; margin-bottom: 0px;">
				<strong>Weapons</strong><br />
				<ul>
					<li><a href="<?= site_url(array("itemslist", "show", Inventory::ITEM_TYPE_ONEHAND)) ?>">one handed</a> 
					(<?= $ctrl->getItemsPerCat( Inventory::ITEM_TYPE_ONEHAND ) ?>)</li>
					<li><a href="<?= site_url(array("itemslist", "show", Inventory::ITEM_TYPE_TWOHAND)) ?>">two handed </a> 
					(<?= $ctrl->getItemsPerCat( Inventory::ITEM_TYPE_TWOHAND ) ?>)</li>
					<li><a href="<?= site_url(array("itemslist", "show", Inventory::ITEM_TYPE_AMMO)) ?>">ammunition </a> 
					(<?= $ctrl->getItemsPerCat( Inventory::ITEM_TYPE_AMMO ) ?>)</li>
					<li><a href="<?= site_url(array("itemslist", "show", Inventory::ITEM_TYPE_SHIELD)) ?>">shields </a> 
					(<?= $ctrl->getItemsPerCat( Inventory::ITEM_TYPE_SHIELD ) ?>)</li>
				</ul>
			</td>
			<td valign="top" style="border-width: 0px; margin-bottom: 0px;">
				<strong>Clothes</strong><br />
				<ul>
					<li><a href="<?= site_url(array("itemslist", "show", Inventory::ITEM_TYPE_ARMS)) ?>">arms </a> 
					(<?= $ctrl->getItemsPerCat( Inventory::ITEM_TYPE_ARMS ) ?>)</li>
					<li><a href="<?= site_url(array("itemslist", "show", Inventory::ITEM_TYPE_FEET)) ?>">feet </a> 
					(<?= $ctrl->getItemsPerCat( Inventory::ITEM_TYPE_FEET ) ?>)</li>
					<li><a href="<?= site_url(array("itemslist", "show", Inventory::ITEM_TYPE_HEAD)) ?>">head </a> 
					(<?= $ctrl->getItemsPerCat( Inventory::ITEM_TYPE_HEAD ) ?>)</li>
					<li><a href="<?= site_url(array("itemslist", "show", Inventory::ITEM_TYPE_LEGS)) ?>">legs </a> 
					(<?= $ctrl->getItemsPerCat( Inventory::ITEM_TYPE_LEGS ) ?>)</li>
					<li><a href="<?= site_url(array("itemslist", "show", Inventory::ITEM_TYPE_TORSO)) ?>">torso </a> 
					(<?= $ctrl->getItemsPerCat( Inventory::ITEM_TYPE_TORSO ) ?>)</li>
				</ul>					
			</td>
			<td valign="top" style="border-width: 0px; margin-bottom: 0px;">
				<strong>Others</strong><br />
				<ul>
					<li><a href="<?= site_url(array("itemslist", "show", Inventory::ITEM_TYPE_RING)) ?>">rings </a> 
					(<?= $ctrl->getItemsPerCat( Inventory::ITEM_TYPE_RING ) ?>)</li>
					<li><a href="<?= site_url(array("itemslist", "show", Inventory::ITEM_TYPE_GENERIC)) ?>">generics </a> 
					(<?= $ctrl->getItemsPerCat( Inventory::ITEM_TYPE_GENERIC ) ?>)</li>
					<li><a href="<?= site_url(array("itemslist", "show", Inventory::ITEM_TYPE_USABLE)) ?>">usable </a> 
					(<?= $ctrl->getItemsPerCat( Inventory::ITEM_TYPE_USABLE ) ?>)</li>
				</ul>
			</td>
		</tr>
	</table>

<? if (isset($itemslist)) { ?>	
<table class="datatable">
    <tr>
        <th colspan="2">Name</th>
        <th>Weight</th>
        <th>Effect</th>
    </tr>
    
    <? foreach ($itemslist as $item) { ?>
    <tr>
        <td><img src="<?= $imageprovider->getItemImage($item->id, $item->image, $item->dyestring) ?>"></td>
        <td>
        	<strong><?= $item->name ?></strong><br />
        	<em><?= $item->description ?></em>
        </td>
        <td align="right"><?= $item->weight ?></td>
        <td align="right"><?= $item->effect ?></td>
    </tr>
    <? } ?>
</table>
<? } ?>
