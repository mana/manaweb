    <h3>Login to The Mana Server Account Manager</h3>

<?  $attributes = array('name' => 'loginForm', 'id' => 'TMWLoginForm');
    echo form_open('myaccount/login', $attributes); ?>

    <?php if (isset($message)) { ?>
        <p style="color: #57565c; font-size: 11pt;
                border-top: 1px solid #9f9894; padding:10px;">
            <strong><?= $message ?></strong>
        </p>
    <? } ?>

        
    <table style="border-width: 0px; margin-bottom: 0px;">
    <?php if ($has_errors) { ?>
    <tr>
        <td colspan="2" style="border: 1px solid #660000; font-weight: bold;
            color: #660000;">
            Something was wrong with your login: <br />
            <?php echo $this->validation->error_string; ?>
        </td>
    </tr>
    <? } ?>
    <tr>
        <td style="border-width: 0px;">  
            <label for="TMWusername">Username: </label>
        </td>
        <td style="border-width: 0px;">  
            <input type="text" size="30" tabindex="1" value="" id="TMWusername" 
                title="Enter your username" name="TMWusername" />
        </td>
    </tr>
    <tr>
        <td style="border-width: 0px;">  
            <label for="TMWpassword">Password: </label>
        </td>
        <td style="border-width: 0px;">  
            <input type="password" size="30" tabindex="2" value="" id="TMWpassword" 
                title="Enter your password" name="TMWpassword" />
        </td>
    </tr>
    <tr>
        <td style="border-width: 0px;">  
            <label for="TMWlanguage">Language: </label>
        </td>
        <td style="border-width: 0px;">  
            <select name="TMWlanguage" size="1" id="TMWlanguage" tabindex="3">
            <?php foreach ($this->translationprovider->getLanguages() as $lng) { ?>
                <option value="<?= $lng['dir'] ?>"><?= $lng['name'] ?></option>
            <?php } ?>
            </select>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: center; border-width: 0px;">  
            <input type="submit" tabindex="4" value="Login" 
                id="TMWsubmit" title="Login" name="TMWsubmit" />
            <input type="reset" tabindex="5" value="Cancel" 
                id="TMWcancel" title="Cancel" name="TMWcancel" />
        </td>
    </tr>    
    <tr>
        <td colspan="2" style="border-width: 0px;">  
            <a href="<?= site_url('myaccount/lostpassword') ?>">&raquo; Lost your password?</a>
        </td>
    </tr>        
    </table>
    
    
<?= form_close(); ?>

<script type="text/javascript">
<!--
    // set the focus to the username field
    document.loginForm.TMWusername.focus();
//-->
</script>
