<style>
    body {
        font-family: 'Roboto', sans-serif;
        /* Modern font */
    }

    .container {
        max-width: 90%;
        padding: 20px;
        margin-top: 10px;
        margin-bottom: 25px;
        background-color: #fff;
        /* White background */
        border-radius: 20px;

    }

    h1 {
        font-size: 2.5rem;
        color: #333;
        /* Dark gray heading */
        margin-bottom: 20px;
    }

    h3 {
        font-size: 1.5rem;
        color: #555;
        /* Slightly lighter heading */
        margin-bottom: 15px;
    }

    .feeling-option {
        display: inline-block;
        margin: 15px;
        cursor: pointer;
        text-align: center;
        transition: transform 0.3s, background-color 0.3s, box-shadow 0.3s;
        padding: 10px;
        border-radius: 15px;
        background-color: #f0f0f0;
        /* Light background */
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        /* Subtle shadow */
    }

    .feeling-option:hover {
        background-color: #e0e0e0;
        /* Darken on hover */
        transform: translateY(-5px);
        /* Lift effect */
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        /* Stronger shadow on hover */
    }

    .feeling-option input {
        display: none;
    }

    .feeling-option label {
        font-size: 2rem;
        /* Emoji size */
        color: #555;
        /* Neutral color */
        display: block;
    }

    .feeling-option input:checked+label {
        transform: scale(1.2);
        /* Scale up selected emoji */
        color: #28a745;
        /* Change color for selected */
    }

    .feeling-option input:checked+label::before {
        content: '✔️ ';
        position: relative;
        font-size: 1.5rem;
        color: #28a745;
    }

    .feeling-option input:checked+label {
        font-weight: bold;
        color: #28a745;
    }

    button[type="submit"] {
        background-color: #007bff;
        border: none;
        padding: 10px 20px;
        font-size: 1.2rem;
        color: #fff;
        border-radius: 50px;
        transition: background-color 0.3s, box-shadow 0.3s;
    }

    button[type="submit"]:hover {
        background-color: #0056b3;
        box-shadow: 0 3px 10px rgba(0, 123, 255, 0.3);
        /* Subtle shadow on hover */
    }

    #result {
        background-color: #f0f8ff;
        padding: 15px;
        border-radius: 15px;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
    }
    /* Modal Custom Style */
.modal-content {
    background-color: #f9f9f9;
    /* Light background */
    border-radius: 20px;
    /* Rounded corners */
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
    /* Soft shadow */
    padding: 30px;
    /* Padding inside modal */
}

.modal-header {
    border-bottom: none;
    /* Remove default border */
    padding-bottom: 0;
}

.modal-title {
    font-size: 2rem;
    /* Large modern title */
    font-weight: bold;
    color: #333;
}

.modal-body {
    font-size: 1.2rem;
    /* Increase font size */
    color: #555;
    /* Slightly lighter text */
    padding-top: 10px;
}

.modal-footer {
    border-top: none;
    /* Remove top border */
    text-align: center;
}

.modal-footer .btn {
    background-color: #007bff;
    border: none;
    color: white;
    font-size: 1rem;
    padding: 10px 20px;
    border-radius: 50px;
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
}

.modal-footer .btn:hover {
    background-color: #0056b3;
    /* Darker on hover */
    box-shadow: 0 4px 10px rgba(0, 123, 255, 0.3);
    /* Shadow on hover */
}

.modal.fade .modal-dialog {
    transform: translateY(-50px);
    /* Slide-in effect */
    transition: transform 0.3s ease-out;
}

.modal.show .modal-dialog {
    transform: translateY(0);
}

.modal-backdrop {
    background-color: rgba(0, 0, 0, 0.5);
    /* Darkened background */
}
.custom-modal {
    margin-top: 100px; /* Adjust this value to move the modal lower or higher */
}

</style>


<h1 class="text-center font-weight-bold">HOW DO YOU FEEL TODAY?</h1>

<div class="container">
    <form id="feelingForm" class="text-center">
        <div>
            <h3>Physically</h3>
            <div class="feeling-option">
                <input type="radio" id="sick" name="physical_feeling" value="sick" required>
                <label for="sick">🤒 Sick</label>
            </div>
            <div class="feeling-option">
                <input type="radio" id="tired" name="physical_feeling" value="tired" required>
                <label for="tired">😴 Tired</label>
            </div>
            <div class="feeling-option">
                <input type="radio" id="energetic" name="physical_feeling" value="energetic" required>
                <label for="energetic">💪 Energetic</label>
            </div>
        </div>

        <div>
            <h3 class="mt-4">Emotionally</h3>
            <div class="feeling-option">
                <input type="radio" id="happy" name="emotional_feeling" value="happy" required>
                <label for="happy">😊 Happy</label>
            </div>
            <div class="feeling-option">
                <input type="radio" id="sad" name="emotional_feeling" value="sad" required>
                <label for="sad">😢 Sad</label>
            </div>
            <div class="feeling-option">
                <input type="radio" id="angry" name="emotional_feeling" value="angry" required>
                <label for="angry">😠 Angry</label>
            </div>
        </div>

        <div>
            <h3 class="mt-4">Mentally</h3>
            <div class="feeling-option">
                <input type="radio" id="calm" name="mental_feeling" value="calm" required>
                <label for="calm">😌 Calm</label>
            </div>
            <div class="feeling-option">
                <input type="radio" id="stressed" name="mental_feeling" value="stressed" required>
                <label for="stressed">😩 Stressed</label>
            </div>
            <div class="feeling-option">
                <input type="radio" id="anxious" name="mental_feeling" value="anxious" required>
                <label for="anxious">😟 Anxious</label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Get Recommendation</button>
    </form>
</div>
<!-- Modal for Result -->
<div class="modal fade" id="resultModal" tabindex="-1" aria-labelledby="resultModalLabel" aria-hidden="true">
    <div class="modal-dialog custom-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resultModalLabel">Your Recommendation</h5>
            </div>
            <div class="modal-body" id="resultContent">
                <!-- Result will be shown here -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.7/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

<script>
    document.getElementById('feelingForm').addEventListener('submit', function(event) {
        event.preventDefault();

        const physicalFeeling = document.querySelector('input[name="physical_feeling"]:checked').value;
        const emotionalFeeling = document.querySelector('input[name="emotional_feeling"]:checked').value;
        const mentalFeeling = document.querySelector('input[name="mental_feeling"]:checked').value;

        fetch('http://127.0.0.1:5000/predict', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    physical_feeling: physicalFeeling,
                    emotional_feeling: emotionalFeeling,
                    mental_feeling: mentalFeeling
                })
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById('resultContent').innerText = data.recommendation;
                var resultModal = new bootstrap.Modal(document.getElementById('resultModal'));
                resultModal.show();
            })
            .catch(error => {
                console.error('Error:', error);
                document.getElementById('resultContent').innerText = 'An error occurred. Please try again.';
                var resultModal = new bootstrap.Modal(document.getElementById('resultModal'));
                resultModal.show();
            });
    });
</script>

</body>

</html>