@extends('layout')

@section('content')
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý sinh viên</title>

    <!-- CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/css/button.css?v={{ time() }}">
    <link rel="stylesheet" href="/css/styleDT.css?v={{ time() }}">

    <style>
        .btn_cn {
            display: flex;
            margin: 0;
            gap: 5px;
        }
        
    </style>
</head>
<body>

<main class="table" id="students_table">
    <section class="table__header">
        <h1>Quản lý sinh viên</h1>
        <div class="input-group">
            <input type="search" id="searchMaSV" placeholder="Mã SV">
        </div>
        <div class="input-group">
            <input type="search" id="searchHoTen" placeholder="Họ tên">
        </div>
        <button id="btnSearch" style="border: none; background: transparent;"><i class="fa fa-search"></i></button>
        <div class="Insert">
<form action="{{ url('/admin/sinhvien/create') }}" method="GET">
        <button class="button-85" role="button" type="submit">Thêm sinh viên</button>
    </form>
        </div>
    </section>

    <section class="table__body">
        <table>
            <thead>
                <tr>
                    <th>Mã SV</th>
                    <th>User ID</th>
                    <th>Mã khoa</th>
                    <th>Mã ngành</th>
                    <th>Họ tên</th>
                    <th>Ngày sinh</th>
                    <th>Giới tính</th>
                    <th>Quê quán</th>
                    <th>Email</th>
                    <th>Số điện thoại</th>
                    <th>Khóa học</th>
                    <th>Chức năng</th>
                </tr>
            </thead>
            <tbody id="student-table-body">
                <!-- JS sẽ render dữ liệu tại đây -->
            </tbody>
        </table>
    </section>
</main>

<!-- Axios -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    // ✅ Khai báo đúng 1 lần token
const token = localStorage.getItem('token'); // ✅ Giống bên login
    console.log("Token hiện tại:", token);

    if (!token) {
        alert("Bạn chưa đăng nhập hoặc token không tồn tại!");
    }

    // ✅ Gắn token vào tất cả request
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

    function loadSinhVien() {
    let maSV = document.getElementById("searchMaSV").value;
    let hoTen = document.getElementById("searchHoTen").value;

    axios.get('/api/admin/sinhvien', {
      
        params: {
            ma_sinh_vien: maSV,
            ho_ten: hoTen
        }
    })
    .then(response => {
        const tbody = document.getElementById("student-table-body");
        tbody.innerHTML = "";

        response.data.forEach(sv => {
            let html = `<tr>
                <td>${sv.ma_sinh_vien}</td>
                <td>${sv.user_id ?? ''}</td> 
                <td>${sv.ma_khoa ?? ''}</td>
                <td>${sv.ma_nganh ?? ''}</td>
                <td>${sv.ho_ten}</td>
                <td>${sv.ngay_sinh}</td>
                <td>${sv.gioi_tinh}</td>
                <td>${sv.que_quan}</td>
                <td>${sv.email}</td>
                <td>${sv.so_dien_thoai}</td>
                <td>${sv.khoa_hoc}</td>
                <td class="btn_cn">
                    <button class="button-85" onclick="editSinhVien('${sv.ma_sinh_vien}')">Sửa</button>
                    <button class="button-85" onclick="deleteSinhVien('${sv.ma_sinh_vien}')">Xóa</button>
                </td>
            </tr>`;
            tbody.insertAdjacentHTML('beforeend', html);
        });
    })
    .catch(error => {
        alert("Lỗi tải dữ liệu sinh viên: " + (error.response?.data?.message || error.message));
        console.error(error);
    });
}


 async function deleteSinhVien(maSV) {
    if (!confirm('Bạn có chắc muốn xóa sinh viên này?')) return;

    try {
        await axios.delete(`/api/admin/sinhvien/${maSV}`);
        alert('✅ Đã xóa thành công');
        loadSinhVien(); // Refresh lại danh sách
    } catch (error) {
        alert('❌ Lỗi khi xóa sinh viên: ' + (error.response?.data?.message || error.message));
        console.error(error);
    }
}


function editSinhVien(maSV) {
    // Điều hướng sang trang edit giao diện (web.php có route này)
    window.location.href = `/admin/sinhvien/${maSV}/edit`;
}


    document.getElementById('btnSearch').addEventListener('click', loadSinhVien);
    window.onload = loadSinhVien;
</script>

</body>
</html>
@endsection
