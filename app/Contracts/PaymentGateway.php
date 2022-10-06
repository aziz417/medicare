<?php 
namespace App\Contracts;

use App\Models\Appointment;
use App\Models\Transaction;

/**
 * Payment Gateway
 * 
 * @package MedicsBD
 * @author Saiful Alam <hi@msar.me>
 * @version 1.0.0
 */
interface PaymentGateway
{
    /**
     *  Set the amount
     *
     * @return mixed
     */
    public function setAmount($amount);

    /**
     *  Set the customer details
     *
     * @return mixed
     */
    public function setCustomer(array $user = []);

    /**
     *  Set the others attributes
     *
     * @return mixed
     */
    public function others(array $others = []);

    /**
     *  Make the payment call
     *
     * @return mixed
     */
    public function payment(Transaction $transaction, Appointment $appointment);
}
