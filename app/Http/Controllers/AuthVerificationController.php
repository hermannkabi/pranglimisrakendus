<?php

namespace App\Http\Controllers;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Inertia\Inertia;
use Illuminate\Http\Request;

class AuthVerificationController extends Controller
{
    /**
     * Instantiate a new VerificationController instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('signed')->only('verify');
        $this->middleware('throttle:6,1')->only('verify', 'resend');
    }

    /**
     * Display an email verification notice.
     *
     * @return \Illuminate\Http\Response
     */
    public function notice(Request $request)
    {
        return $request->user()->hasVerifiedEmail() 
            ? redirect()->route('dashboard') : Inertia::render('EmailVerify/EmailVerifyPage');
    }

    /**
     * User's email verificaiton.
     *
     * @param  \Illuminate\Http\EmailVerificationRequest $request
     * @return \Illuminate\Http\Response
     */
    public function verify(EmailVerificationRequest $request)
    {
        $request->fulfill();
        return redirect()->route('dashboard');
    }

    /**
     * Resent verificaiton email to user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function resend(Request $request)
    {
        if($request->user()->hasVerifiedEmail()){
            return redirect()->route("dashboard");
        }

        $request->user()->sendEmailVerificationNotification();
        return back()
        ->withSuccess('A fresh verification link has been sent to your email address.');
    }
}
