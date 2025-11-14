@extends('layouts.master')

@section('content')
<div class="container mx-auto p-4 max-w-md">
    <div class="bg-white shadow-md rounded-lg p-6 text-center">
        <h2 class="text-2xl font-bold mb-4">Thông tin khách mời</h2>

        <p><strong>Họ tên:</strong> {{ $guest->full_name }}</p>
        <p><strong>Số điện thoại:</strong> {{ $guest->phone }}</p>
        <p><strong>Trạng thái:</strong> 
            @if($guest->status)
                <span class="text-green-600 font-bold">Đã đến</span>
            @else
                <span class="text-red-600 font-bold">Chưa đến</span>
            @endif
        </p>

        @if(!$guest->status)
            <button id="checkinBtn" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
                Xác nhận đến
            </button>
        @else
            <p class="mt-4"><strong>Mã dự thưởng:</strong> {{ $guest->number }}</p>
        @endif
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('checkinBtn');
    if (!btn) return;

    btn.addEventListener('click', () => {
        fetch("{{ route('guests.qr.scan') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({ qr_token: "{{ $guest->qr_token }}" })
        })
        .then(res => res.json())
        .then(data => {
            if (data.ok) {
                alert(`Check-in thành công! Mã dự thưởng: ${data.guest.number}`);
                location.reload();
            } else {
                alert(data.message || 'Lỗi khi check-in');
            }
        })
        .catch(err => {
            console.error(err);
            alert('Có lỗi xảy ra');
        });
    });
});
</script>
@endsection
