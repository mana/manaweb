<h3>Quick search</h3>

<table style="border-width: 0px; margin-bottom: 0px;">

    <?php if (isset($error)) { ?>
    <tr>
        <td colspan="3" style="border: 1px solid #660000; font-weight: bold;
            color: #660000;">
            An error occured: <br />
            <?= $error ?>
        </td>
    </tr>
    <? } ?>
    
    <? $attributes = array('name'=>'searchAccount', 'id'=>'TMWsearchAccount');
        echo form_open('admin/search_account', $attributes); ?>
    <tr>
        <td style="border-width: 0px;">  
            <label for="TMWusername">search Account: </label>
        </td>
        <td style="border-width: 0px;">  
            <input type="text" size="30" tabindex="1" value="" id="TMWusername" 
                title="Enter an account name" name="TMWusername">
        </td>
        <td style="border-width: 0px;">  
            <input type="submit" tabindex="2" value="Search" 
                id="TMWsubmit" title="Search" name="TMWsearch">
        </td>
    </tr>
    <?= form_close(); ?>
    
    <? $attributes = array('name'=>'searchCharacter', 'id'=>'TMWsearchCharacter');
        echo form_open('admin/search_character', $attributes); ?>
    <tr>
        <td style="border-width: 0px;">  
            <label for="TMWusername">search Character: </label>
        </td>
        <td style="border-width: 0px;">  
            <input type="text" size="30" tabindex="3" value="" id="TMWcharacter" 
                title="Enter a character name" name="TMWcharacter">
        </td>
        <td style="border-width: 0px;">  
            <input type="submit" tabindex="4" value="Search" 
                id="TMWsubmit" title="Search" name="TMWsearch">
        </td>
    </tr>
    <?= form_close(); ?>
    
</table>
<div class="autocomplete" id="TMWusernameList" style="display:none"></div>
<div class="autocomplete" id="TMWcharacterList" style="display:none"></div>
    

<?php if (isset($result_account) || isset($result_character)) { ?>

<!-- search results -->
<h3>Search result</h3>

<table class="datatable">

    <?php if (isset($result_account)) { 
              if ($result_account === false) {    
    ?>
    <tr>
        <th>Sorry, your search returns no rows.</th>
    </tr>
    <? } else { ?>
    <tr>
        <th>ID</th>
        <th>Username</th>
        <th>Groups</th>
    </tr>
    <?php foreach ($result_account as $account) { ?>
    <tr>
        <td><?= $account->id ?></td>
        <td><?= str_replace( 
            $searchstring, 
            '<span style="font-weight:bold; color:red;">'.$searchstring.'</span>', 
            $account->username) ?></td>
        <td><?
                foreach ($this->user->getUserLevelString($account->level) as $group )
                {
                    echo $group . ", ";
                }
            ?>
            ( <?= $account->level ?> )
        </td>
    </tr>
    <?php }}} ?>
    
    <?php if (isset($result_character)) { 
        if ($result_character === false) {  ?>
    <tr>
        <th>Sorry, your search returns no rows.</th>
    </tr>
    <? } else { ?>        
    <tr>
        <th>ID</th>
        <th>Character</th>
        <th>Username</th>
        <th>Gender</th>
        <th>Level</th>
        <th>Money</th>
        <th>Map</th>
    </tr>
    <?php foreach ($result_character as $char) { ?>
    <tr>
        <td align="right"><a href="<?= site_url(array("admin/show_character", $char->getId() )) ?>"><?= $char->getId() ?></a></td>
        <td><a href="<?= site_url(array("admin/show_character", $char->getId() )) ?>">
            <?= str_replace(
            $searchstring, 
            '<span style="font-weight:bold; color:red;">'.$searchstring.'</span>', 
            $char->getName()) ?></a></td>
        <td><?= $char->getUsername() ?></td>
        <td align="center"><?= $char->getGender('image') ?></td>
        <td align="right"><?= $char->getLevel() ?></td>
        <td align="right"><?= $char->getMoney('string') ?></td>
        <td align="right"><?= $char->getMap()->getDescription() ?></td>
    </tr>
    <?php }}} ?>


</table>
<!-- end of search results ------------------------------------------------ -->
<?php } ?>

<script type="text/javascript">
    new Ajax.Autocompleter('TMWusername', 'TMWusernameList', 
        '<?php echo site_url() . "/admin/search_account_ajax" ?>', { });
    new Ajax.Autocompleter('TMWcharacter', 'TMWcharacterList',
        '<?php echo site_url() . "/admin/search_character_ajax" ?>', { });
</script>
