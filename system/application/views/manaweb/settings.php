<p>
    <?= T_('settings_descr') ?>
    <?= T_('settings_selection') ?>

    <ul>
        <li>
            <a href="#"
               onclick="showPanel('form_change_password'); return false;">
                <?= T_('settings_select_password') ?>
            </a>
        </li>
        <li>
            <a href="<?= site_url('accountmanager/showForm/ChangeMailaddress') ?>">
                <?= T_('settings_select_mail') ?>
            </a>
        </li>
        <li>
            <a href="#"
               onclick="showPanel('form_delete_account'); return false;">
                <?= T_('settings_select_delete_account') ?>
            </a>
        </li>
    </ul>
</p>

<!-- ####################  change password #################### -->
<div id="form_change_password" style="display:none;">
    <div>
        <h3><?= T_('settings_change_pwd_head') ?></h3>
        <p><?php
            if (!isset($pwd_changed_message))
            {
                // output description
                echo T_('settings_change_pwd_descr');
            }
            ?></p>

            <?php
            $attributes = array('name'=>'changePassword', 'id'=>'ManaChangePassword');
            echo form_open('accountmanager/changepassword', $attributes); ?>

        <table style="border-width: 0px; margin-bottom: 0px;">
            <? if ($has_errors) { ?>
            <tr>
                <td colspan="2" style="border: 1px solid #660000; font-weight: bold;
                    color: #660000;">
                    Something was wrong with your new password: <br />
                    <?= validation_errors(); ?>
                </td>
            </tr>
            <? } ?>
        <? if (isset($pwd_changed_message)) { ?>
            <tr>
                <td colspan="2" style="border: 1px solid #006600; font-weight: bold;
                    color: #006600;">
                    <?= $pwd_changed_message; ?>
                </td>
            </tr>
            <? } else { ?>
            <tr>
                <td style="border-width: 0px;">
                    <label for="Mana_old_password">
                        <?= T_('settings_old_password') ?>:
                    </label>
                </td>
                <td style="border-width: 0px;">
                    <input type="password" size="30" tabindex="1" value=""
                           id="Mana_old_password"
                           title="<?= T_('settings_enter_old_password') ?>"
                           name="Mana_old_password" />
                </td>
            </tr>
            <tr>
                <td style="border-width: 0px;">
                    <label for="Mana_new_password">
                        <?= T_('settings_new_password') ?>:
                    </label>
                </td>
                <td style="border-width: 0px;">
                    <input type="password" size="30" tabindex="2" value=""
                           id="Mana_new_password"
                           title="<?= T_('settings_enter_new_password') ?>"
                           name="Mana_new_password" />
                </td>
            </tr>
            <tr>
                <td style="border-width: 0px;">
                    <label for="Mana_retype_password">
                        <?= T_('settings_retype_password') ?>:
                    </label>
                </td>
                <td style="border-width: 0px;">
                    <input type="password" size="30" tabindex="3" value=""
                           id="Mana_retype_password"
                           title="<?= T_('settings_retype_new_password') ?>"
                           name="Mana_retype_password" />
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center; border-width: 0px;">

                    <input type="submit" tabindex="4"
                           value="<?= T_('settings_change_password') ?>!"
                           title="<?= T_('settings_change_password') ?>!"
                           id="ManasubmitPassword"
                           name="ManasubmitPassword" />
                    <input type="reset" tabindex="5"
                           value="<?= T_('cancel')?>"
                           id="ManacancelPassword"
                           title="<?= T_('cancel')?>"
                           name="ManacancelPassword" />
                </td>
            </tr>
            <? } ?>
        </table>
        <?= form_close(); ?>
    </div>
</div>

<!-- ####################  delete account form #################### -->
<div id="form_delete_account" style="display:none;">
    <div>
        <h3><?= T_('settings_delete_account_head') ?></h3>
        <p><?= T_('settings_delete_account_descr') ?></p>
        <p>
            <?php
            $attributes = array('name' => 'deleteAccount', 'id' => 'ManaDeleteAccount');
            echo form_open('accountmanager/delete_account', $attributes); ?>
            <input type="submit" tabindex="6"
                   value="<?= T_('settings_delete_account') ?>"
                   id="ManasubmitDelete" title="<?= T_('settings_delete_account') ?>"
                   name="Manasubmit" />
            <?= form_close(); ?>
        </p>
    </div>
</div>

<!-- ####################  dhtml code #################### -->
<script type="text/javascript">
    function showPanel(name)
    {
        var panels = ['form_delete_account', 'form_change_password'];
        for (i = 0; i < panels.length; i++)
        {
            if (panels[i] != name && $(panels[i]).getStyle("display") != "none")
            {
                Effect.Fade(panels[i], {queue:'first'});
            }
        }
        Effect.Appear(name, {queue:'end'});
    }

    <?php if ($has_errors || isset($pwd_changed_message)) { ?>
        // show the change password pannel in case of errors
        showPanel('form_change_password');
        <? } ?>
</script>
