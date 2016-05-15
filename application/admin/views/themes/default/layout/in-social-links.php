<?php
$franchise = getFranchise1();
if(!$franchise)
    return false;
?>
<div class="col-lg-12 social-part">
    <div class="row">
        <?php if ($franchise->facebook): ?>
            <div class="col-lg-6">
                <div id="fb-root"></div>
                <script>(function (d, s, id) {
                        var js, fjs = d.getElementsByTagName(s)[0];
                        if (d.getElementById(id))
                            return;
                        js = d.createElement(s);
                        js.id = id;
                        js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.3";
                        fjs.parentNode.insertBefore(js, fjs);
                    }(document, 'script', 'facebook-jssdk'));</script>
                <div class="fb-page" data-href="<?php echo $franchise->facebook ?>" data-hide-cover="true" data-show-facepile="true" data-show-posts="false"><div class="fb-xfbml-parse-ignore"><blockquote cite="<?php echo $franchise->facebook ?>"><a href="<?php echo $franchise->facebook ?>"></a></blockquote></div></div>
            </div>
        <?php endif; ?>

        <?php if ($franchise->twitter): ?>
            <div class="col-lg-6">
                <script>window.twttr = (function (d, s, id) {
                        var js, fjs = d.getElementsByTagName(s)[0],
                                t = window.twttr || {};
                        if (d.getElementById(id))
                            return t;
                        js = d.createElement(s);
                        js.id = id;
                        js.src = "https://platform.twitter.com/widgets.js";
                        fjs.parentNode.insertBefore(js, fjs);

                        t._e = [];
                        t.ready = function (f) {
                            t._e.push(f);
                        };

                        return t;
                    }(document, "script", "twitter-wjs"));</script>

                <a class="twitter-timeline"
                   data-widget-id="600720083413962752"
                   href="<?php echo $franchise->twitter ?>"
                   width="300"
                   height="300">
                </a>
            </div>
        <?php endif; ?>
    </div>
    <div class="row">
        <div class="col-lg-6">
            <?php if ($franchise->google): ?>
                <script src="https://apis.google.com/js/platform.js" async defer></script>
                <g:person href="https://plus.google.com/104257338956463298535" data-rel="author"></g:person>
            <?php endif; ?>
        </div>
        <div class=" col-lg-6">
            <?php if ($franchise->pinterest): ?>
                <a data-pin-do="embedUser" href="http://www.pinterest.com/mmathroo/"></a>
                <script type="text/javascript" async defer src="//assets.pinterest.com/js/pinit.js"></script>
            <?php endif; ?>
        </div>
    </div>
    <div class="clearfix"></div>
</div>
