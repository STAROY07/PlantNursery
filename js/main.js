// ==========================================
// 🌿 ONLINE PLANT NURSERY - CLIENT LOGIC
// ==========================================

// Global State Initializer
(function() {
    if (!localStorage.getItem('nursery_cart')) {
        localStorage.setItem('nursery_cart', JSON.stringify([]));
    }
    if (!localStorage.getItem('nursery_wishlist')) {
        localStorage.setItem('nursery_wishlist', JSON.stringify([]));
    }
    if (!localStorage.getItem('nursery_orders')) {
        localStorage.setItem('nursery_orders', JSON.stringify([
            {
                id: 'ORD-94821',
                date: new Date().toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric' }),
                items: [{ name: 'Snake Plant', price: 299, qty: 1, img: 'Images/airpurify/air_purify_1.jpeg' }],
                total: 299,
                status: 'In Transit',
                address: 'Plot 42, Sector 5, Kolkata, WB'
            }
        ]));
    }
})();

// Toast Notification
function showToast(message, type = 'success') {
    let toast = document.getElementById('nursery-toast');
    if (!toast) {
        toast = document.createElement('div');
        toast.id = 'nursery-toast';
        toast.style.cssText = `
            position: fixed;
            bottom: 25px;
            right: 25px;
            background: #2e7d32;
            color: white;
            padding: 14px 24px;
            border-radius: 8px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.3);
            font-family: 'Poppins', sans-serif;
            font-size: 15px;
            font-weight: 500;
            z-index: 99999;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            opacity: 0;
            transform: translateY(20px);
        `;
        document.body.appendChild(toast);
    }
    
    const icon = type === 'success' ? '✓' : 'ℹ';
    toast.style.background = type === 'success' ? '#2e7d32' : '#d32f2f';
    toast.innerHTML = `<span style="font-weight:bold; font-size:18px;">${icon}</span> ${message}`;
    toast.style.opacity = '1';
    toast.style.transform = 'translateY(0)';

    clearTimeout(window.toastTimer);
    window.toastTimer = setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateY(20px)';
    }, 3000);
}

// Update Badges
function updateBadges() {
    const cart = JSON.parse(localStorage.getItem('nursery_cart') || '[]');
    const wishlist = JSON.parse(localStorage.getItem('nursery_wishlist') || '[]');

    const totalCartCount = cart.reduce((sum, item) => sum + (item.qty || 1), 0);
    const totalWishCount = wishlist.length;

    document.querySelectorAll('#cart-badge, .cart-badge').forEach(el => {
        el.textContent = totalCartCount;
        el.style.display = totalCartCount > 0 ? 'inline-flex' : 'none';
    });

    document.querySelectorAll('#wishlist-badge, .wishlist-badge').forEach(el => {
        el.textContent = totalWishCount;
        el.style.display = totalWishCount > 0 ? 'inline-flex' : 'none';
    });
}

// Add To Cart
function addToCart(plant) {
    let cart = JSON.parse(localStorage.getItem('nursery_cart') || '[]');
    const existingIndex = cart.findIndex(item => item.name.toLowerCase() === plant.name.toLowerCase());

    if (existingIndex > -1) {
        cart[existingIndex].qty = (cart[existingIndex].qty || 1) + 1;
    } else {
        cart.push({
            id: plant.id || Date.now(),
            name: plant.name,
            price: parseInt(plant.price.replace(/[^\d]/g, '')) || 199,
            img: plant.img || 'Images/bg.jpg',
            qty: 1
        });
    }

    localStorage.setItem('nursery_cart', JSON.stringify(cart));
    updateBadges();
    showToast(`"${plant.name}" added to cart!`);
}

