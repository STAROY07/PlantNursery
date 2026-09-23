import os, re

def convert_php_to_html(php_path, html_path):
    if not os.path.exists(php_path):
        print(f"Skipping {php_path} (not found)")
        return

    with open(php_path, "r", encoding="utf-8", errors="ignore") as f:
        content = f.read()

    # Remove PHP code
    content = re.sub(r"<\?php.*?\?>", "", content, flags=re.DOTALL)

    # Replace links
    replacements = {
        "index.php": "index.html",
        "plant_diseases_100.php": "plant_diseases_100.html",
        "Flower_Plants_Shop.php": "Flower_Plants_Shop.html",
        "Fruit_Plants_Shop.php": "Fruit_Plants_Shop.html",
        "Medicinal_Plants_Shop.php": "Medicinal_Plants_Shop.html",
        "air_purification_plants_detailed.php": "air_purification_plants_detailed.html",
        "cart.php": "cart_page.html",
        "login.php": "login.html",
        "Register.php": "Register.html",
        "register.php": "Register.html",
        "contact.php": "contact.html",
        "orders.php": "orders.html",
        "track_order.php": "track_order.html",
        "profile.php": "profile.html",
        "wishlist.php": "wishlist.html",
        "logout.php": "index.html",
        "images/": "Images/"
    }

    for k, v in replacements.items():
        content = content.replace(k, v)

    # Embed Font Awesome and main.js
    fa_and_script = '''
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="js/main.js"></script>
    '''

    if "js/main.js" not in content:
        if "</body>" in content:
            content = content.replace("</body>", f"{fa_and_script}\n</body>")
        else:
            content += f"\n{fa_and_script}\n"

    with open(html_path, "w", encoding="utf-8") as f:
        f.write(content)
    print(f"Converted {php_path} -> {html_path} ({len(content)} bytes)")

files = [
    ("plant_diseases_100.php", "plant_diseases_100.html"),
    ("Flower_Plants_Shop.php", "Flower_Plants_Shop.html"),
    ("Fruit_Plants_Shop.php", "Fruit_Plants_Shop.html"),
    ("Medicinal_Plants_Shop.php", "Medicinal_Plants_Shop.html"),
    ("air_purification_plants_detailed.php", "air_purification_plants_detailed.html"),
    ("contact.php", "contact.html"),
    ("orders.php", "orders.html"),
    ("track_order.php", "track_order.html"),
    ("profile.php", "profile.html"),
    ("wishlist.php", "wishlist.html")
]

for php_f, html_f in files:
    convert_php_to_html(php_f, html_f)
