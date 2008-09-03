<h3><?= lang('weapon_skills') ?></h3>
<table style="border-width: 0px; margin-bottom: 0px;">
    <tr>
        <td style="border-width: 0px;">
            <img src='<?= base_url() ?>images/items/unarmed.png'>
        </td>
        <td style="border-width: 0px;">  
            <span class="label"><?= lang('character_skill_none') ?>: </span>
        </td>
        <td style="border-width: 0px;" align="right">  
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
        <td style="border-width: 0px;" align="right">  
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
        <td style="border-width: 0px;" align="right">  
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
        <td style="border-width: 0px;" align="right">  
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
        <td style="border-width: 0px;" align="right">  
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
        <td style="border-width: 0px;" align="right">  
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
        <td style="border-width: 0px;" align="right">  
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
        <td style="border-width: 0px;" align="right">  
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
        <td style="border-width: 0px;" align="right">  
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
        <td style="border-width: 0px;" align="right">  
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
        <td style="border-width: 0px;" align="right">  
            <span class="input">
                <?= $char->getSkill(Character::CHAR_SKILL_WHIP) ?>
            </span>
        </td>    
        <td style="border-width: 0px;"^colspan="3">&nbsp;</td>
    </tr>

</table>
<?= to_the_top() ?>


<h3><?= lang('magic_skills') ?></h3>
<p>Sorry, this feature is not yet supported.</p>
<?= to_the_top() ?>


<h3><?= lang('crafting_skills') ?></h3>
<p>Sorry, this feature is not yet supported.</p>
<?= to_the_top() ?>
   