// Add To Wishlist
function addToWishlist(plant) {
    let wishlist = JSON.parse(localStorage.getItem('nursery_wishlist') || '[]');
    const exists = wishlist.some(item => item.name.toLowerCase() === plant.name.toLowerCase());

    if (!exists) {
        wishlist.push({
            id: plant.id || Date.now(),
            name: plant.name,
            price: plant.price,
            img: plant.img
        });
        localStorage.setItem('nursery_wishlist', JSON.stringify(wishlist));
        updateBadges();
        showToast(`"${plant.name}" added to wishlist!`);
    } else {
        showToast(`"${plant.name}" is already in your wishlist!`, 'info');
    }
}

// Live Search Filtering
function initLiveSearch() {
    const searchInputs = document.querySelectorAll('input[name="q"], input[type="search"], #search-box');
    searchInputs.forEach(input => {
        input.addEventListener('input', function(e) {
            const query = e.target.value.toLowerCase().trim();
            const cards = document.querySelectorAll('.plant-card, .card, .disease-card, .item');
            
            cards.forEach(card => {
                const text = card.innerText.toLowerCase();
                if (text.includes(query) || query === '') {
                    card.style.display = '';
                } else {
                    card.style.display = 'none';
                }
            });
        });

        const form = input.closest('form');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
            });
        }
    });
}

// Intercept Add-To-Cart & Add-To-Wishlist Forms
function initFormInterceptors() {
    document.addEventListener('submit', function(e) {
        const form = e.target;
        const action = form.getAttribute('action') || '';

        // Add to Cart
        if (action.includes('add_to_cart')) {
            e.preventDefault();
            const card = form.closest('.plant-card') || form.parentElement;
            const nameEl = card.querySelector('h3, h4, .plant-title');
            const priceEl = card.querySelector('.price, .plant-price, p');
            const imgEl = card.querySelector('img');

            const plant = {
                name: nameEl ? nameEl.innerText.trim() : 'Plant Item',
                price: priceEl ? priceEl.innerText.trim() : '₹199',
                img: imgEl ? imgEl.getAttribute('src') : 'Images/bg.jpg'
            };
            addToCart(plant);
            return;
        }

        // Add to Wishlist
        if (action.includes('add_to_wishlist')) {
            e.preventDefault();
            const card = form.closest('.plant-card') || form.parentElement;
            const nameEl = card.querySelector('h3, h4, .plant-title');
            const priceEl = card.querySelector('.price, .plant-price');
            const imgEl = card.querySelector('img');

            const plant = {
                name: nameEl ? nameEl.innerText.trim() : 'Plant Item',
                price: priceEl ? priceEl.innerText.trim() : '₹199',
                img: imgEl ? imgEl.getAttribute('src') : 'Images/bg.jpg'
            };
            addToWishlist(plant);
            return;
        }

        // Registration Form
        if (action.includes('Register') || action.includes('register_process')) {
            e.preventDefault();
            const nameInput = form.querySelector('input[name="name"]');
            const emailInput = form.querySelector('input[name="email"]');
            const passInput = form.querySelector('input[name="password"]');

            const user = {
                name: nameInput ? nameInput.value : 'User',
                email: emailInput ? emailInput.value : '',
                password: passInput ? passInput.value : ''
            };

            let users = JSON.parse(localStorage.getItem('nursery_users') || '[]');
            users.push(user);
            localStorage.setItem('nursery_users', JSON.stringify(users));
            localStorage.setItem('nursery_currentUser', JSON.stringify(user));

            showToast('Account created successfully! Redirecting...', 'success');
            setTimeout(() => {
                window.location.href = 'index.html';
            }, 1200);
            return;
        }

        // Login Form
        if (action.includes('login')) {
            e.preventDefault();
            const emailInput = form.querySelector('input[name="email"]');
            const passInput = form.querySelector('input[name="password"]');

            const email = emailInput ? emailInput.value : 'user@example.com';
            const user = {
                name: email.split('@')[0] || 'User',
                email: email
            };

            localStorage.setItem('nursery_currentUser', JSON.stringify(user));
            showToast('Login successful! Redirecting...', 'success');
            setTimeout(() => {
                window.location.href = 'index.html';
            }, 1000);
            return;
        }

        // Contact / Feedback Form
        if (action.includes('contact') || action.includes('support')) {
            e.preventDefault();
            showToast('Thank you! Your message has been sent successfully.', 'success');
            form.reset();
            return;
        }
    });
}

