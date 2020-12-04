<?php

namespace App\Http\Controllers;

use App\Models\Sepet;
use App\Models\SepetUrun;
use App\Models\Urun;
use Cart;
use Validator;
use Illuminate\Http\Request;

class SepetController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except('ekle');//Kayıtlı kullanıcılar erişebilir.
    }

    public function index()
    {
    	return view('sepet');
    }

    public function ekle()
    {
        $urun= Urun::find(request('id'));
        $cartItem=Cart::add($urun->id, $urun->urun_adi, 1, $urun->fiyati,['slug'=>$urun->slug]);//Sepete ekleme işlemi

        if(auth()->check())//kullanıcı girişi yapıldıysa
        {
            $aktif_sepet_id=session('aktif_sepet_id');
            if(!isset($aktif_sepet_id)){

            $aktif_sepet=Sepet::create([  //sepet kaydı oluştur
                'kullanici_id'=>auth()->id()
            ]);
            $aktif_sepet_id=$aktif_sepet->id;
            session()->put('aktif_sepet_id',$aktif_sepet_id);
            }
            SepetUrun::updateOrCreate(//sepete ürün ekleme işlemi
                ['sepet_id'=>$aktif_sepet_id,'urun_id'=>$urun->id],
                ['adet'=>$cartItem->qty,'fiyati'=>$urun->fiyati,'durum'=>'Beklemede']
            );
        }

        return redirect()->route('sepet')
            ->with('mesaj_tur','success')
            ->with('mesaj', 'Ürün sepete eklendi.');
    }

    public function kaldir($rowid)
    {
        if(auth()->check())
        {
            $aktif_sepet_id=session('aktif_sepet_id');
            $cartItem=Cart::get($rowid);
            SepetUrun::where('sepet_id',$aktif_sepet_id)->where('urun_id',$cartItem->id)->delete();//id'sine göre ürün silme işlemi
        }
        Cart::remove($rowid);
        return redirect()->route('sepet')
            ->with('mesaj_tur','success')
            ->with('mesaj', 'Ürün sepetten kaldırıldı.');

    }
    public function bosalt()
    {
        if(auth()->check())
        {
            $aktif_sepet_id=session('aktif_sepet_id');
            SepetUrun::where('sepet_id',$aktif_sepet_id)->delete();//tüm ürünleri kaldırma işlemi
        }

        Cart::destroy();
        return redirect()->route('sepet')
            ->with('mesaj_tur','success')
            ->with('mesaj', 'Sepetiniz boşaltıldı.');

    }

    public function guncelle($rowid)
    {
        $validator=Validator::make(request()->all(),[
           'adet'=> '2required|numeric|between:0,5'
        ]);
        if($validator->fails())//hata oluştuğunda
        {
            session()->flash('mesaj_tur','danger');
            session()->flash('mesaj','Adet değeri 0 ile 5 arasında olmalıdır.');
            return response()->json(['success'=>false]);
        }

        if(auth()->check())
        {
            $aktif_sepet_id=session('aktif_sepet_id');
            $cartItem=Cart::get($rowid);
            if(request('adet')==0)
            SepetUrun::where('sepet_id',$aktif_sepet_id)->where('urun_id',$cartItem->id)->delete();
            else
            SepetUrun::where('sepet_id',$aktif_sepet_id)->where('urun_id',$cartItem->id)
            ->update(['adet'=>request('adet')]);//güncelleme işlemi
        }

        Cart::update($rowid,request(('adet')));

        session()->flash('mesaj_tur','success');
        session()->flash('mesaj','Adet bilgisi güncellendi');

        return response()->json(['success'=>true]);
    }

}
