import sys
import tensorflow as tf
import tensorflow_hub as hub
import json

def build_and_train_model(params):
    # Unpack hyperparameters
    directory_path = params['directory_path']
    learning_rate = float(params['learning_rate'])
    epochs = int(params['epochs'])
    patience = int(params['patience'])
    monitor = params['monitor']
    optimizer_name = params['optimizer']
    activation_function = params['activation_function']
    validation_split = float(params['validation_split'])

    # Data Preparation
    img_size = 224
    batch_size = 64
    train_dataset = tf.keras.utils.image_dataset_from_directory(
        directory_path,
        validation_split=validation_split,
        subset="training",
        seed=123,
        image_size=(img_size, img_size),
        batch_size=batch_size,
        label_mode="categorical"
    )

    val_dataset = tf.keras.utils.image_dataset_from_directory(
        directory_path,
        validation_split=validation_split,
        subset="validation",
        seed=123,
        image_size=(img_size, img_size),
        batch_size=batch_size,
        label_mode="categorical"
    )

    # Define Model Architecture
    model_url = 'https://tfhub.dev/google/imagenet/resnet_v2_50/classification/5'
    model = tf.keras.Sequential([
        tf.keras.layers.Rescaling(1./255, input_shape=(img_size, img_size, 3)),
        hub.KerasLayer(model_url),
        tf.keras.layers.Dense(10, activation=activation_function)  # Adjust based on your class count
    ])

    # Compile Model
    optimizer = None
    if optimizer_name.lower() == "adam":
        optimizer = tf.keras.optimizers.Adam(learning_rate)
    elif optimizer_name.lower() == "sgd":
        optimizer = tf.keras.optimizers.SGD(learning_rate)
    elif optimizer_name.lower() == "rmsprop":
        optimizer = tf.keras.optimizers.RMSprop(learning_rate)
    else:
        raise ValueError("Unsupported optimizer. Use 'adam', 'sgd', or 'rmsprop'.")

    model.compile(
        optimizer=optimizer,
        loss=tf.keras.losses.CategoricalCrossentropy(),
        metrics=['accuracy']
    )

    # Define Callbacks
    checkpoint = tf.keras.callbacks.ModelCheckpoint(
        "best_model.h5", monitor=monitor, mode="min", save_best_only=True, verbose=1
    )
    early_stopping = tf.keras.callbacks.EarlyStopping(
        monitor=monitor, patience=patience, restore_best_weights=True
    )
    reduce_lr = tf.keras.callbacks.ReduceLROnPlateau(
        monitor=monitor, factor=0.2, patience=3, min_lr=0.0001
    )

    # Train Model
    history = model.fit(
        train_dataset,
        epochs=epochs,
        validation_data=val_dataset,
        callbacks=[checkpoint, early_stopping, reduce_lr]
    )

    return "Training Complete"

if __name__ == "__main__":
    params = json.loads(sys.argv[1])
    result = build_and_train_model(params)
    print(result)
