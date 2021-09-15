<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\Data;
use App\Jobs\SendMails;
use Twilio\Rest\Client;
use App\Jobs\SaveUsers;
use App\User;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function sendSMS()
    {
        $to = '+2001026880555';
        $message = 'Test From Twilio';

        $accountSid = 'ACf53a8c23c78a600fbefe220c5f4feef6';
        $authToken = 'e9d6bbbfd8c8f1ef0de76b224d269568';
        $twilioNumber = '+12058585531';
        try {
            $client = new Client($accountSid, $authToken);
            $client->messages->create(
                $to, [
                    "body" => $message,
                    "from" => $twilioNumber,
                ]
            );
            return 'good';
        } catch (TwilioException $e) {
            dd($e);
        }
    }
    

    public function sendMails()
    {

        /*
        $emails = Data::select('email')->get();
        foreach($emails as $email){
           Mail::to($email)->send(new \App\Mail\TestMail());
        }
        */

        $emails = Data::chunk(50,function($data){
               dispatch(new SendMails($data));
        });
        return 'will send in back ground can do any other things';
        
    }


    public function createOffer(){
      $users = User::select('id','name')->get();
      return view('createOffer',compact('users'));
    }
    
    public function saveOffer(Request $request){
        //return $request;
        dispatch(new SaveUsers($request->all()));
        return 'success';
    }

}
