# DPD Webservices for Laravel
//TODO: Badges (wenn public) https://shields.io

This is a laravel package for the _DPD Webservices_ based on Michiel Meertens' "DPD Webservice"
(https://github.com/meertensm/DPD). 

## Installation
```bash
$ composer require bernhardk/dpd
```

## Features
- Submit a shipment to the dpd webservice and retrieve it's label and tracking information
- Retrieve parcel status information

## Requirements
- PHP SOAP extension needs to be installed (https://stackoverflow.com/questions/2509143/how-do-i-install-soap-extension/41518256)
- Edit configuration parameters in /config/dpd.php. All parameters are described.

## Basic shipment usage
The package registers a class that can be directly used:
```php
app()->dpdShipment
```

The following code describes a sample usage and returns a PDF file.

```php
// Enable DPD B2C delivery method
app()->dpdShipment->setPredict([
    'channel' => 'email',
    'value' => 'someone@mail.com',
    'language' => 'EN'
]);
// ATTENTION: Cause of privacy reasons transmitting clients email address is only allowed if client agreed.

// Set the general shipmentdata
app()->dpdShipment->setGeneralShipmentData([
    'product' => 'CL',
    'mpsCustomerReferenceNumber1' => 'Test shipment'
]);

// Set the sender's address
app()->dpdShipment->setSender([
    'name1' => 'Your Company',
    'street' => 'Street 12',
    'country' => 'NL',
    'zipCode' => '1234AB',
    'city' => 'Amsterdam',
    'email' => 'contact@yourcompany.com',
    'phone' => '1234567645'
]);

// Set the receiver's address
app()->dpdShipment->setReceiver([
    'name1' => 'Joh Doe',
    'name2' => null,
    'street' => 'Street',
    'houseNo' => '12',
    'zipCode' => '1234AB',
    'city' => 'Amsterdam',
    'country' => 'NL',
    'contact' => null,
    'phone' => null,
    'email' => null,
    'comment' => null
]);

// Add as many parcels as you want
app()->dpdShipment->addParcel([
    'weight' => 3000, // In gram
    'height' => 10, // In centimeters
    'width' => 10,
    'length' => 10
]);

app()->dpdShipment->addParcel([
    'weight' => 5000,
    'height' => 0, // In centimeters
    'width' => 0,
    'length' => 0 // All parameters need to be given. Enter 0 if you have no value
]);

// Submit the shipment
app()->dpdShipment->submit();

// Get the trackingdata
$trackinglinks = app()->dpdShipment->getParcelResponses();

// Show the pdf label
header('Content-Type: application/pdf');
echo app()->dpdShipment->getLabels();
```

## Basic tracking usage 
The package registers a class that can be directly used:
```php
app()->dpdTracking
```

The following code describes a sample usage and returns the Tracking-Status.

```php
// Retrieve the parcel's status by it's awb number
$parcelStatus = app()->dpdTracking->getStatus('09981122330100');
```

## Support
If you have any questions or problems please open a new issue.

## License
This package is licensed under the MIT license. The package is based on Michiel Meertens' "DPD Webservice"
(https://github.com/meertensm/DPD), which is also licensed under the MIT license.

## TODOs
- WSDL-Pfade auf aktuelle Version aktualisieren
- README fertig schreiben (inkl. Badgets, Beispielen und Danksagung)
    - https://github.com/meertensm/DPD
    - https://www.youtube.com/watch?v=H-euNqEKACA (ab ca. 00:45)
- Freigabe von DPD einholen
- bei Packagist bereitstellen
- Installation per composer auf leerem Projekt testen