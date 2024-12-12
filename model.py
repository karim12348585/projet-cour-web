import sys
import json
import pandas as pd
import numpy as np
import matplotlib.pyplot as plt
import seaborn as sns
import os
import tensorflow as tf
import tensorflow_hub as hub
from tensorflow.keras.models import Sequential
from tensorflow.keras.layers import Dense
from tensorflow.keras.callbacks import ModelCheckpoint, EarlyStopping, ReduceLROnPlateau 

import warnings
warnings.filterwarnings("ignore")


# Fonction pour charger les données
def load_data(fpath, img_size=224, batch_size=64, validation_split=0.2, test_split=0.2):
    train = tf.keras.utils.image_dataset_from_directory(
        fpath,
        validation_split=validation_split,
        subset="training",
        seed=123,
        image_size=(img_size, img_size),
        batch_size=batch_size,
        label_mode="categorical"
    )
    
    val = tf.keras.utils.image_dataset_from_directory(
        fpath,
        validation_split=validation_split,
        subset="validation",
        seed=123,
        image_size=(img_size, img_size),
        batch_size=batch_size,
        label_mode="categorical"
    )
    
    return train, val


# Fonction pour construire le modèle
def build_model(img_size=224, activation_function="softmax", learning_rate=0.001, optimizer='adam'):
    Model_URL = 'https://kaggle.com/models/google/resnet-v2/frameworks/TensorFlow2/variations/50-classification/versions/2'
    model = Sequential([
        tf.keras.layers.Rescaling(1./255, input_shape=(img_size, img_size, 3)),
        hub.KerasLayer(Model_URL),
        Dense(4, activation=activation_function)
    ])
    
    if optimizer == 'adam':
        optimizer = tf.keras.optimizers.Adam(learning_rate=learning_rate)
    
    model.compile(
        loss=tf.keras.losses.CategoricalCrossentropy(),
        optimizer=optimizer,
        metrics=["accuracy"]
    )
    
    return model


# Fonction pour entraîner le modèle
def train_model(model, train, val, epochs=5, patience=3, monitor="val_loss", batch_size=64):
    model_name = "model_reduit.h5"
    
    checkpoint = ModelCheckpoint(model_name, 
                                 monitor=monitor, 
                                 mode="min", 
                                 save_best_only=True, 
                                 verbose=1)
    
    earlystopping = EarlyStopping(monitor=monitor, min_delta=0, patience=patience,
                                  verbose=1, restore_best_weights=True)
    
    reduce_lr = ReduceLROnPlateau(monitor=monitor, factor=0.2, patience=3, min_lr=0.0001)
    
    history = model.fit(train, epochs=epochs, validation_data=val, callbacks=[checkpoint, earlystopping, reduce_lr])
    
    return history


