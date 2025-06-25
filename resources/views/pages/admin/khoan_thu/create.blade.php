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
    <form id="khoanThuForm">
        <div class="content">
            <div class="form-box login">
                <h2>Thêm Khoản Thu</h2>

                <div class="input-box">
                    <span class="icon">
                        <img src="/Picture/Pic_login/user.png" alt="" width="15px">
                    </span>
                    <input type="text" required name="ten_khoan_thu" id="ten_khoan_thu">
                    <label>Tên Khoản Thu</label>
                </div>

                <div class="input-box">
                    <span class="icon">
                        <img src="/Picture/Pic_login/category.png" alt="" width="15px">
                    </span>
                    <input type="text" required name="loai_khoan_thu" id="loai_khoan_thu" list="loaikhoanthuOptions">
                    <label>Loại Khoản Thu</label>
                    <datalist id="loaikhoanthuOptions">
                        <option value="Học phí">
                        <option value="BHYT">                        
                        <option value="Khác">
                    </datalist>
                </div>

                <div class="input-box">
                    <span class="icon">
                        <img src="/Picture/Pic_login/soTien.png" alt="" width="15px">
                    </span>
                    <input type="number" required name="so_tien" id="so_tien">
                    <label>Số Tiền</label>
                </div>

                
                <div class="input-box">
                    <span class="icon">
                        <img src="/Picture/Pic_login/calendar.png" alt="" width="15px">
                    </span>
                    <input type="date" required name="han_nop" id="han_nop" style="padding-left:100px">
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
    document.getElementById("khoanThuForm").addEventListener("submit", function(e) {
        e.preventDefault();

        
        const ten_khoan_thu = document.querySelector('input[name="ten_khoan_thu"]').value;
        const loai_khoan_thu = document.querySelector('input[name="loai_khoan_thu"]').value;
        const so_tien = document.querySelector('input[name="so_tien"]').value;
        const han_nop = document.querySelector('input[name="han_nop"]').value;
      

        axios.post('/api/admin/khoanthu', {
            ten_khoan_thu: ten_khoan_thu,
            loai_khoan_thu: loai_khoan_thu,
            so_tien: so_tien,
            han_nop: han_nop
        }, {
            headers: {
                Authorization: 'Bearer ' + localStorage.getItem('token')
            }
        })
        .then(res => {
            alert(res.data.message);
            window.location.href = '/admin/khoanthu';
        })
        .catch(err => {
            console.error(err);
            alert('Tạo khoản thu thất bại: ' + (err.response?.data?.error || 'Lỗi không xác định'));
        });
    });

   
</script>
@endsection
