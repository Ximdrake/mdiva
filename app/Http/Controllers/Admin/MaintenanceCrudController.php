<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MaintenanceRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;
use Illuminate\Http\Request;
use App\Models\Patients;
use App\Models\Maintenance;
use Twilio\Rest\Client;
use Backpack\CRUD\app\Library\Widget;
/**
 * Class MaintenanceCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MaintenanceCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Maintenance::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/maintenance');
        CRUD::setEntityNameStrings('maintenance', 'maintenances');
        $this->setupListOperation();
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        CRUD::setFromDb(); // set columns from db columns.
        CRUD::column('patient_id')->type('select')->model('App\Models\Patients')->label("Patient");
        CRUD::column('status')->visibleInTable('true')->label("Status");
        CRUD::button('send')->stack('line')->view('crud::buttons.send')->meta([
            'access' => true,
        ]);
        CRUD::button('check')->stack('line')->view('crud::buttons.check')->meta([
            'access' => true,
        ]);
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(MaintenanceRequest::class);
        CRUD::setFromDb(); // set fields from db columns.
        CRUD::field('patient_id')->type('select')->model('App\Models\Patients')->attribute('full_name')->label("Patient's Name");
        CRUD::field('status')->type('select_from_array')->options(['Pending' => 'Pending', 'Ongoing' => 'Ongoing', 'Completed' => 'Completed']);
        
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    public function send(Request $request, $id)
    {
        $TWILIO_ACCOUNT_SID='AC5fc8541c2877a452e646c0161896045f';
        $TWILIO_AUTH_TOKEN='d84c637709edc8889191bba874dc3f9d';
        $TWILIO_PHONE_NUMBER='+16592468044';
        $maintenance = \App\Models\Maintenance::find($id);
        $patient = Patients::find($maintenance->patient_id);
        $phone = $patient->number;
        $drug = $maintenance->generic_name;
        $sid = $TWILIO_ACCOUNT_SID;
        $token = $TWILIO_AUTH_TOKEN;
        $apiNumber = $TWILIO_PHONE_NUMBER;
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

    public function check(Request $request, $id)
    {
        $maintenance = \App\Models\Maintenance::find($id);
        $maintenance->quantity = intval($maintenance->quantity) - intval($maintenance->per_day);
        if ($maintenance->quantity < 1) {
            $history = \App\Models\History::insert(['patient_id' => $maintenance->patient_id, 'description' => "Maintenance Completed [".$maintenance->quantity_took."/".$maintenance->pres_quantity."]", 'created_at' => date("Y-m-d H:m:s")]);
            $maintenance->status = "Completed";
        } else {
            $history = \App\Models\History::insert(['patient_id' => $maintenance->patient_id, 'description' => "Maintenance still ongoing. Status: [".$maintenance->quantity_took."/".$maintenance->pres_quantity."]", 'created_at' => date("Y-m-d H:m:s")]);
            $maintenance->status = "Ongoing";
        }
    
        $maintenance->quantity_took = $maintenance->quantity_took == null ? intval($maintenance->per_day) : intval($maintenance->quantity_took) + intval($maintenance->per_day);
        $maintenance->save();
    
        if ($maintenance) {
            return response()->json(['success' => true, 'message' => 'Maintenance updated successfully']);
        } else {
            return response()->json(['success' => false, 'message' => 'Failed to update maintenance']);
        }
    }

    public function send2()
    {
      
        $patient = Patients::all()->toArray();
        foreach ($patient as $key => $value) {
            $current_time = date("ga");
            $phone = $value['number'];
            $maintenance = \App\Models\Maintenance::where('patient_id', $value['id'])->get()->toArray();
            $time = $maintenance[0]['time'];
            $generic_name = $maintenance[0]['generic_name'];
            $sid = env('TWILIO_ACCOUNT_SID');
            $token = env('TWILIO_AUTH_TOKEN');
            $apiNumber = env('TWILIO_PHONE_NUMBER');
          
            $client = new Client($sid, $token);
            $to = $this->formatPhoneNumber($phone);
           
            $message = $client->messages->create(
                $to, // To
                [
                    'from' => $apiNumber,
                    'body' => $generic_name,
                ]
            );
            if ($message->sid) {
                return response()->json(['success' => true, 'message' => 'Alert sent successfully', 'data' => json_encode($message->sid)]);
            } else {
                return response()->json(['success' => false, 'message' => 'Failed to send Alert']);
            }
        }
    }

        public function send3()
        {
      
        $maintenance = Maintenance::all()->toArray();
        dd($maintenance);
        // foreach ($patient as $key => $value) {
        //     $current_time = date("ga");
        //     $phone = $value['number'];
        //     $maintenance = \App\Models\Maintenance::where('patient_id', $value['id'])->get()->toArray();
        //     $time = $maintenance[0]['time'];
        //     $generic_name = $maintenance[0]['generic_name'];
        //     $sid = env('TWILIO_ACCOUNT_SID');
        //     $token = env('TWILIO_AUTH_TOKEN');
        //     $apiNumber = env('TWILIO_PHONE_NUMBER');
          
        //     $client = new Client($sid, $token);
        //     $to = $this->formatPhoneNumber($phone);
           
        //     $message = $client->messages->create(
        //         $to, // To
        //         [
        //             'from' => $apiNumber,
        //             'body' => $generic_name,
        //         ]
        //     );
        //     if ($message->sid) {
        //         return response()->json(['success' => true, 'message' => 'Alert sent successfully', 'data' => json_encode($message->sid)]);
        //     } else {
        //         return response()->json(['success' => false, 'message' => 'Failed to send Alert']);
        //     }
        // }
       
    }

   

    // Helper function to format phone number
    private function formatPhoneNumber($phoneNumber)
    {
        $phoneNumber = substr($phoneNumber, 1);
        // Add country code if not already included
        if (substr($phoneNumber, 0, 1) !== '+') {
            $phoneNumber = '+63' . $phoneNumber;
        }

        return $phoneNumber;
    }
    
}

