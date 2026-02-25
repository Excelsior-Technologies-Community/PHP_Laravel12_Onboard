<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Create Your First Post</title>

<style>
    body {
        background: #f4f6f9;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 600px;
        margin: 60px auto;
        padding: 0 20px;
    }

    .card {
        background: #ffffff;
        padding: 40px;
        border-radius: 15px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
    }

    .card:hover {
        transform: translateY(-5px);
    }

    .title {
        font-size: 28px;
        font-weight: 700;
        color: #222;
        text-align: center;
        margin-bottom: 10px;
    }

    .subtitle {
        font-size: 16px;
        color: #666;
        text-align: center;
        margin-bottom: 30px;
    }

    .input-group {
        margin-bottom: 25px;
    }

    .input-label {
        display: block;
        font-weight: 600;
        margin-bottom: 8px;
        color: #555;
    }

    .input-field, textarea {
        width: 100%;
        padding: 14px 16px;
        border-radius: 10px;
        border: 1px solid #ccc;
        font-size: 16px;
        transition: all 0.3s;
    }

    .input-field:focus, textarea:focus {
        border-color: #4CAF50;
        box-shadow: 0 0 5px rgba(76, 175, 80, 0.3);
        outline: none;
    }

    textarea {
        resize: vertical;
    }

    .btn {
        width: 100%;
        padding: 15px;
        background: #4CAF50;
        color: white;
        font-size: 18px;
        font-weight: 600;
        border: none;
        border-radius: 12px;
        cursor: pointer;
        transition: background 0.3s, transform 0.2s;
    }

    .btn:hover {
        background: #45a049;
        transform: translateY(-2px);
    }

    .back-link {
        display: block;
        text-align: center;
        margin-top: 20px;
        font-weight: 500;
        color: #4CAF50;
        text-decoration: none;
        transition: color 0.3s;
    }

    .back-link:hover {
        color: #388E3C;
    }

    @media (max-width: 640px) {
        .card {
            padding: 30px 20px;
        }
        .title {
            font-size: 24px;
        }
        .btn {
            font-size: 16px;
        }
    }
</style>
</head>
<body>

@if(session('error'))
    <div style="color:red; margin-bottom:15px;">
        Error: {{ session('error') }}
    </div>
@endif

@if($errors->any())
    <div style="color:red; margin-bottom:15px;">
        <ul>
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="container">
    <div class="card">
        <div class="title">Create Your First Post</div>
        <div class="subtitle">Fill in the details below to share your first post.</div>

        <form method="POST" action="/post">
            @csrf

            <div class="input-group">
                <label class="input-label" for="title">Post Title</label>
                <input type="text" name="title" id="title" class="input-field" placeholder="Enter post title" required>
            </div>

            <div class="input-group">
                <label class="input-label" for="body">Post Content</label>
                <textarea name="body" id="body" rows="6" class="input-field" placeholder="Write your post here..." required></textarea>
            </div>

            <button type="submit" class="btn">Save Post</button>
        </form>

        <a href="{{ url('/dashboard') }}" class="back-link">← Back to Dashboard</a>
    </div>
</div>

</body>
</html>