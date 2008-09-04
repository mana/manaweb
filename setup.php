<?php
/*
 *  The Mana World Account Manager
 *  Copyright 2008 The Mana World Development Team
 *
 *  This file is part of The Mana World.
 *
 *  The Mana World  is free software; you can redistribute  it and/or modify it
 *  under the terms of the GNU General  Public License as published by the Free
 *  Software Foundation; either version 2 of the License, or any later version.
 *
 *  The Mana  World is  distributed in  the hope  that it  will be  useful, but
 *  WITHOUT ANY WARRANTY; without even  the implied warranty of MERCHANTABILITY
 *  or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for
 *  more details.
 *
 *  You should  have received a  copy of the  GNU General Public  License along
 *  with The Mana  World; if not, write to the  Free Software Foundation, Inc.,
 *  59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 *  $Id$
 */

    function print_check( $msg, $result, $required="", $state="" )
    {
        echo "
            <tr>
                <td>$msg</td>
                <td>$required</td>
                <td>$state</td>
                <td width=\"50\" class=\"$result\">" . strtoupper($result) . "</td>
            </tr>\n";
        ob_flush();
        flush();
    } 
    
    function print_header($name)
    {
        echo "<tr><td colspan=\"4\" style=\"background-color: #dbbba4;\"><strong>$name</strong></td></tr>\n";
    }
    
    function print_message($msg)
    {
        echo "<tr><td colspan=\"4\"><em>$msg</em></td></tr>\n";
    }
    
    function check_file_exists($filename, $vital=true)
    {
        if (file_exists( $filename ))
        {
            print_check( "Checking existence of file <tt>$filename</tt>", "ok" );
            return true;
        }
        else
        {
            if ($vital) {
                print_check( "Checking existence of file <tt>$filename</tt>", "failed" );
            } else {
                print_check( "Checking existence of file <tt>$filename</tt>", "warning" );
            }
            return false;
        }
    }
    
    
    function try_read_dir($directory)
    {
        if (!is_dir($directory))
        {
            print_check( "Checking existence of directory <tt>$directory</tt>", "failed" );
            return false;
        }
        try {
            scandir($directory);
            print_check( "Checking read permissions of directory <tt>$directory</tt>", "ok" );
            return true;
        } catch(Exception $e)
        {
            print_check( "Checking read permissions of directory <tt>$directory</tt>", "failed" );
            return false;
        }
    }
    
    
    function try_write_dir($directory)
    {
        if (!is_dir($directory))
        {
            print_check( "Checking existence of directory <tt>$directory</tt>", "failed" );
            return false;
        }
        try {
            // try to create file and write to it
            $fp = fopen($directory."/tempfile.tmp", "w+" );
            fputs($fp, "content");
            fclose($fp);
            unlink($directory."/tempfile.tmp"); 
            
            print_check( "Checking write permissions of directory <tt>$directory</tt>", "ok" );
            return true;
        } catch(Exception $e) {
            print_check( "Checking write permissions of directory <tt>$directory</tt>", "failed" );
            return false;
        }
            
    }
    
    
    function run_checks()
    {
        print_header("Checking technical prerequisits");
        // check version of php, should be >= 5.1
        if (intval(substr(PHP_VERSION, 0, 1)) < 4)
        {
            print_check( "checking version of PHP", "failed", ">= 5.1", PHP_VERSION );
            return;
        }
        else
        {
            if (intval(substr(PHP_VERSION, 2, 1)) < 1)
            {
                print_check( "checking version of PHP", "failed", ">= 5.1", PHP_VERSION );
                return;
            }
            else
            { 
                print_check( "checking version of PHP", "ok", ">= 5.1", PHP_VERSION );
            }
        }
        
        // check existence of config files
        print_header("Checking existance of config files");
        if (!check_file_exists('system/application/config/config.php')) return;
        if (!check_file_exists('system/application/config/database.php')) return;
        if (!check_file_exists('system/application/config/email.php')) return;
        
        if (!check_file_exists('system/application/config/tmw_config.user.php', false)) return;  
         
        // try to write to directories
        print_header("Checking directory permissions");
        if (!try_write_dir("./data")) return;
        if (!try_write_dir("./system/logs")) return;
        if (!try_write_dir("./images/items")) return;
        
        // requiring config file
        define('BASEPATH', '.');
        require('./system/application/config/config.php');
        
        // checking config options
        print_header("Checking options in file <tt>./system/application/config/config.php</tt>");
        if ($config['base_url'] == "http://example.com/tmwweb/")
        {   
            print_check( "parameter <tt>base_url</tt>", "failed" );
            return;
        }
        else
        {
            print_check( "parameter <tt>base_url</tt>", "ok" );
        }
        
        // checking wheter a custom logpath is set
        if (strlen($config['log_path']) > 0)
        {
            if (!try_write_dir($config['log_path'])) return;
        }
        // checking wheter a custom cachepath is set
        if (strlen($config['cache_path']) > 0)
        {
            if (!try_write_dir($config['cache_path'])) return;
        }
        
        // checking tmw_options
        print_header("Checking options in file <tt>./system/application/config/tmw_config(.user).php</tt>");
        require_once('./system/application/config/tmw_config.php');
        if (file_exists('./system/application/config/tmw_config.user.php'))
        {
            require_once('./system/application/config/tmw_config.user.php');
        }
        
        if (!check_file_exists($config['tmwserv_maps.xml'])) return;
        if (!check_file_exists($config['tmwserv_items.xml'])) return;
        if (!try_read_dir($config['tmwserv_items_images'])) return;
        
        print_header("Checking database configuration and connection.");
        require_once('./system/application/config/database.php');
        
        if ($db['default']['dbdriver'] != "pdo")
        {
            print_check( "Used database driver", "failed", $db['default']['dbdriver'], "pdo" );
        }
        else
        {
            print_check( "Used database driver", "ok", $db['default']['dbdriver'], "pdo" );
        }
        
        if(substr($db['default']['database'], 0, 6) == "sqlite")
        {
            print_message("&nbsp; &nbsp; &nbsp; &nbsp; Found sqlite as database backend...");
        }
        else
        {
            print_check( "Used database backend", "failed", $db['default']['database'], "sqlite" );
        }
        
    }
    
 
