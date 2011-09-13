<h3><?= T_('account_details') ?></h3>

<table style="border-width: 0px; margin-bottom: 0px;">
    <tr>
        <td style="border-width: 0px;">
            <span class="label"><?= T_('account_id') ?>: </span>
        </td>
        <td style="border-width: 0px;">
            <span class="input"><?= $account->getID() ?></span>
        </td>
        <td rowspan="6" valign="top">
            <h3><?= T_('account_characters'); ?></h3>
            <ul>
            <? foreach ($account->getCharacters("name") as $char)
            {
              echo "<li><a href=\"" . site_url(array("admin/show_character",
                    $char->getId())) . "\">"  .
                    $char->getName() . "</a> (" .
                    $char->isOnline('img') .
                    $char->getGender('image') . ")</li>";
            }
            ?>
            </ul>
        </td>
    </tr>
    <tr>
        <td style="border-width: 0px;">
            <span class="label"><?= T_('account_username') ?>: </span>
        </td>
        <td style="border-width: 0px;">
            <span class="input"><?= $account->getUsername() ?></span>
        </td>
    </tr>
    <tr>
        <td style="border-width: 0px;">
            <span class="label"><?= T_('account_groups') ?>: </span>
        </td>
        <td style="border-width: 0px;">
            <span class="input"><?php
                foreach ($this->user->getUserLevelString($account->getLevel()) as $group )
                {
                    echo $group . ", ";
                }
            ?>( <?= $account->getLevel() ?> )
            </span>
        </td>
    </tr>
    <tr>
        <td style="border-width: 0px;">
            <span class="label"><?= T_('account_status') ?>: </span>
        </td>
        <td style="border-width: 0px;">
            <span class="input">
            <?php
                if ($account->isBanned())
                {
                    echo T_('account_status_banned') . " " .
                    date(T_('date_time_format'), $account->getBanned());
                }
                else
                {
                    echo T_('account_status_active');
                }
            ?>
            </span>
        </td>
    </tr>
    <tr>
        <td style="border-width: 0px;">
            <span class="label"><?= T_('account_registration') ?>: </span>
        </td>
        <td style="border-width: 0px;">
            <span class="input"><?= date(T_('date_time_format'), $account->getRegistration()) ?></span>
        </td>
    </tr>
    <tr>
        <td style="border-width: 0px;">
            <span class="label"><?= T_('account_lastlogin') ?>: </span>
        </td>
        <td style="border-width: 0px;">
            <span class="input"><?= date(T_('date_time_format'), $account->getLastLogin()) ?></span>
        </td>
    </tr>
</table>

<h3><?= T_('administrative_tasks') ?></h3>