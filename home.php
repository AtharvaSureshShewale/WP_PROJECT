<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>

    </style>
</head>
<body>
    
<?php
include('header.php');
?>

    <script>
        const menuBtn = document.getElementById('menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');

        menuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    </script>
  
  <div class="main flex items-center justify-center h-screen">
    <div class="welcome-message text-center">
        <h1 class="text-3xl font-bold">Welcome to SVKMs DJSCEs Term Tracker!</h1>
    </div>
</div>

</body>
</html>