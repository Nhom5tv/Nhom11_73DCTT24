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

        <div class="export__file">
            <button class="button-85" onclick="exportExcel()">
                <i class="fa fa-download"></i> Export
            </button>
        </div>

        <div class="export__file">
            <input type="file" id="excelFileInput" accept=".xlsx, .xls" style="display:none" onchange="handleFileUpload(event)">
            <button class="button-85" onclick="document.getElementById('excelFileInput').click()">
                <i class="fa fa-upload"></i> Upload
            </button>
        </div>
    </section>

    <section class="table__body">
        <table>
            <thead>
                <tr>
                    <th>Mã GV</th>
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

<!-- JS -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
    token = localStorage.getItem('token');
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
                    <td>${gv.ten_khoa ?? gv.ma_khoa}</td>
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

    function editGiangVien(maGV) {
        window.location.href = `/admin/giangvien/${maGV}/edit`;
    }

    async function deleteGiangVien(maGV) {
        if (!confirm('Bạn có chắc muốn xóa giảng viên này?')) return;

        try {
            await axios.delete(`/api/admin/giangvien/${maGV}`);
            alert('✅ Đã xóa thành công');
            loadGiangVien();
        } catch (error) {
            alert('❌ Lỗi khi xóa giảng viên: ' + (error.response?.data?.message || error.message));
        }
    }

    function exportExcel() {
        const table = document.querySelector("table");
        const selectedColumns = [0, 1, 2, 3, 4, 5]; // Các cột cần export
        let data = [];

        const headers = [];
        table.querySelectorAll("thead th").forEach((th, idx) => {
            if (selectedColumns.includes(idx)) headers.push(th.innerText.trim());
        });
        data.push(headers);

        table.querySelectorAll("tbody tr").forEach(tr => {
            const row = [];
            tr.querySelectorAll("td").forEach((td, idx) => {
                if (selectedColumns.includes(idx)) row.push(td.innerText.trim());
            });
            data.push(row);
        });

        const worksheet = XLSX.utils.aoa_to_sheet(data);
        const workbook = XLSX.utils.book_new();
        XLSX.utils.book_append_sheet(workbook, worksheet, "GiangVien");
        XLSX.writeFile(workbook, `giangvien_${new Date().toISOString().slice(0, 10)}.xlsx`);
    }

    function handleFileUpload(event) {
    const file = event.target.files[0];
    if (!file) return;

    const reader = new FileReader();
    reader.onload = function (e) {
        const data = new Uint8Array(e.target.result);
        const workbook = XLSX.read(data, { type: 'array' });
        const sheet = workbook.Sheets[workbook.SheetNames[0]];
       const rows = XLSX.utils.sheet_to_json(sheet, { defval: '', raw: false });

rows.forEach((row, index) => {
    console.log(`Row ${index + 1}:`, Object.keys(row)); // 👈 log ra tất cả key
});

        const giangViens = rows.map(row => ({
    ma_giang_vien: row["Mã GV"],
    ma_khoa: row["Mã Khoa"],
    ho_ten: row["Họ Tên"],
    email: row["Email"],
    so_dien_thoai: row["Số Điện Thoại"],
    chuyen_nganh: row["Chuyên Ngành"]
}));

        // Gửi toàn bộ danh sách lên server qua 1 lần gọi API
        axios.post('/api/admin/giangvien/import', giangViens)
            .then(res => {
                const msg = res.data.message;
                const errors = res.data.errors;
                let alertMsg = msg;
                if (errors && errors.length > 0) {
                    alertMsg += "\nChi tiết lỗi:\n" + errors.join('\n');
                }
                alert(alertMsg);
                loadGiangVien();
            })
            .catch(err => {
                console.error("❌ Lỗi khi import:", err);
                alert('❌ Import thất bại: ' + (err.response?.data?.message || err.message));
            });
    };

    reader.readAsArrayBuffer(file);
}


    document.getElementById('btnSearch').addEventListener('click', loadGiangVien);
    window.onload = loadGiangVien;
</script>

</body>
</html>
@endsection
