import tensorflow as tf
import numpy as np
from tensorflow.keras.preprocessing import image
import matplotlib.pyplot as plt
import tensorflow_hub as hub
from tensorflow.keras.utils import get_custom_objects
import os
import uuid
import sys

# Register KerasLayer from TensorFlow Hub
get_custom_objects()['KerasLayer'] = hub.KerasLayer
folder_path ="pred"

def get_random_filename(extension=".txt"):
    return f"{uuid.uuid4()}{extension}"

# Load the saved model
model = tf.keras.models.load_model('model_reduit.h5')

classes = ["Weimaraner","Japanese_spaniel","Maltese_dog","Chihuahua"]

# Function to preprocess and predict the class of a given image
def predict_image_class(img_path, img_size=224):
    # Load and preprocess the image
    img = image.load_img(img_path, target_size=(img_size, img_size))
    img_array = image.img_to_array(img)  # Convert image to array
    img_array = np.expand_dims(img_array, axis=0)  # Add batch dimension
    img_array = img_array / 255.0  # Normalize the image (same as in model training)

    # Predict the class
    predictions = model.predict(img_array)
    predicted_class = np.argmax(predictions)  # Get the index of the highest prediction

    return predicted_class , predictions

# Function to display the image and predicted class
def display_image_and_prediction(img_path):
    # Get the predicted class and prediction probabilities
    predicted_class , predictions = predict_image_class(img_path)

    # Load the image
    img = image.load_img(img_path)

    # Display the image
    plt.imshow(img)
    plt.axis('off')  # Turn off axis
    plt.title(f"Predicted Class: {classes[predicted_class]}")  # Display predicted class as title
    random_filename = get_random_filename(".png")
    plt.savefig("result_pred/"+random_filename)
    
    

    # Display the prediction probabilities for all classes
    print("Prediction probabilities for all classes:", predictions)
    
    
    # Function to get image file names from a folder
def get_image_names(folder_path):
    # List of allowed image file extensions
    image_extensions = ('.png', '.jpg', '.jpeg', '.bmp', '.gif', '.tiff')
    # Extract file names with image extensions
    image_names = [file for file in os.listdir(folder_path) if file.lower().endswith(image_extensions)]
    return image_names

# Main function to execute the test
if __name__ == "__main__":

    

    # Example usage
    folder__path = sys.argv[1]
   
    image_names = get_image_names(folder_path)    
    for image_name in image_names:
        
        display_image_and_prediction("pred/"+image_name)
        



    


