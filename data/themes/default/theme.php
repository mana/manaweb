<?php

require_once(APPPATH.'models/core/Theme'.EXT);

class DefaultTheme extends Theme
{

    public function Logo()
    {
        return "<div id=\"logo\"></div>";
    }

    public function beforeNavigationBar()
    {
        return "<div id=\"page\"><div id=\"sidebar\">";
    }

    public function afterNavigationBar()
    {
        return "</div>";
    }

    public function beforeNavigationBox()
    {
        return "<div class=\"navbox\">";
    }

    public function NavigationBox(NavigationBox $box)
    {
        $str = "
            <div class=\"navheader\"><h1>" . $box->getHeader() . "</h1></div>
                <div class=\"navbody\">
                <ul>";

        foreach( $box->getItems() as $item )
        {
            $str .= sprintf("<li><a href=\"%s\">%s</a></li>\n",
                $item->getUrl(), $item->getText()
                );
        }
        $str .=  "</ul></div>\n";
        return $str;
    }

    public function afterNavigationBox()
    {
        return "<div class=\"navfooter\">&nbsp;</div>
                </div>\n";
    }

    public function beforeContent()
    {
        return "<div id=\"contents\">";
    }

    public function afterContent()
    {
        return "   </div><!-- /contents -->
           <div style=\"clear: both\"></div>";
    }

}

?>
