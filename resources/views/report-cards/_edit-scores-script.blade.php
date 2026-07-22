<script>
// Edit Score Functions
function editScore(resultId) {
    // Hide display spans, show input fields
    document.getElementById(`ca-display-${resultId}`).classList.add('hidden');
    document.getElementById(`exam-display-${resultId}`).classList.add('hidden');
    document.getElementById(`ca-input-${resultId}`).classList.remove('hidden');
    document.getElementById(`exam-input-${resultId}`).classList.remove('hidden');
    
    // Toggle buttons
    document.getElementById(`edit-btn-${resultId}`).classList.add('hidden');
    document.getElementById(`save-btn-${resultId}`).classList.remove('hidden');
    document.getElementById(`cancel-btn-${resultId}`).classList.remove('hidden');
    
    // Highlight row
    document.getElementById(`row-${resultId}`).classList.add('bg-amber-50');
}

function cancelEdit(resultId) {
    // Show display spans, hide input fields
    document.getElementById(`ca-display-${resultId}`).classList.remove('hidden');
    document.getElementById(`exam-display-${resultId}`).classList.remove('hidden');
    document.getElementById(`ca-input-${resultId}`).classList.add('hidden');
    document.getElementById(`exam-input-${resultId}`).classList.add('hidden');
    
    // Toggle buttons
    document.getElementById(`edit-btn-${resultId}`).classList.remove('hidden');
    document.getElementById(`save-btn-${resultId}`).classList.add('hidden');
    document.getElementById(`cancel-btn-${resultId}`).classList.add('hidden');
    
    // Remove highlight
    document.getElementById(`row-${resultId}`).classList.remove('bg-amber-50');
    
    // Reset input values to original
    const caDisplay = document.getElementById(`ca-display-${resultId}`).textContent.trim();
    const examDisplay = document.getElementById(`exam-display-${resultId}`).textContent.trim();
    document.getElementById(`ca-input-${resultId}`).value = caDisplay;
    document.getElementById(`exam-input-${resultId}`).value = examDisplay;
}

async function saveScore(resultId) {
    const caScore = parseFloat(document.getElementById(`ca-input-${resultId}`).value);
    const examScore = parseFloat(document.getElementById(`exam-input-${resultId}`).value);
    
    // Validate
    if (isNaN(caScore) || caScore < 0 || caScore > 40) {
        alert('CA Score must be between 0 and 40');
        return;
    }
    if (isNaN(examScore) || examScore < 0 || examScore > 60) {
        alert('Exam Score must be between 0 and 60');
        return;
    }
    
    // Show loading state
    const saveBtn = document.getElementById(`save-btn-${resultId}`);
    const originalText = saveBtn.innerHTML;
    saveBtn.innerHTML = '⏳ Saving...';
    saveBtn.disabled = true;
    
    try {
        // Get CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]');
        if (!csrfToken) {
            console.error('CSRF token not found');
            alert('❌ Security token missing. Please refresh the page.');
            return;
        }

        console.log('Sending update request for result ID:', resultId);
        console.log('Data:', { ca_score: caScore, exam_score: examScore });

        const response = await fetch(`{{ url('/results') }}/${resultId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken.content,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify({
                ca_score: caScore,
                exam_score: examScore
            })
        });
        
        console.log('Response status:', response.status);
        const data = await response.json();
        console.log('Response data:', data);
        
        if (data.success) {
            // Update display values
            document.getElementById(`ca-display-${resultId}`).textContent = data.data.ca_score;
            document.getElementById(`exam-display-${resultId}`).textContent = data.data.exam_score;
            document.getElementById(`total-display-${resultId}`).textContent = data.data.total_score;
            
            // Update grade
            const total = data.data.total_score;
            let grade, gradeClass;
            
            if (total >= 75) {
                grade = 'A1';
                gradeClass = 'bg-green-100 text-green-800';
            } else if (total >= 70) {
                grade = 'B2';
                gradeClass = 'bg-green-100 text-green-700';
            } else if (total >= 65) {
                grade = 'B3';
                gradeClass = 'bg-blue-100 text-blue-700';
            } else if (total >= 60) {
                grade = 'C4';
                gradeClass = 'bg-blue-100 text-blue-600';
            } else if (total >= 55) {
                grade = 'C5';
                gradeClass = 'bg-yellow-100 text-yellow-700';
            } else if (total >= 50) {
                grade = 'C6';
                gradeClass = 'bg-yellow-100 text-yellow-600';
            } else if (total >= 45) {
                grade = 'D7';
                gradeClass = 'bg-orange-100 text-orange-700';
            } else if (total >= 40) {
                grade = 'E8';
                gradeClass = 'bg-red-100 text-red-600';
            } else {
                grade = 'F9';
                gradeClass = 'bg-red-100 text-red-700';
            }
            
            const gradeCell = document.getElementById(`grade-cell-${resultId}`);
            gradeCell.innerHTML = `<span class="inline-flex items-center justify-center w-12 h-8 ${gradeClass} rounded-lg font-bold">${grade}</span>`;
            
            // Update remarks
            let remark;
            if (total >= 75) {
                remark = 'Excellent';
            } else if (total >= 60) {
                remark = 'Good';
            } else if (total >= 50) {
                remark = 'Fair';
            } else if (total >= 40) {
                remark = 'Pass';
            } else {
                remark = 'Needs Improvement';
            }
            document.getElementById(`remarks-cell-${resultId}`).textContent = remark;
            
            // Exit edit mode
            cancelEdit(resultId);
            
            // Show success message
            alert('✅ Score updated successfully!');
            
            // Reload page to update totals
            setTimeout(() => window.location.reload(), 500);
        } else {
            alert('❌ Error: ' + (data.message || 'Unknown error'));
        }
    } catch (error) {
        console.error('Error details:', error);
        alert('❌ Failed to save score. Error: ' + error.message);
    } finally {
        saveBtn.innerHTML = originalText;
        saveBtn.disabled = false;
    }
}
</script>
