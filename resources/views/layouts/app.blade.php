<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inventory System</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">
    @include('components.navbar')
    <div class="flex">
        @include('components.sidebar')
        <main class="p-6 w-full">
            @yield('content')
        </main>
    </div>
</body>
</html>
