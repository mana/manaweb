<p>On this page you have serveral options to modify your account settings. 
Maybe you would like to change your stored mailadress, or change your login
password or even drop your complete accout? Just select one of the options
below.
</p>
<p>I would like to...

   <ul>
        <li><a href="#change_password">...change my password.</a></li>
        <li><a href="#change_mail">...change my mailaddress.</a></li>
        <li><a href="#delete_account">...delete my accout.</a></li>
   </ul>   
</p>



<a name="change_password"></a>
<h3>Change your password</h3>

<p>bla</p>

<a name="change_mail"></a>
<h3>Change your mailaddress</h3>

<p>bla</p>

<a name="delete_account"></a>
<h3>Delete your Account</h3>

<p>With this option you can delete your account including all your characters.
Be warned, that this action is <strong>not revertable</strong> and we are 
<strong>not able to restore your characters</strong> after that!
Besides it would be nice if you leave a message in the forum why you wanna 
leave The Mana World.</p>
<?php 
    $attributes = array('name' => 'deleteAccount', 'id' => 'TMWDeleteAccount');
    echo form_open('accountmanager/delete_account', $attributes); ?>

    <table style="border-width: 0px; margin-bottom: 0px;">
    <tr>
        <td colspan="2" style="text-align: center; border-width: 0px;">  
            <input type="submit" tabindex="2" value="Yes, delete my account!" 
                id="TMWsubmit" title="Yes, delete my account!" 
                name="TMWsubmit" />
        </td>
    </tr>        
    </table>
<?php form_close(); ?>

    