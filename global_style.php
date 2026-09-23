<style>

/* Reset */
html, body {
    height: 100%;
    margin: 0;
    padding: 0;
}

/* 🌿 Full Background */
body {
    font-family: 'Poppins', sans-serif;

    background: url('images/bg.jpg') no-repeat center center fixed;
    background-size: cover;
}

/* 🌿 Dark Overlay (fixed properly) */
body::before {
    content: "";
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;

    background: rgba(0, 50, 0, 0.4);

    z-index: -1;
    pointer-events: none; /* 🔥 important */
}

/* 🌿 Ensure full page height */
.main-content {
    min-height: 100vh;
    position: relative;
    z-index: 1;
}

/* 🌿 Smooth scroll (premium feel) */
html {
    scroll-behavior: smooth;
}

/* 🌿 Optional: Glass effect (use anywhere) */
.glass {
    background: rgba(255,255,255,0.15);
    backdrop-filter: blur(8px);
    border-radius: 12px;
    padding: 20px;
}

/* 🌿 Optional: Fade animation */
.fade-in {
    animation: fadeIn 1.2s ease-in-out;
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.wishlist-icon-btn {
    position: relative; /* for absolute badge */
}
.wishlist-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #ff4757;
    color: white;
    font-size: 11px;
    font-weight: bold;
    padding: 2px 6px;
    border-radius: 10px;
    box-shadow: 0 2px 5px rgba(0,0,0,0.2);
    align-items: center;
    justify-content: center;
}
</style>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const badge = document.getElementById('wishlist-badge');
    
    // Fetch initial count
    if (badge) {
        fetch('get_wishlist_count.php')
            .then(response => response.json())
            .then(data => {
                if (data.count > 0) {
                    badge.textContent = data.count;
                    badge.style.display = 'flex';
                }
            })
            .catch(error => console.error('Error fetching wishlist count:', error));
    }

    // Intercept Add to Wishlist forms
    const wishlistForms = document.querySelectorAll('form[action="add_to_wishlist.php"]');
    wishlistForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            
            fetch('add_to_wishlist_ajax.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Update badge
                    if (badge && data.count > 0) {
                        badge.textContent = data.count;
                        badge.style.display = 'flex';
                    }
                    
                    // Show a quick visual feedback on the button
                    const btn = this.querySelector('button[type="submit"]');
                    if (btn) {
                        const originalText = btn.innerHTML;
                        btn.innerHTML = '❤️ Added!';
                        btn.style.background = '#4CAF50'; // Optional success color
                        setTimeout(() => {
                            btn.innerHTML = originalText;
                            btn.style.background = '';
                        }, 1500);
                    }
                } else if (data.status === 'error') {
                    if (data.message === "Please login first.") {
                        window.location.href = "login.php";
                    } else {
                        alert(data.message);
                    }
                }
            })
            .catch(error => console.error('Error adding to wishlist:', error));
        });
    });
});
</script>