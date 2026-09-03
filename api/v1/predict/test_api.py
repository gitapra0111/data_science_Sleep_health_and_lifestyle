import requests

# Alamat loket prediksi baru kita
url = 'http://127.0.0.1:5000/api/v1/predict'

# Data sampel kesehatan pegawai (Pastikan lengkap 12 kolom)
data_pegawai = {
    "Gender": 1,
    "Age": 28,
    "Occupation": 9,
    "Sleep Duration": 5.5,
    "Quality of Sleep": 4,
    "Physical Activity Level": 30,
    "BMI Category": 2,
    "Heart Rate": 85,
    "Daily Steps": 3000,
    "Sleep Disorder": 2,
    "Systolic_BP": 140,
    "Diastolic_BP": 90
}

# Mengirim data dengan metode POST
response = requests.post(url, json=data_pegawai)

# Menampilkan hasil respons dari API
print("Status Code:", response.status_code)
print("Respons dari Server AI:", response.json())