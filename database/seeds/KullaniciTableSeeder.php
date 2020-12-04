<?php

use Illuminate\Database\Seeder;
use App\Kullanici;
use App\Models\KullaniciDetay;

class KullaniciTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(Faker\Generator $faker)
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        Kullanici::truncate();
        KullaniciDetay::truncate();

        $kullanici_yonetici = Kullanici::create([
            'adsoyad'  => 'Muhammed Özdemir',
            'email'    => 'ozdemir@gmail.com',
            'sifre'    => bcrypt('123456'),
            'aktif_mi' => 1,
            'yonetici_mi' =>1
    ]);
        $kullanici_yonetici->detay()->create([
            'adres'=>'Ankara',
            'telefon' =>'(312) 444 55 66',
            'ceptelefonu'=>'(555) 453 65 45'
        ]);

        for($i=0;$i<50;$i++)
        {
            $kullanici_yonetici=Kullanici::create([
                'adsoyad'  => $faker->name,
                'email'    => $faker->unique()->safeEmail,
                'sifre'    =>bcrypt('123456'),
                'aktif_mi' => 1,
                'yonetici_mi'=>0
            ]);
            $kullanici_yonetici->detay()->create([
                'adres'=>$faker->address,
                'telefon' =>$faker->e164PhoneNumber,
                'ceptelefonu'=>$faker->e164PhoneNumber
            ]);
        }
      DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
