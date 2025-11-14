@extends('layouts.master')

@section('content')
    <div class="container mx-auto p-4 max-w-md text-center">
        <h2 class="text-2xl font-bold mb-4">Vé QR Code khách mời</h2>

        <div class="inline-block bg-white p-4 shadow rounded mb-4">
            <img src="{{ route('guests.qr.image', $guest->qr_token) }}" alt="QR Code">
        </div>
        @if ($guest->number)
            <p><strong>Họ tên:</strong> {{ $guest->full_name }}</p>
            <p><strong>Số điện thoại:</strong> {{ $guest->phone }}</p>
        @endif

        <p><strong>Trạng thái:</strong>
            <span id="guestStatus" class="{{ $guest->status ? 'text-green-600 font-bold' : 'text-red-600 font-bold' }}">
                {{ $guest->status ? 'Đã đến' : 'Chưa đến' }}
            </span>
        </p>

        <p class="mt-4">
            <strong>Mã dự thưởng:</strong>
            <span id="guestNumber">
                {{ $guest->status ? $guest->number : 'Sẽ được cấp khi xác nhận' }}
            </span>
        </p>

        @if ($guest->status)
            <span class="text-green-600 font-bold">Chúc mừng bạn đã đến</span>
        @endif
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            let alerted = false;

            async function checkStatus() {
                if (alerted) return;

                try {
                    const res = await fetch("{{ route('guests.status', $guest->qr_token) }}");
                    const data = await res.json();

                    if (data.status) {
                        alerted = true;

                        // Cập nhật trạng thái trên page
                        document.getElementById('guestStatus').textContent = 'Đã đến';
                        document.getElementById('guestStatus').className = 'text-green-600 font-bold';
                        document.getElementById('guestNumber').textContent = data.number;

                        Swal.fire({
                            icon: 'success',
                            title: 'Check-in thành công!',
                            html: `<p>Mã dự thưởng: <strong>${data.number}</strong></p>`
                        });
                    }
                } catch (err) {
                    console.error(err);
                }
            }

            // Poll status mỗi 3 giây
            setInterval(checkStatus, 3000);
        });
    </script>
@endsection
