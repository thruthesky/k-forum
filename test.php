<?php

msg('K forum test begin...');
msg('K forum category slug : ' . FORUM_CATEGORY_SLUG );
msg('K forum path : ' . FORUM_FILE_PATH );
msg('K forum url : ' . FORUM_URL );


msg("Rewrite Rules ---------------------");
$rules = get_option('rewrite_rules');
foreach ( $rules as $pattern => $rewrite ) {
    if ( strpos( $pattern, 'forum' ) !== false ) {
        msg("   $pattern => $rewrite" );
    }
}

// '^forum/([^\/]+)/?$'



function msg( $str ) {
    echo $str . "\n";
}