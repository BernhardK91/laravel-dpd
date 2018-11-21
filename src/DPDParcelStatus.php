<?php namespace MCS;

use Exception;
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
                'cache_wsdl' => WSDL_CACHE_BOTH
            ];
        }
        else{
            $soapParams = [
                'cache_wsdl' => WSDL_CACHE_NONE,
                'exceptions' => true
            ];
        }
        
        try{
            
            $client = new Soapclient($this->environment['parcelStatusWsdl'], $soapParams);
            $header = new SOAPHeader(self::SOAPHEADER_URL, 'authentication', $this->authorisation['token']);
            $client->__setSoapHeaders($header);
            $response = $client->getTrackingData(['parcelLabelNumber' => $awb]);

            $check = (array)$response->trackingresult;
            if (empty($check)) {
                throw new Exception('Parcel not found'); 
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
         throw new Exception($e->faultstring);   
        }
    }
}
