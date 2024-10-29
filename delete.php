<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Add Test</title>
</head>

<body>
<?php
include('header.php');

$servername = "localhost";
$username = "root";
$password = "";
$db = "term_tracker";

$conn = new mysqli($servername, $username, $password, $db);

if ($conn->connect_error) {
    die("Connection Failed!!" . $conn->connect_error);
}

echo "Successfully Connected<br>";
// Get the POST variables
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'delete_table') {
        $department = $_POST['department'];
        $semester = $_POST['semester'];
        $course = $_POST['course'];
        $coursecode = $_POST['coursecode'];
        $termtest = $_POST['termtest'];

// Construct the table name
$tableName = strtolower($department . "_" . $semester ."_" . $course . "_" . $coursecode . "_term" . $termtest);

// Prepare the SQL statement to delete the table
$sql = "DROP TABLE IF EXISTS `$tableName`";

// Execute the query
if ($conn->query($sql) === TRUE) {
    echo "Table $tableName deleted successfully.";
} else {
    echo "Error deleting table: " . $conn->error;
}
    }
}


// Close the connection
$conn->close();
?>

<form id="add-test" class="max-w-7xl mx-auto my-3 p-6 space-y-3 bg-[#2a2185] rounded-3xl shadow-inner" method="post">
<input type="hidden" name="action" value="delete_table">
    <!-- Department, Semester, Course, Course Code, and Term Test No -->
    <div class="flex flex-wrap items-center space-x-4">
        <!-- Department -->
        <div class="flex flex-col w-1/4">
            <label class="text-2xl font-extrabold text-white">Department</label>
            <select id="department" name="department" class="w-full mt-1 p-1 border-2 border-gray-600 rounded-lg" required>
                <option value="" disabled selected>Select a department</option>
                <option value="electronics-telecommunication">Electronics and Telecommunication Engg</option>
                <option value="information-technology">Information Technology</option>
                <option value="computer-engineering">Computer Engineering</option>
                <option value="computer-science-data-science">Computer Science and Engineering (Data Science)</option>
                <option value="ai-ml">Artificial Intelligence and Machine Learning</option>
                <option value="ai-data-science">Artificial Intelligence (AI) and Data Science</option>
                <option value="computer-science-engineering">Computer Science and Engineering</option>
                <option value="iot-cyber-security">IOT and Cyber Security with Block Chain Technology</option>
            </select>
        </div>

        <!-- Semester -->
        <div class="flex flex-col w-1/6">
            <label class="text-2xl font-extrabold text-white">Semester</label>
            <select id="semester" name="semester" class="w-full mt-1 p-1 border-2 border-gray-600 rounded-lg" required>
                <option value="" disabled selected>Select Semester</option>
                <option value="1">I</option>
                <option value="2">II</option>
                <option value="3">III</option>
                <option value="4">IV</option>
                <option value="5">V</option>
                <option value="6">VI</option>
                <option value="7">VII</option>
                <option value="8">VIII</option>
            </select>
        </div>

        <!-- Course -->
        <div class="flex flex-col w-1/6">
            <label class="text-2xl font-extrabold text-white">Course</label>
            <input type="text" id="course" name="course" placeholder="Enter course name" class="w-full mt-1 p-1 border-2 border-gray-600 rounded-lg" required>
        </div>

        <!-- Course Code -->
        <div class="flex flex-col w-1/6">
            <label class="text-2xl font-extrabold text-white">Course Code</label>
            <input type="text" id="coursecode" name="coursecode" placeholder="Enter course code" class="w-full mt-1 p-1 border-2 border-gray-600 rounded-lg" required>
        </div>

        <!-- Term Test No -->
        <div class="flex flex-col w-1/6">
            <label class="text-2xl font-extrabold text-white">Term Test No</label>
            <select id="termtest" name="termtest" class="w-full mt-1 p-1 border-2 border-gray-600 rounded-lg" required>
                <option value="" disabled selected>Enter Term Test No</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
            </select>
        </div>
    </div>
    <button type="submit" class="mt-4 p-2 bg-blue-500 text-white rounded-lg">Delete Table</button>
</form>
</body>
</html>
