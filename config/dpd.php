<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Sandbox
    |--------------------------------------------------------------------------
    |
    | There is a testing environment called sandbox where you can use defined
    | data to test your functionality. As long as sandbox is active, no
    | real shipments will be submitted.
    */

    'sandbox' => true,

    /*
    |--------------------------------------------------------------------------
    | Credentials
    |--------------------------------------------------------------------------
    |
    | Here you need to enter your credentials. If you are still using the sandbox
    | you will find the credentials here: https://esolutions.dpd.com/entwickler/entwicklerdaten/sandbox.aspx
    |
    | delisid       =>  The user's DELIS-Id.
    | customerUid   =>  The user's customer uid. This is needed for subaccounts,
    |                   usually this is equal to DELIS-Id. For sendbox usage use 0.
    | password      =>  The password of the user.
    */

    'delisId'       => '...',
    'customerUid'   => '...',
    'password'      => '...',

    /*
    |--------------------------------------------------------------------------
    | Message language
    |--------------------------------------------------------------------------
    |
    | Defines the language of the messages that are logged. Default is 'en_EN'
    */

    'messageLanguage'       => 'en_EN',

    /*
    |--------------------------------------------------------------------------
    | Tracking language
    |--------------------------------------------------------------------------
    |
    | Defines the language for the track&trace link
    */

    'trackingLanguage'       => 'de_DE',

    /*
    |--------------------------------------------------------------------------
    | Saturday delivery
    |--------------------------------------------------------------------------
    |
    | Defines if saturday delivery is demanded. Only  selectable for product
    | "E12". Default value is false.
    */

    'saturdayDelivery'       => false,

    /*
    |--------------------------------------------------------------------------
    | Printer options
    |--------------------------------------------------------------------------
    |
    | printerLanguage   =>  !!! DEPRECATED !!! => renamed to outputFormat
    |                       The language in which the parcel labels should be
    |                       returned. PDF as file output. In any case the output
    |                       is base64 encoded. Default is PDF.
    | outputFormat      =>  The language in which the parcel labels should be
    |                       returned. PDF as file output. In any case the output
    |                       is base64 encoded. Default is PDF.
    | paperFormat       =>  Declares the paper format for parcel label print,
    |                       either "A4" or "A6". For direct printing the format has
    |                       to be set to "A6". "A7" only for return labels, other
    |                       type are not allowed. Default is A6.
    | startPosition     =>  Start position for print on A4 paper.
    |                       Value range: UPPER_LEFT, UPPER_RIGHT, LOWER_LEFT, LOWER_RIGHT
    */

    'outputFormat'      => 'PDF',
    'paperFormat'       => 'A6',
    'startPosition'     => 'UPPER_LEFT',


    /*
    |--------------------------------------------------------------------------
    | Tracing
    |--------------------------------------------------------------------------
    |
    | If tracing is activated, every SOAP-Request and every SOAP-Response will be
    | logged. For security, privacy and storage reasons this option should only be
    | activated for debug reasons.
    | DEFAULT: false
    */

    'tracing' => false,

];
