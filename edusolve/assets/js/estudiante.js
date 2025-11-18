// assets/js/estudiante.js

document.addEventListener('DOMContentLoaded', function() {
    // Verificar autenticación y rol
    const currentUser = JSON.parse(localStorage.getItem('currentUser'));
    if (!currentUser || currentUser.role !== 'student') {
        window.location.href = '../../pages/auth/login.html';
        return;
    }
    
    // Datos de ejemplo (simulación)
    const studentData = {
        name: currentUser.name,
        grade: '3°A',
        average: 8.2,
        courses: [
            { name: 'Matemáticas', teacher: 'Prof. García', grade: 8.5, status: 'Aprobado' },
            { name: 'Lengua', teacher: 'Prof. Martínez', grade: 7.8, status: 'Aprobado' },
            { name: 'Física', teacher: 'Prof. López', grade: 6.9, status: 'Cursando' }
        ],
        attendance: '92%'
    };
    
    // Cargar información del estudiante
    document.getElementById('studentName').textContent = studentData.name;
    document.getElementById('studentGrade').textContent = studentData.grade;
    document.getElementById('studentAverage').textContent = studentData.average;
    document.getElementById('studentAttendance').textContent = studentData.attendance;
    
    // Cargar materias
    if (document.getElementById('coursesTable')) {
        renderCourses(studentData.courses);
    }
    
    // Funciones
    function renderCourses(courses) {
        const tableBody = document.querySelector('#coursesTable tbody');
        tableBody.innerHTML = '';
        
        courses.forEach(course => {
            const row = document.createElement('tr');
            
            row.innerHTML = `
                <td>${course.name}</td>
                <td>${course.teacher}</td>
                <td>${course.grade}</td>
                <td><span class="status-badge ${course.status.toLowerCase()}">${course.status}</span></td>
                <td>
                    <button class="button button-small" data-course="${course.name}">Detalles</button>
                </td>
            `;
            
            tableBody.appendChild(row);
        });
        
        // Botones de detalles
        document.querySelectorAll('.button-small').forEach(btn => {
            btn.addEventListener('click', function() {
                const courseName = this.getAttribute('data-course');
                alert(`Mostrando detalles de ${courseName}`);
                // En un sistema real, redirigiría a una página de detalles
            });
        });
    }
    
    // Descargar certificado
    const downloadBtn = document.getElementById('downloadCertificate');
    if (downloadBtn) {
        downloadBtn.addEventListener('click', function() {
            // Simular generación de certificado
            alert('Generando certificado... Esto puede tomar unos segundos.');
            setTimeout(() => {
                // Simular descarga
                const link = document.createElement('a');
                link.href = '#';
                link.download = `certificado_${currentUser.name}.pdf`;
                link.click();
                
                showAlert('Certificado descargado correctamente', 'success');
            }, 1500);
        });
    }
    
    function showAlert(message, type) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type}`;
        alertDiv.textContent = message;
        
        const container = document.querySelector('.student-container .container');
        container.prepend(alertDiv);
        
        setTimeout(() => {
            alertDiv.remove();
        }, 3000);
    }
});