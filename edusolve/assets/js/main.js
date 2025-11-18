// assets/js/main.js

document.addEventListener('DOMContentLoaded', function() {
    // Menú móvil
    const mobileBtn = document.querySelector('.mobile-menu-btn');
    const nav = document.querySelector('.nav');
    
    if (mobileBtn && nav) {
        mobileBtn.addEventListener('click', function() {
            this.classList.toggle('active');
            nav.classList.toggle('active');
            this.setAttribute('aria-expanded', this.classList.contains('active'));
        });
    }
    
    // Cerrar menú al hacer clic en un enlace (solo móvil)
    const navLinks = document.querySelectorAll('.nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth <= 768 && mobileBtn && nav) {
                mobileBtn.classList.remove('active');
                nav.classList.remove('active');
                mobileBtn.setAttribute('aria-expanded', 'false');
            }
        });
    });
    
    // Efecto de scroll en el header
    const header = document.querySelector('.header');
    if (header) {
        window.addEventListener('scroll', function() {
            if (window.scrollY > 50) {
                header.classList.add('scrolled');
            } else {
                header.classList.remove('scrolled');
            }
        });
    }
    
    // Cargar usuario actual en páginas protegidas
    const currentUser = JSON.parse(localStorage.getItem('currentUser'));
    if (currentUser) {
        const userElements = document.querySelectorAll('.current-user');
        userElements.forEach(el => {
            el.textContent = currentUser.name;
        });
        
        const logoutBtn = document.getElementById('logoutBtn');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', function() {
                localStorage.removeItem('currentUser');
                window.location.href = '../../pages/auth/login.html';
            });
        }
    }
    
    // Tooltips básicos
    const tooltipElements = document.querySelectorAll('[data-tooltip]');
    tooltipElements.forEach(el => {
        el.addEventListener('mouseenter', showTooltip);
        el.addEventListener('mouseleave', hideTooltip);
    });
    
    function showTooltip(e) {
        const tooltipText = this.getAttribute('data-tooltip');
        const tooltip = document.createElement('div');
        tooltip.className = 'tooltip';
        tooltip.textContent = tooltipText;
        
        document.body.appendChild(tooltip);
        
        const rect = this.getBoundingClientRect();
        tooltip.style.top = `${rect.top - tooltip.offsetHeight - 10}px`;
        tooltip.style.left = `${rect.left + rect.width / 2 - tooltip.offsetWidth / 2}px`;
    }
    
    function hideTooltip() {
        const tooltip = document.querySelector('.tooltip');
        if (tooltip) {
            tooltip.remove();
        }
    }
});