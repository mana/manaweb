<h3><?= lang('account_details') ?></h3>

<table style="border-width: 0px; margin-bottom: 0px;">
    <tr>
        <td style="border-width: 0px;">
            <span class="label"><?= lang('account_id') ?>: </span>
        </td>
        <td style="border-width: 0px;">
            <span class="input"><?= $account->getID() ?></span>
        </td>
        <td rowspan="6" valign="top">
            <h3><?= lang('account_characters'); ?></h3>
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
            <span class="label"><?= lang('account_username') ?>: </span>
        </td>
        <td style="border-width: 0px;">
            <span class="input"><?= $account->getUsername() ?></span>
        </td>
    </tr>
    <tr>
        <td style="border-width: 0px;">
            <span class="label"><?= lang('account_groups') ?>: </span>
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
            <span class="label"><?= lang('account_status') ?>: </span>
        </td>
        <td style="border-width: 0px;">
            <span class="input">
            <?php
                if ($account->isBanned())
                {
                    echo lang('account_status_banned') . " " .
                    date(lang('date_time_format'), $account->getBanned());
                }
                else
                {
                    echo lang('account_status_active');
                }
            ?>
            </span>
        </td>
    </tr>
    <tr>
        <td style="border-width: 0px;">
            <span class="label"><?= lang('account_registration') ?>: </span>
        </td>
        <td style="border-width: 0px;">
            <span class="input"><?= date(lang('date_time_format'), $account->getRegistration()) ?></span>
        </td>
    </tr>
    <tr>
        <td style="border-width: 0px;">
            <span class="label"><?= lang('account_lastlogin') ?>: </span>
        </td>
        <td style="border-width: 0px;">
            <span class="input"><?= date(lang('date_time_format'), $account->getLastLogin()) ?></span>
        </td>
    </tr>
</table>

<h3><?= lang('administrative_tasks') ?></h3>