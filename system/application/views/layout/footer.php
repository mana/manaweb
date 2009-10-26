<?php

    $themeinfo =& $themeprovider->getThemeInfo();
    $theme     =& $themeprovider->getTheme();
?>

    <?= $theme->afterContent() ?>


   <div id="footer">
    &copy; 2009 The Mana Source Dev Team -
    [<a href="http://validator.w3.org/check?uri=referer">xhtml</a>] 
    [<a href="http://jigsaw.w3.org/css-validator/check/referer">css</a>] -
    Version: <?= MANAWEB_VERSION ?> from <?= MANAWEB_VERSION_DATE ?>
   </div>
   
  </div> <!-- /page -->
 </body>
</html>
