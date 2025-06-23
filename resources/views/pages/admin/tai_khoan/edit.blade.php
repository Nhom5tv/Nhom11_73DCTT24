@extends('layout')

@section('content')
<link rel="stylesheet" href="/css/dulieu.css?v={{ time() }}">

<div class="formDangnhap">
    <form id="editForm">
        <div class="content">
            <div class="form-box login">
                <h2>Sửa thông tin tài khoản</h2>

                <input type="hidden" name="id" id="userId" value="{{ $id }}">

                <div class="input-box">
                    <span class="icon"><img src="/Picture/Pic_login/user.png" alt="" width="15px"></span>
                    <input type="text" required name="name" id="name">
                    <label>Tên đăng nhập</label>
                </div>

                <div class="input-box">
                    <span class="icon"><img src="/Picture/Pic_login/email.png" alt="" width="15px"></span>
                    <input type="email" required name="email" id="email">
                    <label>Email</label>
                </div>

                <div class="input-box">
                    <span class="icon"><img src="/Picture/Pic_login/user.png" alt="" width="15px"></span>
                    <input type="text" readonly name="role" id="role" required style="padding-left: 80px;">
                    <label>Quyền</label>
                </div>

                <button type="submit" class="btn">Lưu</button>
                <br>
                <div style="text-align: center"><a href="/admin/taikhoan">Quay lại</a></div>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    
    const userId = '{{ $id }}';

    // Load dữ liệu user
    axios.get(`/api/admin/taikhoan/${userId}`, {
        headers: { 
             Authorization: 'Bearer ' + localStorage.getItem('token')
        }
    }).then(res => {
        const user = res.data;
        document.getElementById('name').value = user.name;
        document.getElementById('email').value = user.email;
        document.getElementById('role').value = user.role;
        
    }).catch(() => alert("Không tải được dữ liệu người dùng"));

    // Submit cập nhật
    document.getElementById('editForm').addEventListener('submit', function(e) {
        e.preventDefault();

        axios.put(`/api/admin/taikhoan/${userId}`, {
            name: document.getElementById('name').value,
            email: document.getElementById('email').value
        }, {
            headers: {
                Authorization: 'Bearer ' + localStorage.getItem('token')
             }
        }).then(res => {
            alert(res.data.message);
            window.location.href = "/admin/taikhoan";
        }).catch(err => {
            console.error(err);
            alert("Cập nhật thất bại: " + (err.response?.data?.error || 'Lỗi không xác định'));
        });
    });
</script>
@endsection
