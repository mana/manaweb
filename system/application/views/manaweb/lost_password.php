<h3>Lost your password?</h3>

<p>If you`ve forgotton your password, don`t panic! You can reset your password 
   with this simple form. Please provide your username and the mailaddress you 
   registered with. After that you will receive a email containing a link
   where you can change your password.
   </p>
   
   
<?  $attributes = array('name' => 'lostPassword', 'id' => 'ManaResetPassword');
    echo form_open('myaccount/resetpassword', $attributes); ?>
    
    <table style="border-width: 0px; margin-bottom: 0px;">
    <? if ($has_errors) { ?>
    <tr>
        <td colspan="2" style="border: 1px solid #660000; font-weight: bold;
            color: #660000;">
            Something was wrong with your data: <br />
            <?php echo $this->validation->error_string; ?>
        </td>
    </tr>
    <? } ?>
    <tr>
        <td style="border-width: 0px;">  
            <label for="Manausername">Username: </label>
        </td>
        <td style="border-width: 0px;">  
            <input type="text" size="30" tabindex="1" value="" id="Manausername" 
                title="Enter your username" name="Manausername">
        </td>
    </tr>
    <tr>
        <td style="border-width: 0px;">  
            <label for="ManaMail">Email-Address: </label>
        </td>
        <td style="border-width: 0px;">  
            <input type="text" size="50" tabindex="2" value="" id="ManaMail" 
                title="Enter your mailaddress" name="ManaMail">
        </td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: center; border-width: 0px;">  
            <input type="submit" tabindex="3" value="Change password!" 
                id="Manasubmit" title="Login" name="Manasubmit">
            <input type="reset" tabindex="4" value="Cancel" 
                id="Manacancel" title="Cancel" name="Manacancel">
        </td>
    </tr>    
    </table>
    
<?= form_close(); ?>

<script type="text/javascript">
<!--
    // set the focus to the username field
    document.lostPassword.Manausername.focus();
//-->
</script>

