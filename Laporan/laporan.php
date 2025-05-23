<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "smpn4tangsel";

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

// Fetch unique years from tb_peminjaman
$yearQuery = "SELECT DISTINCT YEAR(tgl_pinjam) AS year FROM tb_peminjaman ORDER BY year DESC";
$years = $pdo->query($yearQuery)->fetchAll(PDO::FETCH_COLUMN);

// Fetch data with search and year filter
$search = isset($_GET['search']) ? trim($_GET['search']) : '';
$year = isset($_GET['year']) ? trim($_GET['year']) : '';

// Get current page and rows per page
$currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$rowsPerPage = isset($_GET['rowsPerPage']) ? max(1, (int)$_GET['rowsPerPage']) : 5;

// Calculate the offset
$offset = ($currentPage - 1) * $rowsPerPage;

// Base SQL query
$sql = "
    SELECT 
        p.no_peminjaman,
        p.tgl_pinjam,
        p.tgl_kembali,
        pb.tgl_dikembalikan,
        p.nis,
        p.nama,
        p.id_buku,
        p.judul_buku,
        IF(pb.keterlambatan IS NULL, 0, pb.keterlambatan) AS keterlambatan,
        IF(pb.total_denda IS NULL, 0, pb.total_denda) AS denda,
        IF(pb.no_peminjaman IS NOT NULL, 'Dikembalikan', 'Belum Dikembalikan') AS status
    FROM tb_peminjaman p
    LEFT JOIN tb_pengembalian pb ON p.no_peminjaman = pb.no_peminjaman
";

// Build WHERE clause
$conditions = [];
$params = [];

if (!empty($search)) {
    $conditions[] = "(p.nama LIKE :search OR p.judul_buku LIKE :search OR p.nis LIKE :search OR p.no_peminjaman LIKE :search)";
    $params[':search'] = '%' . $search . '%';
}

if (!empty($year)) {
    $conditions[] = "YEAR(p.tgl_pinjam) = :year";
    $params[':year'] = $year;
}

if (!empty($conditions)) {
    $sql .= " WHERE " . implode(' AND ', $conditions);
}

// Add pagination - Order by no_peminjaman ascending
$sql .= " ORDER BY p.no_peminjaman ASC LIMIT :limit OFFSET :offset";

// Prepare and execute the query
$stmt = $pdo->prepare($sql);

// Bind all parameters
foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value);
}
$stmt->bindValue(':limit', (int)$rowsPerPage, PDO::PARAM_INT);
$stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Count total rows for pagination
$countSql = "SELECT COUNT(*) FROM tb_peminjaman p LEFT JOIN tb_pengembalian pb ON p.no_peminjaman = pb.no_peminjaman";

if (!empty($conditions)) {
    $countSql .= " WHERE " . implode(' AND ', $conditions);
}

$countStmt = $pdo->prepare($countSql);
foreach ($params as $key => $value) {
    if ($key !== ':limit' && $key !== ':offset') {
        $countStmt->bindValue($key, $value);
    }
}
$countStmt->execute();
$totalRows = $countStmt->fetchColumn();

