<h3><?= lang('weapon_skills') ?></h3>
<table style="border-width: 0px; margin-bottom: 0px;">
    <?

for ($skillid = Character::CHAR_SKILL_MIN;
        $skillid <= Character::CHAR_SKILL_MAX;
        $skillid++ )
        {
            $skill = $char->getSkillInfo($skillid);
            if ($skill['level'] > 0)
            {
                echo "<tr>
                        <td style=\"border-width: 0px;\">
                            <img src=\"".base_url()."images/items/".$skill['icon']."\">
                        </td>
                        <td style=\"border-width: 0px;\">
                            <span class=\"label\">".
                                lang($skill['text']) . ": </span>
                        </td>
                        <td style=\"border-width: 0px;\" align=\"right\">
                            <span class=\"input\">".$skill['level']."</span>
                        </td>
                        <td style=\"border-width: 0px;\">
                            <span class=\"input\">".$skill['exp_delta']." / ".
                                $skill['exp_max_delta']."</span>
                        </td>
                      </tr>";
            } // skill level > 0
        } // loop over all skills
    ?>
</table>
<?= to_the_top() ?>


<h3><?= lang('magic_skills') ?></h3>
<p>Sorry, this feature is not yet supported.</p>
<?= to_the_top() ?>


<h3><?= lang('crafting_skills') ?></h3>
<p>Sorry, this feature is not yet supported.</p>
<?= to_the_top() ?>
   