echo "<?xml version=\"1.0\" encoding=\"ISO-8859-15\" ?>"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
 <head>
  <title>The Mana World</title>
  <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-15"/>
  <meta http-equiv="Content-Style-Type" content="text/css"/>
  <meta http-equiv="Content-Language" content="en"/>
  
  <link rel="shortcut icon" href="./images/icon16.png" type="image/png"/>
  <link rel="icon" href="./images/icon16.png" type="image/png"/>
  
  <meta name="description" content="The Mana World is a free MMORPG game for Linux, MacOS X and Windows"/>
  <meta name="keywords" content="The Mana World, Mana, World, MMORPG, RPG, free, GPL"/>
  <meta name="author" content="The Mana World Dev Team"/>
  <style type="text/css">
   @import url( "default.css");
  </style>
  <style type="text/css">
    .ok {
        font-weight: bold;
        color: dark-green;
        background-color: #00ff00;
    }
    .failed {
        font-weight: bold;
        color: #000000;
        background-color: #ff6666;
    }
    .warning {
        font-weight: bold;
        color: #000000;
        background-color: #ffff66;
    }
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
                  <h2>The Mana World Account Manager - Setup</h2>
                 </div>
                </div>
               </div>
                      
   <table class="datatable">
   <tr>
        <th>Action</th>
        <th>Required Value</th>
        <th>Your value</th>
        <th>Success</th>
    </tr>                
    <?php
        ob_start();
        run_checks();
    ?> 

    </table>

               <div style="clear: both"></div>

              </div> <!-- /contents -->
             </div>
            </div>
           </div>
          </div>
         </div>
        </div>

       </div> <!-- /main -->
      </div>
     </div>
    </div>
   </div>
   
    <div id="footer">
    &copy; 2004-2008 The Mana World Dev Team -
    [<a href="http://validator.w3.org/check?uri=referer">xhtml</a>] [<a href="http://jigsaw.w3.org/css-validator/check/referer">css</a>]
   </div>
  </div> <!-- /page -->

    </body>
</html> 
