<h3><?= T_('Item dictionary')?></h3>

	<? $attributes = array('name'=>'ManasearchItemForm', 'id'=>'ManasearchItemForm');
    echo form_open('itemslist/search_item', $attributes); ?>

	<table style="border-width: 0px; margin-bottom: 0px;">
		<tr>
			<td colspan="3"><?= T_('You can search an item by its name...')?> </td>
		</tr>

	    <?php if (isset($result_items)) {
              if ($result_items === false) { ?>
	    <tr>
	        <td colspan="3" style="border: 1px solid #660000; font-weight: bold;
	            color: #660000;">
	            <?= T_('Sorry, your search returns no rows.')?>
	        </td>
	    </tr>
	    <? }} ?>
		<tr>
			<td colspan="3" style="border-width: 0px; margin-bottom: 0px;">
				<table style="border-width: 0px; margin-bottom: 0px;">
					<tr>
				        <td style="border-width: 0px;">
				            <input type="text" size="30" tabindex="1" value="" id="ManaSearchItem"
				                title="Enter a item name" name="ManaSearchItem" />
				        </td>
				        <td style="border-width: 0px;">
				            <input type="submit" tabindex="2" value="Search"
				                id="Manasubmit" title="Search" name="Manasearch" />
				        </td>
				    </tr>
				</table>
			</td>
		</tr>

		<tr>
			<td colspan="3"><?= T_('... or by its category')?>: </td>
		</tr>
		<tr>
			<td valign="top" style="border-width: 0px; margin-bottom: 0px;">
				<strong><?= T_('Weapons')?></strong><br />
				<ul>
					<li><a href="<?= site_url(array("itemslist", "show", Inventory::ITEM_TYPE_ONEHAND)) ?>"><?= T_('one handed')?></a>
					(<?= $ctrl->getItemsPerCat( Inventory::ITEM_TYPE_ONEHAND ) ?>)</li>
					<li><a href="<?= site_url(array("itemslist", "show", Inventory::ITEM_TYPE_TWOHAND)) ?>"><?= T_('two handed')?></a>
					(<?= $ctrl->getItemsPerCat( Inventory::ITEM_TYPE_TWOHAND ) ?>)</li>
					<li><a href="<?= site_url(array("itemslist", "show", Inventory::ITEM_TYPE_AMMO)) ?>"><?= T_('ammunition')?></a>
					(<?= $ctrl->getItemsPerCat( Inventory::ITEM_TYPE_AMMO ) ?>)</li>
					<li><a href="<?= site_url(array("itemslist", "show", Inventory::ITEM_TYPE_SHIELD)) ?>"><?= T_('shields')?></a>
					(<?= $ctrl->getItemsPerCat( Inventory::ITEM_TYPE_SHIELD ) ?>)</li>
				</ul>
			</td>
			<td valign="top" style="border-width: 0px; margin-bottom: 0px;">
				<strong><?= T_('Clothes')?></strong><br />
				<ul>
					<li><a href="<?= site_url(array("itemslist", "show", Inventory::ITEM_TYPE_ARMS)) ?>"><?= T_('arms')?></a>
					(<?= $ctrl->getItemsPerCat( Inventory::ITEM_TYPE_ARMS ) ?>)</li>
					<li><a href="<?= site_url(array("itemslist", "show", Inventory::ITEM_TYPE_FEET)) ?>"><?= T_('feet')?></a>
					(<?= $ctrl->getItemsPerCat( Inventory::ITEM_TYPE_FEET ) ?>)</li>
					<li><a href="<?= site_url(array("itemslist", "show", Inventory::ITEM_TYPE_HEAD)) ?>"><?= T_('head')?></a>
					(<?= $ctrl->getItemsPerCat( Inventory::ITEM_TYPE_HEAD ) ?>)</li>
					<li><a href="<?= site_url(array("itemslist", "show", Inventory::ITEM_TYPE_LEGS)) ?>"><?= T_('legs')?></a>
					(<?= $ctrl->getItemsPerCat( Inventory::ITEM_TYPE_LEGS ) ?>)</li>
					<li><a href="<?= site_url(array("itemslist", "show", Inventory::ITEM_TYPE_TORSO)) ?>"><?= T_('torso')?></a>
					(<?= $ctrl->getItemsPerCat( Inventory::ITEM_TYPE_TORSO ) ?>)</li>
				</ul>
			</td>
			<td valign="top" style="border-width: 0px; margin-bottom: 0px;">
				<strong><?= T_('Others')?></strong><br />
				<ul>
					<li><a href="<?= site_url(array("itemslist", "show", Inventory::ITEM_TYPE_RING)) ?>"><?= T_('rings')?> </a>
					(<?= $ctrl->getItemsPerCat( Inventory::ITEM_TYPE_RING ) ?>)</li>
					<li><a href="<?= site_url(array("itemslist", "show", Inventory::ITEM_TYPE_GENERIC)) ?>"><?= T_('generics')?></a>
					(<?= $ctrl->getItemsPerCat( Inventory::ITEM_TYPE_GENERIC ) ?>)</li>
					<li><a href="<?= site_url(array("itemslist", "show", Inventory::ITEM_TYPE_USABLE)) ?>"><?= T_('usable')?></a>
					(<?= $ctrl->getItemsPerCat( Inventory::ITEM_TYPE_USABLE ) ?>)</li>
				</ul>
			</td>
		</tr>
	</table>
	<?= form_close(); ?>
    <div class="autocomplete" id="ManaSearchItemList" style="display:none"></div>

<? if (isset($itemslist)) { ?>
<table class="datatable">
    <tr>
        <th colspan="2"><?= T_('Name') ?></th>
        <th><?= T_('Weight') ?></th>
        <th><?= T_('Effect') ?></th>
    </tr>

    <? foreach ($itemslist as $item) { ?>
    <tr>
        <td><img src="<?= $imageprovider->getItemImage($item->id, $item->image, $item->dyestring) ?>" /></td>
        <td>
        	<strong><?
        	if (isset($result_items))
        	{
	        	echo str_replace($searchstring,
	        		'<span style="font-weight:bold; color:red;">'.$searchstring.'</span>',
            		$item->name);
        	}
        	else
        	{
        		echo $item->name;
    		} ?></strong><br />
        	<em><?= $item->description ?></em>
        </td>
        <td align="right"><?= $item->weight ?></td>
        <td align="right"><?= $item->effect ?></td>
    </tr>
    <? } ?>
</table>
<? } ?>


<script type="text/javascript">
<!--
    new Ajax.Autocompleter('ManaSearchItem', 'ManaSearchItemList',
        '<?php echo site_url() . "/itemslist/search_item_ajax" ?>',
        {minChars:3});

    // set the focus to the search field
    $('ManaSearchItem').focus();
//-->
</script>
