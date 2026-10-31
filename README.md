# HR-Tech & Health Analytics: Employee Stress Prediction System

An end-to-end Machine Learning project that predicts employee stress levels from health and lifestyle metrics. The user interface and model inference run together in a single Streamlit application.

## 📌 Executive Summary
This project includes:
1. **Data Science Pipeline:** Exploratory Data Analysis (EDA) and model training using Jupyter Notebook.
2. **Streamlit Application:** An interactive form that loads the trained Random Forest model and displays stress predictions.

## 📊 Dataset Reference
The machine learning model was trained using the **Sleep Health and Lifestyle Dataset**. 
Due to version control best practices, the raw `.csv` and `.zip` files are excluded from this repository. 
You can access and download the original dataset here: 
👉 [Kaggle: Sleep Health and Lifestyle Dataset](https://www.kaggle.com/datasets/uom190346a/sleep-health-and-lifestyle-dataset)

## 🛠️ Tech Stack & Technologies
* **Application & Machine Learning:** Python, Streamlit, Pandas, Scikit-Learn, Joblib, Jupyter Notebook
* **Deployment (Planned):** Streamlit Community Cloud or another Python hosting service

## 📂 Repository Structure
* `main.ipynb` : The complete Data Science workflow (Data Cleaning, EDA, Label Encoding, and Random Forest training achieving 100% accuracy).
* `app.py` : The Streamlit application, including the input form and model inference.
* `model_prediksi_stres.pkl` : The exported, pre-trained Random Forest classification model.
* `requirements.txt` : The Python dependencies required to run the application.

### Deployment over HTTPS
Streamlit Community Cloud provides HTTPS automatically. For other hosting platforms, configure HTTPS through the hosting provider or reverse proxy; a PHP `.htaccess` file is not needed.
## 🚀 How to Run Locally

Open a terminal in the project directory and run:
```bash
# Install dependencies
pip install -r requirements.txt

# Start the Streamlit application
streamlit run app.py
```