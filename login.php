<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SVKMs DJSCE Term Tracker</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<style>
  .search label ion-icon {
    position: absolute;
    top: 0;
    left: 10px;
    font-size: 1.2rem;
  }
</style>
<body>
    <div class="flex flex-col lg:flex-row h-screen">

        <div class="w-full lg:w-1/2 bg-gray-100 relative">
            <img src="login.png" alt="Login" class="w-full h-full object-cover border-6">
            <img src="bgremovelogo.png" alt="Logo" class="absolute top-[-25px] left-1/2 transform -translate-x-1/2 w-40 h-auto lg:top-[8px] sm:w-40 sm:h-auto md:w-60 lg:h-auto">
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center bg-[#2a2185]">
            <div class="p-6 w-full max-w-lg">
                <h1 class="font-bold text-3xl text-orange-400 text-center lg:text-left">LOGIN</h1>
                <p class="text-gray-500 text-white text-center lg:text-left">Enter your information to login</p>

                <form action="">
                    <div class="text-left mt-6">
                        <label for="email" class="text-md font-italic text-white" style="padding-top: 20px;">Email</label><br>
                        <input type="email" placeholder="example@gmail.com" class="border border-gray-300 rounded-lg w-full lg:w-[420px] h-12 p-2 text-gray-700 focus:border-gray-950 focus:ring-2 focus:ring-gray-200 focus:outline-none">
                    </div>

                    <div class="text-left mt-6">
                        <label for="password" class="text-md font-italic text-white">Password</label><br>
                        <input type="password" placeholder="Password" class="border border-gray-300 rounded-lg w-full lg:w-[420px] h-12 p-2 text-gray-700 focus:border-gray-950 focus:ring-2 focus:ring-gray-200 focus:outline-none">
                    </div>
                    
                    <button type="submit" class="bg-white p-2 text-[#2a2185] text-xl rounded-lg w-full lg:w-[420px] h-12 mt-8 font-bold hover:bg-[#797474] hover:text-orange-400">
                        Login
                    </button>
                </form>
                <br>
                <div class="text-center lg:text-left mt-4">
                    <p class="text-white">Don't have an account? <a href="register.php" class="text-white hover:text-orange-400">Sign-in</a></p>
                </div>
            </div>
        </div> 
    </div>

    <script>

        const defaultUser = {
            email: "user@gmail.com",
            password: "pass123"
        };

        function handleLogin(event) {
            event.preventDefault(); 

            const emailInput = document.querySelector('input[type="email"]').value;
            const passwordInput = document.querySelector('input[type="password"]').value;

            if (emailInput === defaultUser.email && passwordInput === defaultUser.password) {
                alert("Login successful!");
                window.location.href = 'home.php'; 
            } else {
                alert("Invalid email or password. Please try again.");
            }
        }
        const loginForm = document.querySelector('form');
        loginForm.addEventListener('submit', handleLogin);
    </script>
</body>
</html>
