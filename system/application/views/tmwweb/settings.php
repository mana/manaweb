<p>
<?= lang('settings_descr') ?>
</p>
<p><?= lang('settings_selection') ?>

   <ul>
        <li>
            <a href="#change_password">
                <?= lang('settings_select_password') ?>
            </a>
        </li>
        <li>
            <a href="#change_mail">
                <?= lang('settings_select_mail') ?>
            </a>
        </li>
        <li>
            <a href="#delete_account">
                <?= lang('settings_select_delete_account') ?>
            </a>
        </li>
   </ul>   
</p>



<a name="change_password"></a>
<h3><?= lang('settings_change_pwd_head') ?></h3>

<p>
    <?= lang('settings_change_pwd_descr') ?>
</p>
   
<?php 
    $attributes = array('name'=>'changePassword', 'id'=>'TMWChangePassword');
    echo form_open('accountmanager/changepassword', $attributes); ?>
    
    <table style="border-width: 0px; margin-bottom: 0px;">
    <? if ($has_errors) { ?>
    <tr>
        <td colspan="2" style="border: 1px solid #660000; font-weight: bold;
            color: #660000;">
            Something was wrong with your new password: <br />
            <?php echo $this->validation->error_string; ?>
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
    <? } ?>
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
                id="TMWsubmit" 
                name="TMWsubmit" />
            <input type="reset" tabindex="5" 
                value="<?= lang('cancel')?>" 
                id="TMWcancel" 
                title="<?= lang('cancel')?>" 
                name="TMWcancel" />
        </td>
    </tr>    
    </table>
    
<?= form_close(); ?>

<a name="change_mail"></a>
<h3><?= lang('settings_change_mail_head') ?></h3>

<p><?= lang('settings_change_mail_descr') ?></p>

<a name="delete_account"></a>
<h3><?= lang('settings_delete_account_head') ?></h3>

<p><?= lang('settings_delete_account_descr') ?></p>
<?php 
    $attributes = array('name' => 'deleteAccount', 'id' => 'TMWDeleteAccount');
    echo form_open('accountmanager/delete_account', $attributes); ?>

    <table style="border-width: 0px; margin-bottom: 0px;">
    <tr>
        <td colspan="2" style="text-align: center; border-width: 0px;">  
            <input type="submit" tabindex="6" 
                value="<?= lang('settings_delete_account') ?>" 
                id="TMWsubmit" title="<?= lang('settings_delete_account') ?>" 
                name="TMWsubmit" />
        </td>
    </tr>        
    </table>
<?php form_close(); ?>

    