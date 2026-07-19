/**
 * Dependent Dropdowns for SMS Project
 * Handles cascading dropdown relationships like Class -> Class Arm
 */

/**
 * Run function when the DOM is ready
 * @param {Function} fn 
 */
function runWhenReady(fn) {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', fn);
    } else {
        fn();
    }
}

/**
 * Initialize Class to Arm dependent dropdown (Client-side filtering)
 * @param {string} classSelectId - ID of the class select element
 * @param {string} armSelectId - ID of the arm select element
 */
function initializeClassArmDropdown(classSelectId = 'class_id', armSelectId = 'arm_id') {
    runWhenReady(function() {
        const classSelect = document.getElementById(classSelectId);
        const armSelect = document.getElementById(armSelectId);
        
        if (!classSelect || !armSelect) {
            console.warn('Class or Arm select element not found');
            return;
        }
        
        // Store all arm options initially with their class relationships
        const allArmOptions = Array.from(armSelect.querySelectorAll('option'));
        const armsData = allArmOptions.slice(1).map(option => ({
            value: option.value,
            text: option.textContent.trim(),
            classId: option.getAttribute('data-class-id')
        }));

        // Function to filter arms based on selected class
        function filterArms() {
            const selectedClassId = classSelect.value;
            
            // Clear current options except the first one (placeholder)
            armSelect.innerHTML = '<option value="">Select Arm</option>';
            
            if (!selectedClassId) {
                // If no class selected, disable arm select
                armSelect.disabled = true;
                return;
            }
            
            // Enable arm select
            armSelect.disabled = false;
            
            // Add filtered options for the selected class
            const filteredArms = armsData.filter(arm => arm.classId === selectedClassId);
            
            filteredArms.forEach(arm => {
                const option = document.createElement('option');
                option.value = arm.value;
                option.textContent = arm.text;
                option.setAttribute('data-class-id', arm.classId);
                armSelect.appendChild(option);
            });

            // If no arms found for this class, show informative message
            if (filteredArms.length === 0) {
                const option = document.createElement('option');
                option.value = '';
                option.textContent = 'No arms available for this class';
                option.disabled = true;
                armSelect.appendChild(option);
            }
        }

        // Listen for class selection changes
        classSelect.addEventListener('change', filterArms);
        
        // Initialize on page load
        if (classSelect.value) {
            filterArms();
            // Restore previously selected arm (for validation errors with old input)
            const preselectedArm = armSelect.getAttribute('data-selected');
            if (preselectedArm) {
                armSelect.value = preselectedArm;
            }
        } else {
            armSelect.disabled = true;
        }
    });
}

/**
 * Initialize Class to Arm dependent dropdown (AJAX-based - for dynamic loading)
 * More suitable for large datasets and real-time data
 * @param {string} classSelectId - ID of the class select element
 * @param {string} armSelectId - ID of the arm select element
 * @param {string} apiUrl - Base API URL (e.g., '/classes')
 */
function initializeClassArmDropdownAjax(classSelectId = 'class_id', armSelectId = 'arm_id', apiUrl = '/classes') {
    runWhenReady(function() {
        const classSelect = document.getElementById(classSelectId);
        const armSelect = document.getElementById(armSelectId);
        
        if (!classSelect || !armSelect) {
            console.warn('Class or Arm select element not found');
            return;
        }

        // Function to load arms via AJAX
        function loadArms(classId, selectedArmId = null) {
            if (!classId) {
                armSelect.innerHTML = '<option value="">Select Arm</option>';
                armSelect.disabled = true;
                return;
            }

            // Show loading state
            armSelect.innerHTML = '<option value="">Loading arms...</option>';
            armSelect.disabled = true;

            // Fetch arms from server
            fetch(`${apiUrl}/${classId}/arms`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to load arms');
                    }
                    return response.json();
                })
                .then(data => {
                    // Clear and repopulate
                    armSelect.innerHTML = '<option value="">Select Arm</option>';
                    
                    if (data.arms && data.arms.length > 0) {
                        armSelect.disabled = false;
                        
                        data.arms.forEach(arm => {
                            const option = document.createElement('option');
                            option.value = arm.id;
                            
                            // Include capacity and student count in the display
                            let displayText = arm.name;
                            if (arm.students_count !== undefined && arm.capacity) {
                                displayText += ` (${arm.students_count}/${arm.capacity})`;
                            } else if (arm.students_count !== undefined) {
                                displayText += ` (${arm.students_count} students)`;
                            }
                            
                            option.textContent = displayText;
                            option.setAttribute('data-class-id', arm.class_id);
                            
                            // Pre-select if this was the previously selected arm
                            if (selectedArmId && arm.id == selectedArmId) {
                                option.selected = true;
                            }
                            
                            armSelect.appendChild(option);
                        });
                    } else {
                        // No arms available
                        const option = document.createElement('option');
                        option.value = '';
                        option.textContent = 'No arms available for this class';
                        option.disabled = true;
                        armSelect.appendChild(option);
                        armSelect.disabled = true;
                    }
                })
                .catch(error => {
                    console.error('Error loading arms:', error);
                    armSelect.innerHTML = '<option value="">Error loading arms</option>';
                    armSelect.disabled = true;
                });
        }

        // Listen for class selection changes
        classSelect.addEventListener('change', function() {
            const classId = this.value;
            loadArms(classId);
        });

        // Initialize on page load if a class is already selected
        const initialClassId = classSelect.value;
        const preselectedArm = armSelect.getAttribute('data-selected');
        
        if (initialClassId) {
            loadArms(initialClassId, preselectedArm);
        } else {
            armSelect.disabled = true;
        }
    });
}

/**
 * Initialize Subject to Class dependent dropdown for timetables
 * @param {string; } subjectSelectId - ID of the subject select element
 * @param {string} classSelectId - ID of the class select element
 */
function initializeSubjectClassDropdown(subjectSelectId = 'subject_id', classSelectId = 'class_id') {
    runWhenReady(function() {
        const subjectSelect = document.getElementById(subjectSelectId);
        const classSelect = document.getElementById(classSelectId);
        
        if (!subjectSelect || !classSelect) {
            return;
        }
        
        // Store all class options with their subject relationships
        const allClassOptions = Array.from(classSelect.querySelectorAll('option'));
        const classesData = allClassOptions.slice(1).map(option => ({
            value: option.value,
            text: option.textContent.trim(),
            subjectId: option.getAttribute('data-subject-id')
        }));

        function filterClasses() {
            const selectedSubjectId = subjectSelect.value;
            
            classSelect.innerHTML = '<option value="">Select Class</option>';
            
            if (!selectedSubjectId) {
                classSelect.disabled = true;
                return;
            }
            
            classSelect.disabled = false;
            
            const filteredClasses = classesData.filter(cls => {
                // If no subject filter, show all
                if (!cls.subjectId) return true;
                return cls.subjectId === selectedSubjectId;
            });
            
            filteredClasses.forEach(cls => {
                const option = document.createElement('option');
                option.value = cls.value;
                option.textContent = cls.text;
                if (cls.subjectId) {
                    option.setAttribute('data-subject-id', cls.subjectId);
                }
                classSelect.appendChild(option);
            });
        }

        subjectSelect.addEventListener('change', filterClasses);
        
        if (subjectSelect.value) {
            filterClasses();
        }
    });
}
