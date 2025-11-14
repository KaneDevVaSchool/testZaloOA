@extends('layouts.master')

@section('content')
<div class="container mx-auto p-4 max-w-md text-center">
    <h2 class="text-2xl font-bold mb-4">Vé QR Code khách mời</h2>

    <div class="inline-block bg-white p-4 shadow rounded mb-4">
        <img src="{{ route('guests.qr.image', $guest->qr_token) }}" alt="QR Code">
    </div>

    {{-- Thông tin khách --}}
    <div id="guestInfo">
        @if ($guest->status && $guest->number)
            <p><strong>Họ tên:</strong> <span id="guestName">{{ $guest->full_name }}</span></p>
            <p><strong>Số điện thoại:</strong> <span id="guestPhone">{{ $guest->phone }}</span></p>
        @else
            <p><strong>Họ tên:</strong> <span id="guestName">---</span></p>
            <p><strong>Số điện thoại:</strong> <span id="guestPhone">---</span></p>
        @endif
    </div>

    <p><strong>Trạng thái:</strong>
        <span id="guestStatus" class="{{ $guest->status ? 'text-green-600 font-bold' : 'text-red-600 font-bold' }}">
            {{ $guest->status ? 'Đã đến' : 'Chưa đến' }}
        </span>
    </p>

    <p class="mt-4"><strong>Mã dự thưởng:</strong>
        <span id="guestNumber">{{ $guest->status ? $guest->number : 'Sẽ được cấp khi xác nhận' }}</span>
    </p>

    <span id="guestCongrats" class="text-green-600 font-bold" style="{{ $guest->status ? '' : 'display:none;' }}">
        Chúc mừng bạn đã đến
    </span>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    let alerted = false;

    async function pollStatus() {
        if(alerted) return;

        try {
            const res = await fetch("{{ route('guests.status', $guest->qr_token) }}");
            const data = await res.json();

            if(data.status) {
                alerted = true;

                // Update trực tiếp trên page
                document.getElementById('guestStatus').textContent = 'Đã đến';
                document.getElementById('guestStatus').className = 'text-green-600 font-bold';
                document.getElementById('guestNumber').textContent = data.number;

                document.getElementById('guestName').textContent = data.full_name;
                document.getElementById('guestPhone').textContent = data.phone;
                document.getElementById('guestCongrats').style.display = 'inline';

                Swal.fire({
                    icon: 'success',
                    title: 'Check-in thành công!',
                    html: `<p>Mã dự thưởng: <strong>${data.number}</strong></p>`
                });
            }
        } catch(err) {
            console.error(err);
        }
    }

    // Poll mỗi 3 giây
    setInterval(pollStatus, 3000);
});
</script>
@endsection
