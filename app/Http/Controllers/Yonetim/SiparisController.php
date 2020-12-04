<?php

namespace App\Http\Controllers\Yonetim;

use App\Http\Controllers\Controller;
use App\Models\Siparis;


class SiparisController extends Controller
{
    public function index()
    {
        if(request()->filled('aranan'))
        {
            request()->flash();//formdan gönderilen tüm değerleri session içersinde sakla
            $aranan = request('aranan');
            $list=Siparis::where('adsoyad','like',"%$aranan%")
                ->orWhere('id',$aranan)
                ->orderByDesc('id')
                ->paginate(8)
                ->appends('aranan',$aranan);
        }else{
            $list=Siparis::orderByDesc('id')->paginate(8);//her sayfada 8 kullanıcıyı listele
        }
        return view('yonetim.siparis.index',compact('list'));
    }

    public function form($id = 0)
    {
        if($id > 0)
        {
            $entry=Siparis::with('sepet.sepet_urunler.urun')->find($id);
        }

        return view('yonetim.siparis.form',compact('entry'));
    }

    public function kaydet($id=0)
    {

        $this->validate(request(),[
            'adsoyad'=>'required',
            'adres'=>'required',
            'telefon'=>'required',
            'durum'=>'required'
        ]);
        $data = request()->only('adsoyad','adres','telefon','ceptelefonu','durum');//formdaki güncellenecek veriler


        if($id > 0)
        {
            //güncelleme işlemleri
            $entry=Siparis::where('id',$id)->firstOrFail();
            $entry->update($data);

        }

        return redirect()
            ->route('yonetim.siparis.duzenle',$entry->id)
            ->with('mesaj',($id > 0 ? 'Güncellendi' : 'Kaydedildi'))
            ->with('mesaj_tur','success');

    }

    public function sil($id)
    {
        Siparis::destroy($id);

        return redirect()
            ->route('yonetim.siparis')
            ->with('mesaj','Kayıt Silindi')
            ->with('mesaj_tur','success');
    }
}
