<?php

namespace App\Http\Controllers;

use App\Jobs\SendVerifyEmail;
use App\Models\User;
use App\Models\VerifyCodes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Stmt\TryCatch;
use App\Jobs\ExpireCode;

class RegisterController extends Controller
{
    public function old2(Request $req)
    {
        $user = User::where('email', $req->email)->first();
        if (empty($user)) {
            return Controller::Response('', true, 'email available');
        } else {
            return Controller::Response('', false, 'email taken');
        }
    }
    public function old(Request $req)
    {
        $user = User::where('email', $req->email)->first();
        $checkUsername = User::Where('username', $req->username)->first();
        if (empty($user)) {
            if (empty($checkUsername)) {
                if (!empty($req->password)) {
                    $createUser = User::create([
                        'username' => $req->username,
                        'password' => Hash::make($req->password),
                        'email' => $req->email
                    ]);
                    $token = $createUser->createToken($req->email)->plainTextToken;
                } else {
                    return Controller::Response('', false, 'invalid password');
                }
            } else {
                return Controller::Response('', true, 'username taken');
            }

            return  Controller::Response(['token' => $token], true, "user created");
        } else {
            return Controller::Response('', false, 'email taken');
        }
    }

    public function sendMail(Request $req)
    {
        // $email = $req->email;
        // $password = $req->password;
        // $isMember = User::Where('email', $email)->first();
        // if (empty($isMember)) {
        //     $hasCode = VerifyCodes::Wehre('email', $email)->first();
        //     if (!empty($hasCode)) {
        //         $VerifyTime = $hasCode->created_at->addMinutes(3);
        //         if ($VerifyTime < now()) {

        //             $newCode = (string) mt_rand(100000, 999999);
        //             $details = [
        //                 'title' => 'Your Verifcation Code MoboPie',
        //                 'body' => $hasCode->code ?? $newCode,
        //                 'email' => $email
        //             ];
        //             $emailData = [
        //                 'email' => $email,
        //                 'details' => $details
        //             ];
        //             SendVerifyEmail::dispatch($emailData);
        //             $on = now()->addMinutes(3);
        //             ExpireCode::dispatch($emailData)->delay($on);
        //         } else {
        //             $timeFirst  = strtotime($VerifyTime);
        //             $timeSecond = strtotime(now());
        //             $differenceInSeconds =   $timeFirst - $timeSecond;
        //             return $SendResponse = Controller::Response(['timeleft' => $differenceInSeconds], false, 'give it a try for a few more moments');
        //         }
        //     }
        // } else {
        //     return Controller::Response('', false, 'email taken');
        // }
        $user = User::where('email', $req->email)->get()->first();
        if (empty($user)) {
            $code = (string) mt_rand(100000, 999999);
            if (empty($user)) {

                $vcode = VerifyCodes::where('email', $req->email)->first();
                if (!empty($vcode)) {


                    $VerifyTime = $vcode->created_at->addMinutes(3);

                    if ($VerifyTime < now()) {

                        $details = [
                            'title' => 'Your Verifcation Code MoboPie',
                            'body' => $vcode->code ?? $code,
                            'email' => $req->email
                        ];
                        $emaildata = [
                            'email' => $req->email,
                            'details' => $details
                        ];
                        SendVerifyEmail::dispatch($emaildata);


                        $on = now()->addMinutes(3);
                        $data = [
                            'email' => $req->email,
                        ];
                        ExpireCode::dispatch($data)->delay($on);

                        $code = $vcode ?? $code;
                        return Controller::Response(['code' => $code->code ?? $code], true, 'sent');
                    } else {
                        $timeFirst  = strtotime($VerifyTime);
                        $timeSecond = strtotime(now());
                        $differenceInSeconds =   $timeFirst - $timeSecond;
                        return $SendResponse = Controller::Response(['timeleft' => $differenceInSeconds], false, 'give it a try for a few more moments');
                    }
                } else {
                    $details = [
                        'title' => 'Your Verifcation Code MoboPie',
                        'body' => $vcode->code ?? $code,
                        'email' => $req->email
                    ];
                    $emaildata = [
                        'email' => $req->email,
                        'details' => $details
                    ];
                    if (empty($vcode)) {
                        VerifyCodes::create([
                            'code' => $vcode->code ?? $code,
                            'email' => $req->email
                        ]);
                    }
                    $emailstatus = SendVerifyEmail::dispatchSync($emaildata);
                    $on = now()->addMinutes(1);
                    $data = [
                        'email' => $req->email,
                    ];
                    ExpireCode::dispatch($data)->delay($on);
                    if ($emailstatus == 'failed') {
                        return Controller::Response('', false, 'email failed to send');
                    }
                    $code = $vcode ?? $code;
                    return Controller::Response(['code' => $code->code ?? $code], true, 'sent');
                }
            } else {
                return Controller::Response('', false, 'duplicate');
            }
        } else {
            return Controller::Response('', false, 'duplicate');
        }
    }
}
