from flask import Flask, request, jsonify
import torch
from transformers import AutoTokenizer
import pandas as pd
import torch.nn as nn
from transformers import XLMRobertaModel
import numpy as np

app = Flask(__name__)


class CustomXLMRobertaModel(nn.Module):
    def __init__(self, num_labels):
        super(CustomXLMRobertaModel, self).__init__()
        model_name = 'symanto/xlm-roberta-base-snli-mnli-anli-xnli'
        self.roberta = XLMRobertaModel.from_pretrained(model_name)
        self.dropout = nn.Dropout(0.1)
        self.classifier = nn.Sequential(
            nn.Linear(768, 512),
            nn.LayerNorm(512),
            nn.ReLU(),
            nn.Dropout(0.1),
            nn.Linear(512, num_labels)
        )
        self.loss = nn.CrossEntropyLoss()
        self.num_labels = num_labels

    def forward(self, input_ids, attention_mask, labels=None):
        output = self.roberta(input_ids=input_ids,
                              attention_mask=attention_mask)
        output = self.dropout(output.pooler_output)
        logits = self.classifier(output)

        if labels is not None:
            loss = self.loss(logits.view(-1, self.num_labels), labels.view(-1))
            return {"loss": loss, "logits": logits}
        else:
            return logits


# Define the number of labels for classification
num_labels = 2
# Re-instantiate the custom model
model = CustomXLMRobertaModel(num_labels=num_labels)

# Load the model weights and tokenizer
model.load_state_dict(torch.load(
    'custom_xlm_roberta_model.pth',  map_location=torch.device('cpu')))
model.eval()
tokenizer = AutoTokenizer.from_pretrained("./model")


# FOR SINGLE INPUT SENTIMENT ANALYSIS (GET REQUEST)
def analyze_sentiment(text):
    inputs = tokenizer(text, return_tensors="pt")
    outputs = model(**inputs)
    logits = outputs
    probabilities = torch.softmax(logits, dim=-1)
    predicted_class = torch.argmax(probabilities, dim=-1).item()
    return predicted_class


@app.route('/analyze', methods=['GET'])
def analyze():
    text = request.args.get('text')  # Get parameter from the URL query string
    if text is None:
        return jsonify({'error': 'No text provided'}), 400

    result = analyze_sentiment(text)
    return jsonify({'sentiment': result})


# FOR CSV FILE PROCESSING (POST REQUEST)
@app.route('/process_csv', methods=['POST'])
def process_csv():
    if 'file' not in request.files:
        return jsonify({"error": "No file part in the request"}), 400

    file = request.files['file']

    if file.filename == '':
        return jsonify({"error": "No selected file"}), 400

    if file and file.filename.endswith('.csv'):
        # Read the CSV file into a pandas DataFrame
        dataDF = pd.read_csv(file, encoding="Latin-1")

        # Define batch size
        batch_size = 32  # Adjust based on available memory
        input_texts = dataDF['text'].tolist()

        # Process in batches
        all_predicted_labels = []
        for i in range(0, len(input_texts), batch_size):
            batch_texts = input_texts[i:i+batch_size]

            # Tokenize the input texts (this will handle multiple texts as a batch)
            inputs = tokenizer(batch_texts, return_tensors="pt",
                               truncation=True, padding=True)

            # Extract input_ids and attention_mask from the tokenized input
            input_ids = inputs["input_ids"]
            attention_mask = inputs["attention_mask"]

            # Disable gradient calculations for inference
            with torch.no_grad():
                # Remove token_type_ids from inputs if it exists
                if "token_type_ids" in inputs:
                    del inputs["token_type_ids"]

                outputs = model(**inputs)
                logits = outputs

                # Compute probabilities
                probabilities = torch.softmax(logits, dim=-1)

                # Get the predicted classes
                predicted_classes = torch.argmax(probabilities, dim=-1)

            # Define the label mapping
            label_mapping = {0: 'Negative', 1: 'Positive'}

            # Map numeric predictions to string labels
            predicted_labels = [label_mapping[class_index]
                                for class_index in predicted_classes.cpu().numpy()]
            all_predicted_labels.extend(predicted_labels)

        # Add predicted labels to the DataFrame
        dataDF['Predicted_Sentiment'] = all_predicted_labels

        # Convert the DataFrame to JSON
        result_json = dataDF.to_json(orient='records')

        return jsonify(result_json)

    else:
        return jsonify({"error": "Invalid file format, only CSV files are allowed"}), 400


if __name__ == "__main__":
    app.run(host='127.0.0.1', port=5000, debug=True)
