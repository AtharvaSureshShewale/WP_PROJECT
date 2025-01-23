let questionCount = 0;
const marksCoContainer = document.getElementById('marks-co-container');
const totalMarksDisplay = document.getElementById('total-marks');
const addQuestionBtn = document.getElementById('add-question-btn');
const deleteQuestionBtn = document.getElementById('delete-question-btn');

if (addQuestionBtn) {
    addQuestionBtn.addEventListener('click', () => {
        questionCount++;
        const questionDiv = document.createElement('div');
        questionDiv.classList.add('flex', 'flex-wrap', 'items-center', 'space-x-4');
        questionDiv.innerHTML = `
            <div class="flex flex-col w-3/6">
                <input type="text" name="question[]" placeholder="Enter Question" class="mt-1 p-1 border-2 border-gray-600 rounded-lg" required>
            </div>
            <div class="flex flex-col w-1/6">
                <input type="number" name="marks[]" min="1" placeholder="Marks" class="mt-1 p-1 border-2 border-gray-600 rounded-lg" required>
            </div>
            <div class="flex flex-col w-1/6">
                <input type="number" name="co[]" min="1" placeholder="CO" class="mt-1 p-1 border-2 border-gray-600 rounded-lg" required>
            </div>
        `;
        marksCoContainer.appendChild(questionDiv);
        updateTotalMarks();
    });
}

if (deleteQuestionBtn) {
    deleteQuestionBtn.addEventListener('click', () => {
        if (questionCount > 0) {
            marksCoContainer.removeChild(marksCoContainer.lastChild);
            questionCount--;
            updateTotalMarks();
        }
    });
}

function updateTotalMarks() {
    const marksInputs = document.querySelectorAll('input[name="marks[]"]');
    let totalMarks = 0;
    marksInputs.forEach(input => {
        totalMarks += parseInt(input.value) || 0;
    });
    totalMarksDisplay.textContent = totalMarks;
}
