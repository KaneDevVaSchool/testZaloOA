@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4 text-center">
    <h2 class="text-xl font-bold mb-4">QR Code khách mời</h2>

    <div class="inline-block bg-white p-4 shadow rounded">
        <img src="{{ route('guests.qr.image', $guest->qr_token) }}" alt="QR Code">
    </div>

    <p class="mt-4">Họ tên: {{ $guest->full_name }}</p>
    <p>Số điện thoại: {{ $guest->phone }}</p>
</div>
@endsection
