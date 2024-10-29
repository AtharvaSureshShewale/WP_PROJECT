<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SVKMs DJSCEs Term Tracker</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="flex flex-col lg:flex-row h-screen">
        <div class="w-full lg:w-1/2 bg-gray-100 relative">
            <img src="login.png" alt="Login" class="w-full h-full object-cover border-2">
            <img src="bgremovelogo.png" alt="Logo" class="absolute top-[-25    px] left-1/2 transform -translate-x-1/2 w-40 h-auto lg:top-[8px] sm:w-40 sm:h-auto md:w-60 lg:h-auto">

        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center bg-[#2a2185]">
            <div class="p-6 w-full max-w-lg">
                <h1 class="font-bold text-3xl text-orange-400 text-center lg:text-left">REGISTER</h1>
                <p class="text-gray-500 text-white text-center lg:text-left">Enter your information to register</p>

                <form action="">
                    <div class="flex flex-col lg:flex-row mt-10 space-y-4 lg:space-y-0 lg:space-x-4">
                        <div class="text-left">
                            <label for="first-name" class="text-md font-italic text-white">First Name</label><br>
                            <input type="text" placeholder="First Name" class="border border-gray-300 rounded-lg w-full lg:w-[200px] h-12 p-2 text-gray-700 focus:border-gray-950 focus:ring-2 focus:ring-gray-200 focus:outline-none">
                        </div>
                        
                        <div class="text-left">
                            <label for="last-name" class="text-md font-italic text-white">Last Name</label><br>
                            <input type="text" placeholder="Last Name" class="border border-gray-300 rounded-lg w-full lg:w-[200px] h-12 p-2 text-gray-700 focus:border-gray-950 focus:ring-2 focus:ring-gray-200 focus:outline-none">
                        </div>
                    </div>

                    <div class="text-left mt-6">
                        <label for="email" class="text-md font-italic text-white">Email</label><br>
                        <input type="email" placeholder="example@gmail.com" class="border border-gray-300 rounded-lg w-full lg:w-[420px] h-12 p-2 text-gray-700 focus:border-gray-950 focus:ring-2 focus:ring-gray-200 focus:outline-none">
                    </div>

                    <div class="text-left mt-6">
                        <label for="password" class="text-md font-italic text-white">Password</label><br>
                        <input type="password" placeholder="Password" class="border border-gray-300 rounded-lg w-full lg:w-[420px] h-12 p-2 text-gray-700 focus:border-gray-950 focus:ring-2 focus:ring-gray-200 focus:outline-none">
                    </div>

                    <button type="button" onclick="location.href='index.html'" class="bg-white p-2 text-[#2a2185] text-xl rounded-lg w-full lg:w-[420px] h-12 mt-8 font-bold hover:bg-[#797474] hover:text-orange-400">
                        Register Now
                    </button>
                </form>
                <br>
                <div class="text-center lg:text-left mt-4">
                    <p class="text-white">Already have an account? <a href="login.php" class="text-white hover:text-orange-400">Log In !</a></p>
                </div>
            </div>
        </div> 
    </div>
</body>
</html>
