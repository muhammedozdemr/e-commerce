@extends('layouts.master')<!-- Oluşturduğumuz şablonu extends ile belirttik. -->
@section('title','Sepet')<!-- Şablon içerisindeki değiştirilebilir alanları section fonksiyonu ile belirttik. -->
@section('content')
	    <div class="container">
        <div class="bg-content">
            <h2>Sepet</h2>
            @include('layouts.partials.alert')

            @if(count(Cart::content())>0)
            <table class="table table-bordererd table-hover">

                <tr>
                    <th colspan="2">Ürün</th>
                    <th>Adet Fiyatı</th>
                    <th>Adet</th>
                    <th>Tutar</th>
                    <th>İşlem</th>
                </tr>
               @foreach(Cart::content() as $urunCartItem)
                <tr>
                    <td style="width:120px;"><a href="{{route('urun',$urunCartItem->options->slug)}}">
                            <img src="http://via.placeholder.com/120x100?text=ÜrünResmi"></a></td>

                    <td><a href="{{route('urun',$urunCartItem->options->slug)}}">{{$urunCartItem->name}}</a>
                    <form action="{{route('sepet.kaldir',$urunCartItem->rowId)}}" method="post">
                        {{csrf_field() }}
                        {{method_field('DELETE')}}<!-- Sepetteki ürünü kaldırma işlemi -->
                        <input type="submit" class="btn btn-danger btn-xs" value="Sepetten Kaldır">
                    </form>
                    </td>
                    <td>{{$urunCartItem->price}}</td>
                    <td>
                        <a href="#" class="btn btn-xs btn-default urun-adet-azalt" data-id="{{$urunCartItem->rowId}}" data-adet="{{$urunCartItem->qty-1}}">-</a>
                        <span style="padding: 10px 20px">{{$urunCartItem->qty}}</span>
                        <a href="#" class="btn btn-xs btn-default urun-adet-artir" data-id="{{$urunCartItem->rowId}}" data-adet="{{$urunCartItem->qty+1}}">+</a>
                    </td>
                    <td>18.99</td>
                    <td class="text-right" >
                        {{$urunCartItem->subtotal}}<!--Tutar ve adete göre hesplama işlemi-->
                    </td>
                </tr>
                @endforeach
                <tr>
                    <th colspan="4" class="text-right">Alt Toplam</th>
                    <td class="text-right">{{Cart::subtotal()}}</td><!-- Sepetteki tüm ürünlerin fiyat toplamı-->
                </tr>
                <tr>
                    <th colspan="4" class="text-right">KDV</th>
                    <td class="text-right">{{Cart::tax()}}</td><!-- Sepetteki tüm ürünlerin kdv değerini otomatik olarak hesaplar-->
                </tr>
                <tr>
                    <th colspan="4" class="text-right">Genel Toplam</th>
                    <td class="text-right">{{Cart::total()}}</td><!-- Sepetteki tüm ürünlerin fiyat toplamı-->
                </tr>
            </table>
                <div>
                    <form action="{{route('sepet.bosalt')}}" method="post">
                        {{csrf_field()}}
                        {{method_field('DELETE')}}
                        <input type="submit" class="btn btn-info pull-left" value="Sepeti Boşalt">
                    </form>
                    <a href="{{route('odeme')}}" class="btn btn-success pull-right btn-lg">Ödeme Yap</a>
                </div>
            @else
                <p>Sepetinizde ürün yok!</p>
            @endif

        </div>
    </div>
@endsection
@section('footer')
    <script>
        $(function(){
            $('.urun-adet-artir, .urun-adet-azalt').on('click',function(){
                var id=$(this).attr('data-id');
                var adet = $(this).attr('data-adet')?;
                $.ajax({
                    type: 'PATCH',
                    url: '{{url('sepet/guncelle')}}' +id,
                    data: {adet: adet},
                    success: function(){
                        if(result.success){
                            window.location.href= '{{route('sepet')}}';
                        }

                    }
                });
            });
        });
    </script>
    @endsection
