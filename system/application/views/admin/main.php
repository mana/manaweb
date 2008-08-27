<h3>Quick search</h3>

<table style="border-width: 0px; margin-bottom: 0px;">

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
        <th>Level</th>
    </tr>
    <?php foreach ($result_account as $account) { ?>
    <tr>
        <td><?= $account->id ?></td>
        <td><?= $account->username ?></td>
        <td>
            <?= $this->user->getUserLevelString($account->level) ?>
            ( <?= $account->level ?> )
        </td>
    </tr>
    <?php }}} ?>
    
    <?php if (isset($result_character)) { ?>
    <tr>
        <th>ID</th>
        <th>Character</th>
        <th>Username</th>
        <th>Gender</th>
        <th>Level</th>
        <th>Money</th>
    </tr>
    <?php } ?>


</table>
<!-- end of search results ------------------------------------------------ -->
<?php } ?>
