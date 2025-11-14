@extends('layouts.master')
@section('content')
    <h1>Danh sách khách mời</h1>
    <?php
    $guests = App\Models\Guest::all();
    ?>
    <h1>Danh sách khách mời</h1>

    <div style="display: flex; flex-wrap: wrap; gap: 20px;">
        @foreach ($guests as $guest)
            <div style="border:1px solid #ccc; padding:16px; width:220px; text-align:center;">
                <h3>{{ $guest->full_name }}</h3>
                <p>SĐT: {{ $guest->phone }}</p>

                @if ($guest->status)
                    {{-- QR code link tới route check-in --}}
                    <img src="data:image/png;base64,{{ base64_encode(
                        QrCode::format('png')->size(200)->generate(url('/qr/visit/' . $guest->qr_token)),
                    ) }}"
                        alt="QR {{ $guest->full_name }}">

                    <p><strong>Đã check-in</strong></p>
                    <p>Mã dự thưởng: <span style="color:red;">Ẩn</span></p>
                @else
                    <p><em>Chưa check-in</em></p>
                @endif
            </div>
        @endforeach
    </div>
@endsection
