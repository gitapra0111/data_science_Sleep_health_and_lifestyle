from flask import Flask, request, jsonify
from flask_cors import CORS
import joblib
import pandas as pd
import logging

# Inisialisasi aplikasi Flask
app = Flask(__name__)

# Mengaktifkan CORS agar API bisa menerima request dari Frontend (PHP/JavaScript)
CORS(app)

# Konfigurasi sistem pencatatan (Logging) untuk memantau aktivitas server
logging.basicConfig(level=logging.INFO, format='%(asctime)s - %(levelname)s - %(message)s')

# Memuat model saat server pertama kali dihidupkan dengan penanganan error
try:
    model = joblib.load('model_prediksi_stres.pkl')
    logging.info("Model Random Forest berhasil dimuat.")
except Exception as e:
    logging.error(f"Gagal memuat model: {e}")
    model = None

# Fitur pengecekan status server
@app.route('/', methods=['GET'])
def health_check():
    return jsonify({
        'status': 'success',
        'message': 'API HR-Tech Analytics Berjalan Normal.'
    }), 200

# Endpoint prediksi dengan versi (Best Practice: /api/v1/...)
@app.route('/api/v1/predict', methods=['POST'])
def predict_stress():
    # 1. Pengecekan apakah model tersedia
    if model is None:
        return jsonify({
            'status': 'error',
            'message': 'Internal Server Error: Model AI tidak tersedia.'
        }), 500

    try:
        # 2. Menerima data JSON dari Frontend
        data = request.get_json()
        
        # 3. Validasi: Pastikan data tidak kosong
        if not data:
            return jsonify({
                'status': 'error',
                'message': 'Bad Request: Tidak ada data yang dikirim.'
            }), 400

        # Daftar kolom yang wajib ada sesuai dataset pelatihan
        required_columns = [
            'Gender', 'Age', 'Occupation', 'Sleep Duration', 
            'Quality of Sleep', 'Physical Activity Level', 
            'BMI Category', 'Heart Rate', 'Daily Steps', 
            'Sleep Disorder', 'Systolic_BP', 'Diastolic_BP'
        ]

        # 4. Validasi: Pastikan semua kolom terpenuhi
        missing_cols = [col for col in required_columns if col not in data]
        if missing_cols:
            return jsonify({
                'status': 'error',
                'message': f'Data tidak lengkap. Kehilangan atribut: {", ".join(missing_cols)}'
            }), 400

        # 5. Eksekusi Prediksi
        df = pd.DataFrame([data])
        prediction = model.predict(df)[0]

        # 6. Mengembalikan respons sukses terstruktur
        logging.info("Prediksi berhasil dijalankan.")
        return jsonify({
            'status': 'success',
            'data': {
                'stress_level_prediction': int(prediction)
            },
            'message': 'Tingkat stres berhasil diprediksi.'
        }), 200

    except Exception as e:
        # Menangkap error tak terduga tanpa mematikan server
        logging.error(f"Error saat prediksi: {str(e)}")
        return jsonify({
            'status': 'error',
            'message': 'Terjadi kesalahan pada pemrosesan data.',
            'error_details': str(e)
        }), 500

if __name__ == '__main__':
    # Menjalankan server pada port 5000
    app.run(host='0.0.0.0', port=5000, debug=True)