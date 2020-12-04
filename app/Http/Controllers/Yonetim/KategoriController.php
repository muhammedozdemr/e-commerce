<?php

namespace App\Http\Controllers\Yonetim;

use App\Http\Controllers\Controller;
use App\Kullanici;
use App\Models\Kategori;
use App\Models\KullaniciDetay;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class KategoriController extends Controller
{
    public function index()
    {
        if(request()->filled('aranan') ||request()->filled('ust_id'))
        {
            request()->flash();//formdan gönderilen tüm değerleri session içersinde sakla
            $aranan = request('aranan');
            $ust_id = request('ust_id');
            $list=Kategori::with('ust_kategori')
                ->where('kategori_adi','like',"%$aranan%")
                ->where('ust_id',$ust_id)
                ->orderByDesc('id')
                ->paginate(2)
                ->appends(['aranan'=>$aranan,'ust_id'=>$ust_id]);
        }else{
            request()->flush();//Temizle işlemi yapıldığında aranan değerleri arama kutusundan siler
            $list=Kategori::with('ust_kategori')->orderByDesc('id')->paginate(8);//her sayfada 8 kullanıcıyı listele
        }
        $anakategoriler=Kategori::whereRaw('ust_id is null')->get();

        return view('yonetim.kategori.index',compact('list','anakategoriler'));
    }

    public function form($id = 0)
    {
        $entry= new Kategori;
        if($id > 0)
        {
            $entry=Kategori::find($id);
        }

        $kategoriler=Kategori::all();//Tüm kategorileri çetik
        return view('yonetim.kategori.form',compact('entry','kategoriler'));//compact ile view içerisine aktarma işlemi
    }

    public function kaydet($id = 0)
    {
        $data = request()->only('kategori_adi','slug','ust_id');
        if(request()->filled('slug')) {
            $data['slug'] = str_slug(request('kategori_adi'));
            request()->merge(['slug'=>$data['slug']]);
        }
        $this->validate(request(),[
            'kategori_adi'=>'required',
            'slug'=>(request('original_slug')!=request('slug') ? 'unique:kategori,slug' : '')//kategori tablosundaki slug değerini kontrol eder
        ]);

        if($id > 0)
        {
            //güncelleme işlemleri
            $entry=Kategori::where('id',$id)->firstOrFail();
            $entry->update($data);

        }
        else
        {
            //Kaydet işlemleri
            $entry=Kategori::create($data);
        }
        return redirect()
            ->route('yonetim.kategori.duzenle',$entry->id)
            ->with('mesaj',($id > 0 ? 'Güncellendi' : 'Kaydedildi'))
            ->with('mesaj_tur','success');

    }

    public function sil($id)
    {
        //attach :Kayıt ekleme
        //detach:Kayıt silme

        $kategori=Kategori::find($id);
        $kategori->urunler()->detach();
        $kategori->delete();

        return redirect()
            ->route('yonetim.kategori')
            ->with('mesaj','Kayıt Silindi')
            ->with('mesaj_tur','success');
    }
}
