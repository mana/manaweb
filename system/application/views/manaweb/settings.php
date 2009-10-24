<p>
    <?= lang('settings_descr') ?>
    <?= lang('settings_selection') ?>

    <ul>
        <li>
            <a href="#"
               onclick="showPanel('form_change_password'); return false;">
                <?= lang('settings_select_password') ?>
            </a>
        </li>
        <li>
            <a href="<?= site_url('accountmanager/showForm/ChangeMailaddress') ?>">
                <?= lang('settings_select_mail') ?>
            </a>
        </li>
        <li>
            <a href="#"
               onclick="showPanel('form_delete_account'); return false;">
                <?= lang('settings_select_delete_account') ?>
            </a>
        </li>
    </ul>
</p>

<!-- ####################  change password #################### -->
<div id="form_change_password" style="display:none;">
    <div>
        <h3><?= lang('settings_change_pwd_head') ?></h3>
        <p><?php
            if (!isset($pwd_changed_message))
            {
                // output description
                echo lang('settings_change_pwd_descr');
            }
            ?></p>

            <?php
            $attributes = array('name'=>'changePassword', 'id'=>'TMWChangePassword');
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
                    <label for="TMW_old_password">
                        <?= lang('settings_old_password') ?>:
                    </label>
                </td>
                <td style="border-width: 0px;">
                    <input type="password" size="30" tabindex="1" value=""
                           id="TMW_old_password"
                           title="<?= lang('settings_enter_old_password') ?>"
                           name="TMW_old_password" />
                </td>
            </tr>
            <tr>
                <td style="border-width: 0px;">
                    <label for="TMW_new_password">
                        <?= lang('settings_new_password') ?>:
                    </label>
                </td>
                <td style="border-width: 0px;">
                    <input type="password" size="30" tabindex="2" value=""
                           id="TMW_new_password"
                           title="<?= lang('settings_enter_new_password') ?>"
                           name="TMW_new_password" />
                </td>
            </tr>
            <tr>
                <td style="border-width: 0px;">
                    <label for="TMW_retype_password">
                        <?= lang('settings_retype_password') ?>:
                    </label>
                </td>
                <td style="border-width: 0px;">
                    <input type="password" size="30" tabindex="3" value=""
                           id="TMW_retype_password"
                           title="<?= lang('settings_retype_new_password') ?>"
                           name="TMW_retype_password" />
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align: center; border-width: 0px;">

                    <input type="submit" tabindex="4"
                           value="<?= lang('settings_change_password') ?>!"
                           title="<?= lang('settings_change_password') ?>!"
                           id="TMWsubmitPassword"
                           name="TMWsubmitPassword" />
                    <input type="reset" tabindex="5"
                           value="<?= lang('cancel')?>"
                           id="TMWcancelPassword"
                           title="<?= lang('cancel')?>"
                           name="TMWcancelPassword" />
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
        <h3><?= lang('settings_delete_account_head') ?></h3>
        <p><?= lang('settings_delete_account_descr') ?></p>
        <p>
            <?php
            $attributes = array('name' => 'deleteAccount', 'id' => 'TMWDeleteAccount');
            echo form_open('accountmanager/delete_account', $attributes); ?>
            <input type="submit" tabindex="6"
                   value="<?= lang('settings_delete_account') ?>"
                   id="TMWsubmitDelete" title="<?= lang('settings_delete_account') ?>"
                   name="TMWsubmit" />
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
