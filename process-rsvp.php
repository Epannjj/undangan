<?php
// process-rsvp.php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = htmlspecialchars($_POST['name'] ?? '');
    $status = htmlspecialchars($_POST['status'] ?? '');
    $message = htmlspecialchars($_POST['message'] ?? '');

    if (!empty($name) && !empty($message)) {
        // Data baru yang dikirim
        $newComment = [
            'name' => $name,
            'status' => $status,
            'message' => $message,
            'date' => date('d M Y, H:i')
        ];

        // Simulasi simpan ke file JSON (Atau ganti dengan query INSERT INTO database MySQL Anda)
        $file = 'comments.json';$currentData = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
        array_unshift($currentData,$newComment); // Menaruh ucapan terbaru di urutan paling atas
        file_put_contents($file, json_encode($currentData));

        echo json_encode(['status' => 'success', 'message' => 'Ucapan berhasil dikirim!']);
        exit;
    }
}

echo json_encode(['status' => 'error', 'message' => 'Gagal mengirim data.']);
?>
indonesia