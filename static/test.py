import torch
import torch.nn as nn
import torch.nn.utils.prune as prune
from transformers import XLMRobertaModel
from torch.quantization import quantize_dynamic


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


# Re-instantiate the custom model
num_labels = 2
model = CustomXLMRobertaModel(num_labels=num_labels)

# Load the original model's state dictionary
model.load_state_dict(torch.load(
    'custom_xlm_roberta_model.pth', map_location=torch.device('cpu')))

# Apply dynamic quantization
model = quantize_dynamic(model, {torch.nn.Linear}, dtype=torch.qint8)

# Save the quantized model
torch.save(model, 'quantized_model.pth')
