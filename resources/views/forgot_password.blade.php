<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quên mật khẩu</title>

    <link href="https://cdn.lineicons.com/4.0/lineicons.css" rel="stylesheet" />
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/login.css?v={{ time() }}">
    
    <style>
        .content { margin-top: 70px; }
        .formDangnhap {
            position: absolute;
            top: 0; right: 0; bottom: 0; left: 0;
        }
    </style>
</head>
<body>
    <div class="formDangnhap">
        <form id="forgotForm">
            <div class="content" style="background-color:white;">
                <div class="form-box login">
                    <h2>Khôi phục mật khẩu</h2>

                    <div class="input-box">
                        <span class="icon">
                            <img src="/Picture/Pic_login/email.png" alt="" width="15px">
                        </span>
                        <input type="email" required name="email" placeholder="Nhập email đã đăng ký">
                        <label><i class="lni lni-graduation"></i> Email</label>
                    </div>

                    <button type="submit" class="btn">Gửi mail khôi phục</button>
                    <p id="forgot-error" style="color:red; margin-top:10px;"></p>
                    <p id="forgot-success" style="color:green; margin-top:10px;"></p>

                    <p style="margin-top: 10px;">
                        <a href="/login">Quay lại đăng nhập</a>
                    </p>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        document.getElementById("forgotForm").addEventListener("submit", function(e) {
            e.preventDefault();

            const email = document.querySelector('input[name="email"]').value;

            axios.post('/api/forgot-password', { email: email })
                .then(res => {
                    document.getElementById('forgot-success').innerText = res.data.message || "Đã gửi mail khôi phục!";
                    document.getElementById('forgot-error').innerText = "";
                })
                .catch(err => {
                    const msg = err.response?.data?.error || "❌ Không gửi được mail. Kiểm tra lại email.";
                    document.getElementById('forgot-error').innerText = msg;
                    document.getElementById('forgot-success').innerText = "";
                });
        });
    </script>
</body>
</html>
