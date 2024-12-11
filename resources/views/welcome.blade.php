<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Submission</title>
   
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
            margin: 0;
            background-color: #f7f7f7;
        }
        #form-container {
            width: 100%;
            max-width: 400px;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        #form {
            display: flex;
            flex-direction: column;
        }
        input {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            padding: 10px;
            background-color: #4CAF50;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .message {
            margin-top: 15px;
            padding: 10px;
            border-radius: 4px;
            text-align: center;
            display: none;
        }
        .success {
            background-color: #dff0d8;
            color: #3c763d;
        }
        .error {
            background-color: #f2dede;
            color: #a94442;
        }
    </style>
</head>
<body>
    
    <div id="form-container">
        <form id="form">
            <input type="text" name="name" placeholder="Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Submit</button>
        </form>
        <div id="message" class="message"></div>
    </div>

    <script>
        document.getElementById('form').addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData.entries());
            const messageDiv = document.getElementById('message');
            
            try {
                const response = await fetch('http://localhost:8000/api/submit-data', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();
                if (response.ok) {
                    messageDiv.textContent = result.message;
                    messageDiv.className = 'message success';
                } else {
                    messageDiv.textContent = result.message || 'An error occurred.';
                    messageDiv.className = 'message error';
                }
            } catch (error) {
                messageDiv.textContent = 'Request failed. Please try again.';
                messageDiv.className = 'message error';
            }

            messageDiv.style.display = 'block';
        });
    </script>
</body>
</html>
