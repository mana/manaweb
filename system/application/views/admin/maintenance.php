<h3>Cached data</h3>
<p>Tmwweb stores some temporary data like the provided maps in its own data
directory for fast and reliable access. If you made modifications to the
original data (maybe in tmwserv), it is necessary to have tmwweb to refresh its 
stored data.</p>

<? if (isset($action_result)) { ?>
<p style="border: 1px solid black; padding:10px;">
    <strong><?= $action_result ?></strong>
    <? if(isset($missing_item_images) && sizeof($missing_item_images) > 0) { ?>
    <br />
    <span style="color: red; font-weight: bold;">
    For the following items, the image could not be found. Please update the
    <tt>./images/items</tt> directory of your tmwweb installation.<br />
    <tt>
    <!--<ul>-->
        <? foreach ($missing_item_images as $img) { 
                echo $img . ", ";
           }
        ?>        
    <!--</ul>-->
    </tt>
    </span>
    <? } ?>
</p>
<? } ?>


<table style="border-width: 0px; margin-bottom: 0px;">
    <tr>
        <th>Filename</th>
        <th>Description</th>
        <th>local Version</th>
        <th>Actions</th>
    </tr>
    <tr>
        <td>  
            <span class="label">maps.xml</span>
        </td>
        <td>  
            The file <tt>maps.xml</tt> contains all maps provided by the map 
            server. Tmwweb uses this file to show descriptions of the 
            character locations.
        </td>
        <td>
            <span class="label"><?= date(lang('date_time_format'), 
                $maps_file_age); ?></span>            
        </td>
        <td>  
            <span class="label">
                <a href="<?= site_url('admin/maintenance/reload_maps.xml') ?>">
                <img src="<?= base_url() ?>images/view-refresh.png" 
                    style="vertical-align: middle"
                    title="Reload maps database"
                    border="0">
                </a>
                &nbsp;
            </span>    
        </td>
    </tr>    
    <tr>
        <td>  
            <span class="label">items.xml</span>
        </td>
        <td>  
            The file <tt>items.xml</tt> contains all known items of The Mana 
            World. Tmwweb stores all itmes redundant in the database for a 
            faster access.
        </td>
        <td></td>
        <td>  
            <span class="label">
                <a href="<?= site_url('admin/maintenance/reload_items.xml') ?>">
                <img src="<?= base_url() ?>images/view-refresh.png" 
                    style="vertical-align: middle"
                    title="Reload item database"
                    border="0">
                </a>
                &nbsp;
            </span>    
        </td>
    </tr>    
</table>
