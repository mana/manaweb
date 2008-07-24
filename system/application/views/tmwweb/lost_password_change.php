<h3>Hello <?= $username ?>, now change your password!</h3>

<p>
    Please type in a new password for your account and retype it to be sure you
    have no typo in it.
</p>
   
   
<?php 
    $attributes = array('name'=>'changePassword', 'id'=>'TMWChangePassword');
    echo form_open('myaccount/setnewpassword', $attributes); ?>
    
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
    <tr>
        <td style="border-width: 0px;">  
            <span class="label">Username: </span>
        </td>
        <td style="border-width: 0px;">  
            <span class="input"><?= $username ?></span>
        </td>
    </tr>
    <tr>
        <td style="border-width: 0px;">  
            <span class="label">Activation key: </span>
        </td>
        <td style="border-width: 0px;">  
            <span class="input"><?= $key ?></span>
        </td>
    </tr>
    <tr>
        <td style="border-width: 0px;">  
            <label for="TMWpassword">Password: </label>
        </td>
        <td style="border-width: 0px;">  
            <input type="password" size="30" tabindex="1" value="" 
                id="TMWpassword" title="Enter your password" 
                name="TMWpassword" />
        </td>
    </tr>
    <tr>
        <td style="border-width: 0px;">  
            <label for="TMWpassword2">retype Password: </label>
        </td>
        <td style="border-width: 0px;">  
            <input type="password" size="30" tabindex="2" value="" 
                id="TMWpassword2" title="Retype your password" 
                name="TMWpassword2" />
        </td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: center; border-width: 0px;">  
            <input type="hidden" name="TMWUsername" value="<?= $username ?>" />
            <input type="hidden" name="TMWActivationKey" value="<?= $key ?>" />
            
            <input type="submit" tabindex="3" value="Change password!" 
                id="TMWsubmit" title="Login" name="TMWsubmit" />
            <input type="reset" tabindex="4" value="Cancel" 
                id="TMWcancel" title="Cancel" name="TMWcancel" />
        </td>
    </tr>    
    </table>
    
<?= form_close(); ?>

<p style="color: #57565c; font-size: 11pt;
          border-top: 1px solid #9f9894; padding:10px;">
    <strong>Note:</strong> The activation key will be invalidated after you`ve
    set your new password. If you plan to change it again sometimes, you have
    to request anouther activation key.
</p>

<script type="text/javascript">
<!--
    // set the focus to the username field
    document.changePassword.TMWpassword.focus();
//-->
</script>

