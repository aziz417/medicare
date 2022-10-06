<?php

namespace App\Services;

use Illuminate\Support\Manager;
use App\Services\Payment\Manual;
use App\Services\Payment\PayPal;
use App\Services\Payment\AamarPay;
use App\Services\Payment\PortWallet;
/**
 * Payment Manager
 * 
 * @package MedicsBD
 * @author Saiful Alam <hi@msar.me>
 * @version 1.0.0
 */
class PaymentGatewayManager extends Manager
{
    /**
     * Get the default driver name.
     *
     * @return string
     */
    public function getDefaultDriver()
    {
        return settings('payment_default_gateway', config('system.payment.gateway', 'aamarpay'));
    }

    /**
     * Creates a new Payment driver
     *
     * @return \App\Services\Payment\Manual
     */
    public function createManualDriver()
    {
        return new Manual();
    }

    /**
     * Creates a new Payment driver
     *
     * @return \App\Services\Payment\PortWallet
     */
    public function createPortwalletDriver()
    {
        return new PortWallet();
    }

    /**
     * Creates a new AamarPay Payment driver
     *
     * @return \App\Services\Payment\PayPal
     */
    public function createPaypalDriver()
    {
        return new PayPal();
    }

    /**
     * Creates a new AamarPay Payment driver
     *
     * @return \App\Services\Payment\PayPal
     */
    public function createAamarpayDriver()
    {
        return new AamarPay();
    }

}