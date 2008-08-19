<h3>Attention!</h3>

<p><strong>Do you really want to delete your account? Remember: this action
cannot be undone!</strong></p>

<?php 
    $attributes = array('name' => 'deleteAccount', 'id' => 'TMWDeleteAccount');
    echo form_open('accountmanager/execute_delete', $attributes); ?>

    <table style="border-width: 0px; margin-bottom: 0px;">
    <tr>
        <td colspan="2" style="text-align: center; border-width: 0px;">  
            <input type="submit" tabindex="1" value="Yes, delete my account!" 
                id="TMWsubmit" title="Yes, delete my account!" 
                name="TMWsubmit" />
            <input type="submit" tabindex="2" 
                value="No, I want to keep on playing!" 
                id="TMWsubmit" title="Yes, delete my account!" 
                name="TMWcancel" />
        </td>
    </tr>        
    </table>
<?php form_close(); ?>
