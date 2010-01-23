<?php

// loop over all skinnsets
foreach ($skillprovider->getSkillsets() as $skillset)
{
    echo "\n<h3>" . $skillset->getName() . "</h3>\n";
    echo "<table style=\"border-width: 0px; margin-bottom: 0px;\">";

    // loop over all skins in the skinset
    foreach ($skillset->getSkills() as $skill)
    {
        $skillinfo = $char->getSkillInfo($skill->getId());
        if (true || $skillinfo['level'] > 0)
        {
            echo "<tr>
                    <td style=\"border-width: 0px;\">";

                    if ($skill->hasIcon()) {
                        echo "
                        <img src=\"".
                            $imageprovider->getImage($skill->getIcon(), Imageprovider::MANADATA_DIRECTORY)
                    ."\">";
                    }
                    echo "
                    </td>
                    <td style=\"border-width: 0px;\">
                        <span class=\"label\">".
                            $skill->getName() . ": </span>
                    </td>
                    <td style=\"border-width: 0px;\" align=\"right\">
                        <span class=\"input\">".$skillinfo['level']."</span>
                    </td>
                    <td style=\"border-width: 0px;\">
                        <span class=\"input\">".$skillinfo['exp_delta']." / ".
                            $skillinfo['exp_max_delta']."</span>
                    </td>
                  </tr>";
        } // skill level > 0
    } // foreach skill

    echo "</table>";
    to_the_top();

} // foreach skillset

?>
