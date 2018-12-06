# DPD Webservices for Laravel

[![Latest Stable Version](https://poser.pugx.org/bernhardk/laravel-dpd/v/stable)](https://packagist.org/packages/bernhardk/laravel-dpd)
[![GitHub issues](https://img.shields.io/github/issues/BernhardK91/laravel-dpd.svg)](https://github.com/BernhardK91/laravel-dpd/issues)
[![GitHub license](https://img.shields.io/github/license/BernhardK91/laravel-dpd.svg)](https://github.com/BernhardK91/laravel-dpd/blob/master/LICENSE.txt)
[![Packagist](https://img.shields.io/packagist/dt/bernhardk/laravel-dpd.svg)](https://packagist.org/packages/bernhardk/laravel-dpd)
[![Twitter](https://img.shields.io/twitter/url/https/github.com/BernhardK91/laravel-dpd.svg?style=social)](https://twitter.com/intent/tweet?text=Wow:&url=https%3A%2F%2Fgithub.com%2FBernhardK91%2Flaravel-dpd)


This is a laravel package for the _DPD Webservices_ based on Michiel Meertens' "DPD Webservice"
(https://github.com/meertensm/DPD). 

## Installation
You can install the package via composer with the following command into an existing laravel project. Please note the requirements below. 
```bash
$ composer require bernhardk/laravel-dpd
```

After that you need to publish the config-file with the following command:
```bash
$ php artisan vendor:publish --provider="BernhardK\Dpd\DpdServiceProvider" --tag="config"
```

As soon you have configured the credentials in /config/dpd.php you are ready to use the package as described below.

## Features
- Submit a shipment to the dpd webservice and retrieve it's label and tracking information
- Retrieve parcel status information

## Requirements
- PHP SOAP extension needs to be installed (https://stackoverflow.com/questions/2509143/how-do-i-install-soap-extension/41518256)
- Edit configuration parameters in /config/dpd.php. All parameters are described.

## DPD Webservice versions

 | Webservice                   | Version   | Documentation |
 | --- | --- | --- |
 | Login Service                | 2.0       | [Login Service Documentation](https://esolutions.dpd.com/dokumente/LoginService_V2_0.pdf) |
 | Shipment Service             | 3.2       | [Shipment Service Documentation](https://esolutions.dpd.com/dokumente/ShipmentService_V3_2.pdf) |
 | Parcel Life Cycle Service    | 2.0       | [Parcel Life Cycle Service Documentation](https://esolutions.dpd.com/dokumente/ParcelLifeCycleService_V2_0.pdf) |

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
