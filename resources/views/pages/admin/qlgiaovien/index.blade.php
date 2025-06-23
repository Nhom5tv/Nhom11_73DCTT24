@extends('layout')

@section('content')
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý giảng viên</title>

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

<main class="table" id="teachers_table">
    <section class="table__header">
        <h1>Quản lý giảng viên</h1>
        <div class="input-group">
            <input type="search" id="searchMaGV" placeholder="Mã GV">
        </div>
        <div class="input-group">
            <input type="search" id="searchHoTen" placeholder="Họ tên">
        </div>
        <button id="btnSearch" style="border: none; background: transparent;"><i class="fa fa-search"></i></button>
        <div class="Insert">
            <form action="{{ url('/admin/giangvien/create') }}" method="GET">
                <button class="button-85" role="button" type="submit">Thêm giảng viên</button>
            </form>
        </div>
    </section>

    <section class="table__body">
        <table>
            <thead>
                <tr>
                    <th>Mã GV</th>
                    <th>User ID</th>
                    <th>Mã khoa</th>
                    <th>Họ tên</th>
                    <th>Email</th>
                    <th>Số điện thoại</th>
                    <th>Chuyên ngành</th>
                    <th>Chức năng</th>
                </tr>
            </thead>
            <tbody id="teacher-table-body">
                <!-- JS sẽ render dữ liệu tại đây -->
            </tbody>
        </table>
    </section>
</main>

<!-- Axios -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    const token = localStorage.getItem('token');
    console.log("Token hiện tại:", token);

    if (!token) {
        alert("Bạn chưa đăng nhập hoặc token không tồn tại!");
    }

    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

    function loadGiangVien() {
        let maGV = document.getElementById("searchMaGV").value;
        let hoTen = document.getElementById("searchHoTen").value;

        axios.get('/api/admin/giangvien', {
            params: {
                ma_giang_vien: maGV,
                ho_ten: hoTen
            }
        })
        .then(response => {
            const tbody = document.getElementById("teacher-table-body");
            tbody.innerHTML = "";

            response.data.forEach(gv => {
                let html = `<tr>
                    <td>${gv.ma_giang_vien}</td>
                    <td>${gv.user_id ?? ''}</td>
                    <td>${gv.ma_khoa ?? ''}</td>
                    <td>${gv.ho_ten}</td>
                    <td>${gv.email}</td>
                    <td>${gv.so_dien_thoai ?? ''}</td>
                    <td>${gv.chuyen_nganh ?? ''}</td>
                    <td class="btn_cn">
                        <button class="button-85" onclick="editGiangVien('${gv.ma_giang_vien}')">Sửa</button>
                        <button class="button-85" onclick="deleteGiangVien('${gv.ma_giang_vien}')">Xóa</button>
                    </td>
                </tr>`;
                tbody.insertAdjacentHTML('beforeend', html);
            });
        })
        .catch(error => {
            alert("Lỗi tải dữ liệu giảng viên: " + (error.response?.data?.message || error.message));
            console.error(error);
        });
    }

    async function deleteGiangVien(maGV) {
        if (!confirm('Bạn có chắc muốn xóa giảng viên này?')) return;

        try {
            await axios.delete(`/api/admin/giangvien/${maGV}`);
            alert('✅ Đã xóa thành công');
            loadGiangVien();
        } catch (error) {
            alert('❌ Lỗi khi xóa giảng viên: ' + (error.response?.data?.message || error.message));
            console.error(error);
        }
    }

    function editGiangVien(maGV) {
        window.location.href = `/admin/giangvien/${maGV}/edit`;
    }

    document.getElementById('btnSearch').addEventListener('click', loadGiangVien);
    window.onload = loadGiangVien;
</script>

</body>
</html>
@endsection

