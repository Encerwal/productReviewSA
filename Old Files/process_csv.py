import sys
import pandas as pd


def process_csv(file_path):
    # DEBUGGING: Print to stderr to capture in PHP
    # print(f"Processing file: {file_path}", file=sys.stderr)
    try:
        # Read the CSV file with Latin-1 encoding
        df = pd.read_csv(file_path, encoding='latin-1')
        return df.head(50).to_html()
    except UnicodeDecodeError as e:
        return f"UnicodeDecodeError: {str(e)}"
    except Exception as e:
        return f"Error processing CSV: {str(e)}"


if __name__ == "__main__":
    if len(sys.argv) != 2:
        print("Usage: python process_csv.py <file_path>", file=sys.stderr)
        sys.exit(1)
    file_path = sys.argv[1]
    result = process_csv(file_path)
    print(result)
