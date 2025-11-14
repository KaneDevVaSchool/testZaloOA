@extends('layouts.master')

@section('content')
    <div class="container mx-auto p-4 max-w-md text-center">
        <h2 class="text-2xl font-bold mb-4"> Vé QR Code khách mời</h2>

        <div class="inline-block bg-white p-4 shadow rounded mb-4">
            <img src="{{ route('guests.qr.image', $dataCheckinCus->qr_token) }}" alt="QR Code">
        </div>

        @if ($dataCheckinCus->status == 1)
            <p><strong>Họ tên:</strong> {{ $dataCheckinCus->full_name }}</p>
            <p><strong>Số điện thoại:</strong> {{ $dataCheckinCus->phone }}</p>
      
        @endif
        </p>
        {{-- 
    <p><strong>Họ tên:</strong> {{ $dataCheckinCus->full_name }}</p>
    <p><strong>Số điện thoại:</strong> {{ $dataCheckinCus->phone }}</p>
    <p><strong>Trạng thái:</strong> 
        @if ($dataCheckinCus->status)
            <span class="text-green-600 font-bold">Đã đến</span>
        @else
            <span class="text-red-600 font-bold">Chưa đến</span>
        @endif
    </p> --}}

        {{-- chỗ này sẽ gửi lại mã dự thưởng khi checkin --}}
        @if (!$dataCheckinCus->status)
            <p class="mt-4"><strong>Mã dự thưởng:</strong> Sẽ được cấp khi xác nhận</p>
        @else
            <p class="mt-4"><strong>Mã dự thưởng:</strong> {{ $dataCheckinCus->number }}</p>
            <span class="text-green-600 font-bold">Chúc mừng bạn đã đến</span>
        @endif
    </div>

    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const btn = document.getElementById('checkinBtn');
            if (!btn) return;

            btn.addEventListener('click', async () => {
                try {
                    const res = await fetch("{{ route('guests.qr.scan') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}"
                        },
                        body: JSON.stringify({
                            qr_token: "{{ $dataCheckinCus->qr_token }}"
                        })
                    });

                    const data = await res.json();

                    if (data.ok) {
                        // Sinh lucky code client-side
                        const luckyCode = Math.random().toString(36).substring(2, 10).toUpperCase();

                        Swal.fire({
                            icon: 'success',
                            title: 'Check-in thành công!',
                            html: `
                        <p>Mã dự thưởng: <strong>${data.dataCheckinCus.number}</strong></p>
          
                    `,
                            confirmButtonText: 'OK'
                        }).then(() => location.reload());

                        // Gửi lucky code về server nếu muốn lưu


                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Lỗi',
                            text: data.message || 'Lỗi khi check-in'
                        });
                    }
                } catch (err) {
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
