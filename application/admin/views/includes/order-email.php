<?php //com_e($order_details,0);
//echo "</pre>"; ?>
<html xmlns="http://www.w3.org/1999/xhtml"><head>
        <!-- If you delete this meta tag, Half Life 3 will never be released. -->
        <meta name="viewport" content="width=device-width">
            <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
                <title>Bodyguard - Workwear Shop</title>
                <link rel="stylesheet" type="text/css" href="stylesheets/email.css">
                    </head>

                    <body bgcolor="#FFFFFF">
                        <table style="width: 900px;margin:0 auto;">
                            <thead>
                                <tr>
                                    <td width="60%">
                                        <a title="Bodyguard - Shop all you want" class="col-md-3" href="http://bodyguard.webnseo.co.uk/">
                                            <img src="http://bodyguard.webnseo.co.uk/imgs/logo.jpg" alt="Bodyguard - Shop all you want"> 
                                        </a>
                                    </td>
                                    <td align="left" width="40%"><h1 class="collapse" style="font-size: 30px;"> Order Confirmation </h1></td> 
                                </tr>
                            </thead>
                        </table>

                        <table style="width: 900px;margin:0 auto;padding-top:30px;">
                            <thead>
                                <tr>
                                    <td width="60%" style="vertical-align: top"> 
                                        <h3 style="font-weight: 600; margin-bottom: 5px; margin-top: 5px; font-size: 16px;"> Order #: <?php echo $order_details['order_num'] ?></h3> 
                                        <h3 style="font-weight: 600; margin-bottom: 5px; margin-top: 5px; font-size: 16px;"> Date : <?php echo date("d M Y",strtotime($order_details['order_time'])); ?> </h3> 
                                    </td>
                                    <td width="40%" style="vertical-align: top"> 
                                        <h3 style="font-weight: 600; margin-bottom: 5px; margin-top: 5px; font-size: 16px;"> 
                                            Bodyguard Workwear Ltd.<br>
                                                Adams Street,<br>
                                                    Birmingham,<br>
                                                        B7 4LS.<br>
                                        </h3> 
                                        <h3 style="font-weight: 600; margin-bottom: 5px; margin-top: 5px; font-size: 16px;"> 
                                            VAT : 227036527 <br>
                                        </h3> 
                                    </td>
                                </tr>
                            </thead>
                        </table>
                        
                        
                        <table style="width: 900px;margin:0 auto;">
                            <thead>
                                <tr>
                                    <td width="100%" style="vertical-align: top; padding-top: 20px; padding-bottom: 20px;"> 
                                        <table cellspacing="0" border="0" style="border: 2px solid rgb(170, 170, 170); padding: 0px; margin: 0px;width:100%">
                                            <thead style="background: rgb(238, 238, 238) none repeat scroll 0% 0%; border: 2px solid rgb(170, 170, 170) ! important;">
                                                <tr style="">
                                                    <th width="60%" style="
                                                        text-align: left;
                                                        padding:10px 15px;
                                                        border-top: 0px solid rgb(170, 170, 170);
                                                        border-right: 2px solid rgb(170, 170, 170);
                                                        border-left: 0px solid rgb(170, 170, 170);
                                                        border-bottom: 2px solid rgb(170, 170, 170);
                                                        margin:0;
                                                    ">
                                                            Sold to:
                                                    </th>
                                                    <th width="40%" style="
                                                            text-align: left;
                                                            padding:10px 15px;
                                                            border:none;
                                                            margin:0;
                                                            border-top: 0px solid rgb(170, 170, 170);
                                                            border-right: none;
                                                            border-left: none;
                                                            border-bottom: 2px solid rgb(170, 170, 170);
                                                        ">
                                                        Ship to:
                                                    </th>
                                                </tr>
                                            </thead>

                                            <tbody style="font-weight: bold; line-height: 22px;">
                                                <tr>
                                                    <td style="padding: 10px 15px;">
                                                        
                                                            <?php echo $order_details['address_1'] ?>,<br>
                                                                <?php echo $order_details['city'] ?>,<br>
                                                                    <?php echo $order_details['county'] ?>, <?php echo $order_details['postcode'] ?> <br>
                                                                        <?php echo $order_details['country'] ?> <br>
                                                                            T: <?php echo $order_details['phone'] ?> <br>
                                                                                <?php echo $order_details['order_email'] ?>
                                                                                </td>
                                                                                <td>
                                                                                    
                                                                                        <?php echo $order_details['billing_address1'] ?>,<br>
                                                                                            <?php echo $order_details['billing_city'] ?>,<br>
                                                                                                <?php echo $order_details['billing_county'] ?>, <?php echo $order_details['postcode'] ?>  <br>
                                                                                                    <?php echo $order_details['billing_country'] ?> <br>
                                                                                                        T: <?php echo $order_details['billing_phone'] ?> <br>
                                                                                                        </td>
                                                                                                        </tr>
                                                                                                        </tbody>
                                                                                                    </table>
                                    </td>
                                </tr>
                            </thead>
                        </table>
                        
                        
                        
                        
                        <table style="width: 900px;margin:0 auto;">
                            <thead>
                                <tr>
                                    <td width="60%" style="vertical-align: top"> 
                                        <table cellspacing="0" style="border:2px solid #a9a9a9; border-bottom:1px solid #a9a9a9; border-left:none;border-right:none;padding-bottom: 40px;width:100%;">
                                            <thead style="background: rgb(238, 238, 238) none repeat scroll 0% 0%; border: 2px solid rgb(170, 170, 170) ! important;">
                                                <tr style="text-align: left">
                                               
                                                <th style="width: 150px; padding:10px 10px;border-bottom:2px solid #a9a9a9"> Sku </th>
                                                <th style="width: 200px; padding:10px 10px;border-bottom:2px solid #a9a9a9"> Qty</th>
                                                <th style="width: 750px; padding:10px 10px;border-bottom:2px solid #a9a9a9"> Product</th>
                                                <th style="width: 120px; padding:10px 10px;border-bottom:2px solid #a9a9a9"> Price</th>
                                                 <th style="width: 80px; padding: 10px; margin-bottom: 40px; border-bottom: 2px solid rgb(169, 169, 169);border-left:2px solid #a9a9a9">Qty</th>
                                                <th style="width: 120px; padding:10px 10px;border-bottom:2px solid #a9a9a9"> Tax</th>
                                                <th style="width: 120px; padding:10px 10px;border-bottom:2px solid #a9a9a9;border-right:2px solid #a9a9a9"> Subtotal</th>
                                                </tr>
                                            </thead>

                                            <tbody style="font-size: 15px;padding: 10px;">
                                                <?php foreach($order_items as $oitem){?>
                                                    <tr style="">
                                                        <td style="padding:15px 10px;">1</td>
                                                        <td style="padding:15px 10px;"> <?php echo $oitem['product_ref'] ?> </td>
                                                       
                                                        <td style="padding:15px 10px;"> <?php echo $oitem['order_item_name'] ?></td>
                                                        <td style="padding:15px 10px;"> £  <?php echo $oitem['order_item_price'] ?>  </td>
                                                         <td style="padding:15px 10px;"> <?php echo $oitem['order_item_qty'] ?> </td>
                                                        <td style="padding:15px 10px;"> £  <?php echo  round($oitem['order_item_qty'] * $oitem['order_item_price'],2) - round((round($oitem['order_item_qty'] * $oitem['order_item_price'],2) * (100 / (100 + $order_details['vat']))), 2) ; ?></td>
                                                        <td style="padding:15px 10px;"> £  <?php echo number_format(round($oitem['order_item_qty'] * $oitem['order_item_price'],2),2)?> </td>
                                                    </tr>
                                                <?php } ?>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </thead>
                        </table>
                        
                        
                                        <table style="width: 900px;margin:0 auto;">
                                            <thead>
                                                <tr>
                                                    <td width="60%" style="vertical-align: top"></td> 
                                                    <td width="40%" style="vertical-align: top;padding-bottom: 10px;padding-top: 10px;"> 
                                                                        <table style="width: 100%">
                                                                            <tbody><tr>
                                                                            <td style="padding: 10px 0;">
                                                                            <strong>Order Subtotal:</strong>
                                                                            </td>
                                                                            <td style="text-align: right;">
                                                                            £ <?php echo $order_details['order_total'] ?>
                                                                            </td>
                                                                            </tr>
                                                                         
                                                                            <tr>
                                                                            <td style="padding: 10px 0;">
                                                                            <strong> VAT [<?php echo number_format($order_details['vat'],2) ?>%]:</strong>
                                                                            </td>
                                                                            <td style="text-align: right;">
                                                                            £ <?php echo  round($order_details['order_total'],2) -
                                                                                    round(($order_details['order_total'] * (100 / (100 + $order_details['vat']))), 2) ?>
                                                                            </td>
                                                                            </tr>
                                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td width="60%"></td>
                                                    <td width="40%" style="border:1px solid #a9a9a9;border-bottom:none; border-left:none;border-right:none;padding-top: 10px;padding-bottom: 10px;"> 
                                                        <table>
                                                            <tbody><tr>
                                                                <td style="padding: 5px 0;">
                                                                    <strong>Grand Total:</strong>
                                                                </td>
                                                                <td style="text-align: right;">
                                                                    <strong>£ <?php echo $order_details['order_total'] ?></strong>
                                                                </td>
                                                            </tr>
                                                            
                                                            
                                                        </tbody></table>
                                                    </td>
                                                </tr>
                                            </thead>
                        </table>
                        
                        
                        <table style="width: 900px;margin:0 auto;">
                            <tbody>
                                <tr>
                                    <td width="100%" style="text-align: center;padding-top: 35px">
                                        <a style="color: rgb(0, 0, 0); text-decoration: none; font-weight: 600; font-size: 16px;" href="#">
                                            Visit Us: <?php echo site_url(); ?>
                                        </a>
                                    </td>
                                    </tr>
                                </tbody>
                            </table>
                            
                        
                        
                        


                                                            <!-- End Code Line -->

                                                            


                                </body></html>