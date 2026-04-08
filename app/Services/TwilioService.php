<?php
namespace App\Services;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class TwilioService
{
    protected  $sid;
    protected  $token;
    protected  $verifySid;
    protected  $from;
    protected  $base_url;
    protected $channel;

    public function __construct()
    {
        $this->base_url = config('twilio.base_url', 'https://api.twilio.com');
        $this->sid = config('twilio.sid');
        $this->token = config('twilio.token');
        $this->verifySid = config('twilio.verify_sid');
        $this->from = config('twilio.from');
        $this->channel = config('twilio.channel', 'sms');
    }

    private function makeRequest($url, $data): \Illuminate\Http\Client\Response
    {
        return Http::withBasicAuth($this->sid,$this->token)
            ->timeout(60)
            ->asForm()
            ->post($url, $data);
    }

    public function sendVerificationCode($phoneNumber): array
    {
        try {
            if (!preg_match('/^(\+966\d{9}|\+20\d{10})$/', $phoneNumber)) {
                return [
                    'success' => false,
                    'status' => 400,
                    'message' => translate_message('invalid_phone_number'),
                ];
            }
            $url = "$this->base_url/v2/Services/{$this->verifySid}/Verifications";
            $response=$this->makeRequest($url, [
                'To' => $phoneNumber,
                'Channel' => $this->channel,
            ]);
            return[
                'success' => $response->successful(),
                'status'=>$response->json('status'),
                 'message' =>$response->json('status')==='pending'
                   ?translate_message('success_verify')
                   :translate_message('failed_send_code'),
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'status' => 500,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function verifyCode($phoneNumber, $code): array
    {
        try {
            $url = "$this->base_url/v2/Services/$this->verifySid/VerificationCheck";
            $response = $this->makeRequest($url, [
                'To' => $phoneNumber,
                'Code' => $code,
            ]);
            Storage::put('verification.json',json_encode($response->json()) );
                return [
                    'success'=>$response->successful(),
                    'status'=>$response->json('status'),
                    'message' =>$response->json('status')==='approved'
                        ?translate_message('success_verify_otp')
                        : translate_message('failed_verify_otp'),
                ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'status' => 500,
                'message' => $e->getMessage(),
            ];
        }
    }
    public function sendSMS($toPhoneNumber, $message): array
    {
        try {
            if (!preg_match('/^(\+966\d{9}|\+20\d{10})$/', $toPhoneNumber)) {
                return [
                    'success' => false,
                    'status' => 400,
                    'message' => 'Invalid phone number format. It should start with +966 (Saudi) or +20 (Egypt).',
                ];
            }

            $url = "$this->base_url/2010-04-01/Accounts/{$this->sid}/Messages.json";

            $response = $this->makeRequest($url, [
                'To' => $toPhoneNumber,
                'From' => $this->from,
                'Body' => $message,
            ]);
              Storage::put('test.json',json_encode($response->json()));
            return [
                'success' => $response->successful(),
                'status' => $response->json('status'),
                'message' => $response->json('status') === 'queued'
                    ? 'Message Sent Successfully'
                    : 'Failed to Send Message',
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'status' => 500,
                'message' => $e->getMessage(),
            ];
        }
    }
}
