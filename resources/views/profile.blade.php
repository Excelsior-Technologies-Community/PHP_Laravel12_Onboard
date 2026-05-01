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

        .profile-img {
            display: block;
            margin: 0 auto 15px;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
        }
    </style>

    <div class="container">

        <div class="card">

            <div class="title">
                Complete Your Profile
            </div>

            <!-- ✅ SUCCESS MESSAGE -->
            @if(session('success'))
            <p style="color:green; text-align:center;">
                {{ session('success') }}
            </p>
            @endif

            <!-- ✅ IMAGE PREVIEW -->
            @if(isset($profile) && $profile->image)
            <img src="{{ asset('storage/' . $profile->image) }}" class="profile-img">
            @endif

            <!-- ✅ IMPORTANT: enctype added -->
            <form method="POST" action="/profile" enctype="multipart/form-data">

                @csrf

                <div class="input-group">
                    <label class="input-label">Phone Number</label>
                    <input
                        type="text"
                        name="phone"
                        class="input-field"
                        value="{{ old('phone', $profile->phone ?? '') }}"
                        placeholder="Enter phone number"
                        required>
                </div>

                <div class="input-group">
                    <label class="input-label">Address</label>
                    <input
                        type="text"
                        name="address"
                        class="input-field"
                        value="{{ old('address', $profile->address ?? '') }}"
                        placeholder="Enter address"
                        required>
                </div>

                <!-- ✅ NEW IMAGE FIELD -->
                <div class="input-group">
                    <label class="input-label">Profile Image</label>
                    <input type="file" name="image" class="input-field">
                </div>

                <button type="submit" class="btn">
                    Save
                </button>

            </form>

        </div>

    </div>

</x-app-layout>