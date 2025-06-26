@extends('layout')

@section('content')
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quản lý ngành</title>

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

<main class="table" id="nganh_table">
    <section class="table__header">
        <h1>Quản lý ngành</h1>
        <div class="input-group">
            <input type="search" id="searchMaNganh" placeholder="Mã ngành">
        </div>
        <div class="input-group">
            <input type="search" id="searchTenNganh" placeholder="Tên ngành">
        </div>
        <button id="btnSearch" style="border: none; background: transparent;"><i class="fa fa-search"></i></button>
        <div class="Insert">
            <form action="{{ url('/admin/nganh/create') }}" method="GET">
                <button class="button-85" role="button" type="submit">Thêm ngành</button>
            </form>
        </div>
    </section>

    <section class="table__body">
        <table>
            <thead>
                <tr>
                    <th>Mã ngành</th>
                    <th>Tên ngành</th>
                    <th>Mã khoa</th>
                    <th>Thời gian đào tạo</th>
                    <th>Bậc đào tạo</th>
                    <th>Chức năng</th>
                </tr>
            </thead>
            <tbody id="nganh-table-body">
                <!-- JS sẽ render dữ liệu tại đây -->
            </tbody>
        </table>
    </section>
</main>

<!-- Axios -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    const token = localStorage.getItem('token');
    if (!token) {
        alert("Bạn chưa đăng nhập hoặc token không tồn tại!");
    }

    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

    function loadNganh() {
        let maNganh = document.getElementById("searchMaNganh").value;
        let tenNganh = document.getElementById("searchTenNganh").value;

        axios.get('/api/admin/nganh', {
            params: {
                ma_nganh: maNganh,
                ten_nganh: tenNganh
            }
        })
        .then(response => {
            const tbody = document.getElementById("nganh-table-body");
            tbody.innerHTML = "";

            response.data.forEach(nganh => {
                let html = `<tr>
                    <td>${nganh.ma_nganh}</td>
                    <td>${nganh.ten_nganh}</td>
<td>${nganh.ten_khoa}</td>
                    <td>${nganh.thoi_gian_dao_tao ?? ''}</td>
                    <td>${nganh.bac_dao_tao ?? ''}</td>
                    <td class="btn_cn">
                        <button class="button-85" onclick="editNganh('${nganh.ma_nganh}')">Sửa</button>
                        <button class="button-85" onclick="deleteNganh('${nganh.ma_nganh}')">Xóa</button>
                    </td>
                </tr>`;
                tbody.insertAdjacentHTML('beforeend', html);
            });
        })
        .catch(error => {
            alert("Lỗi tải dữ liệu ngành: " + (error.response?.data?.message || error.message));
            console.error(error);
        });
    }

    async function deleteNganh(maNganh) {
        if (!confirm('Bạn có chắc muốn xóa ngành này?')) return;

        try {
            await axios.delete(`/api/admin/nganh/${maNganh}`);
            alert('✅ Đã xóa thành công');
            loadNganh();
        } catch (error) {
            alert('❌ Lỗi khi xóa ngành: ' + (error.response?.data?.message || error.message));
            console.error(error);
        }
    }

    function editNganh(maNganh) {
        window.location.href = `/admin/nganh/${maNganh}/edit`;
    }

    document.getElementById('btnSearch').addEventListener('click', loadNganh);
    window.onload = loadNganh;
</script>

</body>
</html>
@endsection
