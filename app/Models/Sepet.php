<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;


class Sepet extends Model
{
    use SoftDeletes;

    protected $table = "sepet";
    protected $guarded=[];//Belirlenen kolonu korumaya almak

    const CREATED_AT = 'olusturulma_tarihi';
    const UPDATED_AT = 'guncelleme_tarihi';
    const DELETED_AT= 'silinme_tarihi';

    public function siparis()
    {
        return $this->hasOne('App\Models\Siparis');
    }
    public function sepet_urunler()
    {
        return $this->hasMany('App\Models\SepetUrun');
    }

    public static function aktif_sepet_id()
    {
        $aktif_sepet=DB::table('sepet as s')//tabloyu kısaltan bir 's' değere aktardık
            ->leftJoin('siparis as si','si.sepet_id','=','s.id')//siparis tablosu ile ilişki kurulmasını sağladık.'si' değere aktardık
            ->where('s.kullanici_id',auth()->id())//aktif kullanıcıya ait kayıtları çektik
            ->whereRaw('si.id is null')//sepete ait kayıt varsa id değeri dolu gelecektir.Kayıt yok ise id değeri null olarak gelecektir.Yani null değerleri getirecektir
            ->orderByDesc('s.olusturulma_tarihi')//sepetteki oluşturma tarihine göre tersten sıralama
            ->select('s.id')//ilk kayıt
            ->first();//

        if(!is_null($aktif_sepet)) return $aktif_sepet->id;//
    }

    public function sepet_urun_adet()
    {
        return DB::table('sepet_urun')->where('sepet_id',$this->id)->sum('adet');
    }


}
