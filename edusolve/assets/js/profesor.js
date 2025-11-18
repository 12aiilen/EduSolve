document.addEventListener('DOMContentLoaded', function() {
    // Verificar autenticación y rol
    const currentUser = JSON.parse(localStorage.getItem('currentUser'));
    
    // Redirección si no está autenticado o no es profesor
    if (!currentUser || currentUser.role !== 'teacher') {
        window.location.href = '../../pages/auth/login.html';
        return;
    }
    
    // Datos de ejemplo (simulación)
    const courses = [
        { id: 1, name: 'Matemáticas 3°A', students: 25, pendingGrades: 3 },
        { id: 2, name: 'Física 4°B', students: 20, pendingGrades: 0 }
    ];
    
    const students = [
        { id: 1, name: 'Juan Pérez', grade: '7.5', lastExam: '8.0', status: 'Aprobado' },
        { id: 2, name: 'María Gómez', grade: '6.2', lastExam: '5.5', status: 'Cursando' }
    ];
    
    // Cargar información del profesor - Versión segura
    const teacherNameElement = document.getElementById('teacherName') || document.querySelector('.current-user');
    if (teacherNameElement && currentUser.name) {
        teacherNameElement.textContent = currentUser.name;
    }
    
    // Cargar cursos
    if (document.getElementById('coursesList')) {
        renderCourses(courses);
    }
    
    // Cargar estudiantes (en página de calificaciones)
    if (document.getElementById('studentsGradesTable')) {
        renderStudentsGrades(students);
    }
    
    // Funciones
    function renderCourses(courses) {
        const coursesContainer = document.getElementById('coursesList');
        if (!coursesContainer) return;
        
        coursesContainer.innerHTML = '';
        
        courses.forEach(course => {
            const courseCard = document.createElement('div');
            courseCard.className = 'course-card';
            
            courseCard.innerHTML = `
                <h3>${course.name}</h3>
                <p><strong>Estudiantes:</strong> ${course.students}</p>
                <p><strong>Calificaciones pendientes:</strong> ${course.pendingGrades}</p>
                <a href="calificaciones/listado.html?course=${course.id}" class="button button-primary">Ver Calificaciones</a>
            `;
            
            coursesContainer.appendChild(courseCard);
        });
    }
    
    function renderStudentsGrades(students) {
        const tableBody = document.querySelector('#studentsGradesTable tbody');
        if (!tableBody) return;
        
        tableBody.innerHTML = '';
        
        students.forEach(student => {
            const row = document.createElement('tr');
            
            row.innerHTML = `
                <td>${student.name}</td>
                <td>
                    <input type="number" step="0.1" min="1" max="10" value="${student.grade}" 
                           class="grade-input" data-student-id="${student.id}">
                </td>
                <td>${student.lastExam}</td>
                <td>
                    <select class="status-select" data-student-id="${student.id}">
                        <option value="Aprobado" ${student.status === 'Aprobado' ? 'selected' : ''}>Aprobado</option>
                        <option value="Cursando" ${student.status === 'Cursando' ? 'selected' : ''}>Cursando</option>
                        <option value="Desaprobado" ${student.status === 'Desaprobado' ? 'selected' : ''}>Desaprobado</option>
                    </select>
                </td>
            `;
            
            tableBody.appendChild(row);
        });
        
        // Manejar cambios en calificaciones
        document.querySelectorAll('.grade-input').forEach(input => {
            input.addEventListener('change', function() {
                const studentId = this.getAttribute('data-student-id');
                const newGrade = this.value;
                
                // Validar nota
                if (newGrade < 1 || newGrade > 10) {
                    showAlert('La calificación debe estar entre 1 y 10', 'error');
                    this.value = student.grade;
                    return;
                }
                
                // En un sistema real, aquí harías una petición al backend
                console.log(`Actualizando calificación del estudiante ${studentId} a ${newGrade}`);
                showAlert('Calificación actualizada', 'success');
            });
        });
        
        // Manejar cambios en estado
        document.querySelectorAll('.status-select').forEach(select => {
            select.addEventListener('change', function() {
                const studentId = this.getAttribute('data-student-id');
                const newStatus = this.value;
                
                console.log(`Actualizando estado del estudiante ${studentId} a ${newStatus}`);
                showAlert('Estado actualizado', 'success');
            });
        });
    }
    
    function showAlert(message, type) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type}`;
        alertDiv.textContent = message;
        
        const container = document.querySelector('.teacher-container .container') || document.querySelector('.container');
        if (container) {
            container.prepend(alertDiv);
            
            setTimeout(() => {
                alertDiv.remove();
            }, 3000);
        }
    }
    
    // Subir archivos
    const uploadForm = document.getElementById('uploadGradesForm');
    if (uploadForm) {
        uploadForm.addEventListener('submit', function(e) {
            e.preventDefault();
            // Simular subida de archivo
            const fileInput = document.getElementById('gradesFile');
            if (fileInput.files.length > 0) {
                showAlert('Archivo subido correctamente. Procesando calificaciones...', 'success');
                // Simular procesamiento
                setTimeout(() => {
                    showAlert('Calificaciones actualizadas correctamente', 'success');
                }, 2000);
            } else {
                showAlert('Por favor seleccione un archivo', 'error');
            }
        });
    }
    
    // Cerrar sesión
    const logoutBtn = document.getElementById('logoutBtn');
    if (logoutBtn) {
        logoutBtn.addEventListener('click', function() {
            localStorage.removeItem('currentUser');
            window.location.href = '../../pages/auth/login.html';
        });
    }
});