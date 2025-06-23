@extends('layout')

@section('content')
    <link rel="stylesheet" href="/css/button.css?v={{ time() }}">
    <link rel="stylesheet" href="/css/styleForm.css?v={{ time() }}">
    <style>
        .form-container {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .diem-form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .form-group {
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input {
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        .form-group input[readonly] {
            background-color: #f5f5f5;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        h2 {
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }
    </style>
    <div class="form-container">
        <h1>Sửa Khoa: {{ $ma_khoa }}</h1>
        <form id="form-sua-khoa" class="diem-form">
            @csrf
            <input type="hidden" id="ma_khoa" value="{{ $ma_khoa }}">

            <div class="form-group">
                <label for="ma_khoa_display">Mã Khoa:</label>
                <input type="text" id="ma_khoa_display" value="{{ $ma_khoa }}" readonly>
            </div>
            <div class="form-group">
                <label for="ten_khoa">Tên Khoa:</label>
                <input type="text" class="form-control" id="ten_khoa" name="ten_khoa" required>
            </div>

            <div class="form-group">
                <label for="lien_he">Liên hệ:</label>
                <input type="text" class="form-control" id="lien_he" name="lien_he" value="">
            </div>

            <div class="form-group">
                <label for="ngay_thanh_lap">Ngày thành lập:</label>
                <input type="date" class="form-control" id="ngay_thanh_lap" name="ngay_thanh_lap">
            </div>

            <div class="form-group">
                <label for="tien_moi_tin_chi">Tiền mỗi tín chỉ:</label>
                <input type="number" class="form-control" id="tien_moi_tin_chi" name="tien_moi_tin_chi" step="0.01"
                    min="0">
            </div>

            <div class="form-actions">
                <button type="button" class="btn btn-primary" onclick="updateKhoa()">Cập nhật</button>
                <button type="button" class="button-85" onclick="window.history.back()">Quay lại</button>
            </div>

        </form>
    </div>
@endsection
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`;
       
        async function loadKhoaData() {
            const ma_khoa = document.getElementById('ma_khoa').value;
            if (!ma_khoa) {
                console.error('Không tìm thấy ma_khoa');
                alert('Lỗi: Mã khoa không hợp lệ');
                return;
            }
            try {
                axios.get(`/api/admin/dskhoa/${ma_khoa}`)
                .then(res => {
                    const khoa = res.data;
                    document.getElementById('ma_khoa').value = khoa.ma_khoa || '';
                    document.getElementById('ten_khoa').value = khoa.ten_khoa || '';
                    document.getElementById('lien_he').value = khoa.lien_he || '';
                    document.getElementById('ngay_thanh_lap').value = khoa.ngay_thanh_lap || '';
                    document.getElementById('tien_moi_tin_chi').value = khoa.tien_moi_tin_chi || '';
                })
                
            } catch (error) {
                console.error('Lỗi tải dữ liệu khoa:', error);
                alert('Lỗi khi tải dữ liệu: ' + (error.response?.data?.message || error.message));
            }
        }

        async function updateKhoa() {
            const ma_khoa = document.getElementById('ma_khoa').value;
            const ten_khoa = document.getElementById('ten_khoa').value;
            const lien_he = document.getElementById('lien_he').value;
            const ngay_thanh_lap = document.getElementById('ngay_thanh_lap').value;
            const tien_moi_tin_chi = document.getElementById('tien_moi_tin_chi').value;

            if (!ten_khoa) {
                alert('Vui lòng nhập tên khoa');
                return;
            }
            if (!tien_moi_tin_chi) {
                alert('Vui lòng nhập tiền/tín chỉ');
                return;
            }

            try {
                const response = await axios.put(`/api/admin/dskhoa/${ma_khoa}`, {
                    ten_khoa,
                    lien_he,
                    ngay_thanh_lap,
                    tien_moi_tin_chi
                });

                if (response.status === 200) {
                    alert('Cập nhật thông tin khoa thành công');
                    window.location.href = "{{ route('khoa.index') }}";
                }
            } catch (error) {
                console.error('Lỗi khi cập nhật:', error);
                alert('Lỗi khi cập nhật: ' + (error.response?.data?.message || 'Vui lòng thử lại sau'));
            }
        }

        document.addEventListener('DOMContentLoaded', loadKhoaData);
    </script>
