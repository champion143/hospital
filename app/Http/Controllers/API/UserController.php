<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function sendOtp(Request $request)
    {
        $phone_number = $request->input('phone_number');
        $checkCount = Member::where('phone_number',$phone_number)->count();
        if($checkCount == 0)
        {
            $meber = new Member();
            $meber->phone_number = $phone_number;
            $meber->is_name = false;
            $meber->is_email = false;
            $meber->save();
        }
        $member = Member::where('phone_number',$phone_number)->first();
        $member->is_name = $this->getBooleanValue($member->is_name);
        $member->is_email = $this->getBooleanValue($member->is_email);
        $member->auth_token = $this->updateToken($member->id);
        $otp = '1234';
        $member->otp = $otp;
        $userData = array();
        $userData[0] = $member;
        $data = array();
        $data['status'] = 'success';
        $data['messsage'] = 'Send Otp Successfully';
        $data['data'] = $userData;
        echo json_encode($data);
    }

    public function updateToken($user_id)
    {
        $auth_token = Str::random(32);
        Member::where('id',$user_id)
            ->update(
                array(
                    'auth_token' => $auth_token
                )
            );
        return $auth_token;
    }

    public function getBooleanValue($value)
    {
        if($value == 0)
        {
            return false;
        }
        return true;
    }
}
