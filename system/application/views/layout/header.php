<?php

    $themeinfo =& $themeprovider->getThemeInfo();
    $theme     =& $themeprovider->getTheme();

echo "<?xml version=\"1.0\" encoding=\"ISO-8859-15\" ?>"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title>The Mana Source Account Manager</title>
  <meta http-equiv="content-type" content="text/html; charset=utf-8" />
  <meta http-equiv="Content-Style-Type" content="text/css"/>
  <meta http-equiv="Content-Language" content="en"/>

  <meta name="description" content="The Mana Server is a free MMORPG game for Linux, MacOS X and Windows"/>
  <meta name="keywords" content="The Mana Server, Mana, Mana Source, MMORPG, 2D, RPG, free, GPL"/>
  <meta name="author" content="The Mana Server Dev Team"/>

  <link rel="shortcut icon" href="<?= base_url() ?>images/icon16.png" type="image/png"/>
  <link rel="icon" href="<?= base_url() ?>images/icon16.png" type="image/png"/>

  <script src="<?= base_url() ?>ext/scriptaculous-js-1.8.2/lib/prototype.js" type="text/javascript"></script>
  <script src="<?= base_url() ?>ext/scriptaculous-js-1.8.2/src/scriptaculous.js" type="text/javascript"></script>

  <style type="text/css">
   @import url(<?= base_url() ?><?= $themeinfo->getUrl() ?><?= $themeinfo->getStylesheet()?>);
  </style>
 </head>

<body>
    <a name="top"><!-- anchor to link to the top of the page --></a>
    <?php
        echo $theme->beforeLogo();
        echo $theme->Logo();
        echo $theme->afterLogo();
    ?>



    <!-- start of navigation bar -->
    <?php
        echo $theme->beforeNavigationBar();

        // TODO: do this with plugins...
        $nb = new NavigationBox( 'navigation.mana.main', '' );

        $nb->addNavigationItem( new NavigationItem('navigation.mana.main.itemslist',
            _("Itemslist"), site_url('itemslist') ) );
        $nb->addNavigationItem( new NavigationItem('navigation.mana.main.serverstats',
            _("Statistics"), site_url('statistics') ) );

        $navigation->addNavbox($nb, 10);

        // user nav box
        $nb = new NavigationBox( 'navigation.mana.usermenu', _("User menu") );
        $nb->addNavigationItem( new NavigationItem('navigation.mana.usermenu.myaccount',
            _("My Account"), site_url('myaccount') ) );
        if (isset($user_menu) )
        {
            foreach( $user_menu as $link )
            {
                $nb->addNavigationItem( new NavigationItem('navigation.mana.usermenu.' . $link['name'],
                    $link['name'], $link['url'] ) );
            }
        }
        $navigation->addNavbox($nb, 20);

        // char navbox
        if (isset($character_menu))
        {
            $nb = new NavigationBox( 'navigation.mana.character',  $character_menu->getName() );

            $nb->addNavigationItem( new NavigationItem('navigation.mana.character.index',
                _("Character sheet"),  site_url("charcontroller/".$character_menu->getId() )) );
            $nb->addNavigationItem( new NavigationItem('navigation.mana.character.skills',
                _("Skills"),  site_url("charcontroller/".$character_menu->getId()."/skills" )) );
            $nb->addNavigationItem( new NavigationItem('navigation.mana.character.inventory',
                _("Inventory"),  site_url("charcontroller/".$character_menu->getId()."/inventory" )) );
            $nb->addNavigationItem( new NavigationItem('navigation.mana.character.guilds',
                _("Parties &amp; Guilds"),  site_url("charcontroller/".$character_menu->getId()."/guilds" )) );
            $nb->addNavigationItem( new NavigationItem('navigation.mana.character.admin',
                _("Administration"),  site_url("charcontroller/".$character_menu->getId()."/admin" )) );

            $navigation->addNavbox($nb, 30);
        }

        // administration menu
        if ( isset($user_menu) && $this->user->isAdmin() )
        {
            $nb = new NavigationBox( 'navigation.mana.administration',  _("Admin Interface") );
            $nb->addNavigationItem( new NavigationItem('navigation.mana.administration.index',
                _("Admin Interface"),  site_url("admin" )) );
            $nb->addNavigationItem( new NavigationItem('navigation.mana.administration.maintenance',
                _("Maintenance"),  site_url("admin/maintenance" )) );
            $navigation->addNavbox($nb, 90);
        }


        // manabay -- this will be the first more complex plugin i thing...
        $nb = new NavigationBox( 'navigation.mana.manabay', _("Manabay") );
        $nb->addNavigationItem( new NavigationItem('navigation.mana.manabay.auctions',
            _("Auctions"), site_url('manabay') ) );

        $navigation->addNavbox($nb, 100);

        // - - - - - - - - - - - -

        foreach( $navigation->getNavboxes() as $box )
        {
           echo $theme->beforeNavigationBox();
           echo $theme->NavigationBox($box);
           echo $theme->afterNavigationBox();
        }
    ?>

    <!-- end of navigation bar -->
    <?= $theme->afterNavigationBar() ?>
    <?= $theme->beforeContent() ?>
    <?= $theme->title($page_title) ?>


