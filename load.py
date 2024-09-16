from flask import Flask, request, jsonify
import torch
from transformers import AutoTokenizer
import pandas as pd
import torch.nn as nn
from transformers import XLMRobertaModel
import numpy as np
import string

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


# Initialize sets for faster keyword lookups
funclist = {
    'functioning', 'function', 'functions', 'operating', 'works', 'active', 'successful', 'work',
    'useful', 'practical', 'serviceable', 'performs', 'perform', 'performing', 'executes',
    'accomplishes', 'running', 'working', 'handy', 'dependable', 'consistent',
    'steady', 'stable', 'streamlined', 'productive', 'competent', 'resourceful', 'beeping', 'sounding', 'alarming',
    'ringing', 'buzzing', 'charges', 'powers', 'energizes', 'replenishes', 'fills', 'connects',
    'links', 'joins', 'interfaces', 'attaches', 'smashes', 'breaks', 'shatters', 'crushes',
    'demolishes', 'malfunction', 'breakdown', 'failure', 'glitch', 'reaches',
    'matches', 'aligns', 'adapts', 'gumagana', 'sira', 'cuts', 'slices', 'trims', 'shears',
    'chops', 'nakaayos', 'organized', 'arranged', 'sorted', 'heats', 'warms', 'boils', 'cooks',
    'toasts', 'hindi gumagana', 'non-functional', 'lasted', 'endured',
    'survived', 'remained', 'glitches', 'bugs', 'errors', 'issues', 'faults', 'uses', 'utilizes',
    'employs', 'applies', 'leverages', 'nakakapaso', 'burns', 'overheats', 'scalds', 'correct',
    'creates', 'produces', 'generates', 'forms', 'supports', 'aids', 'assists', 'backs',
    'reinforces', 'leaking', 'dripping', 'seeping', 'oozing', 'drains', 'empties', 'depletes',
    'siphons', 'handles', 'manages', 'grips', 'maneuvering', 'navigating', 'steering', 'guiding',
    'safe', 'secure', 'protected', 'easy', 'simple', 'straightforward', 'effortless', 'install',
    'set up', 'mount', 'place', 'configure', 'expands', 'enlarges', 'extends', 'grows',
    'fitting', 'suitable', 'appropriate', 'matching', 'maliit',
    'bubble-wrapped', 'padded', 'protected', 'tamang-tama', 'well-suited', 'magaan',
    'lightweight', 'not heavy', 'nag-aano', 'acting up', 'misbehaving', 'bura', 'erased',
    'wiped', 'removed', 'ka-tanggap', 'acceptable', 'unusable', 'alarm', 'alert',
    'warning', 'siren', 'expiration', 'expiry', 'termination', 'frustrating', 'irritating',
    'replacement', 'substitute', 'new part', 'alternative', 'ripped',
    'shredded', 'expire', 'lapse', 'agreement', 'installed', 'mounted', 'fix', 'repair',
    'mend', 'purpose', 'role', 'operation', 'leak', 'escape', 'seep', 'breach',
    'charging', 'powering', 'replenishing', 'energizing', 'operate', 'control', 'manage', 'run',
    'non-filled', 'empty', 'unfilled', 'hollow', 'replace', 'substitute', 'exchange',
    'swap', 'fail', 'collapse', 'break down', 'restore', 'firmly',
    'well-tuned', 'effective', 'functional', 'operational', 'usable', 'efficient',
    'functioning', 'beeping', 'charges', 'connects', 'smashes', 'malfunction', 'arrives',
    'operates', 'fits', 'gumagana', 'sira', 'cuts', 'nakaayos', 'heats', 'lasted',
    'glitches', 'uses', 'nakakapaso', 'accurate', 'supports', 'leaking', 'drains', 'handles',
    'maneuvering', 'safe', 'easy', 'install', 'expands', 'fitting', 'maliit',
    'naka-bubble wrap', 'tamang-tama', 'magaan', 'nag-aano', 'bura', 'ka-tanggap',
    'alarm', 'expiration', 'replacement', 'ripped', 'expire', 'installed',
    'peephole', 'fixes', 'functionality', 'leakage',
    'replaceable', 'exchangeable', 'substitutable', 'fails', 'repairable', 'fixable', 'mendable',
    'securely', 'job'
}

