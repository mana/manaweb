    <h3>Login to The Mana Server Account Manager</h3>

<?  $attributes = array('name' => 'loginForm', 'id' => 'ManaLoginForm');
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
            <label for="Manausername">Username: </label>
        </td>
        <td style="border-width: 0px;">
            <input type="text" size="30" tabindex="1" value="" id="Manausername"
                title="Enter your username" name="Manausername" />
        </td>
    </tr>
    <tr>
        <td style="border-width: 0px;">
            <label for="Manapassword">Password: </label>
        </td>
        <td style="border-width: 0px;">
            <input type="password" size="30" tabindex="2" value="" id="Manapassword"
                title="Enter your password" name="Manapassword" />
        </td>
    </tr>
    <tr>
        <td style="border-width: 0px;">
            <label for="Manastyle">Style: </label>
        </td>
        <td style="border-width: 0px;">
            <select name="Manastyle" size="1" id="Manastyle" tabindex="4">
            <?php foreach ($themeprovider->getThemes(true) as $th) { ?>
                <option value="<?= $th->getShortname() ?>"><?= $th->getName() ?></option>
            <?php } ?>
            </select>
        </td>
    </tr>
    <tr>
        <td colspan="2" style="text-align: center; border-width: 0px;">
            <input type="submit" tabindex="4" value="Login"
                id="Manasubmit" title="Login" name="Manasubmit" />
            <input type="reset" tabindex="5" value="Cancel"
                id="Manacancel" title="Cancel" name="Manacancel" />
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
    document.loginForm.Manausername.focus();
//-->
</script>
