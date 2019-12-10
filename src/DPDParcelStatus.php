<?php namespace BernhardK\Dpd;

use Exception;
use Illuminate\Support\Facades\Log;
use Soapclient;
use SoapFault;
use SOAPHeader;

class DPDParcelStatus{

    protected $environment;
    protected $authorisation;

    const TEST_PARCELSTATUS_WSDL = 'https://public-ws-stage.dpd.com/services/ParcelLifeCycleService/V2_0/?wsdl';
    const PARCELSTATUS_WSDL = 'https://public-ws.dpd.com/services/ParcelLifeCycleService/V2_0/?wsdl';
    const SOAPHEADER_URL = 'http://dpd.com/common/service/types/Authentication/2.0';

    /**
     * @param object DPDAuthorisation $authorisationObject
     */
    public function __construct(DPDAuthorisation $authorisationObject, $wsdlCache = true)
    {
        $this->authorisation = $authorisationObject->authorisation;
        $this->environment = [
            'wsdlCache' => $wsdlCache,
            'parcelStatusWsdl' => ($this->authorisation['staging'] ? self::TEST_PARCELSTATUS_WSDL : self::PARCELSTATUS_WSDL),
        ];
    }

    /**
     * Get the parcel's current status
     * @param  string $awb
     * @return array
     */
    public function getStatus($awb)
    {

        if ($this->environment['wsdlCache']){
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

            $client = new Soapclient($this->environment['parcelStatusWsdl'], $soapParams);
            $header = new SOAPHeader(self::SOAPHEADER_URL, 'authentication', $this->authorisation['token']);
            $client->__setSoapHeaders($header);
            $response = $client->getTrackingData(['parcelLabelNumber' => $awb]);

            if(config('dpd.tracing')) {
                Log::debug('DPD: SOAP-Request ParcelStatus: ' . $client->__getLastRequest());
                Log::debug('DPD: SOAP-Response ParcelStatus: ' . $client->__getLastResponse());
            }

            $check = (array)$response->trackingresult;
            if (empty($check)) {
                Log::emergency('DPD: Parcel not found');
                return array();
            }

            foreach($response->trackingresult->statusInfo as $statusInfo){
                if ($statusInfo->isCurrentStatus){
                     return [
                        'statusCode' => $statusInfo->status,
                        'statusLabel' => $statusInfo->label->content,
                        'statusDescription' => $statusInfo->description->content->content,
                    ];
                }
            }
        }
        catch (SoapFault $e)
        {
            Log::emergency('DPD: '.$e->faultstring);
        }
    }
}
