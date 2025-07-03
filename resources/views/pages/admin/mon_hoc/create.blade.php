@extends('layout')

@section('content')

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Thêm Môn Học</title>
        <style>
            .quaylai {
                text-align: center;
                justify-content: center;
                padding-top: 5px;
            }
        </style>
        <link rel="stylesheet" href="/css/dulieu.css?v={{ time() }}">
    </head>
    <div class="content">
        <div class="form-box login">
            <h2>Thêm Môn Học</h2>
            <form id="addForm">
                @csrf

                <div class="input-box">
                    <span class="icon">
                        <img src="/Public/Picture/Pic_login/email.png" alt="" width="15px">
                    </span>
                    <input type="text" id="ma_mon" name="ma_mon" required>
                    <label>Mã Môn</label>
                </div>

                <div class="input-box">
                    <span class="icon">
                        <img src="/Public/Picture/Pic_login/user.png" alt="" width="15px">
                    </span>
                    <input type="text" id="ten_mon" name="ten_mon" required>
                    <label>Tên Môn</label>
                </div>

                <div class="input-box">
                    <span class="icon">
                        <img src="/Public/Picture/Pic_login/user.png" alt="" width="15px">
                    </span>
                    <input type="text" id="ma_nganh" name="ma_nganh" required>
                    <label>Mã ngành</label>
                </div>

                <div class="input-box">
                    <span class="icon">
                        <img src="/Public/Picture/Pic_login/user.png" alt="" width="15px">
                    </span>
                    <input type="number" id="so_tin_chi" name="so_tin_chi" required>
                    <label>Số Tín Chỉ</label>
                </div>

                <div class="input-box">
                    <span class="icon">
                        <img src="/Public/Picture/Pic_login/user.png" alt="" width="15px">
                    </span>
                    <input type="number" id="so_tiet" name="so_tiet" required>
                    <label>Số Tiết</label>
                </div>

                <div>
                    <button type="submit" class="btn">Lưu</button>
                </div>

                <div class="quaylai" style="text-align:center; padding-top:10px;">
                    <a href="/admin/monhoc">Quay lại</a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.getElementById('addForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const data = {
                ma_mon: document.getElementById('ma_mon').value,
                ten_mon: document.getElementById('ten_mon').value,
                ma_nganh: document.getElementById('ma_nganh').value,
                so_tin_chi: document.getElementById('so_tin_chi').value,
                so_tiet: document.getElementById('so_tiet').value,
            };

            axios.post('/api/admin/monhoc', data, {
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('token')
                }
            })

                .then(response => {
                    alert("Thêm môn học thành công!");
                    window.location.href = "/admin/monhoc";
                })
                .catch(error => {
                    if (error.response && error.response.status === 422) {
                        // Laravel validation error
                        const errors = error.response.data.errors;
                        if (errors.ma_mon) {
                            alert("Mã môn đã tồn tại!");
                        } else {
                            alert("Dữ liệu không hợp lệ!");
                        }
                    } else {
                        alert("Thêm thất bại!");
                    }
                    console.error(error);
                });
        });
    </script>
@endsection