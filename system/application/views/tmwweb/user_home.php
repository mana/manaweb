<h3>Welcome <?= $user->username ?>!</h3>

<p>From this page you can manage several options of your account. Also you can
   get current statistics of you characters. Please choose one option 
   below:

   <ul>
        <li>View your character stats</li>
        <li>Change your password</li>
        <li>Change your mailaddress</li>
   </ul>   
</p>

<p>Your current state is <strong><?= $levelstring ?></strong>.
<? if ($this->user->isBanned())
   {
       echo "Your account is banned until ". date('d-m-Y H:i', $this->user->isBanned());
   }
?>
</p>


<?php if ($this->user->hasCharacters()){ ?>
<h3>Character overview</h3>

<p>Here you see a summary of all your characters. Click on the name
of one to see its details.</p>

<table class="datatable">
<tr>
    <th>Name</th>
    <th width="20">Level</th>
    <th width="20">Gender</th>
    <th>Money</th>
    <th>Map</th>
</tr>
<?php foreach ($this->user->getCharacters() as $char){ ?>
<tr>
    <td><a href="<?= site_url('accountmanager/character/' . 
        $char->getID()) ?>"><?= $char->getName() ?></a></td>
    <td align="right"><?= $char->getLevel() ?></td>
    <td align="center"><?= $char->getGender('image') ?></td>
    <td align="right"><?= $char->getMoney('string') ?></td>
    <td><? $map = $char->getMap(); echo $map['name']; ?></td>
</tr>
<? } ?>
</table>    

<?php } ?>

