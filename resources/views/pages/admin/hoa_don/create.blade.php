@extends('layout')

@section('content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/dulieu.css?v={{ time() }}">

    <style>
        .quaylai {
            text-align: center;
            padding-top: 5px;
        }
    </style>

    <form id="formThemHoaDon">
        <div class="content">
            <div class="form-box login">
                <h2>Thêm Hóa Đơn</h2>

                <div class="input-box">
                    <span class="icon"><img src="/images/user.png" alt="" width="15"></span>
                    <input type="text" required name="ma_sinh_vien" id="ma_sinh_vien">
                    <label>Mã Sinh Viên</label>
                </div>

                <label>Tên Khoản Thu</label>
                <div class="input-box" style="text-align: center; margin:10px;">
                    <span class="icon"><img src="/images/category.png" alt="" width="15"></span>
                    <select name="ma_khoan_thu" id="ma_khoan_thu" required style="text-align: center;">
                        <option value="">Chọn khoản thu</option>
                    </select>
                </div>

                <label>Ngày Thanh Toán</label>
                <div class="input-box" style="margin: 5px;">
                    <span class="icon"><img src="/images/calendar.png" alt="" width="15"></span>
                    <input type="date" required name="ngay_thanh_toan" id="ngay_thanh_toan" style="text-align: center;">
                </div>

                <div class="input-box">
                    <span class="icon"><img src="/images/soTien.png" alt="" width="15"></span>
                   <input type="text" required name="so_tien_da_nop" id="so_tien_da_nop" oninput="formatSoTienInline(this)">
                    <label>Số Tiền Đã Nộp</label>

                </div>

                <div class="input-box">
                    <span class="icon"><img src="/images/payment.png" alt="" width="15"></span>
                    <input type="text" required name="hinh_thuc_thanh_toan" id="hinh_thuc_thanh_toan" list="hinhThucOptions" style="text-align: center;">
                    <label>Hình Thức Thanh Toán</label>
                    <datalist id="hinhThucOptions">
                        <option value="Chuyển khoản">
                        <option value="Tiền mặt">
                    </datalist>
                </div>

                <div class="input-box">
                    <span class="icon"><img src="/images/payment.png" alt="" width="15"></span>
                    <input type="text" required name="noi_dung" id="noi_dung">
                    <label>Nội dung</label>
                </div>

                <button type="submit" class="btn">Lưu</button>
                <br>
                <div class="quaylai">
                    <a href="/admin/hoadon">Quay lại</a>
                </div>
            </div>
        </div>
    </form>

    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        function formatSoTienInline(input) {
            let raw = input.value.replace(/\./g, '').replace(/[^0-9]/g, '');
            input.value = Number(raw).toLocaleString('vi-VN');
        }

        document.addEventListener("DOMContentLoaded", () => {
            axios.get("/api/admin/khoanthu", {
                headers: { Authorization: 'Bearer ' + localStorage.getItem('token') }
            })
            .then(res => {
                const select = document.getElementById("ma_khoan_thu");
                res.data.forEach(item => {
                    const option = document.createElement("option");
                    option.value = item.ma_khoan_thu;
                    option.text = item.ten_khoan_thu;
                    select.add(option);
                });
            })
            .catch(err => alert("Lỗi khi tải khoản thu"));

            document.getElementById("formThemHoaDon").addEventListener("submit", function(e) {
                e.preventDefault();

                const data = {
                    ma_sinh_vien: document.getElementById("ma_sinh_vien").value,
                    ma_khoan_thu: document.getElementById("ma_khoan_thu").value,
                    ngay_thanh_toan: document.getElementById("ngay_thanh_toan").value,
                   so_tien_da_nop: document.getElementById("so_tien_da_nop").value.replace(/\./g, ''),
                    hinh_thuc_thanh_toan: document.getElementById("hinh_thuc_thanh_toan").value,
                    noi_dung: document.getElementById("noi_dung").value,
                };

                axios.post("/api/admin/hoadon", data, {
                    headers: { Authorization: 'Bearer ' + localStorage.getItem('token') }
                })
                .then(res => {
                    alert(res.data.message);
                    window.location.href = "/admin/hoadon";
                })
                .catch(err => {
                    if (err.response?.data?.errors) {
                        alert(Object.values(err.response.data.errors).join("\n"));
                    } else {
                        alert("Lỗi khi thêm hóa đơn!");
                    }
                });
            });
        });
    </script>
@endsection
