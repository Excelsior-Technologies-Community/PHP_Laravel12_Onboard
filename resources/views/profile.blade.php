<x-app-layout>

    <style>
        body {
            background: #f4f6f9;
            font-family: Arial, Helvetica, sans-serif;
        }

        .container {
            max-width: 500px;
            margin: 40px auto;
        }

        .card {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .title {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
            color: #333;
            text-align: center;
        }

        .input-group {
            margin-bottom: 20px;
        }

        .input-label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        .input-field {
            width: 100%;
            padding: 12px;
            border-radius: 8px;
            border: 1px solid #ccc;
            font-size: 16px;
        }

        .input-field:focus {
            border-color: #4CAF50;
            outline: none;
        }

        .btn {
            width: 100%;
            padding: 12px;
            background: #4CAF50;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn:hover {
            background: #45a049;
        }
    </style>

    <div class="container">

        <div class="card">

            <div class="title">
                Complete Your Profile
            </div>

            <form method="POST" action="/profile">

                @csrf

                <div class="input-group">
                    <label class="input-label">Phone Number</label>
                    <input type="text" name="phone" class="input-field" placeholder="Enter phone number" required>
                </div>

                <div class="input-group">
                    <label class="input-label">Address</label>
                    <input type="text" name="address" class="input-field" placeholder="Enter address" required>
                </div>

                <button type="submit" class="btn">
                    Save Profile
                </button>

            </form>

        </div>

    </div>

</x-app-layout>