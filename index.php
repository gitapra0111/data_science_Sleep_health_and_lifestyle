<?php
$hasil_prediksi = null;
$error_message = null;

// Daftar pemetaan teks ke angka (Sesuai LabelEncoder yang dilatih di Python)
$mapping_gender = ['Female' => 0, 'Male' => 1];

$mapping_occupation = [
    'Accountant' => 0, 'Doctor' => 1, 'Engineer' => 2, 'Lawyer' => 3, 
    'Manager' => 4, 'Nurse' => 5, 'Sales Representative' => 6, 
    'Salesperson' => 7, 'Scientist' => 8, 'Software Engineer' => 9, 'Teacher' => 10
];

$mapping_bmi = [
    'Normal' => 0, 'Normal Weight' => 1, 'Obese' => 2, 'Overweight' => 3
];

$mapping_disorder = [
    'Insomnia' => 0, 'None' => 1, 'Sleep Apnea' => 2
];

// Cek apakah tombol submit pada form diklik
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Ambil nilai teks dari pilihan user, lalu ubah ke kode angka
        $gender_val = $mapping_gender[$_POST['Gender']] ?? 1;
        $occ_val = $mapping_occupation[$_POST['Occupation']] ?? 9;
        $bmi_val = $mapping_bmi[$_POST['BMI_Category']] ?? 0;
        $disorder_val = $mapping_disorder[$_POST['Sleep_Disorder']] ?? 1;

        $data_input = [
            "Gender"                  => (int)$gender_val,
            "Age"                     => (int)$_POST['Age'],
            "Occupation"              => (int)$occ_val,
            "Sleep Duration"          => (float)$_POST['Sleep_Duration'],
            "Quality of Sleep"        => (int)$_POST['Quality_of_Sleep'],
            "Physical Activity Level" => (int)$_POST['Physical_Activity_Level'],
            "BMI Category"            => (int)$bmi_val,
            "Heart Rate"              => (int)$_POST['Heart_Rate'],
            "Daily Steps"             => (int)$_POST['Daily_Steps'],
            "Sleep Disorder"          => (int)$disorder_val,
            "Systolic_BP"             => (int)$_POST['Systolic_BP'],
            "Diastolic_BP"            => (int)$_POST['Diastolic_BP']
        ];

        $payload = json_encode($data_input);
        $url = 'http://127.0.0.1:5000/api/v1/predict';
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Content-Length: ' . strlen($payload)
        ]);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($http_code === 200) {
            $result = json_decode($response, true);
            if (isset($result['status']) && $result['status'] === 'success') {
                $hasil_prediksi = $result['data']['stress_level_prediction'];
            } else {
                $error_message = "Gagal memproses prediksi dari server AI.";
            }
        } else {
            $error_message = "Tidak dapat terhubung ke server API Python. Pastikan file app.py sedang aktif di terminal.";
        }
    } catch (Exception $e) {
        $error_message = "Terjadi kesalahan: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HR-Tech : Sistem Analisis Stres Pegawai</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-9">
                
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-header bg-primary text-white py-4 rounded-top-4 text-center">
                        <h3 class="mb-1 fw-bold">Sistem Prediksi & Analisis Stres Pegawai</h3>
                        <p class="mb-0 text-white-50">Masukkan data kesehatan harian untuk mendeteksi tingkat kelelahan mental secara instan</p>
                    </div>
                    
                    <div class="card-body p-4 p-md-5">
                        
                        <!-- Notifikasi Hasil Prediksi -->
                        <?php if ($hasil_prediksi !== null): ?>
                            <div class="alert alert-<?php echo ($hasil_prediksi > 6) ? 'danger' : (($hasil_prediksi > 4) ? 'warning' : 'success'); ?> text-center mb-4 p-4 shadow-sm" role="alert">
                                <h4 class="alert-heading fw-bold">Hasil Analisis AI Selesai!</h4>
                                <p class="mb-1">Tingkat Stres Pegawai Terindikasi pada Angka:</p>
                                <span class="display-3 fw-bold"><?php echo $hasil_prediksi; ?></span>
                                <hr>
                                <p class="mb-0 fw-semibold">
                                    <?php 
                                        if($hasil_prediksi > 6) echo "⚠️ Perhatian: Tingkat stres tinggi! Disarankan evaluasi beban kerja.";
                                        else if($hasil_prediksi > 4) echo "⚡ Tingkat stres sedang. Perhatikan waktu istirahat.";
                                        else echo "✅ Kondisi mental stabil dan sehat.";
                                    ?>
                                </p>
                            </div>
                        <?php endif; ?>

                        <?php if ($error_message !== null): ?>
                            <div class="alert alert-danger text-center mb-4" role="alert">
                                <?php echo $error_message; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Formulir User-Friendly -->
                        <form method="POST" action="">
                            <div class="row g-3">
                                
                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Jenis Kelamin</label>
                                    <select name="Gender" class="form-select">
                                        <option value="Male">Laki-laki</option>
                                        <option value="Female">Perempuan</option>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Usia (Tahun)</label>
                                    <input type="number" name="Age" class="form-control" value="28" min="18" max="70" required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Profesi / Pekerjaan</label>
                                    <select name="Occupation" class="form-select">
                                        <?php foreach(array_keys($mapping_occupation) as $occ): ?>
                                            <option value="<?php echo $occ; ?>" <?php echo ($occ == 'Software Engineer') ? 'selected' : ''; ?>><?php echo $occ; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Durasi Tidur (Jam)</label>
                                    <input type="number" step="0.1" name="Sleep_Duration" class="form-control" value="6.5" min="2" max="12" required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Kualitas Tidur (1 - 10)</label>
                                    <input type="number" name="Quality_of_Sleep" class="form-control" value="7" min="1" max="10" required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Aktivitas Fisik (Menit/Hari)</label>
                                    <input type="number" name="Physical_Activity_Level" class="form-control" value="45" min="0" max="180" required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Kategori Berat Badan (BMI)</label>
                                    <select name="BMI_Category" class="form-select">
                                        <?php foreach(array_keys($mapping_bmi) as $bmi): ?>
                                            <option value="<?php echo $bmi; ?>"><?php echo $bmi; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Detak Jantung (BPM)</label>
                                    <input type="number" name="Heart_Rate" class="form-control" value="72" min="50" max="120" required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Jumlah Langkah Harian</label>
                                    <input type="number" name="Daily_Steps" class="form-control" value="7500" min="500" max="25000" required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Riwayat Gangguan Tidur</label>
                                    <select name="Sleep_Disorder" class="form-select">
                                        <?php foreach(array_keys($mapping_disorder) as $dis): ?>
                                            <option value="<?php echo $dis; ?>" <?php echo ($dis == 'None') ? 'selected' : ''; ?>><?php echo $dis; ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Tekanan Darah Sistolik</label>
                                    <input type="number" name="Systolic_BP" class="form-control" value="120" required>
                                </div>

                                <div class="col-md-4">
                                    <label class="form-label fw-bold">Tekanan Darah Diastolik</label>
                                    <input type="number" name="Diastolic_BP" class="form-control" value="80" required>
                                </div>

                            </div>

                            <div class="d-grid mt-4 pt-2">
                                <button type="submit" class="btn btn-primary btn-lg py-3 fw-bold shadow">Analisis Tingkat Stres Pegawai Sekarang</button>
                            </div>
                        </form>

                    </div>
                </div>

            </div>
        </div>
    </div>

</body>
</html>