<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

<style>
    body {
    font-family: 'Montserrat', sans-serif; 
}

    .container {
        max-width: 90%;
        padding: 20px;
        margin-top: 10px;
        margin-bottom: 25px;
        background-color: #fff;
        border-radius: 20px;

    }

    h1 {
        font-size: 2.5rem;
        color: #333;
        margin-bottom: 20px;
        font-weight: 900;
    }

    h3 {
        font-size: 1.5rem;
        color: #555;
        margin-bottom: 15px;
        font-weight: 600;
    }

    .feeling-option {
        display: inline-block;
        margin: 30px;
        cursor: pointer;
        text-align: center;
        transition: transform 0.3s, background-color 0.3s, box-shadow 0.3s;
        padding: 10px;
        border-radius: 15px;
        background-color: #f0f0f0;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        width: 150px; 
        height: 170px; 
    }

    .feeling-option img {
    width: 90px;  
    height: 90px; 
    margin-bottom: 10px; 
}

.feeling-option span {
    font-size: 1.4rem;
    color: #555;
    font-weight: 500;
}

    .feeling-option:hover {
        background-color: #e0e0e0;
        transform: translateY(-5px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
    }

    .feeling-option input {
        display: none;
    }

    .feeling-option label {
        font-size: 2rem;
        color: #555;
        display: block;
        font-weight: 400;
    }

    .feeling-option input:checked + label {
    transform: scale(1.2);
    color: #555; 
    background-color: #ffeb3b; 
    border-radius: 15px;
}


.feeling-option:hover {
    background-color: #e0e0e0; 
    transform: translateY(-5px);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2); 
}

.feeling-option:hover input:checked + label {
    background-color: #ffeb3b; 
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
                <label for="sick">
                    <img src="img/sick.png" alt="Sick">
                    <span>Sick</span>
                </label>
            </div>
            <div class="feeling-option">
                <input type="radio" id="tired" name="physical_feeling" value="tired" required>
                <label for="tired">
                    <img src="img/tired.png" alt="Tired">
                    <span>Tired</span>
                </label>
            </div>
            <div class="feeling-option">
                <input type="radio" id="energetic" name="physical_feeling" value="energetic" required>
                <label for="energetic">
                    <img src="img/laughing.png" alt="Energetic">
                    <span>Energetic</span>
                </label>
            </div>
        </div>

        <div>
            <h3 class="mt-4">Emotionally</h3>
            <div class="feeling-option">
                <input type="radio" id="happy" name="emotional_feeling" value="happy" required>
                <label for="happy">
                    <img src="img/happy.png" alt="Happy">
                    <span>Happy</span>
                </label>
            </div>
            <div class="feeling-option">
                <input type="radio" id="sad" name="emotional_feeling" value="sad" required>
                <label for="sad">
                    <img src="img/sad.png" alt="Sad">
                    <span>Sad</span>
                </label>
            </div>
            <div class="feeling-option">
                <input type="radio" id="angry" name="emotional_feeling" value="angry" required>
                <label for="angry">
                    <img src="img/angry.png" alt="Angry">
                    <span>Angry</span>
                </label>
            </div>
        </div>

        <div>
            <h3 class="mt-4">Mentally</h3>
            <div class="feeling-option">
                <input type="radio" id="calm" name="mental_feeling" value="calm" required>
                <label for="calm">
                    <img src="img/calm.png" alt="Calm">
                    <span>Calm</span>
                </label>
            </div>
            <div class="feeling-option">
                <input type="radio" id="stressed" name="mental_feeling" value="stressed" required>
                <label for="stressed">
                    <img src="img/hypnotized.png" alt="Stressed">
                    <span>Stressed</span>
                </label>
            </div>
            <div class="feeling-option">
                <input type="radio" id="anxious" name="mental_feeling" value="anxious" required>
                <label for="anxious">
                    <img src="img/anxious.png" alt="Anxious">
                    <span>Anxious</span>
                </label>
            </div>
        </div>

        <button type="submit" class="btn btn-success mt-3">Get Recommendation</button>
    </form>
</div>

<!-- Modal for Result -->
<div class="modal fade" id="resultModal" tabindex="-1" aria-labelledby="resultModalLabel" aria-hidden="true">
    <div class="modal-dialog custom-modal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="resultModalLabel">Guideco Recommends you to</h5>
            </div>
            <div class="modal-body font-weight-bold justify-content-center align-items-center" id="resultContent">
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
