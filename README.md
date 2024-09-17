
# Sentiment Analysis Web Application
This web application is developed as part of our thesis project. It enables users to analyze the sentiment of text input or CSV files through a web interface. The backend utilizes a custom-trained XLM-RoBERTa model for sentiment analysis, served via Flask, while the frontend is hosted on Render.

## Table of Contents
* [Overview](#overview)
* [Features](#features)
* [Technologies Used](#technologies-used)
* [Setup Instructions](#setup-instructions)
* [How to Use](#how-to-use)
* [Future Improvements](#future-improvements)

## Overview
This project demonstrates the use of a machine learning model integrated into a web application to perform sentiment analysis. The system can process individual text inputs or large datasets in CSV format. Sentiment analysis results are provided in real time, with the front end deployed on Render and the model running locally using Docker and accessed via zrok.

## Features
* Single Text Input Sentiment Analysis: Users can input a single text string, and the sentiment will be analyzed and displayed.
* CSV File Upload: Users can upload a CSV file containing multiple texts for batch sentiment analysis.
* Custom XLM-RoBERTa Model: The model is custom-trained using the XLM-RoBERTa transformer for accurate sentiment predictions in both English and Filipino Languages.
* Real-time Sentiment Visualization: Sentiment results are displayed instantly for text input and CSV files.
* Responsive Design: The front end is designed to work across different device sizes, ensuring a seamless user experience.

## Technologies Used
* Backend:
  * Python
  * Flask
  * XLM-RoBERTa Model (using the transformers library)
  * Docker
  * Gunicorn (for serving the Flask app)
* Frontend:
  * HTML5, CSS3, JavaScript
  * PHP (for handling front-end logic and requests)
  * Hosted on Render
* Model:
  * Pre-trained XLM-RoBERTa model fine-tuned for sentiment analysis tasks
* Miscellaneous:
  * zrok (for exposing the local Flask server to the internet)
  * Docker (for running the Flask server and model)
    
## Setup Instructions
Follow the instructions below if you want to set this up on your own and try it out in your local computer.
### Frontend (Running the PHP App with XAMPP)
1. Install XAMPP:
* Download and install XAMPP
* After installation, start the Apache server using the XAMPP Control Panel.
2. Set Up the PHP Files on XAMPP:
* Navigate to the htdocs directory of XAMPP, usually located at:
```
C:\xampp\htdocs\
```
* Create a new folder in htdocs for your project, for example:
```
C:\xampp\htdocs\ThesisWeb\
```
3. Clone the repository:
```
git clone https://github.com/Encerwal/productReviewSA.git
cd C:\xampp\htdocs\ThesisWeb\
```
4. Update PHP Code to Communicate with Flask API:<br/> 
* Open single_result.php.
* Remove the '#" at the start of line 7.<br/> 
* Remove the entire line 6. It should look like this: 

```php
function analyze_sentiment($input_text) {
    $url = 'http://localhost:8000/analyze?text=' . urlencode($input_text);
    $response = file_get_contents($url);
    $data = json_decode($response, true);
    return $data['sentiment'];
}
```

* Open upload.php.
* Remove the '#" at the start of line 19 <br/>
* Remove the entire line 18. It should look like this:
```php
// cURL to send the file to the Flask server
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://localhost:8000/process_csv");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
```
* We are still in upload.php.
* Remove the '#" at the start of line 66 <br/>
* Remove the entire line 65. It should look like this:
```php
// cURL to send the file to the Flask server
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://localhost:8000/process_csv");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
```
### Backend (Local Setup)

1. Set up Docker:
* Ensure that Docker is installed and running.
* Navigate using the terminal to the directory of the project folder and make sure you are on the 'load' folder
* Build and run the Docker container that loads the model using the following commands:
```
docker build -t sentiment-app .
docker run -d -p 8000:8000 sentiment-app
```
2. Verify the Flask API is Running:
* To check if the Flask API is working, open your web browser and navigate to:
```
http://localhost:8000/analyze?text=hello
```
* You should see a JSON response with the sentiment analysis result.

### Testing the Connection Between PHP and Flask
Ensure Both Are Running:

* Confirm that the Flask API is running locally in Docker and is accessible via http://localhost:8000/.
* Ensure that XAMPP is running and serving your PHP app on http://localhost/ThesisWeb/.

## How to Use
1. Single Input Sentiment Analysis:

* Go to Services and choose Single Input
* Enter a text in the input field and click "Analyze Sentiment."
* The sentiment result will be displayed on the next page.
2. CSV File Upload:
* Go to Services and choose CSV File 
* Upload a CSV file containing text data or use a sample file by clicking the underlined text 'sample file'.
* The app will process each text and return the sentiment for each row.
  
## Future Improvements
* Model Optimization: Reduce the model's size and memory usage for more efficient deployment.
* Improved UI: Enhance the user interface for better user experience.
* Expanded Sentiment Classes: Allow for more nuanced sentiment categories beyond positive and negative.
