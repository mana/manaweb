<h3><?= lang('character_statistics') ?></h3>
<table>
    <tr>
        <td>
            <span class="label"><?= lang('character_name') ?>: </span>
        </td>
        <td>
            <span class="input"><?= $char->getName() ?></span>
        </td>
        <td>
            <span class="label"><?= lang('character_attr_str') ?>: </span>
        </td>
        <td align="right">
            <span class="input">
                <?= $char->getAttribute(Character::CHAR_ATTR_STRENGTH) ?>
            </span>
        </td>

        <td style="border-width: 0px;" rowspan="6">

            <table style="border-width: 0px; margin-bottom: 0px; padding: 0px;  border-spacing: 0px;">
                <tr>
                    <td style="border-width: 0px; margin: 0px;
                        width: 36px; height: 38px;
                        background-image: url(<?= base_url() ?>images/slots/slot.png);
                        background-repeat: no-repeat;">
                    </td>
                    <td style="border-width: 0px; margin-bottom: 0px;
                        width: 36px; height: 38px;
                        background-image: url(<?= base_url() ?>images/slots/helmet.png);
                        background-repeat: no-repeat;">
                    </td>
                    <td style="border-width: 0px; margin: 0px;
                        width: 36px; height: 38px;
                        background-image: url(<?= base_url() ?>images/slots/slot.png);
                        background-repeat: no-repeat;">
                    </td>
                </tr>
                <tr>
                    <td style="border-width: 0px; margin: 10px;
                        width: 36px; height: 38px;
                        background-image: url(<?= base_url() ?>images/slots/slot.png);
                        background-repeat: no-repeat;">
                    </td>
                    <td style="border-width: 0px; margin: 0px;
                        width: 36px; height: 38px;
                        background-image: url(<?= base_url() ?>images/slots/torso.png);
                        background-repeat: no-repeat;">
                    </td>
                    <td style="border-width: 0px; margin: 0px;
                        width: 36px; height: 38px;
                        background-image: url(<?= base_url() ?>images/slots/gloves.png);
                        background-repeat: no-repeat;">
                    </td>
                </tr>
                <tr>
                    <td style="border-width: 0px; margin: 0px;
                        width: 36px; height: 38px;
                        background-image: url(<?= base_url() ?>images/slots/weapon1.png);
                        background-repeat: no-repeat;">
                        <img src='<?= base_url() ?>images/items/axe.png'>
                    </td>
                    <td style="border-width: 0px; margin: 0px;
                        width: 36px; height: 38px;
                        background-image: url(<?= base_url() ?>images/slots/legs.png);
                        background-repeat: no-repeat;">
                        <img src='<?= base_url() ?>images/items/armor-legs-shorts.png'>
                    </td>
                    <td style="border-width: 0px; margin-bottom: 0px;
                        width: 36px; height: 38px;
                        background-image: url(<?= base_url() ?>images/slots/weapon2.png);
                        background-repeat: no-repeat;">
                    </td>
                </tr>
                <tr>
                    <td style="border-width: 0px; margin: 0px;
                        width: 36px; height: 38px;
                        background-image: url(<?= base_url() ?>images/slots/ring.png);
                        background-repeat: no-repeat;">
                    </td>
                    <td style="border-width: 0px; margin-bottom: 0px;
                        width: 36px; height: 38px;
                        background-image: url(<?= base_url() ?>images/slots/feet.png);
                        background-repeat: no-repeat;">
                    </td>
                    <td style="border-width: 0px; margin: 0px;
                        width: 36px; height: 38px;
                        background-image: url(<?= base_url() ?>images/slots/ring.png);
                        background-repeat: no-repeat;">
                    </td>
                </tr>
            </table>

        </td>

    </tr>
    <tr>
        <td style="border-width: 0px;">
            <span class="label"><?= lang('character_gender') ?>: </span>
        </td>
        <td style="border-width: 0px;">
            <span class="input"><?= $char->getGender('image') ?></span>
        </td>
        <td style="border-width: 0px;">
            <span class="label"><?= lang('character_attr_agi') ?>: </span>
        </td>
        <td style="border-width: 0px;" align="right">
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
        <td style="border-width: 0px;" align="right">
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
            <span class="input"><?= $char->getAttribute(Character::CHAR_ATTR_GP) ?></span>
        </td>
        <td style="border-width: 0px;">
            <span class="label"><?= lang('character_attr_vit') ?>: </span>
        </td>
        <td style="border-width: 0px;" align="right">
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
                <?= $char->getMap()->getDescription(); ?>
            </span>
        </td>
        <td style="border-width: 0px;">
            <span class="label"><?= lang('character_attr_int') ?>: </span>
        </td>
        <td style="border-width: 0px;" align="right">
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
        <td style="border-width: 0px;" align="right">
            <span class="input">
                <?= $char->getAttribute(Character::CHAR_ATTR_WILLPOWER) ?>
            </span>
        </td>
    </tr>
</table>