quallist = {
    "quality", "premium", "top-tier", "luxury", "upscale", "defect", "defective",
    "high-quality", "superior", "top-notch", "well-made", "expertly crafted", "well-built",
    "solid", "sturdy", "strong", "tough", "reliable", "dependable", "trustworthy", "consistent",
    "secure", "elegant", "refined",
    "first-rate", "clear", "distinct", "cleanly", "neatly", "smoothly", "nice", "pleasant",
    "agreeable", "sleek", "polished", "comfortable", "cozy",
    "flawless", "perfect", "impeccable", "faultless", "adequate", "beautiful", "lovely",
    "charming", "endearing", "captivating", "intriguing", "engaging", "compelling",
    "classy", "sophisticated", "timely", "practical", "relevant", "accurate", "detailed",
    "up-to-date", "new", "recent", "fresh", "genuine", "authentic", "original", "true", "unique",
    "classic", "ageless", "high-grade", "top-quality", "finest", "ultimate", "complete",
    "well-maintained", "well-protected", "artisanal",
    "handcrafted", "handmade", "design", "style", "layout", "interesting", "pleasant",
    "delightful", "stunning", "striking", "credible", "sharp", "perceptive", "acute", "tapered",
    "delicate", "fragile", "brittle", "weak", "flimsy", "low-quality", "poor", "subpar", "inadequate",
    "insufficient", "deficient", "damaged", "faulty", "flawed", "ruined", "shattered",
    "unfit", "unsuitable", "inappropriate", "shoddy", "substandard", "condition"
    "material", "durable", "matibay", "kalidad", "scratches", "scratch", "pristine", "broken", "broke"
}

pricelist = {
    "affordable", "economical", "reasonable", "budget-friendly", "cost-effective",
    "inexpensive", "expensive", "costly", "high-priced",
    "overpriced", "pricey", "exorbitant", "lavish", "upscale", "extravagant",
    "steep", "excessively high", "high-end", "discounts", "discount", "bargain",
    "deal", "steal", "offer", "discounted", "reduced", "marked down", "sale",
    "fair", "moderate", "pricing and cost", "price", "cost", "rate", "value",
    "fee", "charge", "pricing", "balanced expense",
    "worth", "worthwhile", "justifiable", "top-tier",
    "efficient", "abot-kaya", "matipid", "makatarungan",
    "mura", "mahal", "magastos", "mataas ang presyo",
    "masyadong mahal", "maluhong", "magarbo",
    "abot-kayang", "alok", "may diskwento",
    "nabawasan", "katamtaman", "presyo", "cheap"
    "gastos", "halaga", "bayad", "singil", "pagpepresyo", "sulit", "cheaper", "pricier",
    "masmura", "masmahal"
}

keywords = {
    'functionality': funclist,
    'quality': quallist,
    'price': pricelist
}


# FOR CLASSIFICATION OF REVIEWS
def classify_review(review):
    scores = {'functionality': 0, 'quality': 0, 'price': 0}

    # Tokenize the review
    words = review.lower().split()

    # Score the review based on keyword presence
    for word in words:
        for category, keywords_set in keywords.items():
            if word in keywords_set:
                scores[category] += 1

    # Determine the category with the highest score
    max_category = max(scores, key=scores.get)

    # If all scores are zero, assign "others"
    if all(score == 0 for score in scores.values()):
        return 'others'

    return max_category


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
        dataDF['sentiment'] = all_predicted_labels
        # Add categories to each review
        dataDF['category'] = dataDF['text'].str.replace(
            f'[{string.punctuation}]', ' ', regex=True).apply(classify_review)

        # Convert the DataFrame to JSON
        result_json = dataDF.to_json(orient='records')

        return jsonify(result_json)

    else:
        return jsonify({"error": "Invalid file format, only CSV files are allowed"}), 400


if __name__ == "__main__":
    app.run(host='127.0.0.1', port=5000, debug=True)
