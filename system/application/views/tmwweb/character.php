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

<h3><?= lang('weapon_skills') ?></h3>
<table style="border-width: 0px; margin-bottom: 0px;">
    <tr>
        <td style="border-width: 0px;">
            <img src='<?= base_url() ?>images/items/unarmed.png'>
        </td>
        <td style="border-width: 0px;">  
            <span class="label"><?= lang('character_skill_none') ?>: </span>
        </td>
        <td style="border-width: 0px;">  
            <span class="input">
                <?= $char->getSkill(Character::CHAR_SKILL_NONE) ?>
            </span>
        </td>
        
        <td style="border-width: 0px;">  
            <img src='<?= base_url() ?>images/items/mace.png'>
        </td>
        <td style="border-width: 0px;">  
            <span class="label"><?= lang('character_skill_mace') ?>: </span>
        </td>
        <td style="border-width: 0px;">  
            <span class="input">
                <?= $char->getSkill(Character::CHAR_SKILL_MACE) ?>
            </span>
        </td>        
        <td style="border-width: 0px;">
            <img src='<?= base_url() ?>images/items/shooting.png'>
        </td>
        <td style="border-width: 0px;">  
            <span class="label"><?= lang('character_skill_shooting') ?>: </span>
        </td>
        <td style="border-width: 0px;">  
            <span class="input">
                <?= $char->getSkill(Character::CHAR_SKILL_SHOOTING) ?>
            </span>
        </td>    </tr>
    
    <tr>
        <td style="border-width: 0px;">  
            <img src='<?= base_url() ?>images/items/knife.png'>
        </td>
        <td style="border-width: 0px;">  
            <span class="label"><?= lang('character_skill_knife') ?>: </span>
        </td>
        <td style="border-width: 0px;">  
            <span class="input">
                <?= $char->getSkill(Character::CHAR_SKILL_KNIFE) ?>
            </span>
        </td>
        
        <td style="border-width: 0px;">  
            <img src='<?= base_url() ?>images/items/polearm.png'>
        </td>
        <td style="border-width: 0px;">  
            <span class="label"><?= lang('character_skill_polearm') ?>: </span>
        </td>
        <td style="border-width: 0px;">  
            <span class="input">
                <?= $char->getSkill(Character::CHAR_SKILL_POLEARM) ?>
            </span>
        </td>
        
        <td style="border-width: 0px;">  
            <img src='<?= base_url() ?>images/items/bow.png'>
        </td>
        <td style="border-width: 0px;">  
            <span class="label"><?= lang('character_skill_bow') ?>: </span>
        </td>
        <td style="border-width: 0px;">  
            <span class="input">
                <?= $char->getSkill(Character::CHAR_SKILL_BOW) ?>
            </span>
        </td>
    </tr>
    <tr>
        <td style="border-width: 0px;">  
            <img src='<?= base_url() ?>images/items/sword.png'>
        </td>
        <td style="border-width: 0px;">  
            <span class="label"><?= lang('character_skill_sword') ?>: </span>
        </td>
        <td style="border-width: 0px;">  
            <span class="input">
                <?= $char->getSkill(Character::CHAR_SKILL_SWORD) ?>
            </span>
        </td>
        <td style="border-width: 0px;">  
            <img src='<?= base_url() ?>images/items/staff.png'>
        </td>
        <td style="border-width: 0px;">  
            <span class="label"><?= lang('character_skill_staff') ?>: </span>
        </td>
        <td style="border-width: 0px;">  
            <span class="input">
                <?= $char->getSkill(Character::CHAR_SKILL_STAFF) ?>
            </span>
        </td>
        <td style="border-width: 0px;">  
            <img src='<?= base_url() ?>images/items/thrown.png'>
        </td>
        <td style="border-width: 0px;">  
            <span class="label"><?= lang('character_skill_thrown') ?>: </span>
        </td>
        <td style="border-width: 0px;">  
            <span class="input">
                <?= $char->getSkill(Character::CHAR_SKILL_THROWN) ?>
            </span>
        </td>
    </tr>
    <tr>
        <td style="border-width: 0px;">  
            <img src='<?= base_url() ?>images/items/axe.png'>
        </td>
        <td style="border-width: 0px;">  
            <span class="label"><?= lang('character_skill_axe') ?>: </span>
        </td>
        <td style="border-width: 0px;">  
            <span class="input">
                <?= $char->getSkill(Character::CHAR_SKILL_AXE) ?>
            </span>
        </td>    
        <td style="border-width: 0px;">  
            <img src='<?= base_url() ?>images/items/whip.png'>
        </td>
        <td style="border-width: 0px;">  
            <span class="label"><?= lang('character_skill_whip') ?>: </span>
        </td>
        <td style="border-width: 0px;">  
            <span class="input">
                <?= $char->getSkill(Character::CHAR_SKILL_WHIP) ?>
            </span>
        </td>    
        <td style="border-width: 0px;"^colspan="3">&nbsp;</td>
    </tr>

</table>

<h3><?= lang('magic_skills') ?></h3>
<h3><?= lang('crafting_skills') ?></h3>


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
   