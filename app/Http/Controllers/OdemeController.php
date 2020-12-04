<?php

namespace App\Http\Controllers;

use App\Models\Siparis;
use Illuminate\Http\Request;
use Cart;

class OdemeController extends Controller
{
    public function index()
    {
        if(!auth()->check())
        {
            return redirect()->route('kullanici.oturumac')
            ->With('mesaj_tur','info')
            ->with('mesaj','Ödeme işlemi için oturum açmanız gerekmektedir.');
        }
        else if(count(Cart::content())==0)
        {
            return redirect()->route('anasayfa')
                ->With('mesaj_tur','info')
                ->with('mesaj','Ödeme işlemi için sepetinizde bir ürün bulunmalıdır.');
        }
        $kullanici_detay=auth()->user()->detay;

    	return view('odeme' ,compact('kullanici_detay'));
    }

    public function odemeyap()
    {
        $siparis=\request()->all();
        $siparis['sepet_id']=session('aktif_sepet_id');
        $siparis['banka']="BankaAdı";
        $siparis['taksit_sayisi']=1;
        $siparis['durum']='Siparişiniz alındı';
        $siparis['siparis_tutari']=Cart::subtotal();

        Siparis::create($siparis);
        Cart::destroy();
        session()->forget('aktif_sepet_id');

        return redirect()->route('siparis')
            ->with('mesaj_tur','success')
            ->with('mesaj','Ödeme başarılı bir şekilde gerçekleştirildi.');
    }
}
