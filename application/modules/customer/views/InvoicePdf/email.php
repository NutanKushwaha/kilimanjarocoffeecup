<table width="100%" cellspacing="0" cellpadding="0" border="0" style="border-collapse:collapse;padding:0;margin:0 auto;background-color:#ebebeb;font-size:12px">
    <tbody>
        <tr>
            <td valign="top" align="center" style="font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;padding:0;margin:0;width:100%">
                <table cellspacing="0" cellpadding="0" border="0" align="center" style="border-collapse:collapse;padding:0;margin:0 auto;width:900px">
                    <tbody>
                        <tr>
                            <td style="font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;padding:0;margin:0">
                                <table cellspacing="0" cellpadding="0" border="0" style="border-collapse:collapse;padding:0;margin:0;width:100%;background: #fff none repeat scroll 0 0;">
                                    <tbody>
                                        <tr>
                                            <td style="font-family: Verdana,Arial; font-weight: normal; border-collapse: collapse; vertical-align: top; padding: 15px 0px 10px 5px; margin: 0px;"> 
                                                <a href="<?= base_url(); ?>" style="color:#3696c2;float:left;display:block" target="_blank"> 
                                                    <img width="auto" border="0" height=" auto" class="CToWUd" src="<?= base_url(); ?>imgs/logo.png" alt="" style="outline:none;text-decoration:none"></a>
                                                <br>                                        
                                                <br>                                        
                                                <br>     
                                                <br>
                                                <h2 style="margin: 0px; padding-left: 10px; font-size: 21px"> KilimanjaroCoffeeCupCompany </h2>
                                                <p style="margin: 0px; padding-left: 10px; font-size: 12px;"> Adams St,<br> New York B7 4LS,<br> US
                                                </p>

                                            </td>
                                            <td style="background:#fff">
                                                <h2 style="text-align: right; font-family: arial; margin-top: 15px; padding-right: 15px; color: rgb(126, 126, 126); font-size: 35px;"> Invoice </h2>
                                                <p style="text-align: right; margin-right: 15px; font-size: 13px; margin-top: 0px;"><strong>Telephone: </strong> +44 121 359 8880  <br>
                                                    <strong>Email:</strong> info@kilimanjaro.com<br>
                                                    <strong>Website:</strong> www.kilimanjaro.com 
                                                </p>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td valign="top" style="font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;padding:5px;margin:0;border:1px solid #ebebeb;background:#fff">
                                <table cellspacing="0" cellpadding="0" border="0" style="border-collapse:collapse;padding:0;margin:0;width:100%">
                                    <tbody>
                                        <tr>
                                            <td style="font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;padding:0;margin:0">
                                                <table cellspacing="0" cellpadding="0" border="0" style="border-collapse:collapse;padding:0;margin:0;width:100%">
                                                    <tbody>
                                                        <tr>
                                                            <td style="font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;padding:10px 15px 0;margin:0;padding-top:10px;text-align:left">
                                                                <div style="border: 1px solid rgb(170, 170, 170); width: 350px; float: left; padding: 0px;">
                                                                    <h6 style="border-bottom: 1px solid rgb(170, 170, 170); font-family: Verdana,Arial; font-weight: 700; font-size: 12px; margin-bottom: 0px; margin-top: 5px; text-transform: uppercase; padding: 5px 10px;">Bill
                                                                        to:</h6>
                                                                    <p style="font-family: Verdana,Arial; font-weight: normal; font-size: 12px; line-height: 20px; margin-bottom: 15px; margin-top: 2px; padding: 5px 10px;"><span>
                                                                            <?= $shipBill['first_name'] . ' ' . $shipBill['last_name'] ?><br>
                                                                            <?= $shipBill['address_1'] ?><br>
                                                                            <?php if ($shipBill['company']) { ?>
                                                                                <?= $shipBill['company'] ?><br>
                                                                            <?php } ?>
                                                                            <?php if ($shipBill['address_2']) { ?>
                                                                                <?= $shipBill['address_2'] ?><br>
                                                                            <?php } ?>
                                                                            <?= $shipBill['city'] ?>, <?= $shipBill['county'] ?>, <?= $shipBill['postcode'] ?><br>
                                                                            <?= $shipBill['country'] ?><br>
                                                                            <?= $shipBill['phone'] ?><br>
                                                                        </span></p>
                                                                </div>
                                                            </td>

                                                            <td style="font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;padding:10px 15px 0;margin:0;padding-top:10px;text-align:left">
                                                                <div style="border: 1px solid rgb(170, 170, 170); width: 350px; float: right;">
                                                                    <h6 style="border-bottom: 1px solid rgb(170, 170, 170); font-family: Verdana,Arial; font-weight: 700; margin-bottom: 0px; margin-top: 5px; text-transform: uppercase; padding: 5px 10px; font-size: 12px;">Ship
                                                                        to:</h6>
                                                                    <p style="font-family: Verdana,Arial; font-weight: normal; font-size: 12px; line-height: 20px; margin-bottom: 15px; margin-top: 2px; padding: 5px 10px;"><span>Joan
                                                                            <?= $shipBill['first_name'] . ' ' . $shipBill['last_name'] ?><br>
                                                                            <?= $shipBill['billing_address1'] ?><br>
                                                                            <?php if ($shipBill['billing_company']) { ?>
                                                                                <?= $shipBill['billing_company'] ?><br>
                                                                            <?php } ?>
                                                                            <?php if ($shipBill['billing_address2']) { ?>
                                                                                <?= $shipBill['billing_address2'] ?><br>
                                                                            <?php } ?>
                                                                            <?= $shipBill['billing_city'] ?>, <?= $shipBill['billing_county'] ?>, <?= $shipBill['billing_zipcode'] ?><br>
                                                                            <?= $shipBill['billing_country'] ?><br>
                                                                            <?= $shipBill['billing_phone'] ?><br>
                                                                        </span></p></div>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;padding:10px 15px 0;margin:0;text-align:left;padding-bottom:10px;">

                                                            </td>
                                                            <td style="width:350px;font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;padding:10px 15px 0;margin:0;text-align:left;padding-bottom:10px"></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                                <table width="650" cellspacing="0" cellpadding="0" border="1" style="border: 1px solid rgb(234, 234, 234); border-collapse: collapse; padding: 0px; margin: 20px 0px 0px; width: 100%;">
                                                    <thead>
                                                        <tr style="border-top: 2px solid #888;border-bottom: 2px solid #888;">
                                                            <th  align="left" style="padding: 8px 9px; font-family: Verdana,Arial; font-size: 14px; font-weight: 600;">Item</th>
                                                            <th  align="left" style="padding: 8px 9px; font-family: Verdana,Arial; font-size: 14px; font-weight: 600;">Sku</th>
                                                            <th  align="center" style="padding: 8px 9px; font-family: Verdana,Arial; font-size: 14px; font-weight: 600;">Qty</th>
                                                            <th  align="right" style="padding: 8px 9px; font-family: Verdana,Arial; font-size: 14px; font-weight: 600;">Subtotal</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <?php foreach ($order_items as $items) { ?>
                                                            <tr>
                                                                <td valign="top" align="left" style="font-family: Verdana,Arial; font-weight: normal; line-height: 18px; font-size: 12px; padding: 15px 10px;">
                                                                    <?= $items['order_item_name'] ?>
                                                                </td>
                                                                <td valign="top" align="left" style="font-family: Verdana,Arial; font-weight: normal; line-height: 18px; font-size: 12px; padding: 15px 10px;"><?= $items['order_item_name'] ?></td>
                                                                <td valign="top" align="center" style="font-family: Verdana,Arial; font-weight: normal; line-height: 18px; font-size: 12px; padding: 15px 10px;"><?= $items['order_item_qty'] ?></td>
                                                                <td valign="top" align="right" style="font-family: Verdana,Arial; font-weight: normal; line-height: 18px; font-size: 12px; padding: 15px 10px;">
                                                                    <span>  <?= MCC_CURRENCY_SYMBOL . $items['order_item_price'] ?></span> </td>
                                                            </tr>
                                                        <?php } ?>
                                                    </tbody>
                                                    <tbody>
                                                        <tr>
                                                            <td align="right" colspan="3" style="font-family: Verdana,Arial; border-collapse: collapse; vertical-align: top; margin: 0px; font-weight: 600; padding: 10px 9px; font-size: 14px;"> Subtotal </td>
                                                            <td align="right" style="padding:3px 9px;font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;margin:0"> <span><?= MCC_CURRENCY_SYMBOL . $order['cart_total'] ?></span> </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right" colspan="3" style="font-family: Verdana,Arial; border-collapse: collapse; vertical-align: top; margin: 0px; font-weight: 600; padding: 10px 9px; font-size: 14px;"> <strong style="">Grand
                                                                    Total (Excl.Tax)</strong>
                                                            </td>
                                                            <td align="right" style="padding:3px 9px;font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;margin:0"> <strong style="font-family:Verdana,Arial;font-weight:normal"><span><?= MCC_CURRENCY_SYMBOL . round(($order['cart_total'] * 100 / (($order['vat'] + 100))), 2) ?></span></strong> </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right" colspan="3" style="font-family: Verdana,Arial; border-collapse: collapse; vertical-align: top; margin: 0px; font-weight: 600; padding: 10px 9px; font-size: 14px;"> VAT (<?= $order['vat'] ?>%) <br>
                                                            </td>
                                                            <td align="right" style="padding:3px 9px;font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;margin:0" rowspan="1"> <span><?= $order['cart_total'] - (round(($order['cart_total'] * 100 / (($order['vat'] + 100))), 2)) ?></span> </td>
                                                        </tr>
                                                        <tr>
                                                            <td align="right" colspan="3" style="font-family: Verdana,Arial; border-collapse: collapse; vertical-align: top; margin: 0px; font-weight: 600; font-size: 15px; padding: 15px 9px;"> <strong style="">Grand
                                                                    Total (Incl.Tax)</strong>
                                                            </td>
                                                            <td align="right" style="padding:3px 9px;font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;margin:0"> <strong style="font-family:Verdana,Arial;font-weight:normal"><span><?= MCC_CURRENCY_SYMBOL . $order['cart_total'] ?></span></strong> </td>
                                                        </tr>
                                                    </tbody>
                                                </table>

                                            </td>
                                        </tr>


                                        <tr>
                                            <td style="font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;padding:0;margin:0">
                                                <table cellspacing="0" cellpadding="0" border="0" style="border-collapse:collapse;padding:0;margin:0">
                                                    <tbody>
                                                        <tr>
                                                            <td style="font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;padding:0 1%;margin:0;background:#e1f0f8;border-right:1px dashed #c3ced4;text-align:center;width:58%">
                                                                <h1 style="font-family:Verdana,Arial;font-weight:700;font-size:16px;margin:1em 0;line-height:20px;text-transform:uppercase;margin-top:25px">Thank you
                                                                    for your order from
                                                                    KilimanjaroCoffeeCupCompany.</h1>
                                                                <p style="font-family:Verdana,Arial;font-weight:normal;line-height:20px;margin:1em 0">Once your package ships
                                                                    we will send an email with
                                                                    a link to track your
                                                                    order. Your order summary
                                                                    is below. Thank you again
                                                                    for your business.</p>
                                                            </td>
                                                            <td style="font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;padding:2%;margin:0;background:#e1f0f8;width:40%">
                                                                <h4 style="font-family:Verdana,Arial;font-weight:bold;margin-bottom:5px;font-size:12px;margin-top:13px">Order
                                                                    Questions?</h4>
                                                                <p style="font-family:Verdana,Arial;font-weight:normal;font-size:11px;line-height:17px;margin:1em 0"> <b>Call Us:</b> <a style="color:#3696c2;text-decoration:underline">+44 121 359 8880</a><br>
                                                                    <b>Email:</b> <a target="_blank" style="color:#3696c2;text-decoration:underline" href="mailto:info@kilimanjaro.com"></a><a target="_blank" href="mailto:info@kilimanjaro.com">info@kilimanjaro.com</a>
                                                                </p>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="font-family:Verdana,Arial;font-weight:normal;border-collapse:collapse;vertical-align:top;padding:5px 15px;margin:0;text-align:center">
                                                <h3 style="font-family:Verdana,Arial;font-weight:normal;font-size:17px;margin-bottom:10px;margin-top:15px">Your
                                                    order <span>#<?= $order['order_num'] ?></span>
                                                </h3>
                                                <p style="font-family:Verdana,Arial;font-weight:normal;font-size:11px;margin:1em 0 15px">Placed on 
                                                    <?php echo date('d M Y h:i:s ', strtotime($order['order_time'])) ?>
                                                </p>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <h5 style="font-family:Verdana,Arial;font-weight:normal;text-align:center;font-size:22px;line-height:32px;margin-bottom:75px;margin-top:30px">Thank
                    you, Kilimanjaro!</h5>
            </td>
        </tr>
    </tbody>
</table>