<h3><?= lang('character_guild_member') ?></h3>
<?php 
    if ($char->isGuildMember())
    { // the char is member in at least one guild... 
?>
    <p>
        TODO: present a list of all guild memberships
    </p>
<?php 
    } 
    else 
    {  // the char is not member in any guild... 
?>
    <p>
        <?= lang('character_not_guild_member') ?>
    </p>
<?php 
    } 
?>
