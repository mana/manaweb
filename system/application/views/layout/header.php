<?php echo "<?xml version=\"1.0\" encoding=\"ISO-8859-15\" ?>"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title>The Mana World</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-15"/>
  <meta http-equiv="Content-Style-Type" content="text/css"/>
  <meta http-equiv="Content-Language" content="en"/>
  
  <meta name="description" content="The Mana World is a free MMORPG game for Linux, MacOS X and Windows"/>
  <meta name="keywords" content="The Mana World, Mana, World, MMORPG, RPG, free, GPL"/>
  <meta name="author" content="The Mana World Dev Team"/>
  
  <link rel="shortcut icon" href="<?= base_url() ?>images/icon16.png" type="image/png"/>
  <link rel="icon" href="<?= base_url() ?>images/icon16.png" type="image/png"/>

  <script src="<?= base_url() ?>ext/scriptaculous-js-1.8.2/lib/prototype.js" type="text/javascript"></script>
  <script src="<?= base_url() ?>ext/scriptaculous-js-1.8.2/src/scriptaculous.js" type="text/javascript"></script>

  <style type="text/css">
   @import url( "<?= base_url() ?>default.css");
  </style>
 </head>
 <body>
  <a name="top"></a>
  <div id="page">

   <div id="irc_info">
    <a href="irc://irc.freenode.net/themanaworld" title="IRC">
    #themanaworld<br />
    irc.freenode.net
    </a>
   </div>

   <div id="title">
    <h1><span>The Mana World</span></h1>
   </div>

   <div id="main_topleft">
    <div id="main_rightrepeat">
     <div id="main_topright">
      <div id="main_bottomright">
       <div id="main">

        <div style="clear: both;"></div>

        <div id="sidebar">

         <div class="section_topleft">
          <div class="section_bottomleft">
           <div class="section_topright">
            <div class="section_bottomright">
             <div class="section">
               <!-- No newlines after list items because IE 6 can't handle that properly -->
               <ul><?php foreach( $static_menu as $link )
                     { ?><li><a href="<?= $link['url'] ?>"><?= $link['name'] ?></a></li><?php } ?></ul>
             </div>
            </div>
           </div>
          </div>
         </div>

         
         <div class="section_topleft">
          <div class="section_bottomleft">
           <div class="section_topright">
            <div class="section_bottomright">
             <div class="section">                
               <ul><li>Account Manager</li><li><a href="<?= site_url('myaccount') ?>">My Account</a></li><?php if (isset($user_menu) ) {
                   foreach( $user_menu as $link )
                     { ?><li><a href="<?= $link['url'] ?>"><?= $link['name'] ?></a></li><?php } ?></ul>
                <? } ?>
             </div>
            </div>
           </div>
          </div>
         </div>
         
         <? if (isset($character_menu)) { ?>
         <div class="section_topleft">
          <div class="section_bottomleft">
           <div class="section_topright">
            <div class="section_bottomright">
             <div class="section">                
               <ul>
                    <li><?= $character_menu->getName() ?></li>
                    <li><a href="<?= site_url('charcontroller/' . $character_menu->getId() ) ?>">Character sheet</a></li>
                    <li><a href="<?= site_url('charcontroller/' . $character_menu->getId() ) ?>/skills">Skills</a></li>
                    <li><a href="<?= site_url('charcontroller/' . $character_menu->getId() ) ?>/inventory">Inventory</a></li>
                    <li><a href="<?= site_url('charcontroller/' . $character_menu->getId() ) ?>/guilds">Parties &amp; Guilds</a></li>
                    <li><a href="<?= site_url('charcontroller/' . $character_menu->getId() ) ?>/admin">Administration</a></li>
               </ul>
             </div>
            </div>
           </div>
          </div>
         </div>
         <? } ?>
         
         <? if (isset($user_menu) && $this->user->isAdmin()) { ?>
         <div class="section_topleft">
          <div class="section_bottomleft">
           <div class="section_topright">
            <div class="section_bottomright">
             <div class="section">                
               <ul>
                    <li>Admin Interface</li>
                    <li><a href="<?= site_url('admin') ?>">Admin Interface</a></li>
                    <li><a href="<?= site_url('admin/maintenance') ?>">Maintenance</a></li>
               </ul>
             </div>
            </div>
           </div>
          </div>
         </div>
         <? } ?>
         
         <div class="section_topleft">
          <div class="section_bottomleft">
           <div class="section_topright">
            <div class="section_bottomright">
             <div class="section">                
               <ul><li>ManaBay</li><li><a href="<?= site_url('manabay') ?>">Auctions</a></li>
             </div>
            </div>
           </div>
          </div>
         </div>
         
         

       <!-- This empty div fixes a rendering issue with IE 7 -->
       <div></div>

       </div> <!-- /sidebar -->

        <div id="contents_leftrepeat">
         <div id="contents_topleft">
          <div id="contents_bottomleft">
           <div id="contents_rightrepeat">
            <div id="contents_topright">
             <div id="contents_bottomright">
              <div id="contents">

               <div class="main_title_topright">
                <div class="main_title_bottomright">
                 <div class="main_title_topleft">
                  <h2><?=$page_title?></h2>
                 </div>
                </div>
               </div>
               