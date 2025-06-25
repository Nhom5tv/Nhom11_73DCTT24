@extends('layout')

@section('content')
<link rel="stylesheet" href="/css/dulieu.css?v={{ time() }}">

<div class="formDangnhap">
    <form id="formSuaKhoanThu">
        <div class="content">
            <div class="form-box login">
                <h2>Sửa Khoản Thu</h2>

                <input type="hidden" id="ma_khoan_thu" name="ma_khoan_thu">

                <div class="input-box">
                    <span class="icon"><img src="/Picture/Pic_login/email.png" alt="" width="15px"></span>
                    <input type="text" id="ten_khoan_thu" name="ten_khoan_thu" required>
                    <label>Tên Khoản Thu</label>
                </div>

                <div class="input-box">
                    <span class="icon"><img src="/Picture/Pic_login/category.png" alt="" width="15px"></span>
                    <input type="text" id="loai_khoan_thu" name="loai_khoan_thu" list="loaikhoanthuOptions" readonly>
                    <label>Loại Khoản Thu</label>
                    <datalist id="loaikhoanthuOptions">
                        <option value="Học phí">
                        <option value="BHYT">
                        <option value="Khám sức khỏe">
                        <option value="Bảo hiểm thân thể">
                        <option value="Khác">
                    </datalist>
                </div>

                <div class="input-box">
                    <span class="icon"><img src="/Picture/Pic_login/so_tien.png" alt="" width="15px"></span>
                    <input type="number" id="so_tien" name="so_tien" readonly>
                    <label>Số Tiền</label>
                </div>

                <div class="input-box">
                    <span class="icon"><img src="/Picture/Pic_login/han_nop.png" alt="" width="15px"></span>
                    <input type="date" id="han_nop" name="han_nop" required>
                    <label>Hạn Nộp</label>
                </div>

                <button type="submit" class="btn">Lưu</button>
                <br>
                <div class="quaylai">
                    <a href="/admin/khoanthu">Quay lại</a>
                </div>
            </div>
        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    

    const id = window.location.pathname.split('/')[3]; 

    // Load dữ liệu chi tiết
    axios.get(`/api/admin/khoanthu/${id}`, {
        headers: {
            Authorization: 'Bearer ' + localStorage.getItem('token')
         }
    })
    .then(res => {
        const data = res.data;
        document.getElementById('ma_khoan_thu').value = data.ma_khoan_thu;
        document.getElementById('ten_khoan_thu').value = data.ten_khoan_thu;
        document.getElementById('loai_khoan_thu').value = data.loai_khoan_thu;
        document.getElementById('so_tien').value = data.so_tien;
        document.getElementById('han_nop').value = data.han_nop;
    })
    .catch(err => {
        alert("Không thể tải dữ liệu khoản thu.");
        console.error(err);
    });

    // Gửi cập nhật
    document.getElementById('formSuaKhoanThu').addEventListener('submit', function(e) {
        e.preventDefault();

        axios.put(`/api/admin/khoanthu/${id}`, {
            ten_khoan_thu: document.getElementById('ten_khoan_thu').value,
            han_nop: document.getElementById('han_nop').value
        }, {
            headers: {
                Authorization: 'Bearer ' + localStorage.getItem('token')
            }
        })
        .then(res => {
            alert("Cập nhật thành công!");
            window.location.href = "/admin/khoanthu";
        })
        .catch(err => {
            console.error(err);
            alert("Cập nhật thất bại.");
        });
    });
</script>
@endsection
