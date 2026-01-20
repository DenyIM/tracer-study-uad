<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable
{
    use Queueable, SerializesModels;

    public $verificationCode;
    public $userName;

    public function __construct($verificationCode, $userName = null)
    {
        $this->verificationCode = $verificationCode;
        $this->userName = $userName;
    }

    public function build()
    {
        return $this->subject('Kode Verifikasi Reset Password - Tracer Study UAD')
                    ->view('emails.password-reset') // Pastikan ini sesuai
                    ->with([
                        'verificationCode' => $this->verificationCode,
                        'userName' => $this->userName,
                    ]);
    }
}