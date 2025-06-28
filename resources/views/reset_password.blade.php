<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đặt lại mật khẩu</title>

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
        <form id="resetForm">
            <div class="content" style="background-color:white;">
                <div class="form-box login">
                    <h2>Đặt lại mật khẩu</h2>

                    <div class="input-box">
                        <span class="icon"><i class="fa-solid fa-key"></i></span>
                        <input type="password" required name="password" placeholder="Mật khẩu mới">
                       
                    </div>

                    <div class="input-box">
                        <span class="icon"><i class="fa-solid fa-key"></i></span>
                        <input type="password" required name="password_confirmation" placeholder="Nhập lại mật khẩu">
                       
                    </div>

                    <button type="submit" class="btn">Đặt lại</button>
                    <p id="reset-error" style="color:red; margin-top:10px;"></p>
                    <p id="reset-success" style="color:green; margin-top:10px;"></p>
                </div>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
       
        const token = @json($token);
        const email = @json($email);

        if (!token || !email) {
            document.body.innerHTML = "<h2 style='text-align:center; color:red;'>Link không hợp lệ!</h2>";
        }

        document.getElementById("resetForm").addEventListener("submit", function(e) {
            e.preventDefault();

            const password = document.querySelector('input[name="password"]').value;
            const confirm = document.querySelector('input[name="password_confirmation"]').value;

            axios.post('/api/reset-password', {
                token: token,
                email: email,
                password: password,
                password_confirmation: confirm
            })
            .then(res => {
                alert("✅ Đặt lại mật khẩu thành công!");
                window.location.href = '/login';
            })
            .catch(err => {
                const msg = err.response?.data?.error || "❌ Có lỗi xảy ra. Vui lòng thử lại.";
                document.getElementById('reset-error').innerText = msg;
                document.getElementById('reset-success').innerText = "";
            });
        });
    </script>
</body>
</html>
