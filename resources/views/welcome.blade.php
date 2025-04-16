<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Welcome to HIMATEKOM</title>
    <script src="https://cdn.tailwindcss.com"></script>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

   
    </head>
    <body class="bg-gray-100 flex flex-col h-screen">

        <!-- Navbar -->
        <nav class="bg-orange-500 text-white py-4 px-6 flex justify-end">
            <a href="#" class="px-4 py-2 bg-white text-orange-500 rounded-lg hover:bg-orange-100 transition">Login</a>
        </nav>
    
        <!-- Main Content -->
        <div class="flex-grow flex flex-col justify-center items-center text-center">
            <img src="logo.png" alt="HIMATEKOM Logo" class="w-40 h-40 mb-4">
            <h1 class="text-4xl font-bold text-orange-600">Selamat Datang di HIMATEKOM</h1>
            <p class="text-gray-600 mt-2">Himpunan Mahasiswa Teknik Komputer</p>
        </div>
    
    </body>
</html>
