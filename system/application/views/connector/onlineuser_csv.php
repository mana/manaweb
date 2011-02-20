<?php

    // simulate a plain text file
    header('Content-type: text/csv');

    // disable caching for proxies and browsers
    header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
    header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // date in the past

    foreach($users as $user)
    {
        echo sprintf( "\"%s\",%d,%d,%d,\"%s\"\n",
            $user['name'],
            $user['gender'],
            $user['map_id'],
            $user['login_date'],
            date("c", $user['login_date'])
        );
    }

    return;

?>