// Convert all `.php` links in documents to `.html` dynamically for safe navigation
function sanitizeLinks() {
    const linkMap = {
        'index.php': 'index.html',
        'plant_diseases_100.php': 'plant_diseases_100.html',
        'Flower_Plants_Shop.php': 'Flower_Plants_Shop.html',
        'Fruit_Plants_Shop.php': 'Fruit_Plants_Shop.html',
        'Medicinal_Plants_Shop.php': 'Medicinal_Plants_Shop.html',
        'air_purification_plants_detailed.php': 'air_purification_plants_detailed.html',
        'cart.php': 'cart_page.html',
        'cart_page.php': 'cart_page.html',
        'login.php': 'login.html',
        'Register.php': 'Register.html',
        'register.php': 'Register.html',
        'contact.php': 'contact.html',
        'orders.php': 'orders.html',
        'track_order.php': 'track_order.html',
        'profile.php': 'profile.html',
        'wishlist.php': 'wishlist.html',
        'logout.php': 'index.html'
    };

    document.querySelectorAll('a[href]').forEach(a => {
        let href = a.getAttribute('href');
        if (href) {
            for (const [phpFile, htmlFile] of Object.entries(linkMap)) {
                if (href.endsWith(phpFile) || href === phpFile) {
                    a.setAttribute('href', href.replace(phpFile, htmlFile));
                }
            }
        }
    });
}

// Render Dynamic Cart if on cart page
function renderCartPage() {
    const container = document.querySelector('.cart-container') || document.getElementById('cart-container');
    if (!container) return;

    const cart = JSON.parse(localStorage.getItem('nursery_cart') || '[]');

    if (cart.length === 0) {
        container.innerHTML = `
            <div style="text-align: center; padding: 50px 20px; background: white; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1);">
                <div style="font-size: 50px; color: #aaa; margin-bottom: 15px;"><i class="fa-solid fa-cart-shopping"></i></div>
                <h3 style="color: #333; margin-bottom: 10px;">Your Cart is Empty</h3>
                <p style="color: #777; margin-bottom: 25px;">Looks like you haven't added any beautiful plants to your cart yet.</p>
                <a href="Flower_Plants_Shop.html" style="display: inline-block; background: #2e7d32; color: white; padding: 12px 25px; border-radius: 8px; text-decoration: none; font-weight: 600;">Browse Plants</a>
            </div>
        `;
        return;
    }

    let total = 0;
    let itemsHtml = cart.map((item, index) => {
        const itemTotal = (item.price || 199) * (item.qty || 1);
        total += itemTotal;
        return `
            <div class="cart-item" style="display: flex; align-items: center; justify-content: space-between; background: white; border-radius: 10px; padding: 16px; margin-bottom: 14px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); flex-wrap: wrap; gap: 15px;">
                <img src="${item.img || 'Images/bg.jpg'}" alt="${item.name}" style="width: 85px; height: 85px; object-fit: cover; border-radius: 8px;" onerror="this.src='Images/bg.jpg'">
                <div class="cart-details" style="flex: 1; min-width: 180px;">
                    <h3 style="margin: 0 0 5px 0; color: #2e7d32;">${item.name}</h3>
                    <p style="margin: 0; color: #666; font-weight: 500;">₹${item.price} each</p>
                    <p style="margin: 4px 0 0 0; color: #333; font-weight: bold;">Subtotal: ₹${itemTotal}</p>
                </div>
                <div style="display: flex; align-items: center; gap: 8px;">
                    <button onclick="changeCartQty(${index}, -1)" style="width: 32px; height: 32px; border: 1px solid #ccc; background: #f5f5f5; border-radius: 6px; font-weight: bold; cursor: pointer;">-</button>
                    <span style="font-weight: bold; min-width: 24px; text-align: center;">${item.qty || 1}</span>
                    <button onclick="changeCartQty(${index}, 1)" style="width: 32px; height: 32px; border: 1px solid #ccc; background: #f5f5f5; border-radius: 6px; font-weight: bold; cursor: pointer;">+</button>
                </div>
                <button onclick="removeFromCart(${index})" style="background: #ff4757; color: white; border: none; padding: 8px 14px; border-radius: 6px; cursor: pointer; font-weight: 500;"><i class="fa-solid fa-trash"></i> Remove</button>
            </div>
        `;
    }).join('');

    container.innerHTML = `
        ${itemsHtml}
        <div class="cart-summary" style="background: white; padding: 22px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); margin-top: 25px; text-align: right;">
            <div style="font-size: 1.3rem; margin-bottom: 8px; color: #555;">Subtotal: <strong style="color: #2e7d32;">₹${total}</strong></div>
            <div style="font-size: 0.95rem; color: #888; margin-bottom: 18px;">Shipping: <strong style="color: #2e7d32;">FREE</strong></div>
            <h2 style="color: #2e7d32; margin: 0 0 20px 0; font-size: 1.8rem;">Total: ₹${total}</h2>
            <button onclick="handleCheckout(${total})" style="background: linear-gradient(45deg, #2e7d32, #4caf50); color: white; border: none; padding: 14px 32px; border-radius: 8px; font-size: 1.1rem; font-weight: bold; cursor: pointer; box-shadow: 0 4px 15px rgba(46,125,50,0.35);"><i class="fa-solid fa-bag-shopping"></i> Proceed to Checkout</button>
        </div>
    `;
}

