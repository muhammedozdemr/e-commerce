<?php

namespace App\Http\Controllers\Yonetim;

use App\Http\Controllers\Controller;
use App\Models\Kategori;
use App\Models\Urun;
use App\Models\UrunDetay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UrunController extends Controller
{
    public function index()
    {
        if(request()->filled('aranan'))
        {
            request()->flash();//formdan gönderilen tüm değerleri session içersinde sakla
            $aranan = request('aranan');
            $list=Urun::where('urun_adi','like',"%$aranan%")
                ->orWhere('aciklama','like',"%$aranan%")
                ->orderByDesc('id')
                ->paginate(8)
                ->appends('aranan',$aranan);
        }else{
            $list=Urun::orderByDesc('id')->paginate(8);//her sayfada 8 kullanıcıyı listele
        }
        return view('yonetim.urun.index',compact('list'));
    }

    public function form($id = 0)
    {
        $entry= new Urun;
        $urun_kategoriler=[];
        if($id > 0)
        {
            $entry=Urun::find($id);
            $urun_kategoriler=$entry->kategoriler()->pluck('kategori_id')->all();
        }

        $kategoriler=Kategori::all();
        return view('yonetim.urun.form',compact('entry','kategoriler','urun_kategoriler'));
    }

    public function kaydet($id=0)
    {
        $data = request()->only('urun_adi','slug','aciklama','fiyati');
        if(request()->filled('slug')) {
            $data['slug'] = str_slug(request('urun_adi'));
            request()->merge(['slug'=>$data['slug']]);
        }
        $this->validate(request(),[
            'urun_adi'=>'required',
            'fiyati'=>'required',
            'slug'=>(request('original_slug')!=request('slug') ? 'unique:urun,slug' : '')//urun tablosundaki slug değerini kontrol eder
        ]);
        $data_detay=request()->only('goster_slider','goster_gunun_firsati','goster_one_cikan','goster_cok_satan','goster_indirimli');

        $kategoriler=request('kategoriler');


        if($id > 0)
        {
            //güncelleme işlemleri
            $entry=Urun::where('id',$id)->firstOrFail();
            $entry->update($data);
            $entry->detay()->update($data_detay);
            $entry->kategoriler()->sync($kategoriler);//senkronize
        }
        else
        {
            //Kaydet işlemleri
            $entry=Urun::create($data);
            $entry->detay()->create($data_detay);

            $entry->kategoriler()->attach($kategoriler);
        }

        if(request()->hasFile('urun_resmi'))
        {
            $this->validate(request(),[
                'urun_resmi' => 'image|mimes:jpg,png,jpeg,gif|max:2048'
            ]);
            $urun_resmi=request()->file('urun_resmi');
            $urun_resmi=request()->urun_resmi;
            //$urun_resmi->extension();//uzantı
            //$urun_resmi->getClientOriginalName();//dosyanın orjinal ismi
            //$urun_resmi->hashName();//rastgele isimlendirme

            $dosyaadi = $entry->id . "-" . time() . "." . $urun_resmi->extension();
            //$dosyaadi = $urun_resmi->getClientOriginalName();
            //$dosyaadi = $urun_resmi->hashName();

            if($urun_resmi->isValid())//cache de geçici bir dosyayı saklar
            {
                $urun_resmi->move('uploads/urunler',$dosyaadi);//yüklenilecek konum

                UrunDetay::updateOrCreate(//varsa güncelle yoksa oluştur
                    ['urun_id'=>$entry->id],
                    ['urun_resmi'=>$dosyaadi]
                );
            }
        }
        return redirect()
            ->route('yonetim.urun.duzenle',$entry->id)
            ->with('mesaj',($id > 0 ? 'Güncellendi' : 'Kaydedildi'))
            ->with('mesaj_tur','success');

    }

    public function sil($id)
    {
        $urun=Urun::find($id);
        $urun->kategoriler()->detach();//kategori_urun tablosundaki veriyi sil
        //$urun->detay()->delete();//urun_detay tablosundaki veriyi sil
        $urun->delete();

        return redirect()
            ->route('yonetim.urun')
            ->with('mesaj','Kayıt Silindi')
            ->with('mesaj_tur','success');
    }
}