// Calculate total pages
$totalPages = ceil($totalRows / $rowsPerPage);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SIFPER</title>
    <style>
      body {
        font-family: Arial, sans-serif;
        margin: 0;
        padding: 0;
        display: flex;
        flex-direction: column;
        height: 100vh;
        background-image: url("../Img/Sekolahsmpn4.jpg");
        background-size: cover;
        background-position: center;
      }
      .hamburger {
        cursor: pointer;
        padding: 10px;
        position: absolute;
        top: 3px;
        left: 0px;
        z-index: 1000;
        font-size: 30px;
        transition: transform 0.3s;
      }
      .sidebar {
        width: 160px;
        background-color: rgba(244, 244, 244, 0.9);
        padding: 20px;
        height: 100vh;
        box-shadow: 2px 0 5px rgba(0, 0, 0, 0.2);
        display: flex;
        flex-direction: column;
        align-items: center;
        position: fixed;
        left: -200px;
        transition: left 0.3s, opacity 0.3s;
        opacity: 0.9;
        overflow-y: auto;
      }
      .sidebar.open {
        left: 0;
        opacity: 1;
      }
      .sidebar h2 {
        text-align: center;
      }
      .sidebar h4 {
        background: linear-gradient(90deg, #003366, #f1bd00);
        -webkit-background-clip: text;
        background-clip: text;
        color: transparent;
        font-size: 30px;
        margin: 10px 0;
      }
      .sidebar a {
        display: flex;
        align-items: center;
        padding: 10px;
        margin: 10px 0;
        text-decoration: none;
        color: black;
        border-radius: 6px;
        transition: background 0.3s;
        width: 100%;
      }
      .sidebar a:hover {
        background-color: rgba(241, 189, 0, 0.7);
      }
      .icon {
        width: 20px;
        margin-right: 10px;
      }
      .logout {
        display: flex;
        margin-top: 20px;
        margin-bottom: 20px;
        text-align: center;
      }
      .logout a {
        background-color: rgba(0, 0, 0, 0.9);
        color: rgb(255, 255, 255);
        padding: 10px 50px;
        border-radius: 20px;
        text-decoration: none;
        display: inline-block;
      }
      .logout a:hover {
        background-color: rgba(241, 189, 0, 1);
      }
      .content {
        flex-grow: 1;
        padding: 20px;
        background-color: rgba(255, 255, 255, 0.9);
        border-radius: 0px;
        margin-left: 0;
        transition: margin-left 0.3s;
        display: flex;
        flex-direction: column;
        height: 100%;
        overflow: auto;
        position: relative;
      }
      .content.shift {
        margin-left: 200px;
      }
      .header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 10px 0;
      }
      .user-info {
        display: flex;
        align-items: center;
        margin-left: auto;
      }
      .user-info img {
        width: 40px;
        height: auto;
        border-radius: 50%;
        margin-right: 10px;
      }
      .search-container {
        display: flex;
        align-items: center;
        margin-top: 20px;
        justify-content: flex-start;
      }
      .search-box {
        position: relative;
        width: 300px; 
      }
      .search-box input {
        margin-left: 5px;
        background-color: transparent;
        color: black;
        border: 2px solid #ccc;
        border-radius: 30px;
        padding: 8px;
        width: 80%;
      }
      .search-box input:focus {
        outline: none;
        box-shadow: 0 0 5px #000000;
      }
      .year-select {
        position: relative;
        margin-left: 5px;
      }
      .year-select select {
        background-color: transparent;
        color: black;
        border: 2px solid #ccc;
        border-radius: 30px;
        padding: 8px;
        width: 110%;
      }
      .export-print {
        display: flex;
        justify-content: flex-end;
        margin-top: 20px;
      }
      .export-print button {
        margin-left: 10px;
        padding: 8px 12px;
        border: none;
        border-radius: 4px;
        background-color: #f1bd00;
        color: white;
        cursor: pointer;
      }
      .export-print button:hover {
        background-color: #d9a700;
      }
      table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
      }
      th,
      td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: left;
      }
      th {
        background-color: #f1bd00;
        text-align: center;
      }
      .pagination-control {
        display: flex;
        justify-content: flex-end;
        align-items: center;
        margin-top: 20px;
        gap: 10px;
        font-size: 14px;
      }
      .pagination-control select {
        padding: 3px 6px;
        border-radius: 4px;
        border: 1px solid #ccc;
      }
      .pagination-control button {
        background: none;
        border: none;
        font-size: 18px;
        cursor: pointer;
        color: #f1bd00;
      }
      .pagination-control span {
        min-width: 100px;
        text-align: center;
      }
      .halaman {
        flex: -1;
        margin-right: 10px;
      }
      .modal {
        display: none;
        position: fixed;
        z-index: 2000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.4);
      }
      .modal-content {
        background-color: #fff;
        margin: 15% auto;
        padding: 30px;
        border: 1px solid #888;
        width: 300px;
        border-radius: 8px;
        text-align: center;
        box-shadow: 0px 0px 10px #000;
        gap: 15px;
      }
      .modal-content h3 {
        margin-bottom: 20px;
      }
      .modal-content button {
        width: 200px;
        padding: 10px 20px;
        margin: 10px auto;
        border: none;
        border-radius: 30px;
        background-color: #000;
        color: white;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        transition: background-color 0.3s;
      }
      .modal-content button:hover {
        background-color: #d9a700;
      }
      
      .modal-content button img {
          width: 30px;
          height: 30px;
      }
      .close {
        color: #aaa;
        float: right;
        font-size: 24px;
        font-weight: bold;
        cursor: pointer;
      }
      .close:hover {
        color: #000;
      }
      @media print {
        body * {
            visibility: hidden;
        }
        .content {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            margin: 0;
            padding: 0;
            background-color: white;
        }
        .data, .data * {
            visibility: visible;
        }
        .data {
            width: 100%;
            margin: 0;
            padding: 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #000;
            padding: 5px;
        }
      }
    </style>
