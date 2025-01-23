<?php
// Start session
session_start();

// Database connection
include('config.php');

// Handle registration
$error = "";
$success = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = mysqli_real_escape_string($conn, $_POST['first_name']);
    $lastName = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Check if the email already exists
    $checkQuery = "SELECT * FROM login WHERE email='$email'";
    $result = mysqli_query($conn, $checkQuery);

    if (mysqli_num_rows($result) > 0) {
        $error = "Email already registered. Please log in.";
    } else {
        // Insert new user into the database
        $insertQuery = "INSERT INTO login (first_name, last_name, email, password) VALUES ('$firstName', '$lastName', '$email', '$password')";
        if (mysqli_query($conn, $insertQuery)) {
            $success = "Registration successful! You can now log in.";
            header("Location: login.php");
            exit;
        } else {
            $error = "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SVKM's DJSCE Term Tracker</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body>
    <div class="flex flex-col lg:flex-row h-screen">
        <div class="w-full lg:w-1/2 bg-gray-100 relative">
            <img src="login.png" alt="Login" class="w-full h-full object-cover border-2">
            <img src="bgremovelogo.png" alt="Logo" class="absolute top-[-25px] left-1/2 transform -translate-x-1/2 w-40 h-auto lg:top-[8px] sm:w-40 sm:h-auto md:w-60 lg:h-auto">
        </div>

        <div class="w-full lg:w-1/2 flex items-center justify-center bg-[#2a2185]">
            <div class="p-6 w-full max-w-lg">
                <h1 class="font-bold text-3xl text-orange-400 text-center lg:text-left">REGISTER</h1>
                <p class="text-gray-500 text-white text-center lg:text-left">Enter your information to register</p>

                <!-- Display Error or Success Message -->
                <?php if (!empty($error)): ?>
                    <p class="text-red-500 text-center mt-4"><?= $error; ?></p>
                <?php endif; ?>
                <?php if (!empty($success)): ?>
                    <p class="text-green-500 text-center mt-4"><?= $success; ?></p>
                <?php endif; ?>

                <!-- Registration Form -->
                <form action="" method="POST">
                    <div class="flex flex-col lg:flex-row mt-10 space-y-4 lg:space-y-0 lg:space-x-4">
                        <div class="text-left">
                            <label for="first_name" class="text-md font-italic text-white">First Name</label><br>
                            <input type="text" name="first_name" placeholder="First Name" required
                                class="border border-gray-300 rounded-lg w-full lg:w-[200px] h-12 p-2 text-gray-700 focus:border-gray-950 focus:ring-2 focus:ring-gray-200 focus:outline-none">
                        </div>
                        
                        <div class="text-left">
                            <label for="last_name" class="text-md font-italic text-white">Last Name</label><br>
                            <input type="text" name="last_name" placeholder="Last Name" required
                                class="border border-gray-300 rounded-lg w-full lg:w-[200px] h-12 p-2 text-gray-700 focus:border-gray-950 focus:ring-2 focus:ring-gray-200 focus:outline-none">
                        </div>
                    </div>

                    <div class="text-left mt-6">
                        <label for="email" class="text-md font-italic text-white">Email</label><br>
                        <input type="email" name="email" placeholder="example@gmail.com" required
                            class="border border-gray-300 rounded-lg w-full lg:w-[420px] h-12 p-2 text-gray-700 focus:border-gray-950 focus:ring-2 focus:ring-gray-200 focus:outline-none">
                    </div>

                    <div class="text-left mt-6">
                        <label for="password" class="text-md font-italic text-white">Password</label><br>
                        <input type="password" name="password" placeholder="Password" required
                            class="border border-gray-300 rounded-lg w-full lg:w-[420px] h-12 p-2 text-gray-700 focus:border-gray-950 focus:ring-2 focus:ring-gray-200 focus:outline-none">
                    </div>

                    <button type="submit" class="bg-white p-2 text-[#2a2185] text-xl rounded-lg w-full lg:w-[420px] h-12 mt-8 font-bold hover:bg-[#797474] hover:text-orange-400">
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