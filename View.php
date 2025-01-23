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
        die("Connection Failed: " . $conn->connect_error);
    }

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Sanitize form data
        $department = $conn->real_escape_string($_POST['department']);
        $semester = $conn->real_escape_string($_POST['semester']);
        $course = $conn->real_escape_string($_POST['course']);
        $coursecode = $conn->real_escape_string($_POST['coursecode']);
        $termtest = $conn->real_escape_string($_POST['termtest']);

        $tableName = strtolower(str_replace("-", "_", $department . "_" . $semester . "_" . $course . "_" . $coursecode . "_term" . $termtest));

        // Debugging: Print the table name
        echo "Querying table: $tableName <br>";

        // Query the database based on submitted details
        $query = "SELECT * FROM `$tableName`";
        $result = $conn->query($query);

        if (!$result) {
            die("Query failed: " . $conn->error);
        }

        if ($result->num_rows > 0) {
            // Determine the subject count and course outcome count
            $fields = $result->fetch_fields();
            $subject_count = 0;
            $course_outcome_count = 0;

            // Count the number of subject and course outcome columns
            foreach ($fields as $field) {
                if (preg_match('/^Q[0-9]+$/', $field->name)) { // Match columns like Q1, Q2, etc.
                    $subject_count++;
                } elseif (preg_match('/^CO[0-9]+$/', $field->name)) { // Match columns like CO1, CO2, etc.
                    $course_outcome_count++;
                }
            }

            // Create a CSV file
            $file_name = "term_test_{$coursecode}_{$termtest}.csv";
            $file_path = 'downloads/' . $file_name;

            $file = fopen($file_path, 'w');
            
            // Set headers in the CSV file
            $header = array_merge(
                ['id', 'Div', 'RollNo', 'SAPID', 'Name'], // Initial headers
                range('Q1', 'Q'.$subject_count), // Generate headers Q1, Q2, ..., Qn
                ['Total Marks'], // Add "Total Marks"
                range('CO1', 'CO'.$course_outcome_count) // Generate headers CO1, CO2, ..., COn
            );
            fputcsv($file, $header);

            // Fetch data and write to the CSV file
            $students_scores = [];
            while ($row = $result->fetch_assoc()) {
                // Prepare data row to include subject marks and course outcomes
                $dataRow = [
                    $row['id'],
                    $row['Div'],
                    $row['RollNo'],
                    $row['SAPID'],
                    $row['Name'],
                ];

                // Add subject marks
                for ($i = 1; $i <= $subject_count; $i++) {
                    $dataRow[] = $row["Q$i"];
                }

                $dataRow[] = $row['TotalMarks'];

                // Add course outcomes and store them in $students_scores
                $co_scores = [];
                for ($j = 1; $j <= $course_outcome_count; $j++) {
                    $dataRow[] = $row["CO$j"];
                    $co_scores[] = $row["CO$j"];
                }

                fputcsv($file, $dataRow);
                $students_scores[] = $co_scores;
            }

            // Close the file
            fclose($file);

            echo "Data exported successfully! <a href='$file_path' class='text-blue-500 underline'>Click here to download the CSV file</a>";

            // Perform analysis on COs: Count number of students scoring 60% or above
            $thresholds = [5, 8]; // 60% of 8 marks for CO1, 60% of 12 marks for CO2
            $co_above_60_count = array_fill(0, $course_outcome_count, 0); // Initialize array to count students per CO
            $total_students = count($students_scores);

            foreach ($students_scores as $scores) {
                foreach ($scores as $index => $score) {
                    // Check if the score is above or equal to the respective threshold
                    if ($index == 0 && $score >= $thresholds[0]) { // CO1 threshold
                        $co_above_60_count[$index]++;
                    } elseif ($index == 1 && $score >= $thresholds[1]) { // CO2 threshold
                        $co_above_60_count[$index]++;
                    }
                }
            }

            // Display analysis results
            echo "<h2 class='text-xl font-bold mt-4'>Analysis</h2>";
            echo "<table class='min-w-full bg-white text-left'>";
            echo "<thead><tr><th>CO</th><th>No. of Students Scoring 60% and Above</th><th>Percentage</th><th>Attainment Level</th></tr></thead>";
            echo "<tbody>";

            for ($j = 0; $j < $course_outcome_count; $j++) {
                $percentage = ($total_students > 0) ? ($co_above_60_count[$j] / $total_students) * 100 : 0;
                
                            // Determine attainment level based on percentage
                if ($percentage < 40) {
                    $attainment_level = 1; // Below 40%
                } elseif ($percentage >= 40 && $percentage < 60) {
                    $attainment_level = 2; // 40% to 60%
                } else {
                    $attainment_level = 3; // 60% and above
                }
                            
                
                echo "<tr>";
                echo "<td>CO" . ($j + 1) . "</td>";
                echo "<td>" . $co_above_60_count[$j] . "</td>";
                echo "<td>" . number_format($percentage, 2) . "%</td>";
                echo "<td>Level " . $attainment_level . "</td>";
                echo "</tr>";
            }

            echo "</tbody>";
            echo "</table>";
        } else {
            echo "No data found for the provided details.";
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
                    <option value="electronics_telecommunication">Electronics and Telecommunication Engg</option>
                    <option value="information_technology">Information Technology</option>
                    <option value="computer_engineering">Computer Engineering</option>
                    <option value="computer_science_data_science">Computer Science and Engineering (Data Science)</option>
                    <option value="ai_ml">Artificial Intelligence and Machine Learning</option>
                    <option value="ai_data_science">Artificial Intelligence (AI) and Data Science</option>
                    <option value="computer_science_engineering">Computer Science and Engineering</option>
                    <option value="iot_cyber_security">IOT and Cyber Security with Block Chain Technology</option>
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
        <button type="submit" class="mt-4 p-2 bg-blue-500 text-white rounded-lg">View Table</button>
    </form>
    </body>
    </html>
