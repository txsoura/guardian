<?php

namespace App\Http\Helpers;

use Twilio\Rest\Client;

class TwilioHelper
{
 // verify instance
 public static function verify()
 {

     $twilio = new Client(config('services.twilio.sid'), config('services.twilio.auth_token'));

     return  $twilio->verify->v2->services(config('services.twilio.verify_sid'));
 }
}
