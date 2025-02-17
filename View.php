<?php
    include('header.php');?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <script src="https://cdn.tailwindcss.com"></script>
        <title>View Test Analysis</title>
    </head>

    <body>
    <?php

require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'view-test') {

        $department = $_POST['department'];
        $semester = $_POST['semester'];
        $course = $_POST['course_name'];
        $coursecode = $_POST['course_code'];
        $termtest = $_POST['term_test_number'];
        $div = $_POST['div'];

        $tableName = strtolower($department . "_" . $semester . "_" . $course . "_" . $coursecode . "_" . $termtest . "_" . $div);

        $query = "SELECT * FROM `$tableName`";
        $result = $conn->query($query);

        if (!$result) {
            die("Query failed: " . $conn->error);
        }

        if ($result->num_rows > 0) {
            $fields = $result->fetch_fields();
            $file_name = "{$tableName}.csv";
            $file_path = 'downloads/' . $file_name;
            $file = fopen($file_path, 'w');

            // Prepare column headers
            $headers = ['division', 'id', 'sap', 'name'];
            $co_columns = [];
            $total_marks_column = '';

            foreach ($fields as $field) {
                if (preg_match('/^question[0-9]+_/', $field->name)) {
                    $headers[] = $field->name;
                } elseif (preg_match('/^total_marks_[0-9]+$/', $field->name)) {
                    $total_marks_column = $field->name;
                    $headers[] = $total_marks_column;
                } elseif (preg_match('/^CO[0-9]+_[0-9]+$/', $field->name)) {
                    $co_columns[] = $field->name;
                    $headers[] = $field->name;
                }
            }

            fputcsv($file, $headers);

            // Fetch data and write rows
            $students_scores = [];
            while ($row = $result->fetch_assoc()) {
                $dataRow = [
                    $row['division'],
                    $row['id'],
                    $row['sap'],
                    $row['name']
                ];

                foreach ($fields as $field) {
                    if (preg_match('/^question[0-9]+_/', $field->name)) {
                        $dataRow[] = $row[$field->name];
                    }
                }

                $dataRow[] = $row[$total_marks_column];

                foreach ($co_columns as $co_field) {
                    $dataRow[] = $row[$co_field];
                }

                fputcsv($file, $dataRow);
                $students_scores[] = $row;
            }

            // Perform CO analysis
            $co_above_60_count = array_fill(0, count($co_columns), 0);
            $thresholds = [];

            foreach ($co_columns as $co_field) {
                preg_match('/CO[0-9]+_([0-9]+)/', $co_field, $matches);
                if (isset($matches[1])) {
                    $thresholds[] = 0.6 * (int)$matches[1];
                }
            }

            foreach ($students_scores as $scores) {
                foreach ($co_columns as $index => $co_field) {
                    if ($scores[$co_field] >= $thresholds[$index]) {
                        $co_above_60_count[$index]++;
                    }
                }
            }

            // Add analysis summary
            fputcsv($file, []);
            fputcsv($file, ["Analysis Summary"]);
            fputcsv($file, ["CO", "No. of Students Scoring 60% and Above", "Percentage (%)", "Attainment Level"]);

            echo "<h2 class='text-xl font-bold mt-4'>Analysis</h2>";
            echo "<table class='min-w-full bg-white text-left'>";
            echo "<thead><tr><th>CO</th><th>No. of Students Scoring 60% and Above</th><th>Percentage</th><th>Attainment Level</th></tr></thead>";
            echo "<tbody>";

            $total_students = count($students_scores);
            foreach ($co_columns as $index => $co_field) {
                $percentage = ($total_students > 0) ? ($co_above_60_count[$index] / $total_students) * 100 : 0;
                $attainment_level = ($percentage < 40) ? 1 : (($percentage < 60) ? 2 : 3);

                fputcsv($file, [
                    $co_field,
                    $co_above_60_count[$index],
                    number_format($percentage, 2) . "%",
                    "Level " . $attainment_level
                ]);

               

                echo "<tr>";
                echo  "<td>".$co_field."</td>",
                "<td>".$co_above_60_count[$index]."</td>",
                "<td>",number_format($percentage, 2) . "%","</td>",
                "<td>"."Level " . $attainment_level."</td>";
                echo "</tr>";

                
            }

            fclose($file);
            echo "Data exported successfully! <a href='$file_path' class='text-blue-500 underline'>Click here to download the CSV file</a>";
      
            echo "</tbody>";
            echo "</table>";

           
        } else {
            echo "No data found for the provided details.";
        }}
    }

    if (isset($_POST['action']) && $_POST['action'] == 'update_marks') {

        // Handle update request
            $tableName = $_POST['table_name'];
            $student_id = $_POST['student_id'];
            $updated_marks = $_POST['updated_marks'];

            // Fetch all CO columns dynamically from the table
            $result = $conn->query("SHOW COLUMNS FROM `$tableName`");
            $co_columns = [];

            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    if (preg_match('/^CO\d+_\d+$/', $row['Field'])) {
                        $co_columns[] = $row['Field'];
                    }
                }
            }

            $update_query = "UPDATE `$tableName` SET ";
            $total_marks = 0;

            // Initialize CO marks dynamically
            $co_marks = array_fill_keys($co_columns, 0);

            foreach ($updated_marks as $column => $value) {
                $numeric_value = intval($value);
                
                // Exclude CO columns from total marks calculation
                $total_marks += $numeric_value;
                
                // Check if the column is a CO column and update its value dynamically
                foreach ($co_columns as $co_column) {
                    if (strpos($column, explode('_', $co_column)[0]) !== false) {
                        $co_marks[$co_column] += $numeric_value;
                    }
                }
                
                $update_query .= "`$column` = '$numeric_value', ";
            }

            // Add CO columns dynamically to the update query
            foreach ($co_marks as $co_column => $co_value) {
                $update_query .= "`$co_column` = '$co_value', ";
            }

            // Dynamically find the total_marks column name (assuming format total_marks_X)
            $result = $conn->query("SHOW COLUMNS FROM `$tableName` LIKE 'total_marks_%'");
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $total_marks_column = $row['Field'];
                $update_query .= "`$total_marks_column` = '$total_marks', ";
            }

            $update_query = rtrim($update_query, ", ") . " WHERE id='$student_id'";

            // Execute the query to update marks
            if ($conn->query($update_query) === TRUE) {
                echo "<p class='text-green-500'>Marks updated successfully!</p>";
            } else {
                echo "<p class='text-red-500'>Error updating marks: " . $conn->error . "</p>";
            }
        

        // Handle view request
        if ($_POST['action'] === 'view-test') {
            $department = $_POST['department'];
            $semester = $_POST['semester'];
            $course = $_POST['course_name'];
            $coursecode = $_POST['course_code'];
            $termtest = $_POST['term_test_number'];
            $div = $_POST['div'];

            $tableName = strtolower($department . "_" . $semester . "_" . $course . "_" . $coursecode . "_" . $termtest . "_" . $div);

            // Debugging: Print the table name
            echo "Querying table: $tableName <br>";

            // Query the database based on submitted details
            $query = "SELECT * FROM `$tableName`";
            $result = $conn->query($query);

            if (!$result) {
                die("Query failed: " . $conn->error);
            }

            if ($result->num_rows > 0) {
                echo "<h2 class='text-xl font-bold mt-4'>Query Results</h2>";
                echo "<table class='min-w-full bg-white border border-gray-300 text-left'>";
                echo "<thead><tr>";

                // Display table headers dynamically based on fetched column names
                $fields = $result->fetch_fields();
                foreach ($fields as $field) {
                    echo "<th class='px-4 py-2 border'>{$field->name}</th>";
                }
                echo "<th class='px-4 py-2 border'>Action</th>";
                echo "</tr></thead>";
                echo "<tbody>";

                // Populate table rows with data
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<form method='POST' class='inline'>";

                    foreach ($fields as $field) {
                        if ($field->name == 'id') {
                            echo "<td class='px-4 py-2 border'>
                                    <input type='hidden' name='student_id' value='{$row[$field->name]}'>{$row[$field->name]}
                                  </td>";
                        } elseif ($field->name == 'division') {
                            // Non-editable division field
                            echo "<td class='px-4 py-2 border'>{$row[$field->name]}</td>";
                        } elseif ($field->name == 'sap' || $field->name == 'name' || preg_match('/^total_marks_\d+$/', $field->name) || preg_match('/^CO\d+_\d+$/', $field->name)) {
                            // Non-editable fields
                            echo "<td class='px-4 py-2 border'>{$row[$field->name]}</td>";
                        } else {
                            // Editable fields (questions)
                            echo "<td class='px-4 py-2 border'>
                                    <input type='text' name='updated_marks[{$field->name}]' value='{$row[$field->name]}' class='border p-1 w-20'>
                                  </td>";
                        }
                    }

                    echo "<td class='px-4 py-2 border'>
                            <input type='hidden' name='table_name' value='$tableName'>
                            <input type='hidden' name='action' value='update_marks'>
                            <button type='submit' class='bg-blue-500 text-white px-4 py-1 rounded hover:bg-blue-700'>Save</button>
                          </td>";

                    echo "</form>";
                    echo "</tr>";
                }

                echo "</tbody>";
                echo "</table>";
            } else {
                echo "<p class='text-red-500 mt-4'>No data found for the provided details.</p>";
            }
        }
    }

    // Close the connection
    $conn->close();
    ?>

