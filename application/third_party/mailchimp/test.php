<?php 

include('MailChimp.php'); 


$MailChimp = new MailChimp('78ff67ee1547a1264be6842563521ba0-us12');



//print_r($result);
$list_id = '3bca88f363';

$result = $MailChimp->post("lists/$list_id/members", [
                'email_address' => 'test@testmail.com',
                'status'        => 'subscribed',
            ]);
echo "<pre>";
print_r($result);
?>