</head>
<body>
    <div class="hamburger" onclick="toggleSidebar()">&#9776;</div>

    <div class="sidebar" id="sidebar">
        <h2>
            <img src="../Img/Smpn4tangsel.png" alt="Logo Sekolah" style="width: 150px; height: auto" />
        </h2>
        <h4>SIFPER</h4>
        <a href="../Dashboard/Beranda.php"><img src="../Img/dashboard.png" class="icon" alt="Dashboard Icon" />Beranda</a>
        <a href="../Buku/Data_Buku.html"><img src="../Img/Buku.png" class="icon" alt="Buku Icon" />Buku</a>
        <a href="../Siswa/Data_Siswa.php"><img src="../Img/siswa.png" class="icon" alt="Siswa Icon" />Siswa</a>
        <a href="../Peminjaman/Data_Peminjaman.html"><img src="../Img/pinjam.png" class="icon" alt="Peminjaman Icon" />Peminjaman</a>
        <a href="../Pengembalian/Data_Pengembalian.html"><img src="../Img/pengembalian.png" class="icon" alt="Pengembalian Icon" />Pengembalian</a>
        <a href="laporan.php"><img src="../Img/laporan.png" class="icon" alt="Laporan Icon" />Laporan</a>
        <div class="logout"><a href="../Index.html">Logout</a></div>
    </div>

    <div class="content" id="content">
        <div class="header">
            <h2>Laporan Peminjaman & Pengembalian</h2>
            <div class="user-info">
                <img src="../Img/profile.png" alt="User Icon" />
                <span>Ris Naia Natasya</span>
            </div>
        </div>
        
        <div class="search-container">
            <div class="search-box">
                <input 
                    type="text" 
                    id="searchInput" 
                    placeholder="Cari nama, judul buku, NIS, atau no peminjaman..." 
                    value="<?php echo htmlspecialchars($search); ?>"
                />
            </div>

            <div class="year-select">
                <select id="yearDropdown">
                    <option value="">Semua Tahun</option>
                    <?php foreach ($years as $yearOption): ?>
                        <option value="<?php echo $yearOption; ?>" <?php echo ($yearOption == $year) ? 'selected' : ''; ?>>
                            <?php echo $yearOption; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="data">
            <table>
                <thead>
                    <tr>
                        <th>No.</th>
                        <th>No. Peminjaman</th>
                        <th>Tanggal Peminjaman</th>
                        <th>Tanggal Pengembalian</th>
                        <th>Tanggal Dikembalikan</th>
                        <th>NIS</th>
                        <th>Nama</th>
                        <th>Id Buku</th>
                        <th>Judul Buku</th>
                        <th>Keterlambatan (Hari)</th>
                        <th>Denda (Rp.)</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="bookTableBody">
                    <?php if (empty($results)): ?>
                        <tr>
                            <td colspan="12" style="text-align: center;">Tidak ada data yang ditemukan</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($results as $index => $row): ?>
                            <tr>
                                <td><?php echo $offset + $index + 1; ?></td>
                                <td><?php echo htmlspecialchars($row['no_peminjaman']); ?></td>
                                <td><?php echo htmlspecialchars($row['tgl_pinjam']); ?></td>
                                <td><?php echo htmlspecialchars($row['tgl_kembali']); ?></td>
                                <td><?php echo htmlspecialchars($row['tgl_dikembalikan']); ?></td>
                                <td><?php echo htmlspecialchars($row['nis']); ?></td>
                                <td><?php echo htmlspecialchars($row['nama']); ?></td>
                                <td><?php echo htmlspecialchars($row['id_buku']); ?></td>
                                <td><?php echo htmlspecialchars($row['judul_buku']); ?></td>
                                <td><?php echo htmlspecialchars($row['keterlambatan']); ?></td>
                                <td><?php echo htmlspecialchars($row['denda']); ?></td>
                                <td><?php echo htmlspecialchars($row['status']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    
        <div class="pagination-control">
            <label class="halaman">Data Perhalaman</label>
            <select id="rowsPerPage" onchange="changeRowsPerPage()">
                <option value="5" <?php echo ($rowsPerPage == 5) ? 'selected' : ''; ?>>5</option>
                <option value="10" <?php echo ($rowsPerPage == 10) ? 'selected' : ''; ?>>10</option>
                <option value="25" <?php echo ($rowsPerPage == 25) ? 'selected' : ''; ?>>25</option>
                <option value="50" <?php echo ($rowsPerPage == 50) ? 'selected' : ''; ?>>50</option>
                <option value="100" <?php echo ($rowsPerPage == 100) ? 'selected' : ''; ?>>100</option>
            </select>
            <span id="pageInfo">
                <?php 
                    $start = min($totalRows, $offset + 1);
                    $end = min($offset + $rowsPerPage, $totalRows);
                    echo "{$start}–{$end} dari {$totalRows}";
                ?>
            </span>
            <button onclick="previousPage()" <?php echo ($currentPage == 1) ? 'disabled' : ''; ?>>&lt;</button>
            <button onclick="nextPage()" <?php echo ($currentPage >= $totalPages) ? 'disabled' : ''; ?>>&gt;</button>
        </div>

        <div class="export-print">
            <button onclick="showExportOptions()">Export
            </button>
            <button onclick="printData()">
                Print
            </button>
        </div>
    </div>

    <div id="exportModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3>Pilih Format Export</h3>
            <button onclick="exportData('pdf')">
                <img src="../Img/pdf.png" alt="PDF Icon" /> Export as PDF
            </button>
            <button onclick="exportData('excel')">
                <img src="../Img/xls.png" alt="Excel Icon" /> Export as Excel
            </button>
        </div>
    </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.25/jspdf.plugin.autotable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
    let currentPage = <?php echo $currentPage; ?>;
    let rowsPerPage = <?php echo $rowsPerPage; ?>;
    let totalRows = <?php echo $totalRows; ?>;
    let totalPages = <?php echo $totalPages; ?>;
    let searchTimeout;

    function toggleSidebar() {
        const sidebar = document.getElementById("sidebar");
        const content = document.getElementById("content");
        sidebar.classList.toggle("open");
        content.classList.toggle("shift");
    }

    document.getElementById('searchInput').addEventListener('keyup', function(e) {
        if (e.key === 'ArrowUp' || e.key === 'ArrowDown' || 
            e.key === 'ArrowLeft' || e.key === 'ArrowRight' || 
            e.key === 'Control' || e.key === 'Alt' || 
            e.key === 'Shift' || e.key === 'Tab' || 
            e.key === 'CapsLock' || e.key === 'Escape') {
            return;
        }
        
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            searchBooks();
        }, 1000);
    });

    document.getElementById('yearDropdown').addEventListener('change', updateYear);

    function updatePageInfo() {
        const start = (currentPage - 1) * rowsPerPage + 1;
        const end = Math.min(currentPage * rowsPerPage, totalRows);
        document.getElementById("pageInfo").innerText = `${start}–${end} dari ${totalRows}`;
    }

    function changeRowsPerPage() {
        rowsPerPage = parseInt(document.getElementById("rowsPerPage").value);
        currentPage = 1;
        updateUrl();
    }

    function previousPage() {
        if (currentPage > 1) {
            currentPage--;
            updateUrl();
        }
    }

    function nextPage() {
        if (currentPage < totalPages) {
            currentPage++;
            updateUrl();
        }
    }

    function updateUrl() {
        const search = document.getElementById("searchInput").value.trim();
        const year = document.getElementById("yearDropdown").value;
        const url = new URL(window.location.href);
        
        if (search) url.searchParams.set('search', search);
        else url.searchParams.delete('search');
        
        if (year) url.searchParams.set('year', year);
        else url.searchParams.delete('year');
        
        url.searchParams.set('rowsPerPage', rowsPerPage);
        url.searchParams.set('page', currentPage);
        
        window.location.href = url.toString();
    }

    function searchBooks() {
        currentPage = 1;
        updateUrl();
    }

    function updateYear() {
        currentPage = 1;
        updateUrl();
    }

    window.onload = () => {
        updatePageInfo();
        const searchInput = document.getElementById("searchInput");
        searchInput.addEventListener('focus', function() {
            const pos = this.selectionStart;
            setTimeout(() => {
                this.selectionStart = pos;
                this.selectionEnd = pos;
            }, 0);
        });
    };

    function showExportOptions() {
      document.getElementById("exportModal").style.display = "block";
    }

    function closeModal() {
        document.getElementById("exportModal").style.display = "none";
    }

    function exportData(format) {
      const table = document.querySelector("table");
      const rows = table.querySelectorAll("tr");
      const data = [];

      // Ambil header
      const headerRow = rows[0];
      const headerData = [];
      headerRow.querySelectorAll("th").forEach((cell) => {
          headerData.push(cell.innerText);
      });
      data.push(headerData);

      // Ambil isi tabel
      for (let i = 1; i < rows.length; i++) {
          const rowData = [];
          rows[i].querySelectorAll("td").forEach((cell) => {
              rowData.push(cell.innerText);
          });
          data.push(rowData);
      }

      if (format === "pdf") {
          const { jsPDF } = window.jspdf;
          const doc = new jsPDF({
              orientation: "landscape",
              unit: "mm",
              format: "a4",
          });
          doc.autoTable({ 
              head: [data[0]], 
              body: data.slice(1),
              styles: {
                  fontSize: 8,
                  cellPadding: 2,
              },
              margin: { top: 10 }
          });
          doc.save("Laporan_Peminjaman.pdf");
      } else if (format === "excel") {
          const worksheet = XLSX.utils.aoa_to_sheet(data);
          const workbook = XLSX.utils.book_new();
          XLSX.utils.book_append_sheet(workbook, worksheet, "Laporan");
          XLSX.writeFile(workbook, "Laporan_Peminjaman.xlsx");
      }

      closeModal();
    }

    function printData() {
        window.print();
    }

</script>
</body>
</html>