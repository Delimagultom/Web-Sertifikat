<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
include "koneksi.php";

// Check if TCPDF is installed
if (!file_exists('tcpdf/tcpdf.php')) {
    die("TCPDF is not installed. Please run install_tcpdf.php first.");
}

if (!isset($_GET['type']) || !in_array($_GET['type'], ['pdf', 'excel'])) {
    header("Location: admin_dashboard.php");
    exit;
}

$type = $_GET['type'];

// Get certificate data with additional information
$query = mysqli_query($conn, "SELECT 
    s.*, 
    u.nama_kth, 
    u.email,
    u.alamat,
    CASE 
        WHEN DATEDIFF(CURRENT_DATE, s.tanggal_upload) <= 30 THEN 'Baru (30 hari)'
        WHEN DATEDIFF(CURRENT_DATE, s.tanggal_upload) <= 90 THEN 'Menengah (3 bulan)'
        ELSE 'Lama (>3 bulan)'
    END as kategori
    FROM sertifikat s 
    JOIN users u ON s.user_id = u.id 
    ORDER BY s.tanggal_upload DESC");

if (!$query) {
    die("Error fetching data: " . mysqli_error($conn));
}

$data = array();
while ($row = mysqli_fetch_assoc($query)) {
    $data[] = $row;
}

if ($type == 'excel') {
    // Set headers for Excel download
    header('Content-Type: application/vnd.ms-excel; charset=utf-8');
    header('Content-Disposition: attachment;filename="laporan_sertifikat_' . date('Y-m-d') . '.xls"');
    header('Cache-Control: max-age=0');
    header('Pragma: public');

    // Output Excel content with BOM for proper UTF-8 encoding
    echo "\xEF\xBB\xBF"; // UTF-8 BOM

    // Excel header
    echo "LAPORAN SERTIFIKAT KTH\n";
    echo "Tanggal: " . date('d/m/Y H:i') . "\n\n";

    // Column headers
    echo "Nama KTH\tNomor Sertifikat\tTanggal Upload\tKategori\tTipe File\tStatus\tEmail\tAlamat\n";

    // Data rows
    foreach ($data as $row) {
        $file_ext = strtolower(pathinfo($row['nama_file'], PATHINFO_EXTENSION));
        echo implode("\t", array(
            $row['nama_kth'],
            $row['nomor_sertifikat'],
            date('d/m/Y H:i', strtAtime($row['tanggal_upload'])),
            $row['kategori'],
            strtoupper($file_ext),
            'Terverifikasi',
            $row['email'],
            $row['alamat']
        )) . "\n";
    }
} else {
    // For PDF export
    require_once('tcpdf/tcpdf.php');

    // Create new PDF document
    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    // Set document information
    $pdf->SetCreator('Sistem Sertifikat KTH');
    $pdf->SetAuthor('Admin KTH');
    $pdf->SetTitle('Laporan Sertifikat KTH');

    // Set default header data
    $pdf->SetHeaderData('', 0, 'Laporan Sertifikat KTH', 'Dicetak pada: ' . date('d/m/Y H:i'));

    // Set header and footer fonts
    $pdf->setHeaderFont(array('helvetica', '', 12));
    $pdf->setFooterFont(array('helvetica', '', 8));

    // Set default monospaced font
    $pdf->SetDefaultMonospacedFont('courier');

    // Set margins
    $pdf->SetMargins(15, 15, 15);
    $pdf->SetHeaderMargin(5);
    $pdf->SetFooterMargin(10);

    // Set auto page breaks
    $pdf->SetAutoPageBreak(TRUE, 15);

    // Set image scale factor
    $pdf->setImageScale(1.25);

    // Add a page
    $pdf->AddPage();

    // Set font
    $pdf->SetFont('helvetica', '', 10);

    // Add title
    $pdf->SetFont('helvetica', 'B', 14);
    $pdf->Cell(0, 10, 'Laporan Sertifikat KTH', 0, 1, 'C');
    $pdf->SetFont('helvetica', '', 10);
    $pdf->Cell(0, 10, 'Tanggal: ' . date('d/m/Y H:i'), 0, 1, 'C');
    $pdf->cell(10);

    // Create the table content
    $html = '<table border="1" cellpadding="4">
        <thead>
            <tr style="background-color: #f8f9fa;">
                <th width="25%">Nama KTH</th>
                <th width="20%">Nomor Sertifikat</th>
                <th width="15%">Tanggal Upload</th>
                <th width="15%">Kategori</th>
                <th width="10%">Tipe File</th>
                <th width="15%">Status</th>
            </tr>
        </thead>
        <tbody>';

    foreach ($data as $row) {
        $file_ext = strtolower(pathinfo($row['nama_file'], PATHINFO_EXTENSION));
        $html .= '<tr>
            <td>' . $row['nama_kth'] . '</td>
            <td>' . $row['nomor_sertifikat'] . '</td>
            <td>' . date('d/m/Y H:i', strtotime($row['tanggal_upload'])) . '</td>
            <td>' . $row['kategori'] . '</td>
            <td>' . strtoupper($file_ext) . '</td>
            <td>Terverifikasi</td>
        </tr>';
    }

    $html .= '</tbody></table>';

    // Print the table
    $pdf->writeHTML($html, true, false, true, false, '');

    // Add summary
    $pdf->Ln(10);
    $pdf->SetFont('helvetica', 'B', 11);
    $pdf->Cell(0, 10, 'Ringkasan:', 0, 1);
    $pdf->SetFont('helvetica', '', 10);

    $total = count($data);
    $kategori_count = array_count_values(array_column($data, 'kategori'));

    $pdf->Cell(0, 7, 'Total Sertifikat: ' . $total, 0, 1);
    foreach ($kategori_count as $kategori => $count) {
        $pdf->Cell(0, 7, $kategori . ': ' . $count, 0, 1);
    }

    // Close and output PDF document
    $pdf->Output('laporan_sertifikat_' . date('Y-m-d') . '.pdf', 'D');
}
exit;