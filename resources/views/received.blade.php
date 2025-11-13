<h1>Danh sách khách mời</h1>
<?php 
    $guests = App\Models\Guest::all();
    ?>
<div style="display: flex; flex-wrap: wrap; gap: 20px;">
    @foreach ($guests as $guest)
        <div style="border: 1px solid #ccc; border-radius: 10px; padding: 16px; width: 220px; text-align: center;">
            <h3>{{ $guest->full_name }}</h3>
            <p>SĐT: {{ $guest->phone }}</p>

            @if ($guest->status == 1)
                {{-- Hiển thị QR Code nếu khách đã check-in --}}
                <div>
                    Chỗ này là QR CODE
                </div>
                <p><strong>Đã check-in</strong></p>
                <p>Mã dự thưởng: <span style="color: red;">Ẩn</span></p>
            @else
                <p><em>Chưa check-in</em></p>
            @endif
        </div>
    @endforeach
</div>
