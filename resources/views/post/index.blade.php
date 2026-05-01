<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Posts</title>

<style>
    body {
        background: #f4f6f9;
        font-family: Arial, Helvetica, sans-serif;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 800px;
        margin: 40px auto;
        padding: 0 20px;
    }

    .title {
        font-size: 28px;
        font-weight: bold;
        margin-bottom: 20px;
    }

    .card {
        background: #fff;
        padding: 20px;
        margin-bottom: 15px;
        border-radius: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .post-title {
        font-size: 20px;
        font-weight: bold;
    }

    .post-body {
        margin-top: 10px;
        color: #555;
    }

    .btn {
        background: #2196F3;
        color: white;
        padding: 8px 12px;
        border-radius: 6px;
        border: none;
        cursor: pointer;
    }

    .btn:hover {
        background: #1976D2;
    }

    .search-box {
        margin-bottom: 20px;
    }

    .search-input {
        padding: 10px;
        width: 70%;
        border-radius: 6px;
        border: 1px solid #ccc;
    }

    .back {
        display: inline-block;
        margin-bottom: 20px;
        text-decoration: none;
        color: #4CAF50;
        font-weight: bold;
    }
</style>
</head>
<body>

<div class="container">

    <a href="/dashboard" class="back">← Back to Dashboard</a>

    <div class="title">My Posts</div>

    <!-- 🔍 Search -->
    <form method="GET" action="/posts" class="search-box">
        <input type="text" name="search" placeholder="Search posts..." class="search-input">
        <button class="btn">Search</button>
    </form>

    @forelse($posts as $post)
        <div class="card">
            <div class="post-title">{{ $post->title }}</div>
            <div class="post-body">{{ $post->body }}</div>
        </div>
    @empty
        <p>No posts found</p>
    @endforelse

</div>

</body>
</html>