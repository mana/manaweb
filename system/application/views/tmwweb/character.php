<h3><?= lang('character_statistics') ?></h3>
<table style="border-width: 0px; margin-bottom: 0px;">
    <tr>
        <td style="border-width: 0px;">  
            <span class="label"><?= lang('character_name') ?>: </span>
        </td>
        <td style="border-width: 0px;">  
            <span class="input"><?= $char->getName() ?></span>
        </td>
        <td style="border-width: 0px;">  
            <span class="label"><?= lang('character_attr_str') ?>: </span>
        </td>
        <td style="border-width: 0px;">  
            <span class="input">
                <?= $char->getAttribute(Character::CHAR_ATTR_STRENGTH) ?>
            </span>
        </td>
    </tr>
    <tr>
        <td style="border-width: 0px;">  
            <span class="label"><?= lang('character_gender') ?>: </span>
        </td>
        <td style="border-width: 0px;">  
            <span class="input"><?     
        switch ($char->getGender())
        {
            case Character::GENDER_MALE:
                echo "<img src=\"" . base_url() . "images/gender_male.gif\">";
                break;
            case Character::GENDER_FEMALE:
                echo "<img src=\"" . base_url() . "images/gender_female.gif\">";
                break;
        } 
    ?></span>
        </td>
        <td style="border-width: 0px;">  
            <span class="label"><?= lang('character_attr_agi') ?>: </span>
        </td>
        <td style="border-width: 0px;">  
            <span class="input">
                <?= $char->getAttribute(Character::CHAR_ATTR_AGILITY) ?>
            </span>
        </td>
    </tr>    
    <tr>
        <td style="border-width: 0px;">  
            <span class="label"><?= lang('character_level') ?>: </span>
        </td>
        <td style="border-width: 0px;">  
            <span class="input"><?= $char->getLevel() ?></span>
        </td>
        <td style="border-width: 0px;">  
            <span class="label"><?= lang('character_attr_dex') ?>: </span>
        </td>
        <td style="border-width: 0px;">  
            <span class="input">
                <?= $char->getAttribute(Character::CHAR_ATTR_DEXTERITY) ?>
            </span>
        </td>
    </tr>    
    <tr>
        <td style="border-width: 0px;">  
            <span class="label"><?= lang('character_money') ?>: </span>
        </td>
        <td style="border-width: 0px;">  
            <span class="input">
                <?= number_format($char->getMoney(), 0, ".", ",") ?>
            </span>
        </td>
        <td style="border-width: 0px;">  
            <span class="label"><?= lang('character_attr_vit') ?>: </span>
        </td>
        <td style="border-width: 0px;">  
            <span class="input">
                <?= $char->getAttribute(Character::CHAR_ATTR_VITALITY) ?>
            </span>
        </td>        
    </tr>    
    <tr>
        <td style="border-width: 0px;">  
            <span class="label"><?= lang('character_map') ?>: </span>
        </td>
        <td style="border-width: 0px;">  
            <span class="input">
                <? $map = $char->getMap(); echo $map['name']; ?>
            </span>
        </td>
        <td style="border-width: 0px;">  
            <span class="label"><?= lang('character_attr_int') ?>: </span>
        </td>
        <td style="border-width: 0px;">  
            <span class="input">
                <?= $char->getAttribute(Character::CHAR_ATTR_INTELLIGENCE) ?>
            </span>
        </td>        
    </tr>    
    <tr>
        <td style="border-width: 0px;" colspan="2">  
            &nbsp;
        </td>
        <td style="border-width: 0px;">  
            <span class="label"><?= lang('character_attr_will') ?>: </span>
        </td>
        <td style="border-width: 0px;">  
            <span class="input">
                <?= $char->getAttribute(Character::CHAR_ATTR_WILLPOWER) ?>
            </span>
        </td>        
    </tr>        

</table>   

<h3><?= lang('character_guild_member') ?></h3>

<?php 
    if ($char->isGuildMember())
    { // the char is member in at least one guild... 
?>
    <p>
        TODO: present a list of all guild memberships
    </p>
<?php 
    } 
    else 
    {  // the char is member in at least one guild... 
?>
    <p>
        <?= lang('character_not_guild_member') ?>
    </p>
<?php 
    } 
?>


<p>   
    <?= anchor('accountmanager', "&laquo; " . lang('character_back')) ?>
</p>    
   