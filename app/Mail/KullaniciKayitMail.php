<?php

namespace App\Mail;

use App\Kullanici;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class KullaniciKayitMail extends Mailable
{
    use Queueable, SerializesModels;
    public $kullanici;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Kullanici $kullanici)
    {
        $this->kullanici=$kullanici;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()//Göndereceğimiz mail ile ilgili mesaj aayarlamaları yapılabilir.
    {
        return $this
            //->from('mozdemir23@outlook.com') env. dosyasında ayarlama yapılpıştır.
            ->subject(config('app.name'). '-Kullanici Kaydı')
            ->view('mails.kullanici_kayit');
    }
}
