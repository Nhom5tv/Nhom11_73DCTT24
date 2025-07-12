<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập</title>

    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/login.css?v={{ time() }}">

    <style>
        .content {
            margin-top: 70px;
        }

        .formDangnhap {
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
        }
    </style>
</head>

{{-- kiểm tra token hết hạn --}}
<script>
    window.addEventListener('DOMContentLoaded', function () {
        const token = localStorage.getItem('token');
        if (token) {
            // Gọi /api/me để xác minh token
            axios.get('/api/me', {
                headers: {
                    Authorization: 'Bearer ' + token
                }
            })
                .then(res => {
                    const role = res.data.role;
                    if (role === 'admin') window.location.href = '/admin';
                    else if (role === 'giaovien') window.location.href = '/giaovien';
                    else if (role === 'sinhvien') window.location.href = '/sinhvien';
                })
                .catch(() => {
                    // Token không hợp lệ hoặc hết hạn → xóa
                    localStorage.removeItem('token');
                });
        }
    });
</script>

<body>
    <div class="formDangnhap">
        <form id="loginForm">
            <div class="content" style="background-color:white;">
                <div class="form-box login">
                    <h2>Đăng nhập</h2>

                    <div class="input-box">
                        <span class="icon">
                            <img src="/Picture/Pic_login/email.png" alt="" width="15px">
                        </span>
                        <input type="text" required name="txtEmail" >
                        <label><i class="lni lni-graduation"></i> Email</label>
                    </div>

                    <div class="input-box">
                        <span class="icon">
                            <img src="/Picture/Pic_login/khoa.png" alt="" width="15px">
                        </span>
                        <input type="password" required name="txtMatkhau" >
                        <label><i class="lni lni-lock"></i> Password</label>
                    </div>


                    <div class="remember-forgot">
                        <label><input type="checkbox"> Remember me</label>
                        <a href="/forgot-password" style="float: right;">Quên mật khẩu?</a>
                    </div>

                    <button type="submit" class="btn">Đăng nhập</button>
                    <p id="login-error" style="color:red; margin-top:10px;"></p>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.getElementById("loginForm").addEventListener("submit", function (e) {
            e.preventDefault();

            const email = document.querySelector('input[name="txtEmail"]').value;
            const password = document.querySelector('input[name="txtMatkhau"]').value;

            axios.post('/api/login', {
                email: email,
                password: password
            })
                .then(res => {
                    localStorage.setItem('token', res.data.access_token);
                    localStorage.setItem('must_change_password', res.data.must_change_password);
                    loadUser();
                })
                .catch(() => {
                    document.getElementById('login-error').innerText = "❌ Đăng nhập thất bại. Vui lòng kiểm tra lại.";
                });
        });

        function loadUser() {
            axios.get('/api/me', {
                headers: {
                    Authorization: 'Bearer ' + localStorage.getItem('token')
                }
            })
                .then(res => {
                    const user = res.data.user;
                    const role = user.role;
                    mustChange = localStorage.getItem('must_change_password') === 'true';

                    if (mustChange) {

                        window.location.href = '/change-password';
                        return;
                    }
                    if (role === 'admin') window.location.href = '/admin/thongke';
                    else if (role === 'giaovien') {
                        localStorage.setItem('ma_giang_vien', user.ma_giang_vien);
                        window.location.href = '/giaovien/DSdiemgv';
                    }
                    else if (role === 'sinhvien'){
                        localStorage.setItem('ma_sinh_vien', user.ma_sinh_vien);
                        window.location.href = '/sinhvien/diem';
                    }
                    else alert("Không xác định được vai trò.");
                })
                .catch(() => {
                    alert(" Token hết hạn hoặc không hợp lệ.");
                });
        }
    </script>
</body>

</html>
