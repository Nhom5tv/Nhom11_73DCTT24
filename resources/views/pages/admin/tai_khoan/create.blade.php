@extends('layout')

@section('content')
<link rel="stylesheet" href="/css/dulieu.css?v={{ time() }}">

<style>
    .quaylai {
        text-align: center;
        justify-content: center;
        padding-top: 5px;
    }
</style>

<div class="formDangnhap">
    <form id="adminForm">
        <div class="content">
            <div class="form-box login">
                <h2>Thêm tài khoản Admin</h2>

                <div class="input-box">
                    <span class="icon">
                        <img src="/Picture/Pic_login/user.png" alt="" width="15px">
                    </span>
                    <input type="text" required name="name" >
                    <label>Tên Admin</label>
                </div>

                <div class="input-box">
                    <span class="icon">
                        <img src="/Picture/Pic_login/email.png" alt="" width="15px">
                    </span>
                    <input type="email" required name="email" >
                    <label>Email</label>
                </div>

                <div class="input-box">
                    <span class="icon">
                        <img src="/Picture/Pic_login/khoa.png" alt="" width="15px">
                    </span>
                    <input type="password" required name="password" >
                    <label>Mật khẩu</label>
                </div>

                <button type="submit" class="btn">Lưu</button>
                <br>
                <div class="quaylai">
                    <a href="/admin/taikhoan">Quay lại</a>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    document.getElementById("adminForm").addEventListener("submit", function (e) {
        e.preventDefault();

        const name = document.querySelector('input[name="name"]').value;
        const email = document.querySelector('input[name="email"]').value;
        const password = document.querySelector('input[name="password"]').value;

        axios.post('/api/admin/taikhoan', {
            name: name,
            email: email,
            password: password
        }, {
            headers: {
                Authorization: 'Bearer ' + localStorage.getItem('token')
            }
        })
        .then(res => {
            alert(res.data.message);
            window.location.href = '/admin/taikhoan';
        })
        .catch(err => {
            console.error(err);
            alert('Tạo tài khoản thất bại: ' + (err.response?.data?.error || 'Lỗi không xác định'));
        });
    });
</script>
@endsection
