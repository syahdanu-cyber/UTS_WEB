/**
 * Script untuk Aplikasi Edukasi Lingkungan
 */

// Konfirmasi hapus dengan pesan custom
function confirmDelete(message = 'Apakah Anda yakin ingin menghapus data ini?') {
    return confirm(message);
}

// Validasi form sebelum submit
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return true;
    
    const inputs = form.querySelectorAll('input[required], textarea[required], select[required]');
    let isValid = true;
    
    inputs.forEach(input => {
        if (!input.value.trim()) {
            input.style.borderColor = '#e74c3c';
            isValid = false;
            
            // Tambah pesan error
            if (!input.nextElementSibling || !input.nextElementSibling.classList.contains('error-message')) {
                const errorMsg = document.createElement('small');
                errorMsg.className = 'error-message';
                errorMsg.style.color = '#e74c3c';
                errorMsg.textContent = 'Field ini wajib diisi';
                input.parentNode.insertBefore(errorMsg, input.nextSibling);
            }
        } else {
            input.style.borderColor = '#ddd';
            
            // Hapus pesan error jika ada
            if (input.nextElementSibling && input.nextElementSibling.classList.contains('error-message')) {
                input.nextElementSibling.remove();
            }
        }
    });
    
    if (!isValid) {
        alert('Mohon lengkapi semua field yang wajib diisi!');
    }
    
    return isValid;
}

// Auto hide alerts setelah 5 detik
document.addEventListener('DOMContentLoaded', function() {
    const alerts = document.querySelectorAll('.alert');
    
    alerts.forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.3s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
});

// Preview gambar sebelum upload
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const preview = document.getElementById('image-preview');
            if (preview) {
                preview.src = e.target.result;
                preview.style.display = 'block';
                preview.style.maxWidth = '300px';
                preview.style.marginTop = '1rem';
                preview.style.borderRadius = '5px';
            }
        };
        
        reader.readAsDataURL(input.files[0]);
    }
}

// Smooth scroll untuk anchor links
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Konfirmasi sebelum logout
function confirmLogout() {
    return confirm('Apakah Anda yakin ingin keluar?');
}

// Character counter untuk textarea
function setupCharCounter() {
    const textareas = document.querySelectorAll('textarea[data-maxlength]');
    
    textareas.forEach(textarea => {
        const maxLength = textarea.getAttribute('data-maxlength');
        const counter = document.createElement('div');
        counter.className = 'char-counter';
        counter.style.textAlign = 'right';
        counter.style.fontSize = '0.9rem';
        counter.style.color = '#7f8c8d';
        counter.style.marginTop = '0.5rem';
        
        textarea.parentNode.insertBefore(counter, textarea.nextSibling);
        
        function updateCounter() {
            const remaining = maxLength - textarea.value.length;
            counter.textContent = `${textarea.value.length} / ${maxLength} karakter`;
            counter.style.color = remaining < 50 ? '#e74c3c' : '#7f8c8d';
        }
        
        textarea.addEventListener('input', updateCounter);
        updateCounter();
    });
}

// Init saat DOM ready
document.addEventListener('DOMContentLoaded', setupCharCounter);

// Disable button setelah submit untuk mencegah double submit
document.querySelectorAll('form').forEach(form => {
    form.addEventListener('submit', function(e) {
        const submitBtn = this.querySelector('button[type="submit"]');
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Memproses...';
            
            // Re-enable setelah 3 detik (jika form gagal submit)
            setTimeout(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = submitBtn.getAttribute('data-original-text') || 'Submit';
            }, 3000);
        }
    });
});