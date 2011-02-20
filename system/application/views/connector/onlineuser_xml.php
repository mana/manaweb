<?php

    // simulate a plain text file
    header('Content-type: text/xml');

    // disable caching for proxies and browsers
    header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
    header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // date in the past

    $xml = simplexml_load_string("<?xml version='1.0' encoding='utf-8'?><users />");

    foreach($users as $user)
    {
        $xuser = $xml->addChild("user");
        $xuser->addAttribute("name",            $user['name']);
        $xuser->addAttribute("gender",          $user['gender']);
        $xuser->addAttribute("map",             $user['map_id']);
        $xuser->addAttribute("login_timestamp", $user['login_date']);
        $xuser->addAttribute("login_date",      date("c", $user['login_date']));
    }

    echo $xml->asXML();
    return;

?>