<h3><?= T_('Welcome')?> <?= $user->getUsername() ?>!</h3>

<p><?= T_('stats_descr')?>

   <ul>
        <li><?= T_('View your character stats')?></li>
        <li><?= T_('Change your password')?></li>
        <li><?= T_('Change your mailaddress')?></li>
   </ul>
</p>

<p>
<? if ($this->user->isBanned())
   {
       echo "Your account is banned until ". date(T_('date_time_format') .". ",
        $this->user->isBanned());
   }
   else
   {
    echo T_("You are a member of the following access groups:");
    echo "<ul>";
        foreach ($groups as $group)
        {
            echo "<li>$group</li>";
        }
    echo "</ul>";
   }
?>
<?= T_('This account is registered since')?> <strong><?= date(T_('date_time_format'),
    $this->user->getRegistrationDate()) ?></strong>. <?= T_('Your last login was')?>
    <strong><?= date(T_('date_time_format'),
    $this->user->getLastLogin()) ?></strong>.
</p>


<h3><?= T_('Character overview')?></h3>

<?php if ($this->user->hasCharacters()){ ?>

<p><?= T_('Here you see a summary of all your characters. Click on the name of one to see its details.')?></p>

<table class="datatable">
<tr>
    <th><?= T_('Name')?></th>
    <th width="20"><?= T_('Level')?></th>
    <th width="20"><?= T_('Gender')?></th>
    <th><?= T_('Money')?></th>
    <th><?= T_('Map')?></th>
</tr>
<?php foreach ($this->user->getCharacters() as $char){ ?>
<tr>
    <td><a href="<?= site_url('charcontroller/' .
        $char->getID()) ?>"><?= $char->getName() ?></a></td>
    <td align="right"><?= $char->getLevel() ?></td>
    <td align="center"><?= $char->getGender('image') ?></td>
    <td align="right"><?= $char->getAttribute(Character::CHAR_ATTR_GP) ?></td>
    <td><?= $char->getMap()->getDescription() ?></td>
</tr>
<? } ?>
</table>

<?php } else {
    // user has no characters
?>
    <p><?= T_("user-has-no-chars") ?></p>

<?php } ?>

