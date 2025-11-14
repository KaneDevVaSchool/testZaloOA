@extends('layouts.master')

@section('content')
<div class="container mx-auto p-4 max-w-md text-center">
    <h2 class="text-2xl font-bold mb-4">QR Code khách mời</h2>

    <p><strong>Họ tên:</strong> {{ $guest->full_name }}</p>
    <p><strong>Số điện thoại:</strong> {{ $guest->phone }}</p>
    <p><strong>Trạng thái:</strong>
        <span id="guestStatusAdmin" class="{{ $guest->status ? 'text-green-600 font-bold' : 'text-red-600 font-bold' }}">
            {{ $guest->status ? 'Đã đến' : 'Chưa đến' }}
        </span>
    </p>

    <p class="mt-4"><strong>Mã dự thưởng:</strong>
        <span id="guestNumberAdmin">{{ $guest->status ? $guest->number : 'Sẽ được cấp khi xác nhận' }}</span>
    </p>

    @if(!$guest->status)
        <button id="checkinBtn" class="mt-4 bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
            Xác nhận đến
        </button>
    @endif
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const btn = document.getElementById('checkinBtn');
    if(!btn) return;

    btn.addEventListener('click', async () => {
        try {
            const res = await fetch("{{ route('guests.qr.scan') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ qr_token: "{{ $guest->qr_token }}" })
            });

            const data = await res.json();

            if(data.ok) {


                // Cập nhật trạng thái trên page ngay lập tức
                document.getElementById('guestStatusAdmin').textContent = 'Đã đến';
                document.getElementById('guestStatusAdmin').className = 'text-green-600 font-bold';
                document.getElementById('guestNumberAdmin').textContent = data.guest.number;

                Swal.fire({
                    icon: 'success',
                    title: 'Check-in thành công!',
                    html: `
                        <p>Mã dự thưởng: <strong>${data.guest.number}</strong></p>
          
                    `,
                    confirmButtonText: 'OK'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Lỗi',
                    text: data.message || 'Lỗi khi check-in'
                });
            }
        } catch(err) {
            console.error(err);
            Swal.fire({
                icon: 'error',
                title: 'Lỗi',
                text: 'Có lỗi xảy ra'
            });
        }
    });
});
</script>
@endsection
