<?php 
namespace App\Helpers;

use App\Models\Appointment;
use App\Models\Transaction;
use Illuminate\Support\Facades\Http;

/**
 * PortWalletClient
 * 
 * @package MedicsBD
 * @author Saiful Alam <hi@msar.me>
 * @version 1.0.0
 */
class PortWalletClient
{
    private $BASE_URL = "https://api.portwallet.com";

    protected $headers = [];
    protected $customer = [];
    protected $sandbox = "";
    function __construct()
    {
        $key = config('services.payment.portwallet.app_key');
        $secret = config('services.payment.portwallet.app_secret');
        $this->headers = [
            'Authorization' => "Bearer " . base64_encode("{$key}:" . md5($secret . time()))
        ];
        
        $this->sandbox = config('services.payment.portwallet.sandbox', false) ? '-sandbox' : '';
        $this->BASE_URL = "https://api{$this->sandbox}.portwallet.com";
    }

    public function getProcessUrl($id, $fallback = null)
    {
        if( !$id ){
            return $fallback;
        }
        return "https://payment{$this->sandbox}.portwallet.com/payment/?invoice={$id}";
    }

    public function makePayment(Transaction $transaction, Appointment $appointment)
    {
        $this->customer = $transaction->user;
        $response = Http::withHeaders($this->headers)->post("{$this->BASE_URL}/payment/v2/invoice", 
            $this->getPayload($transaction, $appointment));
        return $response->json();
    }

    public function verifyIPN($invoiceID, $amount)
    {
        $response = Http::withHeaders($this->headers)->get("{$this->BASE_URL}/payment/v2/invoice/ipn/{$invoiceID}/{$amount}");
        return $response->json();
    }

    public function getInvoice($invoiceID)
    {
        $response = Http::withHeaders($this->headers)->get("{$this->BASE_URL}/payment/v2/invoice/{$invoiceID}");
        return $response->json();
    }

    public function getPayload(Transaction $transaction, Appointment $appointment)
    {
        $hostname = request()->getHost();
        return [
            'order' => [
                'amount' => (float) $transaction->final_amount,
                'currency' => $transaction->currency ?? 'BDT',
                'redirect_url' => route('payment.portwallet.callback', [
                    'transaction' => $transaction->id, 'appointment' => $appointment->id
                ]),
                'ipn_url' => route('payment.portwallet.callback', [
                    'transaction' => $transaction->id, 'appointment' => $appointment->id
                ]),
                'reference' => $appointment->appointment_code,
                'validity' => 60 * 60 * 2, // 2 hours
            ],
            'product' => [
                'name' => "Appointment {$appointment->appointment_code}",
                'description' => $transaction->description,
            ],
            'billing' => [
                'customer' => [
                    'name' => $this->customer->name,
                    'email' => $this->customer->email ?? "{$this->customer->mobile}@{$hostname}",
                    'phone' => $this->customer->mobile,
                    'address' => [
                        'street' => $this->customer->getMeta('user_address') ?? 'N/A',
                        'city' => $this->customer->getMeta('user_address')['city'] ?? 'None',
                        'state' => $this->customer->getMeta('user_address')['state'] ?? 'None',
                        'zipcode' => $this->customer->getMeta('user_address')['zipcode'] ?? '1000',
                        'country' => 'BD',
                    ],
                ],
            ],
        ];
    }
}
