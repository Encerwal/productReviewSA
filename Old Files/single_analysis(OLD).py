import sys
import torch
from transformers import AutoTokenizer
import torch.nn as nn
from transformers import XLMRobertaModel


# Custom XLMR Roberta Model
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
tokenizer = AutoTokenizer.from_pretrained("./model")


def analyze_sentiment(text):
    inputs = tokenizer(text, return_tensors="pt")
    outputs = model(**inputs)
    logits = outputs
    probabilities = torch.softmax(logits, dim=-1)
    predicted_class = torch.argmax(probabilities, dim=-1).item()
    return predicted_class


if __name__ == "__main__":
    text = sys.argv[1]
    result = analyze_sentiment(text)
    print(result)
