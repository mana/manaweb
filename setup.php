<?php
/*
 *  The Mana Server Account Manager
 *  Copyright 2009 The Mana Project Development Team
 *
 *  This file is part of The Mana Server.
 *
 *  The Mana Server  is free software; you can redistribute  it and/or modify it
 *  under the terms of the GNU General  Public License as published by the Free
 *  Software Foundation; either version 2 of the License, or any later version.
 *
 *  The Mana Server is  distributed in  the hope  that it  will be  useful, but
 *  WITHOUT ANY WARRANTY; without even  the implied warranty of MERCHANTABILITY
 *  or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for
 *  more details.
 *
 *  You should  have received a  copy of the  GNU General Public  License along
 *  with The Mana Server; if not, write to the  Free Software Foundation, Inc.,
 *  59 Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 */

    define('FCPATH', __FILE__);

    require_once 'ext/php-gettext/gettext.inc';

    function print_check( $msg, $result, $required="", $state="", $ann="" )
    {
        echo "
            <tr>
                <td>$msg</td>
                <td>$required</td>
                <td>$state</td>
                <td width=\"50\" class=\"$result\">" . strtoupper($result) . "</td>
            </tr>\n";

        if (strlen($ann) > 0)
        {
	        echo "
            <tr>
                <td colspan=\"3\" style=\"padding-left: 15px;\">$ann</td>
                <td>&nbsp;</td>
            </tr>\n";
        }
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

    function check_file_exists($filename, $annotation="", $vital=true)
    {
        if (file_exists($filename))
        {
            print_check( "Checking existence of file <tt>$filename</tt>", "ok" );
            return true;
        }
        else
        {
            if ($vital) {
                print_check( "Checking existence of file <tt>$filename</tt>", "failed", "", "", $annotation );
            } else {
                print_check( "Checking existence of file <tt>$filename</tt>", "warning", "", "", $annotation );
            }
            return false;
        }
    }


    function try_read_dir($directory, $annotation="")
    {
        if (!is_dir($directory))
        {
            print_check( "Checking existence of directory <tt>$directory</tt>", "failed", "", "",
            	"<strong><tt>$directory</tt> is not a valid directory.</strong><br />" . $annotation);
            return false;
        }
        try {
            scandir($directory);
            print_check( "Checking read permissions of directory <tt>$directory</tt>", "ok" );
            return true;
        } catch(Exception $e)
        {
            print_check( "Checking read permissions of directory <tt>$directory</tt>", "failed", "", "",
            	"<strong>The webserver cannot read the contents of the directory <tt>$directoy</tt>. Please check permissions!</strong><br />" . $annotation);
            return false;
        }
    }


    function try_write_dir($directory, $annotation="")
    {
        if (!is_dir($directory))
        {
            print_check( "Checking existence of directory <tt>$directory</tt>", "failed", "", "",
            	"<strong><tt>$directory</tt> is not a valid directory.</strong><br />" . $annotation);
            return false;
        }
        try {
            // try to create file and write to it
            $fp = @fopen($directory."/tempfile.tmp", "w+" );
            @fputs($fp, "content");
            @fclose($fp);

            if (!is_file($directory."/tempfile.tmp"))
            {
                throw new Exception();
            }

            @unlink($directory."/tempfile.tmp");

            print_check( "Checking write permissions of directory <tt>$directory</tt>", "ok" );
            return true;
        } catch(Exception $e) {
            print_check( "Checking write permissions of directory <tt>$directory</tt>", "failed", "", "",
            	"<strong>The webserver cannot write to directory <tt>$directory</tt>. Please check permissions!</strong><br />" . $annotation);
            return false;
        }

    }


    function run_checks()
    {
        print_header("Checking technical prerequisits");
        // check version of php, should be >= 5.1
        if (intval(substr(PHP_VERSION, 0, 1)) < 4)
        {
            print_check( "checking version of PHP", "failed", ">= 5.1", PHP_VERSION,
            	"Your installed Version of PHP doesn't match the requirements. ".
            	"Please update to a least 5.1"  );
            return;
        }
        else
        {
            if (intval(substr(PHP_VERSION, 2, 1)) < 1)
            {
                print_check( "checking version of PHP", "failed", ">= 5.1", PHP_VERSION,
                "Your installed Version of PHP doesn't match the requirements. ".
            	"Please update to a least 5.1" );
                return;
            }
            else
            {
                print_check( "checking version of PHP", "ok", ">= 5.1", PHP_VERSION );
            }
        }

        // check existence of config files
        print_header("Checking existance of config files");

        if (!check_file_exists('system/application/config/config.php',
        	"This configuration  file is the basic configuration of your Account Manager installation and is therefore ".
        	"vital for any further step. Something is really wrong if you don't have a copy of this file...")) return;


        // requiring config file
        define('BASEPATH', '.');
        require('./system/application/config/config.php');
        print_message("File ./system/application/config/config.php loaded.");


        if (!check_file_exists('system/application/config/database.php',
        	"This configuration file determines which database system you are using for manaserv and/or the account manager and ".
        	"how we can connect to this database. You can find a template for the configuation file called <tt>database.default.php</tt> ".
        	"in the directory <tt>system/application/config</tt>. Just copy and rename this file to <tt>database.php</tt> and ".
        	"make your modifications.")) return;


        if (!check_file_exists('system/application/config/email.php',
        	"To be able to send emails to the user, you have to configure which method should be used. ".
        	"You can find a template for the configuation file called <tt>email.default.php</tt> ".
        	"in the directory <tt>system/application/config</tt>. Just copy and rename this file to <tt>email.php</tt> and ".
        	"make your modifications.")) return;



        // try to write to directories
        print_header("Checking directory permissions");
        if (!try_write_dir("./data",
        	"This directory is used by manaweb to store cached item or map informations, so make sure it is writeable by the webserver.")) return;

        // checking wheter a custom logpath is set
        if (strlen($config['log_path']) > 0)
        {
        	$logdir = $config['log_path'];
        }
        else
        {
            $logdir = "./system/logs";
        }

        if (!try_write_dir($logdir,
        	"According to your configured parameter <tt>log_threshold</tt> in the <tt>config.php</tt> file, manaweb will ".
        	"log debug or error messages to this directory.")) return;

        // checking wheter a custom cachepath is set
        if (strlen($config['cache_path']) > 0)
        {
            $cachedir = $config['cache_path'];
        }
        else
        {
            $cachedir = "./system/cache";
        }
        if (!try_write_dir($cachedir,
        	"To increase performance of page rendering, manaweb is able to cache static content for faster access. The directory " .
        	"therefore needs to be writeable by the webserver to store caching information.")) return;


        // checking mana_options
        print_header("Checking manaweb specific configuration.");
        require_once('./system/application/config/mana_config.php');
        print_message("File ./system/application/config/mana_config.php loaded.");

        if (file_exists('./system/application/config/mana_config.user.php'))
        {
            require_once('./system/application/config/mana_config.user.php');
            print_message("File ./system/application/config/mana_config.user.php loaded.");
        }
        else
        {
            $msg = "File ./system/application/config/mana_config.user.php not found.";
            print_check( $msg, "warning", "", "",
                "This file is not vital for running manaweb. But you should use it to configure ".
                "individual parameters. If you use the mana_config.php directly, you will have to ".
                "be aware during updates." );
        }


        if ($config['base_url'] == "http://example.com/manaweb/")
        {
            print_check( "parameter <tt>base_url</tt>", "failed", "", "",
            	"This option is necessary to build correct internal links. Please set this parameter to the correct root of ".
            	"your manaweb installation. " );
            return;
        }
        else
        {
            print_check( "parameter <tt>base_url</tt>", "ok" );
        }


        if (!try_read_dir($config['manadata_path'],
                'Manaweb needs a local copy of the clients data directory.'
                )) return;

        if (!try_read_dir($config['manaserv-data_path'],
                'Manaweb needs a local copy of the servers data directory.'
                )) return;

        print_header("Checking database configuration and connection.");
        require_once('./system/application/config/database.php');

        if ($db[$active_group]['dbdriver'] != "pdo")
        {
            print_check( "Used database driver", "failed", $db[$active_group]['dbdriver'], "pdo" );
	    return;
        }
        else
        {
            print_check( "Used database driver", "ok", $db['default']['dbdriver'], "pdo" );
        }

        if(substr($db[$active_group]['database'], 0, 6) == "sqlite")
        {
            print_message("&nbsp; &nbsp; &nbsp; &nbsp; Found sqlite as database backend...");
        }
        elseif(substr($db[$active_group]['database'], 0, 5) == "mysql")
	{
	    print_message("&nbsp; &nbsp; &nbsp; &nbsp; Found mysql as database backend...");
	}
	else
        {
            print_check( "Used database backend", "failed", $db[$active_group]['database'], "(sqlite|mysql)..." );
	    return;
        }

	if(file_exists('data/config_required'))
	{
	    @unlink('data/config_required');
	}

	print_header( "Setup is complete." );
	print_message( "<a href='/' title='Continue'>Continue</a> to your installation." );
    }


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

  <link rel="shortcut icon" href="./images/icon16.png" type="image/png"/>
  <link rel="icon" href="./images/icon16.png" type="image/png"/>

  <style type="text/css">
   @import url( "data/themes/default/default.css");
  </style>
  <style type="text/css">
    .ok {
        font-weight: bold;
        color: green;
        background-color:  #00ff00;
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
   <div id="logo"></div>
   <div id="page">

   <div id="irc_info">
    <a href="irc://irc.freenode.net/themanaworld" title="IRC">
    #themanaworld<br />
    irc.freenode.net
    </a>
   </div>

   <div id="title">
    <h1><span>The Mana Server</span></h1>
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
                  <h2>The Mana Server Account Manager - Setup</h2>
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
    &copy; 2004-2008 The Mana Server Dev Team -
    [<a href="http://validator.w3.org/check?uri=referer">xhtml</a>] [<a href="http://jigsaw.w3.org/css-validator/check/referer">css</a>]
   </div>
  </div> <!-- /page -->

    </body>
</html>
