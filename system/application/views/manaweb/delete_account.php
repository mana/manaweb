<h3>Attention!</h3>

<p><strong>Do you really want to delete your account? Remember: this action
cannot be undone!</strong></p>

<?php
    $attributes = array('name' => 'deleteAccount', 'id' => 'ManaDeleteAccount');
    echo form_open('accountmanager/execute_delete', $attributes); ?>

    <table style="border-width: 0px; margin-bottom: 0px;">
    <tr>
        <td colspan="2" style="text-align: center; border-width: 0px;">
            <input type="submit" tabindex="1" value="Yes, delete my account!"
                id="Manasubmit" title="Yes, delete my account!"
                name="Manasubmit" />
            <input type="submit" tabindex="2"
                value="No, I want to keep on playing!"
                id="Manasubmit" title="Yes, delete my account!"
                name="Manacancel" />
        </td>
    </tr>
    </table>
<?= form_close(); ?>
