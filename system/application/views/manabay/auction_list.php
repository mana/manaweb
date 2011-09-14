<h3><?= T_('Next 10 auctions to finish')?></h3>

<? if (isset($next_auctions) && sizeof($next_auctions) == 0) { ?>
	<p><?= T_('Currently, there are no open auctions.')?></p>
<? } else { ?>

<table class="datatable">
    <tr>
        <th></th>
        <th><?= T_('Item')?></th>
        <th><?= T_('Seller')?></th>
        <th><?= T_('Endtime')?></th>
        <th><?= T_('Bids')?></th>
        <th><?= T_('Price')?></th>
    </tr>

    <? foreach ($next_auctions as $auction)
       {
			$duration = $auction->getDuration();
			$item = $auction->getItem();
	?>
    <tr>
        <td width="32"><img src="<?= $imageprovider->getItemImage($item->id, $item->image, $item->dyestring) ?>"></td>
        <td><a href="<?= site_url("manabay/show_by_id/" . $auction->getId() ) ?>">
        	<strong><?= $item->name ?></strong></a>
        	<br /><em><?= $item->description ?></em>
        </td>
        <td><?= $auction->getSeller()->getName(); ?></td>
        <td><?= date( T_('date_time_format_sec'), $auction->getEndtime()); ?><br />
        <?= sprintf( "%dd, %dh, %dm, %ds", $duration['d'], $duration['h'],
        	$duration['m'], $duration['s'] ); ?>
        </td>
        <td><?= $auction->getAuctionStats(Auction::AUCTION_BID_COUNT); ?></td>
        <td align="right"><?= $auction->getAuctionStats(Auction::AUCTION_MAX_PRICE); ?> gp</td>
    </tr>
    <? } ?>
</table>

<? } ?>

<h3><?= T_('Search auctions by category')?></h3>

<table style="border-width: 0px; margin-bottom: 0px;">
	<tr>
		<td valign="top" style="border-width: 0px; margin-bottom: 0px;">
			<strong><?= T_('Weapons')?></strong><br />
			<ul>
				<li><a href="<?= site_url(array("manabay", "show_by_category", Inventory::ITEM_TYPE_ONEHAND)) ?>"><?= T_('one handed')?></a>
				</li>
				<li><a href="<?= site_url(array("manabay", "show_by_category", Inventory::ITEM_TYPE_TWOHAND)) ?>"><?= T_('two handed')?></a>
				</li>
				<li><a href="<?= site_url(array("manabay", "show_by_category", Inventory::ITEM_TYPE_AMMO)) ?>"><?= T_('ammunition')?></a>
				</li>
				<li><a href="<?= site_url(array("manabay", "show_by_category", Inventory::ITEM_TYPE_SHIELD)) ?>"><?= T_('shields')?></a>
				</li>
			</ul>
		</td>
		<td valign="top" style="border-width: 0px; margin-bottom: 0px;">
			<strong><?= T_('Clothes')?></strong><br />
			<ul>
				<li><a href="<?= site_url(array("manabay", "show_by_category", Inventory::ITEM_TYPE_ARMS)) ?>"><?= T_('arms')?></a>
				</li>
				<li><a href="<?= site_url(array("manabay", "show_by_category", Inventory::ITEM_TYPE_FEET)) ?>"><?= T_('feet')?></a>
				</li>
				<li><a href="<?= site_url(array("manabay", "show_by_category", Inventory::ITEM_TYPE_HEAD)) ?>"><?= T_('head')?></a>
				</li>
				<li><a href="<?= site_url(array("manabay", "show_by_category", Inventory::ITEM_TYPE_LEGS)) ?>"><?= T_('legs')?></a>
				</li>
				<li><a href="<?= site_url(array("manabay", "show_by_category", Inventory::ITEM_TYPE_TORSO)) ?>"><?= T_('torso')?></a>
				</li>
			</ul>
		</td>
		<td valign="top" style="border-width: 0px; margin-bottom: 0px;">
			<strong><?= T_('Others')?></strong><br />
			<ul>
				<li><a href="<?= site_url(array("manabay", "show_by_category", Inventory::ITEM_TYPE_RING)) ?>"><?= T_('rings')?> </a>
				</li>
				<li><a href="<?= site_url(array("manabay", "show_by_category", Inventory::ITEM_TYPE_GENERIC)) ?>"><?= T_('generics')?> </a>
				</li>
				<li><a href="<?= site_url(array("manabay", "show_by_category", Inventory::ITEM_TYPE_USABLE)) ?>"><?= T_('usable')?> </a>
				</li>
			</ul>
		</td>
	</tr>
</table>


<h3>ManaBay statistics</h3>

