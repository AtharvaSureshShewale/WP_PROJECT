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
session_start();

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
$coTotals = [];
$total = 0;
$tableName="";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'create_table') {
        // Create Table Form Logic
        $department = $_POST['department'];
        $semester = $_POST['semester'];
        $course = $_POST['course'];
        $coursecode = $_POST['coursecode'];
        $termtest = $_POST['termtest'];

        $tableName = strtolower($department ."". $semester . "" .$course."_" . $coursecode . "_term" . $termtest);

        $createTableSQL = "CREATE TABLE IF NOT EXISTS $tableName(
            id INT PRIMARY KEY AUTO_INCREMENT,
            div VARCHAR(50),
            rollno VARCHAR(50),
            sapid VARCHAR(50),
            student_name VARCHAR(100)
        )";

        if ($conn->query($createTableSQL) === TRUE) {
            echo "Table $tableName created successfully. <br>";

            $numQuestions = (int)$_POST['num_questions'];
            $marksString = trim($_POST['marks']);
            $cosString = trim($_POST['co']);

            if (empty($marksString) || empty($cosString)) {
                echo "Marks and CO values cannot be empty.";
            } else {
                $marks = array_map('trim', explode(',', $marksString));
                $cos = array_map('trim', explode(',', $cosString));
                if (count($marks) === $numQuestions && count($cos) === $numQuestions) {
                    for ($i = 0; $i < $numQuestions; $i++) {
                        $total += $marks[$i];
                        $questionColumnName = "question_" . ($i + 1) . "_$cos[$i]";

                        $query = "ALTER TABLE $tableName ADD $questionColumnName DOUBLE";
                        if ($conn->query($query) === TRUE) {
                            echo "Column '$questionColumnName' added successfully.<br>";
                            if ($i == 0) {
                                $insertQuery = "INSERT INTO $tableName ($questionColumnName) VALUES ($marks[$i])";
                                if ($conn->query($insertQuery) === TRUE) {
                                    echo "Row created with '$questionColumnName' value.<br>";
                                } else {
                                    echo "Error inserting value for '$questionColumnName': " . $conn->error . "<br>";
                                }
                            } else {
                                $updateQuery = "UPDATE $tableName SET $questionColumnName = $marks[$i]";
                                if ($conn->query($updateQuery) === TRUE) {
                                    echo "Value for '$questionColumnName' updated successfully.<br>";
                                } else {
                                    echo "Error updating column '$questionColumnName': " . $conn->error . "<br>";
                                }
                            }
                        } else {
                            echo "Error adding column '$questionColumnName': " . $conn->error . "<br>";
                        }
                    }

                    $query = "ALTER TABLE $tableName ADD totalmarks DOUBLE";
                    if ($conn->query($query) === TRUE) {
                        echo "Column 'totalmarks' added successfully.<br>";
                        $updateQuery = "UPDATE $tableName SET totalmarks = $total";
                        if ($conn->query($updateQuery) === TRUE) {
                            echo "Value for totalmarks updated successfully.<br>";
                        } else {
                            echo "Error updating column totalmarks: " . $conn->error . "<br>";
                        }
                    } else {
                        echo "Error adding column 'totalmarks': " . $conn->error . "<br>";
                    }

                    $uniqueCOs = array_unique($cos);
                    
                    foreach ($uniqueCOs as $co) {
                        $coTotals[$co] = 0;
                    }

                    for ($i = 0; $i < $numQuestions; $i++) {
                        $co = $cos[$i];
                        $coTotals[$co] += $marks[$i];
                    }

                    foreach ($uniqueCOs as $co) {
                        $coColumnName = "co_$co";
                        $query = "ALTER TABLE $tableName ADD $coColumnName DOUBLE";
                        if ($conn->query($query) === TRUE) {
                            echo "Column '$coColumnName' added successfully.<br>";
                            $updateQuery = "UPDATE $tableName SET $coColumnName = {$coTotals[$co]}";
                            if ($conn->query($updateQuery) === TRUE) {
                                echo "Value for $coColumnName updated successfully.<br>";
                            } else {
                                echo "Error updating $coColumnName: " . $conn->error . "<br>";
                            }
                        } else {
                            echo "Error adding column '$coColumnName': " . $conn->error . "<br>";
                        }
                    }
                } else {
                    echo "Error: The number of marks and CO values must match the number of questions.<br>";
                }
            }
        } else {
            echo "Error creating table: " . $conn->error . "<br>";
        }
    } 
}?>


<form id="add-test" class="max-w-7xl mx-auto my-3 p-6 space-y-3 bg-[#2a2185] rounded-3xl shadow-inner" method="post">
<input type="hidden" name="action" value="create_table">
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

    <!-- Question, Marks, CO -->
    <div id="marks-co-container" class="flex space-x-4">
            <div class="mb-4">
                <label class="block text-gray-700">Number of Questions:</label>
                <input type="number" name="num_questions" placeholder="Enter number of questions" class="mt-1 p-2 border-2 border-gray-600 rounded-lg" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">Marks (comma-separated):</label>
                <input type="text" name="marks" placeholder="Marks (e.g., 5,5,5)" class="mt-1 p-2 border-2 border-gray-600 rounded-lg" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700">CO (comma-separated):</label>
                <input type="text" name="co" placeholder="CO (e.g., 1,2,3)" class="mt-1 p-2 border-2 border-gray-600 rounded-lg" required>
            </div>
    </div>
    <input type="file" name="excel" id="excel" class="mt-1 p-2 border-2 border-white-600 rounded-lg text-white"><br>
    <button type="submit" class="mt-4 p-2 bg-blue-500 text-white rounded-lg">Create Test & Import</button>
</form>

</body>
</html>