# Fonction pour afficher les graphes d'entraînement
def plot_training_graphs(history, val):
    # Extract loss and accuracy data from training history
    train_loss = history.history['loss']
    val_loss = history.history['val_loss']
    train_acc = history.history.get('accuracy', history.history.get('acc'))
    val_acc = history.history.get('val_accuracy', history.history.get('val_acc'))

    # Set the number of epochs
    epochs = range(1, len(train_loss) + 1)

    # Plot Training and Validation Loss
    plt.figure(figsize=(12, 5))
    plt.subplot(1, 2, 1)
    plt.plot(epochs, train_loss, label='Training Loss', color='blue')
    plt.plot(epochs, val_loss, label='Validation Loss', color='orange')
    plt.title('Training and Validation Loss')
    plt.xlabel('Epochs')
    plt.ylabel('Loss')
    plt.legend()
    plt.savefig("graphs/graph_loss.png")

    # Plot Training and Validation Accuracy
    plt.subplot(1, 2, 2)
    plt.plot(epochs, train_acc, label='Training Accuracy', color='blue')
    plt.plot(epochs, val_acc, label='Validation Accuracy', color='orange')
    plt.title('Training and Validation Accuracy')
    plt.xlabel('Epochs')
    plt.ylabel('Accuracy')
    plt.legend()
    plt.savefig("graphs/graph_acc.png")

    plt.tight_layout()
    

    # Calculate entropy for each class
    class_entropies = {class_name: [] for class_name in val.class_names}

    for images, labels in val:
        for i, label in enumerate(labels):
            class_index = np.argmax(label)  # Identify the class of the image
            img = images[i].numpy()  # Extract the image
            img = img / 255.0  # Normalize the image if necessary

            # Compute the pixel histogram of the image
            hist = np.histogram(img, bins=256, range=(0, 1))[0]  # Histogram
            hist = hist / hist.sum()  # Normalize to get probabilities

            epsilon = 1e-10  # Avoid log(0)
            entropy = -np.sum(hist * np.log(hist + epsilon))  # Compute entropy

            # Append the entropy to the corresponding class
            class_entropies[val.class_names[class_index]].append(entropy)

    # Plot entropy for each class as a histogram
    num_classes = len(val.class_names)
    fig, axes = plt.subplots(1, num_classes, figsize=(15, 6))  # Create a subplot for each class

    for idx, class_name in enumerate(val.class_names):
        axes[idx].hist(class_entropies[class_name], bins=20, color='skyblue', edgecolor='black')
        axes[idx].set_title(f"Entropy: {class_name}", fontsize=12)
        axes[idx].set_xlabel("Entropy", fontsize=10)
        axes[idx].set_ylabel("Frequency", fontsize=10)

    plt.savefig("graphs/mean entropy.png")
    plt.tight_layout()  # Adjust the spacing
    

    # Compute the average entropy for each class
    avg_class_entropies = [np.mean(class_entropies[class_name]) for class_name in val.class_names]

    # Create a bar chart for the average entropy per class
    plt.figure(figsize=(10, 6))
    plt.bar(val.class_names, avg_class_entropies, color='skyblue')

    # Add titles and labels
    plt.title("Average Entropy by Class", fontsize=16)
    plt.xlabel("Classes", fontsize=12)
    plt.ylabel("Average Entropy", fontsize=12)
    plt.xticks(rotation=45, ha="right")  # Rotate class labels for better readability
    
    plt.savefig("graphs/Average Entropy by Class.png")
    plt.tight_layout()

    

# Fonction principale qui prend les paramètres dynamiques
def main(fpath, img_size=224, batch_size=64, epochs=5, learning_rate=0.001, 
         patience=3, monitor="val_loss", optimizer='adam', activation_function="softmax", 
         validation_split=0.2, test_split=0.2):
    
    # Charger les données
    train, val = load_data(fpath, img_size, batch_size, validation_split, test_split)
    
    # Construire le modèle
    model = build_model(img_size, activation_function, learning_rate, optimizer)
    
    # Entraîner le modèle
    history = train_model(model, train, val, epochs, patience, monitor, batch_size)
    
    # Afficher et sauvegarder les graphiques d'entraînement
    plot_training_graphs(history, val)


if __name__ == "__main__":
    # Ouvrir le fichier params.json et charger les paramètres JSON
    try:
        with open('params.json', 'r') as file:
            params = json.load(file)

        # Appeler la fonction principale avec les paramètres récupérés
        fpath = params.get('directory_path')

        # Convertir les paramètres et effectuer les cast nécessaires
        img_size=224
        batch_size=64
        epochs = int(params.get('epochs', 5))  # Assurer que epochs est un entier
        learning_rate = float(params.get('learning_rate', 0.001))  # Assurer que learning_rate est un float
        patience = int(params.get('patience', 3))  # Assurer que patience est un entier
        monitor = params.get('monitor', 'val_loss')  # Cela reste une chaîne
        optimizer = params.get('optimizer', 'adam')  # Cela reste une chaîne
        activation_function = params.get('activation_function', 'softmax')  # Cela reste une chaîne
        activation_function = activation_function.lower()
        validation_split = float(params.get('validation_split', 0.2))  # Assurer que validation_split est un float
        test_split = float(params.get('test_split', 0.2))  # Assurer que test_split est un float

        # Appeler la fonction principale avec les paramètres correctement castés
        main(fpath, img_size, batch_size, epochs, learning_rate, patience, monitor, optimizer, 
             activation_function, validation_split, test_split)

        print("Exécution terminée")

    except FileNotFoundError:
        print("Le fichier params.json n'a pas été trouvé.")
    except json.JSONDecodeError:
        print("Erreur de décodage JSON dans le fichier params.json.")
    except Exception as e:
        print(f"Une erreur est survenue lors de l'exécution : {e}")


    # Supprimer le fichier JSON après l'exécution
    try:
        os.remove('params.json')
        print("Le fichier params.json a été supprimé avec succès.")
    except FileNotFoundError:
        print("Le fichier params.json n'a pas été trouvé pour la suppression.")
    except Exception as e:
        print(f"Une erreur est survenue lors de la suppression du fichier : {e}")
        