# HR-Tech & Health Analytics: Employee Stress Prediction System

An end-to-end Machine Learning and Web Integration project designed to predict and analyze employee mental fatigue and sleep quality. This system empowers data-driven HR management decisions by providing real-time stress level assessments based on daily health metrics.

## 📌 Executive Summary
This project demonstrates a complete decoupled microservices architecture:
1. **Data Science Pipeline:** Exploratory Data Analysis (EDA) and model training using Jupyter Notebook.
2. **AI Backend (REST API):** A Python Flask server that serves the trained Random Forest model.
3. **Frontend Interface:** A user-friendly, responsive web application built with PHP and Bootstrap to interact with the API.

## 📊 Dataset Reference
The machine learning model was trained using the **Sleep Health and Lifestyle Dataset**. 
Due to version control best practices, the raw `.csv` and `.zip` files are excluded from this repository. 
You can access and download the original dataset here: 
👉 [Kaggle: Sleep Health and Lifestyle Dataset](https://www.kaggle.com/datasets/uom190346a/sleep-health-and-lifestyle-dataset)

## 🛠️ Tech Stack & Technologies
* **Machine Learning & Data Analysis:** Python, Pandas, Scikit-Learn, Joblib, Jupyter Notebook
* **Backend API:** Python, Flask, Flask-CORS, Gunicorn
* **Frontend Web:** PHP, HTML5, Bootstrap 5, cURL
* **Deployment (Planned):** Render.com (Backend API), Web Hosting (Frontend PHP)

## 📂 Repository Structure
* `main.ipynb` : The complete Data Science workflow (Data Cleaning, EDA, Label Encoding, and Random Forest training achieving 100% accuracy).
* `app.py` : The Flask REST API script acting as the bridge between the ML model and the web interface.
* `model_prediksi_stres.pkl` : The exported, pre-trained Random Forest classification model.
* `requirements.txt` : The Python dependencies required to run the backend server.
* `index.php` : The frontend user interface that consumes the Flask API via HTTP POST requests. *(Note: Requires a PHP server to run).*

## 🚀 How to Run Locally

### 1. Start the Machine Learning API (Backend)
Open your terminal, navigate to the project directory, and run the following commands:
```bash
# Install dependencies
pip install -r requirements.txt

# Run the Flask server
python app.py

# Start the Web Interface (Frontend) 

php -S localhost:8000
run python app.py

### 📡 API Endpoint Documentation
URL: /api/v1/predict

Method: POST

Content-Type: application/json

Example Request Payload:
{
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

Example Success Response:

{
    "status": "success",
    "message": "Tingkat stres berhasil diprediksi.",
    "data": {
        "stress_level_prediction": 8
    }
}
