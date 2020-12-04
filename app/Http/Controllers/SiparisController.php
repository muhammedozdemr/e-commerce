<?php

namespace App\Http\Controllers;

use App\Models\Siparis;
use Illuminate\Http\Request;
use function foo\func;

class SiparisController extends Controller
{
    public function index()
    {
        $siparisler=Siparis::with('sepet')
            ->whereHas('sepet',function ($query){
                $query->where('kullanici_id',auth()->id());
            })//ilişkili bir tabloda filtreleme işlemi whereHas ile

            ->orderByDesc('olusturulma_tarihi')->get();//tüm siparişleri çek
    	return view('siparisler',compact('siparisler'));
    }

    public function detay($id)
    {
        $siparis=Siparis::with('sepet.sepet_urunler.urun')
            ->whereHas('sepet',function ($query){
                $query->where('kullanici_id',auth()->id());
            })//ilişkili bir tabloda filtreleme işlemi whereHas ile

            ->where('siparis.id',$id)->firstOrFail();
    	return view('siparis',compact('siparis'));
    }
}
