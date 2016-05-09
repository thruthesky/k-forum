
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>
<script>(function(d, s, id) {
        var js, fjs = d.getElementsByTagName(s)[0];
        if (d.getElementById(id)) return;
        js = d.createElement(s); js.id = id;
        js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.0";
        fjs.parentNode.insertBefore(js, fjs);
    }(document, 'script', 'facebook-jssdk'));</script>
<script type="text/javascript">
    (function() {
        var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
        po.src = 'https://apis.google.com/js/platform.js';
        var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
    })();
</script>
<?php
// Get current page URL
$crunchifyURL = get_permalink();

// Get current page title
$crunchifyTitle = str_replace( ' ', '%20', get_the_title());

// Get Post Thumbnail for pinterest
$crunchifyThumbnail = wp_get_attachment_image_src( get_post_thumbnail_id( get_the_ID()), 'full' );

// Construct sharing URL without using any script
$twitterURL = 'https://twitter.com/intent/tweet?text='.$crunchifyTitle.'&amp;url='.$crunchifyURL.'&amp;via=Crunchify';
$facebookURL = 'https://www.facebook.com/sharer/sharer.php?u='.$crunchifyURL;
$googleURL = 'https://plus.google.com/share?url='.$crunchifyURL;
//$bufferURL = 'https://bufferapp.com/add?url='.$crunchifyURL.'&amp;text='.$crunchifyTitle;

// Based on popular demand added Pinterest too
//$pinterestURL = 'https://pinterest.com/pin/create/button/?url='.$crunchifyURL.'&amp;media='.$crunchifyThumbnail[0].'&amp;description='.$crunchifyTitle;

$variable = null;
// Add sharing button at the end of page/page content
$variable .= '<!-- Crunchify.com social sharing. Get your copy here: http://crunfy.me/1EFBLtA -->';
$variable .= '<div class="crunchify-social">';
$variable .= '<a class="crunchify-link crunchify-twitter" href="'. $twitterURL .'" target="_blank"><span class="dashicons dashicons-text"></span> Twitter</a>';
$variable .= '<a class="crunchify-link crunchify-facebook" href="'.$facebookURL.'" target="_blank">Facebook</a>';
$variable .= '<a class="crunchify-link crunchify-googleplus" href="'.$googleURL.'" target="_blank">Google+</a>';
$variable .= '</div>';

echo $variable;
?>
