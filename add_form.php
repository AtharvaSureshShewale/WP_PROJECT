<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Tab</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.0.3/dist/tailwind.min.css" rel="stylesheet">
</head>

<body>
<?php
require('header.php');
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$db = "term_tracker";

$conn = new mysqli($servername, $username, $password, $db);

if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $department = $_POST['department'] ?? 'default_department';
    $semester = $_POST['semester'] ?? 1;
    $courseName = $_POST['course_name'] ?? 'default_course';
    $courseCode = $_POST['course_code'] ?? '000';
    $termTestNumber = $_POST['term_test_number'] ?? 1;
    $div = $_POST['div'] ?? 'A';
    $totalMarks = intval($_POST['number_of_total'] ?? 0);
    $numberOfQuestions = intval($_POST['number_of_questions'] ?? 0);
    $marksArray = json_decode($_POST['marks_array'] ?? '[]');
    $coArray = json_decode($_POST['co_array'] ?? '[]');
    $extraQuestionsArray = json_decode($_POST['extra_questions_array'] ?? '[]');

    // Validate number of questions
    if ($numberOfQuestions <= 0) {
        die("Invalid number of questions. Please provide a valid input.");
    }

    // Generate table name
    $tableName = strtolower("{$department}_{$semester}_{$courseName}_{$courseCode}_{$termTestNumber}_{$div}");

    // Create table query
    $createTableQuery = "
    CREATE TABLE IF NOT EXISTS `$tableName` (
        division VARCHAR(10),
        id VARCHAR(10) PRIMARY KEY,
        sap BIGINT(15),
        name VARCHAR(50),
    ";

    // Add question columns
    for ($i = 0; $i < $numberOfQuestions; $i++) {
        if (isset($extraQuestionsArray[$i]) && $extraQuestionsArray[$i] === '1') {
            // If extra questions are added, create columns for sub-questions
            $createTableQuery .= "question" . ($i + 1) . "_a_{$marksArray[$i]}_{$coArray[$i]} INT,";
            $createTableQuery .= "question" . ($i + 1) . "_b_{$marksArray[$i]}_{$coArray[$i]} INT,";
        } else {
            // If no extra questions, create a column for the main question
            $createTableQuery .= "question" . ($i + 1) . "_{$marksArray[$i]}_{$coArray[$i]} INT,";
        }
    }

    // Add total marks column
    $createTableQuery .= "total_marks_{$totalMarks} INT DEFAULT 0,";

    // Add CO columns dynamically (only unique COs)
    $uniqueCOs = array_unique($coArray); // Get unique COs
    foreach ($uniqueCOs as $co) {
        $createTableQuery .= "{$co}_{$totalMarks} INT DEFAULT 0,";
    }

    $createTableQuery = rtrim($createTableQuery, ',') . ")";

    // Execute table creation query
    if ($conn->query($createTableQuery)) {
        echo "Table `$tableName` created successfully.<br>";

        // Handle file upload
        if (isset($_FILES['excel_file'])) {
            $fileName = $_FILES['excel_file']['name'];
            $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
            $validExtensions = ['xls', 'xlsx'];

            if (in_array($fileExtension, $validExtensions)) {
                $newFileName = date("Ymd_His") . "." . $fileExtension;
                $targetDirectory = "uploads/" . $newFileName;

                if (move_uploaded_file($_FILES['excel_file']['tmp_name'], $targetDirectory)) {
                    require "excelReader/excel_reader2.php";
                    require "excelReader/SpreadsheetReader.php";

                    $reader = new SpreadsheetReader($targetDirectory);

                    foreach ($reader as $row) {
                        $division = $conn->real_escape_string($row[0]);
                        $id = $conn->real_escape_string($row[1]);
                        $sap = intval($row[2]);
                        $name = $conn->real_escape_string($row[3]);

                        // Prepare the columns and values for the SQL query
                        $columns = ['division', 'id', 'sap', 'name'];
                        $values = ["'$division'", "'$id'", $sap, "'$name'"];

                        // Add question columns and values
                        for ($i = 0; $i < $numberOfQuestions; $i++) {
                            if (isset($extraQuestionsArray[$i]) && $extraQuestionsArray[$i] === '1') {
                                // Add sub-question columns
                                $extraQuestionAColumn = "question" . ($i + 1) . "_a_{$marksArray[$i]}_{$coArray[$i]}";
                                $extraQuestionBColumn = "question" . ($i + 1) . "_b_{$marksArray[$i]}_{$coArray[$i]}";

                                $columns[] = "`$extraQuestionAColumn`";
                                $columns[] = "`$extraQuestionBColumn`";

                                $values[] = isset($row[4 + $i * 2]) ? intval($row[4 + $i * 2]) : 'NULL';
                                $values[] = isset($row[4 + $i * 2 + 1]) ? intval($row[4 + $i * 2 + 1]) : 'NULL';
                            } else {
                                // Add main question column
                                $questionColumn = "question" . ($i + 1) . "_{$marksArray[$i]}_{$coArray[$i]}";
                                $columns[] = "`$questionColumn`";
                                $values[] = isset($row[4 + $i]) ? intval($row[4 + $i]) : 'NULL';
                            }
                        }

                        // Convert columns and values arrays to strings
                        $columnsStr = implode(', ', $columns);
                        $valuesStr = implode(', ', $values);

                        // Build the SQL query
                        $sql = "INSERT INTO `$tableName` ($columnsStr) VALUES ($valuesStr)";

                        // Execute the query
                        if ($conn->query($sql)) {
                            echo "Data inserted successfully.<br>";
                        } else {
                            echo "Error inserting data: " . $conn->error . "<br>";
                        }
                    }

                    // Update total marks
                    $updateTotalMarksQuery = "UPDATE `$tableName` SET `total_marks_{$totalMarks}` = ";
                    $updateTotalMarksQuery .= implode(' + ', array_map(function ($i) use ($marksArray, $coArray, $extraQuestionsArray) {
                        if (isset($extraQuestionsArray[$i]) && $extraQuestionsArray[$i] === '1') {
                            return "`question" . ($i + 1) . "_a_{$marksArray[$i]}_{$coArray[$i]}` + `question" . ($i + 1) . "_b_{$marksArray[$i]}_{$coArray[$i]}`";
                        } else {
                            return "`question" . ($i + 1) . "_{$marksArray[$i]}_{$coArray[$i]}`";
                        }
                    }, range(0, $numberOfQuestions - 1)));

                    if ($conn->query($updateTotalMarksQuery)) {
                        echo "Total marks updated successfully.<br>";
                    } else {
                        echo "Error updating total marks: " . $conn->error . "<br>";
                    }

                    // Update CO columns
                    foreach ($uniqueCOs as $co) {
                        $updateCOQuery = "UPDATE `$tableName` SET `{$co}_{$totalMarks}` = ";
                        $updateCOQuery .= implode(' + ', array_map(function ($i) use ($co, $marksArray, $coArray, $extraQuestionsArray) {
                            if ($coArray[$i] === $co) {
                                if (isset($extraQuestionsArray[$i]) && $extraQuestionsArray[$i] === '1') {
                                    return "`question" . ($i + 1) . "_a_{$marksArray[$i]}_{$coArray[$i]}` + `question" . ($i + 1) . "_b_{$marksArray[$i]}_{$coArray[$i]}`";
                                } else {
                                    return "`question" . ($i + 1) . "_{$marksArray[$i]}_{$coArray[$i]}`";
                                }
                            }
                            return '0';
                        }, range(0, $numberOfQuestions - 1)));

                        if ($conn->query($updateCOQuery)) {
                            echo "CO column `{$co}_{$totalMarks}` updated successfully.<br>";
                        } else {
                            echo "Error updating CO column `{$co}_{$totalMarks}`: " . $conn->error . "<br>";
                        }
                    }
                } else {
                    echo "Failed to upload file.";
                }
            } else {
                echo "Invalid file format. Please upload an Excel file.";
            }
        }
    } else {
        echo "Error creating table: " . $conn->error;
    }
}
?>

    <form id="add-test" class="max-w-7xl mx-auto my-3 p-6 space-y-3 bg-blue-900 rounded-3xl shadow-inner" method="post" enctype="multipart/form-data">
        <input type="hidden" name="action" value="create_table">
        <input type="hidden" id="question-count" name="number_of_questions" value="0">
        <input type="hidden" id="total-count" name="number_of_total" value="0">
        <div class="flex flex-wrap items-center space-x-1">
            <!-- Department Dropdown -->
            <div class="flex flex-col w-1/6">
                <label class="text-2xl font-extrabold text-white">Department</label>
                <select id="department" name="department" class="w-full mt-1 p-1 border-2 border-gray-600 rounded-lg" required>
                    <option value="" disabled selected>Select a department</option>
                    <option value="electronics_telecommunication">Electronics and Telecommunication Engg</option>
                    <option value="information_technology">Information Technology</option>
                    <option value="computer_engineering">Computer Engineering</option>
                    <option value="computer_science-data-science">Computer Science and Engineering (Data Science)</option>
                    <option value="ai_ml">Artificial Intelligence and Machine Learning</option>
                    <option value="ai_data_science">Artificial Intelligence (AI) and Data Science</option>
                    <option value="computer_science_engineering">Computer Science and Engineering</option>
                    <option value="iot_cyber_security">IOT and Cyber Security with Block Chain Technology</option>
                </select>
            </div>

            <!-- Semester Dropdown -->
            <div class="flex flex-col w-1/6">
                <label class="text-2xl font-extrabold text-white">Semester</label>
                <select id="semester" name="semester" class="w-full mt-1 p-1 border-2 border-gray-600 rounded-lg" required>
                    <?php for ($i = 1; $i <= 8; $i++) : ?>
                        <option value="<?= $i ?>"><?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </div>

            <!-- Other Inputs -->
            <div class="flex flex-col w-1/6">
                <label class="text-2xl font-extrabold text-white">Course</label>
                <input type="text" name="course_name" class="mt-1 p-1 border-2 border-gray-600 rounded-lg" placeholder="Course Name" required>
            </div>
            <div class="flex flex-col w-1/6">
                <label class="text-2xl font-extrabold text-white">Course Code</label>
                <input type="text" name="course_code" class="mt-1 p-1 border-2 border-gray-600 rounded-lg" placeholder="Course Code" required>
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

        <!-- Container for dynamic question inputs -->
        <div id="marks-co-container" class="mt-4 space-y-4">
        </div>

        <!-- Total Marks Display -->
        <div>
            <label class="text-2xl font-extrabold text-white">Total Marks are:</label>
            <div id="total-marks" class="text-xl font-bold text-white mt-2">0</div>
        </div>

        <!-- Add Question Button -->
        <button type="button" id="add-question-btn" class="px-4 py-2 bg-green-500 text-white font-bold rounded-lg shadow-md hover:bg-green-700">
            + Add Question
        </button>
        <br>

        <!-- Add Extra Questions Button -->
        <button type="button" id="add-extra-question-btn" class="px-4 py-2 bg-yellow-500 text-white font-bold rounded-lg shadow-md hover:bg-yellow-700">
            + Add Extra Questions
        </button>
        <br>

        <div class="flex flex-col w-1/6">
            <label class="text-2xl font-extrabold text-white">Upload Excel File</label>
            <input type="file" name="excel_file" class="mt-1 p-1 border-2 border-gray-600 rounded-lg" accept=".xls, .xlsx">
        </div>
        <!-- Submit Button -->
        <button type="submit" name="create_table" class="bg-white text-[#2a2185] font-bold rounded-lg py-2 px-4">Create Table & Import</button>
    </form>

    <script>
        let questionCount = 0;
        const marksCoContainer = document.getElementById('marks-co-container');
        const totalMarksDisplay = document.getElementById('total-marks');
        const addQuestionBtn = document.getElementById('add-question-btn');
        const addExtraQuestionBtn = document.getElementById('add-extra-question-btn');

        let marksArray = [];
        let coArray = [];
        let extraQuestionsArray = [];

        addQuestionBtn.addEventListener('click', function () {
            questionCount++;
            document.getElementById('question-count').value = questionCount;

            // Create a container for the question details
            const questionDiv = document.createElement('div');
            questionDiv.classList.add('question-item', 'space-y-2');

            // Create label and input for marks
            const marksLabel = document.createElement('label');
            marksLabel.textContent = `Marks for Question ${questionCount}:`;
            marksLabel.className = 'inline-block pr-3 text-xl font-bold text-white';
            const marksInput = document.createElement('input');
            marksInput.type = 'number';
            marksInput.placeholder = `Enter marks for question ${questionCount}`;
            marksInput.className = 'block w-1/2 mt-2 p-2 border-2 border-gray-600 rounded-lg';
            marksInput.required = true;
            marksInput.min = '1';
            marksInput.addEventListener('input', updateTotalMarks);

            // Create label and select for CO
            const coLabel = document.createElement('label');
            coLabel.textContent = `CO for Question ${questionCount}:`;
            coLabel.className = 'inline-block pr-3 text-xl font-bold text-white mt-4';
            const coSelect = document.createElement('select');
            coSelect.className = 'block w-1/2 mt-2 p-2 border-2 border-gray-600 rounded-lg';
            coSelect.required = true;

            // Add predefined CO options
            const coOptions = ['CO1', 'CO2', 'CO3', 'CO4', 'CO5', 'CO6'];
            coOptions.forEach(co => {
                const option = document.createElement('option');
                option.value = co;
                option.textContent = co;
                coSelect.appendChild(option);
            });

            // Append labels and inputs to the question container
            questionDiv.appendChild(marksLabel);
            questionDiv.appendChild(marksInput);
            questionDiv.appendChild(coLabel);
            questionDiv.appendChild(coSelect);

            // Append the question container to the main container
            marksCoContainer.appendChild(questionDiv);

            // Add data to the arrays
            marksArray.push(marksInput);
            coArray.push(coSelect);
            extraQuestionsArray.push('0'); // Default to no extra questions
        });

        addExtraQuestionBtn.addEventListener('click', function () {
            if (questionCount === 0) {
                alert("Please add a question first.");
                return;
            }

            const lastQuestionDiv = marksCoContainer.lastElementChild;
            const questionIndex = marksCoContainer.children.length - 1;

            // Hide the main question input
            const marksInput = lastQuestionDiv.querySelector('input[type="number"]');
            marksInput.type = 'hidden';

            // Create label and input for extra question a
            const extraQuestionALabel = document.createElement('label');
            extraQuestionALabel.textContent = `Extra Question ${questionIndex + 1}_a:`;
            extraQuestionALabel.className = 'inline-block pr-3 text-xl font-bold text-white';
            const extraQuestionAInput = document.createElement('input');
            extraQuestionAInput.type = 'number';
            extraQuestionAInput.placeholder = `Enter marks for extra question ${questionIndex + 1}_a`;
            extraQuestionAInput.className = 'block w-1/2 mt-2 p-2 border-2 border-gray-600 rounded-lg';
            extraQuestionAInput.required = true;
            extraQuestionAInput.min = '1';
            extraQuestionAInput.addEventListener('input', updateTotalMarks);

            // Create label and input for extra question b
            const extraQuestionBLabel = document.createElement('label');
            extraQuestionBLabel.textContent = `Extra Question ${questionIndex + 1}_b:`;
            extraQuestionBLabel.className = 'inline-block pr-3 text-xl font-bold text-white';
            const extraQuestionBInput = document.createElement('input');
            extraQuestionBInput.type = 'number';
            extraQuestionBInput.placeholder = `Enter marks for extra question ${questionIndex + 1}_b`;
            extraQuestionBInput.className = 'block w-1/2 mt-2 p-2 border-2 border-gray-600 rounded-lg';
            extraQuestionBInput.required = true;
            extraQuestionBInput.min = '1';
            extraQuestionBInput.addEventListener('input', updateTotalMarks);

            // Append labels and inputs to the question container
            lastQuestionDiv.appendChild(extraQuestionALabel);
            lastQuestionDiv.appendChild(extraQuestionAInput);
            lastQuestionDiv.appendChild(extraQuestionBLabel);
            lastQuestionDiv.appendChild(extraQuestionBInput);

            // Update the extraQuestionsArray
            extraQuestionsArray[questionIndex] = '1';
        });

        // Function to update total marks
        function updateTotalMarks() {
            let totalMarks = 0;

            const marksInputs = document.querySelectorAll('.question-item input[type="number"]:not([placeholder*="CO"])');

            // Calculate the total marks
            marksInputs.forEach(input => {
                totalMarks += parseInt(input.value) || 0;
            });

            // Display the total marks
            totalMarksDisplay.textContent = totalMarks;
            document.getElementById('total-count').value = totalMarks;
        }

        // Before form submission, add the arrays to hidden inputs
        document.getElementById('add-test').addEventListener('submit', function () {
            // Add the marks and CO arrays to the form as hidden inputs
            const marksHiddenInput = document.createElement('input');
            marksHiddenInput.type = 'hidden';
            marksHiddenInput.name = 'marks_array';
            marksHiddenInput.value = JSON.stringify(marksArray.map(input => input.value));
            this.appendChild(marksHiddenInput);

            const coHiddenInput = document.createElement('input');
            coHiddenInput.type = 'hidden';
            coHiddenInput.name = 'co_array';
            coHiddenInput.value = JSON.stringify(coArray.map(select => select.value));
            this.appendChild(coHiddenInput);

            const extraQuestionsHiddenInput = document.createElement('input');
            extraQuestionsHiddenInput.type = 'hidden';
            extraQuestionsHiddenInput.name = 'extra_questions_array';
            extraQuestionsHiddenInput.value = JSON.stringify(extraQuestionsArray);
            this.appendChild(extraQuestionsHiddenInput);
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.4/xlsx.full.min.js"></script>
</body>

</html>