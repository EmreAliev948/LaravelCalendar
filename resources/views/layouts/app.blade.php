<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendar App</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://unpkg.com/boxicons@latest/css/boxicons.min.css">
    
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        dark: {
                            100: '#1a1a1a',
                            200: '#2c2c2c',
                            300: '#333333',
                            400: '#444444',
                        }
                    }
                }
            }
        }
    </script>
    @yield('head')
</head>

<body class="bg-gray-900 text-gray-100">
    <div class="min-h-screen">
        <main>
            @yield('content')
        </main>
    </div>

    <!-- Scripts -->
    @yield('script')
</body>
</html>