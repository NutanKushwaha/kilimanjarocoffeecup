
<div class="col-lg-12">
    <?php // echo $message; ?>
    <h1>Payment Success</h1>
    <p>Thank you for shopping. Your payment is done. Please check the detail below,</p>
    <table class="formTable">
        <tr>
            <td class="fieldLabel">Status:</td>
            <td class="fieldData"><?php echo $payment_details->Status; ?></td>
        </tr>
        <tr>
            <td class="fieldLabel">Amount:</td>
            <td class="fieldData"><?php echo $payment_details->Amount; ?></td>
        </tr>
    </table>
</div>