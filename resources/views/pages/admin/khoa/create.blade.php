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
        <h1>Thêm Khoa Mới</h1>
        <form id="form-them-khoa" class="diem-form">
            @csrf
            <div class="form-group">
                <label for="ten_khoa">Tên Khoa:</label>
                <input type="text" class="form-control" id="ten_khoa" name="ten_khoa" required>
            </div>

            <div class="form-group">
                <label for="lien_he">Liên hệ:</label>
                <input type="text" class="form-control" id="lien_he" name="lien_he">
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
                <button type="button" class="button-85" onclick="addKhoa()">Thêm</button>
                <button type="button" class="button-85" onclick="window.history.back()">Hủy</button>
            </div>
        </form>
    </div>
@endsection
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        axios.defaults.headers.common['Authorization'] = `Bearer ${localStorage.getItem('token')}`;

        function addKhoa(){
            const data = {
                ten_khoa: document.getElementById('ten_khoa').value.trim(),
                lien_he: document.getElementById('lien_he').value.trim(),
                ngay_thanh_lap: document.getElementById('ngay_thanh_lap').value.trim(),
                tien_moi_tin_chi: parseFloat(document.getElementById('tien_moi_tin_chi').value.trim())
            };
            axios.post('/api/admin/dskhoa', data)
                .then(response => {
                    alert('Thêm khoa thành công');
                    window.location.href = "{{ route('khoa.index') }}";
                })
                .catch(error => {
                    console.error('Lỗi:', error);
                    const errorMsg = error.response?.data?.message || error.message;
                    alert('Lỗi: ' + errorMsg);
                });
        }
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('ma_khoa').focus();
        });
    </script>
