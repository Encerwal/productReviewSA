# Use an official Python image
FROM python:3.9

# Set the working directory
WORKDIR /app

# Copy the local files to the container
COPY . /app

# Install dependencies
RUN pip install --no-cache-dir -r requirements.txt

# Install Gunicorn
RUN pip install gunicorn

# Expose the port
EXPOSE 8000

# Run Gunicorn
CMD ["gunicorn", "-w", "2", "-b", "0.0.0.0:8000", "load:app"]
