<?php namespace BernhardK\Dpd;

use Exception;
use Illuminate\Support\Facades\Log;
use Soapclient;
use SoapFault;
use SOAPHeader;

class DPDAuthorisation{

    public $authorisation = [
        'staging' => false,
        'delisId' => null,
        'password' => null,
        'messageLanguage' => 'en_EN',
        'customerNumber' => null,
        'token' => null
    ];

    const TEST_LOGIN_WSDL = 'https://public-ws-stage.dpd.com/services/LoginService/V2_0/?wsdl';
    const LOGIN_WSDL = 'https://public-ws.dpd.com/services/LoginService/V2_0?wsdl';

    /**
     * Get an authorisationtoken from the DPD webservice
     * @param array   $array
     * @param boolean $wsdlCache, cache the wsdl
     */
    public function __construct($array, $wsdlCache = true)
    {
        $this->authorisation = array_merge($this->authorisation, $array);
        $this->environment = [
            'wsdlCache' => $wsdlCache,
            'loginWsdl' => ($this->authorisation['staging'] ? self::TEST_LOGIN_WSDL : self::LOGIN_WSDL),
        ];

        if($this->environment['wsdlCache']){
            $soapParams = [
                'cache_wsdl' => WSDL_CACHE_BOTH,
                'trace' => config('dpd.tracing')
            ];
        }
        else{
            $soapParams = [
                'cache_wsdl' => WSDL_CACHE_NONE,
                'exceptions' => true,
                'trace' => config('dpd.tracing')
            ];
        }

        try{

            $client = new Soapclient($this->environment['loginWsdl'], $soapParams);

            $auth = $client->getAuth([
                'delisId' => $this->authorisation['delisId'],
                'password' => $this->authorisation['password'],
                'messageLanguage' => $this->authorisation['messageLanguage'],
            ]);

            if(config('dpd.tracing')) {
                Log::debug('DPD: SOAP-Request Authorisation: ' . $client->__getLastRequest());
                Log::debug('DPD: SOAP-Response Authorisation: ' . $client->__getLastResponse());
            }

            $auth->return->messageLanguage = $this->authorisation['messageLanguage'];
            $this->authorisation['token'] = $auth->return;

            Log::debug('DPD: Authorisation successfull.');
        }
        catch (SoapFault $e){
            Log::emergency('DPD: '.$e->detail->authenticationFault->errorMessage);
        }
    }
}
