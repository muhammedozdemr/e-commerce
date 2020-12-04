@extends('yonetim.layouts.master')
@section('title','Kullanıcı Yönetimi')
@section('content')
    <h2 class="sub-header">Kullanıcı Kayıt Formu</h2>
    <form action="{{ route('yonetim.kullanici.kaydet' , @$entry->id) }}" method="post">
        {{csrf_field()}}
        <div class="pull-right">
            <button type="submit" class="btn btn-primary">
                {{ @$entry->id > 0 ? "Güncelle" : "Kaydet" }}
            </button>
        </div>
        <h4 class="sub-header">
            Kullanıcı {{ @$entry->id > 0 ? "Düzenle" : "Ekle" }}
        </h4>

        @include('layouts.partials.errors')
        @include('layouts.partials.alert')
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="adsoyad">Ad Soyad</label>
                    <input type="text" class="form-control" id="adsoyad" name="adsoyad" placeholder="Ad Soyad" value="{{old('adsoyad',$entry->adsoyad)}}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Email" value="{{old('email',$entry->email)}}">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="sifre">Parola</label>
                    <input type="password" class="form-control" id="sifre" name="sifre" placeholder="Parola">
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="adres">Adres</label>
                    <textarea type="text" class="form-control" id="adres" name="adres" placeholder="Adres" value="{{old('adres',$entry->detay->adres)}}"></textarea>
                </div>
            </div>
        </div>
        <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="telefon">Telefon</label>
                <input type="text" class="form-control" id="telefon" name="telefon" placeholder="Telefon" value="{{old('telefon',$entry->detay->telefon)}}">
            </div>
        </div>
        </div>
        <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <label for="ceptelefonu">Cep Telefonu</label>
                <input type="text" class="form-control" id="ceptelefonu" name="ceptelefonu" placeholder="Cep Telefonu" value="{{old('ceptelefonu',$entry->detay->ceptelefonu)}}">
            </div>
        </div>
        </div>
        <div class="checkbox">
            <label>
                <input type="hidden" name="aktif_mi" value="0">
                <input type="checkbox" name="aktif_mi" value="1" {{old('aktif_mi',$entry->aktif_mi) ? 'checked' : ''}}> Aktif Mi
            </label>
        </div>
        <div class="checkbox">
            <label>
                <input type="hidden" name="yonetici_mi" value="0">
                <input type="checkbox" name="yonetici_mi" value="1" {{old('yonetici_mi',$entry->yonetici_mi) ? 'checked' : ''}}> Yönetici Mi
            </label>
        </div>
    </form>
@endsection
