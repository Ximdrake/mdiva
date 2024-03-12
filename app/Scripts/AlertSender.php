<?php

namespace App\Scripts;

use App\Models\Maintenance;
use App\Models\Patients;
use Twilio\Rest\Client;

class AlertSender
   {
      function Run()
      {
        $maintenance = new Maintenance();
        print json_encode($maintenance->patients());
        die;
        $patient = Patients::find($maintenance->patient_id);
        $phone = $patient->number;
        $drug = $maintenance->generic_name;
        $sid = env('TWILIO_ACCOUNT_SID');
        $token = env('TWILIO_AUTH_TOKEN');
        $apiNumber = env('TWILIO_PHONE_NUMBER');
      
        $client = new Client($sid, $token);
        $to = $this->formatPhoneNumber($phone);
        
        $message = $client->messages->create(
            $to, // To
            [
                'from' => $apiNumber,
                'body' => $drug,
            ]
        );
        // Optionally, you can check if the message was sent successfully
        if ($message->sid) {
            return response()->json(['success' => true, 'message' => 'Alert sent successfully']);
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to send Alert']);
        }
      }
   }
   (new AlertSender)->Run();