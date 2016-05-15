 <!--<a title="Go to Top" href="#" id="yt-totop"></a>-->
<section id="footer" class="pad-top40">
    <div class="footer_top">

        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center">
            <ul class="social-contact list-inline">
                <li> <a href="#"><i class="fa fa-4x fa-twitter"></i></a></li>
                <li> <a href="#"><i class="fa fa-4x fa-facebook"></i></a></li>
                <li> <a href="#"><i class="fa fa-4x fa-youtube"></i></a></li>
                <li> <a href="#"><i class="fa fa-4x fa-instagram"></i> </a></li>
                <li><a href="#"> <i class="fa fa-4x fa-pinterest"></i></a></li>
            </ul>  
        </div>
        <div class="clearfix"></div>
        <div class="container">
            <hr />
        </div>
        <div class="clearfix"></div>
        <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center pad-bot30 pad-top15">
            <img src="<?=  base_url();?>image/logo.png" alt="kilimanjaro" />
        </div>
    </div>
<div class="clearfix"></div>
</section>

<div class="footer_b pad-top10">
    <div class="container">
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="footer_bottom text-center">
                    <p>&copy; <?php echo date('Y') . '  '; ?>. All Rights Reserved.
                        
                </div>
            </div>


        </div>
    </div>
</div>

<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $("#yt-totop").hide();
        $(function () {
            var wh = $(window).height();
            var whtml = $(document).height();
            $(window).scroll(function () {
                if ($(this).scrollTop() > whtml / 10) {
                    $('#yt-totop').fadeIn();
                } else {
                    $('#yt-totop').fadeOut();
                }
            });
            $('#yt-totop').click(function () {
                $('body,html').animate({
                    scrollTop: 0
                }, 800);
                return false;
            });
        });
    });
</script>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=true"></script>
