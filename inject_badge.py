import glob

files = [
    'c:/xampp/htdocs/plant_nursery/Flower_Plants_Shop.php',
    'c:/xampp/htdocs/plant_nursery/Fruit_Plants_Shop.php',
    'c:/xampp/htdocs/plant_nursery/Medicinal_Plants_Shop.php',
    'c:/xampp/htdocs/plant_nursery/air_purification_plants_detailed.php',
    'c:/xampp/htdocs/plant_nursery/contact.php',
    'c:/xampp/htdocs/plant_nursery/plant_diseases_100.php',
]

for filepath in files:
    with open(filepath, 'r', encoding='utf-8', errors='ignore') as f:
        content = f.read()

    a_tag = '<a href="wishlist.php" class="wishlist-icon-btn" title="My Wishlist">'
    if a_tag in content and 'id="wishlist-badge"' not in content:
        content = content.replace(a_tag, a_tag + '\n      <span id="wishlist-badge" class="wishlist-badge" style="display:none;">0</span>')
        
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)
        print("Updated", filepath)
    else:
        print("Skipped", filepath)
