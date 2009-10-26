<?php

require_once(APPPATH.'models/core/Theme'.EXT);

class TMWTheme extends Theme
{

    public function Logo()
    {
        return "<div id=\"page\">

   <div id=\"irc_info\">
    <a href=\"irc://irc.freenode.net/manasource\" title=\"IRC\">
    #manasource<br />
    irc.freenode.net
    </a>

   </div>

   <div id=\"title\">
    <h1><span>The Mana</span></h1>
        </div>";
    }

    public function beforeNavigationBar()
    {
        return " <div id=\"main_topleft\">
    <div id=\"main_rightrepeat\">
     <div id=\"main_topright\">

      <div id=\"main_bottomright\">
       <div id=\"main\">

        <div style=\"clear: both;\"></div>
        <div id=\"sidebar\">";
    }

    public function afterNavigationBar()
    {
        return "   <div></div>

       </div> <!-- /sidebar -->
        ";
    }

    public function beforeNavigationBox()
    {
        return "<div class=\"section_topleft\">
              <div class=\"section_bottomleft\">
               <div class=\"section_topright\">
                <div class=\"section_bottomright\">
                 <div class=\"section\">";
    }

    public function NavigationBox(NavigationBox $box)
    {
        $str = "<ul><li>" .$box->getHeader() . "</li>";
        foreach( $box->getItems() as $item )
        {
            $str .= sprintf("<li><a href=\"%s\">%s</a></li>",
                $item->getUrl(), $item->getText()
                );
        }
        $str .=  "</ul>";
        return $str;
    }

    public function afterNavigationBox()
    {
        return "   </div>
            </div>

           </div>
          </div>
         </div>
";
    }

    public function beforeContent()
    {
        return "<div id=\"contents_leftrepeat\">
         <div id=\"contents_topleft\">
          <div id=\"contents_bottomleft\">
           <div id=\"contents_rightrepeat\">
            <div id=\"contents_topright\">

             <div id=\"contents_bottomright\">
              <div id=\"contents\">";
    }

    public function afterContent()
    {
        return  "              <div style=\"clear: both\"></div>

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
       </div>";

    }

    public function title($string)
    {
        return "<div class=\"main_title_topright\">
                <div class=\"main_title_bottomright\">
                 <div class=\"main_title_topleft\">
                  <h2>" . $string . "</h2>
                 </div>
                </div>
               </div>";

    }
}

?>
