<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Delete Tab</title>
</head>

<body>
<?php
include('header.php');
include('config.php');

// Get the POST variables
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'delete_table') {
        $department = $_POST['department'];
        $semester = $_POST['semester'];
        $course = $_POST['course_name'];
        $coursecode = $_POST['course_code'];
        $termtest = $_POST['term_test_number'];
        $div=$_POST['div'];

// Construct the table name
$tableName = strtolower( $department . "_" . $semester ."_" . $course . "_" . $coursecode ."_". $termtest."_".$div);

// Prepare the SQL statement to delete the table
$sql = "DROP TABLE IF EXISTS `$tableName`";

// Execute the query
if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Table $tableName deleted successfully.');</script>";
} else {
    echo "<script>alert('Error deleting table: $error');</script>";
}
    }
}


// Close the connection
$conn->close();
?>

<form id="delete_table" class="max-w-7xl mx-auto my-3 p-6 space-y-3 bg-blue-900 rounded-3xl shadow-inner" method="post"
        enctype="multipart/form-data">
        <input type="hidden" name="action" value="delete_table">
        <div class="flex flex-wrap items-center space-x-1">
            <!-- Department Dropdown -->
            <div class="flex flex-col w-1/6">
                <label class="text-2xl font-extrabold text-white">Department</label>
                <select id="department" name="department" class="w-full mt-1 p-1 border-2 border-gray-600 rounded-lg"
                    required>
                    <option value="" disabled selected>Select a department</option>
                    <option value="electronics_telecommunication">Electronics and Telecommunication Engg</option>
                    <option value="information_technology">Information Technology</option>
                    <option value="computer_engineering">Computer Engineering</option>
                    <option value="computer_science-data-science">Computer Science and Engineering (Data Science)
                    </option>
                    <option value="ai_ml">Artificial Intelligence and Machine Learning</option>
                    <option value="ai_data_science">Artificial Intelligence (AI) and Data Science</option>
                    <option value="computer_science_engineering">Computer Science and Engineering</option>
                    <option value="iot_cyber_security">IOT and Cyber Security with Block Chain Technology</option>
                </select>
            </div>

            <!-- Semester Dropdown -->
            <div class="flex flex-col w-1/6">
                <label class="text-2xl font-extrabold text-white">Semester</label>
                <select id="semester" name="semester" class="w-full mt-1 p-1 border-2 border-gray-600 rounded-lg"
                    required>
                    <?php for ($i = 1; $i <= 8; $i++): ?>
                        <option value="<?= $i ?>"><?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <!-- Other Inputs -->
            <div class="flex flex-col w-1/6">
                <label class="text-2xl font-extrabold text-white">Course</label>
                <input type="text" name="course_name" class="mt-1 p-1 border-2 border-gray-600 rounded-lg"
                    placeholder="Course Name" required>
            </div>
            <div class="flex flex-col w-1/6">
                <label class="text-2xl font-extrabold text-white">Course Code</label>
                <input type="text" name="course_code" class="mt-1 p-1 border-2 border-gray-600 rounded-lg"
                    placeholder="Course Code" required>
            </div>
            <div class="flex flex-col w-1/6">
                <label class="text-2xl font-extrabold text-white">Term Test</label>
                <select name="term_test_number" class="mt-1 p-1 border-2 border-gray-600 rounded-lg" required>
                    <option value="term1">Test 1</option>
                    <option value="term2">Test 2</option>
                    <option value="term3">Test 3</option>
                </select>
            </div>
            <div class="flex flex-col w-1/6">
                <label class="text-2xl font-extrabold text-white">Div</label>
                <select name="div" class="mt-1 p-1 border-2 border-gray-600 rounded-lg" required>
                    <option value="div1">Div 1</option>
                    <option value="div2">Div 2</option>
                    <option value="div3">Div 3</option>
                </select>
            </div>
        </div>

        <!-- Submit Button -->
        <button type="submit" name="create_table" class="bg-white text-[#2a2185] font-bold rounded-lg py-2 px-4">Delete Table</button>
    </form>
</body>
</html>
