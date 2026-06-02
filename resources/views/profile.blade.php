<x-app-layout>

    <style>
        body {
            background: #f4f6f9;
            font-family: Arial, Helvetica, sans-serif;
        }

        .container {
            max-width: 550px;
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
            border-color: #3b82f6;
            outline: none;
        }

        .btn {
            width: 100%;
            padding: 12px;
            background: #3b82f6;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            transition: 0.3s;
            font-weight: bold;
        }

        .btn:hover {
            background: #2563eb;
        }

        .profile-img {
            display: block;
            margin: 0 auto 15px;
            width: 100px;
            height: 100px;
            border-radius: 50%;
            object-fit: cover;
            border: 3px solid #3b82f6;
        }

        .meter-container {
            margin-bottom: 25px;
            background: #f3f4f6;
            padding: 15px;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
        }

        .steps-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 25px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 15px;
        }

        .step-badge {
            padding: 6px 14px;
            border-radius: 9999px;
            font-size: 13px;
            font-weight: 600;
        }

        .step-active {
            background: #3b82f6;
            color: white;
        }

        .step-inactive {
            background: #e5e7eb;
            color: #6b7280;
        }

        .error-msg {
            color: #dc2626;
            font-size: 14px;
            margin-top: 5px;
            display: block;
        }
    </style>

    <div class="container">

        <div class="card">

            <div class="title">
                Complete Your Profile
            </div>

            @if(session('success'))
            <p style="color:green; text-align:center; font-weight: bold; margin-bottom: 15px;">
                {{ session('success') }}
            </p>
            @endif

            <div class="meter-container">
                <div style="display: flex; justify-content: space-between; margin-bottom: 6px;">
                    <span style="font-size: 14px; font-weight: 600; color: #1e3a8a;">Profile Completeness</span>
                    <span style="font-size: 14px; font-weight: 600; color: #1e3a8a;">{{ $completeness ?? 0 }}%</span>
                </div>
                <div style="width: 100%; bg-color: #e5e7eb; background: #e5e7eb; rounded-radius: 9999px; border-radius: 9999px; height: 12px; overflow: hidden;">
                    <div style="background: #3b82f6; height: 12px; border-radius: 9999px; transition: width 0.5s ease-in-out; width: {{ $completeness ?? 0 }}%;"></div>
                </div>
            </div>

            <div class="steps-header">
                <span class="step-badge {{ ($profile->onboarding_step ?? 1) >= 1 ? 'step-active' : 'step-inactive' }}">1. Personal</span>
                <span class="step-badge {{ ($profile->onboarding_step ?? 1) >= 2 ? 'step-active' : 'step-inactive' }}">2. Details</span>
                <span class="step-badge {{ ($profile->onboarding_step ?? 1) >= 3 ? 'step-active' : 'step-inactive' }}">3. Avatar</span>
            </div>

            @if(isset($profile) && $profile->image)
            <img src="{{ asset('storage/' . $profile->image) }}" class="profile-img">
            @endif

            <form method="POST" action="/onboarding/wizard" enctype="multipart/form-data">

                @csrf

                @if(($profile->onboarding_step ?? 1) == 1)
                    <div class="input-group">
                        <label class="input-label">Phone Number</label>
                        <input
                            type="text"
                            name="phone"
                            class="input-field"
                            value="{{ old('phone', $profile->phone ?? '') }}"
                            placeholder="Enter phone number"
                            required>
                        @error('phone') <span class="error-msg">{{ $message }}</span> @enderror
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
                        @error('address') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>
                @endif

                @if(($profile->onboarding_step ?? 1) == 2)
                    <div class="input-group">
                        <label class="input-label">Professional Bio</label>
                        <textarea 
                            name="bio" 
                            class="input-field" 
                            rows="3" 
                            placeholder="Tell us about yourself..." 
                            required>{{ old('bio', $profile->bio ?? '') }}</textarea>
                        @error('bio') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="input-group">
                        <label class="input-label">Location / City</label>
                        <input
                            type="text"
                            name="location"
                            class="input-field"
                            value="{{ old('location', $profile->location ?? '') }}"
                            placeholder="e.g. Junagadh, Gujarat"
                            required>
                        @error('location') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>

                    <div class="input-group">
                        <label class="input-label">Skills (Comma separated)</label>
                        <input
                            type="text"
                            name="skills"
                            class="input-field"
                            value="{{ old('skills', $profile->skills ?? '') }}"
                            placeholder="e.g. PHP, Laravel, MySQL"
                            required>
                        @error('skills') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>
                @endif

                @if(($profile->onboarding_step ?? 1) == 3)
                    <div class="input-group">
                        <label class="input-label">Profile Image Target</label>
                        <input type="file" name="image" class="input-field">
                        @error('image') <span class="error-msg">{{ $message }}</span> @enderror
                    </div>
                @endif

                <button type="submit" class="btn">
                    {{ ($profile->onboarding_step ?? 1) == 3 ? 'Finish & Launch Dashboard' : 'Next Step' }}
                </button>

            </form>

        </div>

    </div>

</x-app-layout>