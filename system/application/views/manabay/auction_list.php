<h3>Next 10 auctions to finish</h3>

<? if (isset($next_auctions) && sizeof($next_auctions) == 0) { ?>
	<p>Currently, there are no open auctions.</p>
<? } else { ?>

<table class="datatable">
    <tr>
        <th></th>
        <th>Item</th>
        <th>Seller</th>
        <th>Endtime</th>
        <th>Bids</th>
        <th>Price</th>
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
        <td><?= date( lang('date_time_format_sec'), $auction->getEndtime()); ?><br />
        <?= sprintf( "%dd, %dh, %dm, %ds", $duration['d'], $duration['h'],
        	$duration['m'], $duration['s'] ); ?>
        </td>
        <td><?= $auction->getAuctionStats(Auction::AUCTION_BID_COUNT); ?></td>
        <td align="right"><?= $auction->getAuctionStats(Auction::AUCTION_MAX_PRICE); ?> gp</td>
    </tr>
    <? } ?>
</table>

<? } ?>

<h3>Search auctions by category</h3>

<table style="border-width: 0px; margin-bottom: 0px;">
	<tr>
		<td valign="top" style="border-width: 0px; margin-bottom: 0px;">
			<strong>Weapons</strong><br />
			<ul>
				<li><a href="<?= site_url(array("manabay", "show_by_category", Inventory::ITEM_TYPE_ONEHAND)) ?>">one handed</a>
				</li>
				<li><a href="<?= site_url(array("manabay", "show_by_category", Inventory::ITEM_TYPE_TWOHAND)) ?>">two handed </a>
				</li>
				<li><a href="<?= site_url(array("manabay", "show_by_category", Inventory::ITEM_TYPE_AMMO)) ?>">ammunition </a>
				</li>
				<li><a href="<?= site_url(array("manabay", "show_by_category", Inventory::ITEM_TYPE_SHIELD)) ?>">shields </a>
				</li>
			</ul>
		</td>
		<td valign="top" style="border-width: 0px; margin-bottom: 0px;">
			<strong>Clothes</strong><br />
			<ul>
				<li><a href="<?= site_url(array("manabay", "show_by_category", Inventory::ITEM_TYPE_ARMS)) ?>">arms </a>
				</li>
				<li><a href="<?= site_url(array("manabay", "show_by_category", Inventory::ITEM_TYPE_FEET)) ?>">feet </a>
				</li>
				<li><a href="<?= site_url(array("manabay", "show_by_category", Inventory::ITEM_TYPE_HEAD)) ?>">head </a>
				</li>
				<li><a href="<?= site_url(array("manabay", "show_by_category", Inventory::ITEM_TYPE_LEGS)) ?>">legs </a>
				</li>
				<li><a href="<?= site_url(array("manabay", "show_by_category", Inventory::ITEM_TYPE_TORSO)) ?>">torso </a>
				</li>
			</ul>
		</td>
		<td valign="top" style="border-width: 0px; margin-bottom: 0px;">
			<strong>Others</strong><br />
			<ul>
				<li><a href="<?= site_url(array("manabay", "show_by_category", Inventory::ITEM_TYPE_RING)) ?>">rings </a>
				</li>
				<li><a href="<?= site_url(array("manabay", "show_by_category", Inventory::ITEM_TYPE_GENERIC)) ?>">generics </a>
				</li>
				<li><a href="<?= site_url(array("manabay", "show_by_category", Inventory::ITEM_TYPE_USABLE)) ?>">usable </a>
				</li>
			</ul>
		</td>
	</tr>
</table>


<h3>ManaBay statistics</h3>

