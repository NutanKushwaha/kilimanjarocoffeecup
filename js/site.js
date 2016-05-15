
$(document).ready(function ($) {
    /*----------wishlist-----------*/
    $('.link-wishlist').click(function () {
        var pid = $(this).attr('data-product_id');
        $.ajax({
            url: '/wishlist/add',
            context: document.body,
            type: "POST",
            dataType: 'JSON',
            data: {product_id: pid},
            success: function (data) {
                if (data.response == true)
                {
                    $('#bpopup').html(data.msg);
                    $('#bpopup').fancybox().trigger('click');;
                }
            },
            error: function (data) {
                location.href = "/customer/login"
            },
        })
        return false;
    });
    /*---------flexislider for detail page---------*/
    $('.flexslider').flexslider({
        animation: "slide",
        animationLoop: false,
        itemWidth: 210,
        itemMargin: 5,
        minItems: 2,
        maxItems: 4,
        controlNav: false,
        prevText: "",
        nextText: ""
    });
    /*----------review form---------*/
    $('#reviewForm').submit(function () {
        $('.error').hide();
        $.ajax({
            url: '/rating/',
            type: 'post',
            dataType: 'json',
            data: $('#reviewForm').serialize()
        }).done(function (data) {
            if (data.status == "error")
            {

                $(data.msg.slice(0, -1)).show();
                return false;
            }
            $('.ratingResponse').html(data.html);
            $('#reviewForm').fadeOut();
            return false
        })
        return false;
    })
    /*------------review rating-------------*/
    var $jk = $.noConflict();
    $jk.fn.raty.defaults.path = '/img';

    $jk(function () {
        $jk('#half').raty({
            half: true,
            hints: [['bad 1/2', 'bad'], ['poor 1/2', 'poor'], ['regular 1/2', 'regular'], ['good 1/2', 'good'], ['gorgeous 1/2', 'gorgeous']]
        });

        $jk('#starHalf').raty({
            half: true,
            path: null,
            starHalf: '/img/star-half.png',
            starOff: '/img/star-off.png',
            starOn: '/img/star-on.png'
        });
    });

    $(document).on('click', '.remove-item', function () {
        var row_id = $(this).attr('data-product-id');
        $.get("/catalogue/cart/removeCartItem/" + row_id + '/1', function (data) {
            $('.item-count').html(data.itemcount)
            $('.dropdown-cart').html(data.html)
        }, 'json');
    })

    $(".sm-searbox-content").jqTransform();
    //megamenu complete
    $('#mega-1').dcVerticalMegaMenu({
        rowItems: '4'
    });
    $(".fancybox-button").fancybox({
        prevEffect: 'none',
        nextEffect: 'none',
        closeBtn: false,
        helpers: {
            title: {type: 'inside'},
            buttons: {}
        }
    });

})