window.changeCartQty = function(index, delta) {
    let cart = JSON.parse(localStorage.getItem('nursery_cart') || '[]');
    if (cart[index]) {
        cart[index].qty = (cart[index].qty || 1) + delta;
        if (cart[index].qty <= 0) {
            cart.splice(index, 1);
        }
        localStorage.setItem('nursery_cart', JSON.stringify(cart));
        updateBadges();
        renderCartPage();
    }
};

window.removeFromCart = function(index) {
    let cart = JSON.parse(localStorage.getItem('nursery_cart') || '[]');
    cart.splice(index, 1);
    localStorage.setItem('nursery_cart', JSON.stringify(cart));
    updateBadges();
    renderCartPage();
    showToast('Item removed from cart');
};

window.handleCheckout = function(total) {
    const address = prompt('Enter your Delivery Address for Order Placement:', 'House No. 12, Green Avenue, New Delhi');
    if (!address) return;

    const cart = JSON.parse(localStorage.getItem('nursery_cart') || '[]');
    const newOrder = {
        id: 'ORD-' + Math.floor(100000 + Math.random() * 900000),
        date: new Date().toLocaleDateString('en-IN', { day: 'numeric', month: 'short', year: 'numeric' }),
        items: cart,
        total: total,
        status: 'Order Placed',
        address: address
    };

    let orders = JSON.parse(localStorage.getItem('nursery_orders') || '[]');
    orders.unshift(newOrder);
    localStorage.setItem('nursery_orders', JSON.stringify(orders));

    localStorage.setItem('nursery_cart', JSON.stringify([]));
    updateBadges();
    renderCartPage();

    alert(`🎉 Order Placed Successfully!\n\nOrder ID: ${newOrder.id}\nAmount: ₹${total}\nEstimated Delivery: 3-5 Days`);
    window.location.href = 'orders.html';
};

// Document Loaded
document.addEventListener('DOMContentLoaded', function() {
    updateBadges();
    initFormInterceptors();
    initLiveSearch();
    sanitizeLinks();
    renderCartPage();
});