<?php


require 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {

        // Handle update request
        if ($_POST['action'] == 'update_marks') {
            $tableName = $_POST['table_name'];
            $student_id = $_POST['student_id'];
            $updated_marks = $_POST['updated_marks'];

            // Fetch all CO columns dynamically from the table
            $result = $conn->query("SHOW COLUMNS FROM `$tableName`");
            $co_columns = [];

            if ($result) {
                while ($row = $result->fetch_assoc()) {
                    if (preg_match('/^CO\d+_\d+$/', $row['Field'])) {
                        $co_columns[] = $row['Field'];
                    }
                }
            }

            $update_query = "UPDATE `$tableName` SET ";
            $total_marks = 0;

            // Initialize CO marks dynamically
            $co_marks = array_fill_keys($co_columns, 0);

            foreach ($updated_marks as $column => $value) {
                $numeric_value = intval($value);
                
                // Exclude CO columns from total marks calculation
                $total_marks += $numeric_value;
                
                // Check if the column is a CO column and update its value dynamically
                foreach ($co_columns as $co_column) {
                    if (strpos($column, explode('_', $co_column)[0]) !== false) {
                        $co_marks[$co_column] += $numeric_value;
                    }
                }
                
                $update_query .= "`$column` = '$numeric_value', ";
            }

            // Add CO columns dynamically to the update query
            foreach ($co_marks as $co_column => $co_value) {
                $update_query .= "`$co_column` = '$co_value', ";
            }

            // Dynamically find the total_marks column name (assuming format total_marks_X)
            $result = $conn->query("SHOW COLUMNS FROM `$tableName` LIKE 'total_marks_%'");
            if ($result && $result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $total_marks_column = $row['Field'];
                $update_query .= "`$total_marks_column` = '$total_marks', ";
            }

            $update_query = rtrim($update_query, ", ") . " WHERE id='$student_id'";

            // Execute the query to update marks
            if ($conn->query($update_query) === TRUE) {
                echo "<p class='text-green-500'>Marks updated successfully!</p>";
            } else {
                echo "<p class='text-red-500'>Error updating marks: " . $conn->error . "</p>";
            }
        }

        // Handle view request
        if ($_POST['action'] === 'view-test') {
            $department = $_POST['department'];
            $semester = $_POST['semester'];
            $course = $_POST['course_name'];
            $coursecode = $_POST['course_code'];
            $termtest = $_POST['term_test_number'];
            $div = $_POST['div'];

            $tableName = strtolower($department . "_" . $semester . "_" . $course . "_" . $coursecode . "_" . $termtest . "_" . $div);

            // Debugging: Print the table name
            echo "Querying table: $tableName <br>";

            // Query the database based on submitted details
            $query = "SELECT * FROM `$tableName`";
            $result = $conn->query($query);

            if (!$result) {
                die("Query failed: " . $conn->error);
            }

            if ($result->num_rows > 0) {
                echo "<h2 class='text-xl font-bold mt-4'>Query Results</h2>";
                echo "<table class='min-w-full bg-white border border-gray-300 text-left'>";
                echo "<thead><tr>";

                // Display table headers dynamically based on fetched column names
                $fields = $result->fetch_fields();
                foreach ($fields as $field) {
                    echo "<th class='px-4 py-2 border'>{$field->name}</th>";
                }
                echo "<th class='px-4 py-2 border'>Action</th>";
                echo "</tr></thead>";
                echo "<tbody>";

                // Populate table rows with data
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<form method='POST' class='inline'>";

                    foreach ($fields as $field) {
                        if ($field->name == 'id') {
                            echo "<td class='px-4 py-2 border'>
                                    <input type='hidden' name='student_id' value='{$row[$field->name]}'>{$row[$field->name]}
                                  </td>";
                        } elseif ($field->name == 'division') {
                            // Non-editable division field
                            echo "<td class='px-4 py-2 border'>{$row[$field->name]}</td>";
                        } elseif ($field->name == 'sap' || $field->name == 'name' || preg_match('/^total_marks_\d+$/', $field->name) || preg_match('/^CO\d+_\d+$/', $field->name)) {
                            // Non-editable fields
                            echo "<td class='px-4 py-2 border'>{$row[$field->name]}</td>";
                        } else {
                            // Editable fields (questions)
                            echo "<td class='px-4 py-2 border'>
                                    <input type='text' name='updated_marks[{$field->name}]' value='{$row[$field->name]}' class='border p-1 w-20'>
                                  </td>";
                        }
                    }

                    echo "<td class='px-4 py-2 border'>
                            <input type='hidden' name='table_name' value='$tableName'>
                            <input type='hidden' name='action' value='update_marks'>
                            <button type='submit' class='bg-blue-500 text-white px-4 py-1 rounded hover:bg-blue-700'>Save</button>
                          </td>";

                    echo "</form>";
                    echo "</tr>";
                }

                echo "</tbody>";
                echo "</table>";
            } else {
                echo "<p class='text-red-500 mt-4'>No data found for the provided details.</p>";
            }
        }
    }
}

$conn->close();
?>


    <form id="view-test" class="max-w-7xl mx-auto my-3 p-6 space-y-3 bg-blue-900 rounded-3xl shadow-inner" method="post" 
    enctype="multipart/form-data">
    <input type="hidden" name="action" value="view-test">
        <!-- Department, Semester, Course, Course Code, and Term Test No -->
        <div class="flex flex-wrap items-center space-x-1">
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
                <select id="semester" name="semester" class="w-full mt-1 p-1 border-2 border-gray-600 rounded-lg"
                    required>
                    <?php for ($i = 1; $i <= 8; $i++): ?>
                        <option value="<?= $i ?>"><?= $i ?></option>
                    <?php endfor; ?>
                </select>
            </div>

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
        <button type="submit" class="bg-white text-[#2a2185] font-bold rounded-lg py-2 px-4">View Table</button>
    </form>
    </body>
    </html>
