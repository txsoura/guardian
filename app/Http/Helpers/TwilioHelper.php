<?php

namespace App\Http\Helpers;

use Twilio\Exceptions\ConfigurationException;
use Twilio\Rest\Client;
use Twilio\Rest\Verify\V2\ServiceContext;

class TwilioHelper
{
    /**
     * verify instance
     * @throws ConfigurationException
     */
    public static function verify(): ServiceContext
    {

        $twilio = new Client(config('services.twilio.sid'), config('services.twilio.auth_token'));

        return $twilio->verify->v2->services(config('services.twilio.verify_sid'));
    }
}
