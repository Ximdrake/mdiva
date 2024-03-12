<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MinuteTask extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:minute-task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        dd("ayay");
        // $patient = Patients::all();
        // foreach ($variable as $key => $value) {
        //     # code...
        // }
        // $phone = $patient->number;
       
        // $sid = env('TWILIO_ACCOUNT_SID');
        // $token = env('TWILIO_AUTH_TOKEN');
        // $twilioPhoneNumber = env('TWILIO_PHONE_NUMBER');
      
        // $client = new Client($sid, $token);
        // $to = $this->formatPhoneNumber($phone);
        // return response()->json(['success' => true, 'message' => 'Alert sent successfully']);
        // // $message = $client->messages->create(
        // //     $to, // To
        // //     [
        // //         'from' => $twilioPhoneNumber,
        // //         'body' => 'biogesic na lang po kaya namin',
        // //     ]
        // // );

        // // Optionally, you can check if the message was sent successfully
        // if ($message->sid) {
        //     return response()->json(['success' => true, 'message' => 'Alert sent successfully']);
        // } else {
        //     return response()->json(['success' => false, 'message' => 'Failed to send Alert']);
        // }
    }
}
