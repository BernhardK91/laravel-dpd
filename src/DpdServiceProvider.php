<?php

namespace BernhardK\Dpd;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class DpdServiceProvider extends ServiceProvider {


    public function boot()
    {

        $this->publishes([
            __DIR__.'/../config/dpd.php' => config_path('dpd.php'),
        ], 'config');

    }

    public function register()
    {

        $this->mergeConfigFrom(
            __DIR__.'/../config/dpd.php', 'dpd'
        );

        $this->app->singleton('dpdShipment', function(){

            if (config('dpd.delisId') == '...') {
                Log::info('DPD: Package is installed, but config is not set yet. Update config at /config/dpd.php.');
            }

            $authorisation = new DPDAuthorisation([
                'staging' => config('dpd.sandbox'),
                'delisId' => config('dpd.delisId'),
                'password' => config('dpd.password'),
                'messageLanguage' => config('dpd.messageLanguage'),
                'customerNumber' => config('dpd.customerUid')
            ]);

            $shipment = new DPDShipment($authorisation);

            // Set the language for the track&trace link
            $shipment->setTrackingLanguage(config('dpd.trackingLanguage'));

            // Enable saturday delivery
            $shipment->setSaturdayDelivery(config('dpd.saturdayDelivery'));

            // Set the printer options
            $shipment->setPrintOptions([
                'printerLanguage' => config('dpd.printerLanguage'),
                'paperFormat' => config('dpd.paperFormat'),
                'startPosition' => config('dpd.startPosition')
            ]);

            return $shipment;
        });

        $this->app->singleton('dpdTracking', function(){

            if (config('dpd.delisId') == '...') {
                Log::info('DPD: Package is installed, but config is not set yet. Update config at /config/dpd.php.');
            }

            $authorisation = new DPDAuthorisation([
                'staging' => config('dpd.sandbox'),
                'delisId' => config('dpd.delisId'),
                'password' => config('dpd.password'),
                'messageLanguage' => config('dpd.messageLanguage'),
                'customerNumber' => config('dpd.customerUid')
            ]);

            $status = new DPDParcelStatus($authorisation);

            return $status;
        });
    }
}