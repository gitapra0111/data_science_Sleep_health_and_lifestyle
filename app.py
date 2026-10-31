from pathlib import Path

import joblib
import pandas as pd
import streamlit as st


MODEL_PATH = Path(__file__).with_name("model_prediksi_stres.pkl")
FEATURE_COLUMNS = [
    "Gender",
    "Age",
    "Occupation",
    "Sleep Duration",
    "Quality of Sleep",
    "Physical Activity Level",
    "BMI Category",
    "Heart Rate",
    "Daily Steps",
    "Sleep Disorder",
    "Systolic_BP",
    "Diastolic_BP",
]

GENDER_CODES = {"Female": 0, "Male": 1}
OCCUPATION_CODES = {
    "Accountant": 0,
    "Doctor": 1,
    "Engineer": 2,
    "Lawyer": 3,
    "Manager": 4,
    "Nurse": 5,
    "Sales Representative": 6,
    "Salesperson": 7,
    "Scientist": 8,
    "Software Engineer": 9,
    "Teacher": 10,
}
BMI_CODES = {"Normal": 0, "Normal Weight": 1, "Obese": 2, "Overweight": 3}
SLEEP_DISORDER_CODES = {"Insomnia": 0, "None": 1, "Sleep Apnea": 2}


@st.cache_resource
def load_model():
    return joblib.load(MODEL_PATH)


st.set_page_config(
    page_title="Prediksi Stres Pegawai",
    page_icon="💤",
    layout="wide",
)

st.title("Sistem Prediksi Tingkat Stres Pegawai")
st.write(
    "Masukkan data kesehatan dan gaya hidup untuk mendapatkan prediksi "
    "tingkat stres dari model machine learning."
)

if not MODEL_PATH.is_file():
    st.error(
        f"File model tidak ditemukan: {MODEL_PATH.name}. "
        "Pastikan file model berada di folder yang sama dengan app.py."
    )
    st.stop()

model = load_model()

with st.form("stress_prediction_form"):
    st.subheader("Data pegawai")
    first_row = st.columns(3)
    gender = first_row[0].selectbox("Jenis kelamin", list(GENDER_CODES))
    age = first_row[1].number_input("Usia (tahun)", min_value=18, max_value=70, value=28)
    occupation = first_row[2].selectbox(
        "Profesi",
        list(OCCUPATION_CODES),
        index=list(OCCUPATION_CODES).index("Software Engineer"),
    )

    second_row = st.columns(3)
    sleep_duration = second_row[0].number_input(
        "Durasi tidur (jam)", min_value=2.0, max_value=12.0, value=6.5, step=0.1
    )
    sleep_quality = second_row[1].number_input(
        "Kualitas tidur (1-10)", min_value=1, max_value=10, value=7
    )
    physical_activity = second_row[2].number_input(
        "Aktivitas fisik (menit/hari)", min_value=0, max_value=180, value=45
    )

    third_row = st.columns(3)
    bmi_category = third_row[0].selectbox("Kategori BMI", list(BMI_CODES))
    heart_rate = third_row[1].number_input(
        "Detak jantung (BPM)", min_value=50, max_value=120, value=72
    )
    daily_steps = third_row[2].number_input(
        "Jumlah langkah harian", min_value=500, max_value=25000, value=7500, step=500
    )

    fourth_row = st.columns(3)
    sleep_disorder = fourth_row[0].selectbox(
        "Gangguan tidur", list(SLEEP_DISORDER_CODES), index=1
    )
    systolic_bp = fourth_row[1].number_input(
        "Tekanan darah sistolik", min_value=1, value=120
    )
    diastolic_bp = fourth_row[2].number_input(
        "Tekanan darah diastolik", min_value=1, value=80
    )

    submitted = st.form_submit_button(
        "Prediksi tingkat stres",
        type="primary",
        use_container_width=True,
    )

if submitted:
    input_values = {
        "Gender": GENDER_CODES[gender],
        "Age": age,
        "Occupation": OCCUPATION_CODES[occupation],
        "Sleep Duration": sleep_duration,
        "Quality of Sleep": sleep_quality,
        "Physical Activity Level": physical_activity,
        "BMI Category": BMI_CODES[bmi_category],
        "Heart Rate": heart_rate,
        "Daily Steps": daily_steps,
        "Sleep Disorder": SLEEP_DISORDER_CODES[sleep_disorder],
        "Systolic_BP": systolic_bp,
        "Diastolic_BP": diastolic_bp,
    }
    input_data = pd.DataFrame([input_values], columns=FEATURE_COLUMNS)
    prediction = int(model.predict(input_data)[0])

    st.subheader("Hasil prediksi")
    st.metric("Prediksi tingkat stres", prediction)
    if prediction > 6:
        st.error("Tingkat stres terindikasi tinggi. Pertimbangkan evaluasi beban kerja.")
    elif prediction > 4:
        st.warning("Tingkat stres terindikasi sedang. Perhatikan waktu istirahat.")
    else:
        st.success("Tingkat stres terindikasi rendah.")

st.caption(
    "Hasil ini merupakan prediksi model, bukan diagnosis medis."
)
