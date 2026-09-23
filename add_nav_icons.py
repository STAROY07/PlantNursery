import re

# The shop pages nav block - same links, same replacements
shop_files = [
    'c:/xampp/htdocs/plant_nursery/Flower_Plants_Shop.php',
    'c:/xampp/htdocs/plant_nursery/Fruit_Plants_Shop.php',
    'c:/xampp/htdocs/plant_nursery/Medicinal_Plants_Shop.php',
    'c:/xampp/htdocs/plant_nursery/air_purification_plants_detailed.php',
    'c:/xampp/htdocs/plant_nursery/contact.php',
]

# plant_diseases_100.php has slightly different nav links
diseases_file = 'c:/xampp/htdocs/plant_nursery/plant_diseases_100.php'

# Common shop page nav replacements
shop_replacements = [
    # Home
    ('<a href="index.php">Home</a>',
     '<a href="index.php">🏠 Home</a>'),
    # Flower Plants
    ('<a href="Flower_Plants_Shop.php">Flower Plants</a>',
     '<a href="Flower_Plants_Shop.php">🌸 Flower Plants</a>'),
    # Fruit Plants
    ('<a href="Fruit_Plants_Shop.php">Fruit Plants</a>',
     '<a href="Fruit_Plants_Shop.php">🍋 Fruit Plants</a>'),
    # Medicinal Plants
    ('<a href="Medicinal_Plants_Shop.php">Medicinal Plants</a>',
     '<a href="Medicinal_Plants_Shop.php">🌿 Medicinal Plants</a>'),
    # Air Purifying Plants
    ('<a href="air_purification_plants_detailed.php">Air Purifying Plants</a>',
     '<a href="air_purification_plants_detailed.php">💨 Air Purifying Plants</a>'),
    # Air Plants (contact.php uses shorter label)
    ('<a href="air_purification_plants_detailed.php">Air Plants</a>',
     '<a href="air_purification_plants_detailed.php">💨 Air Plants</a>'),
    # Cart
    ('<a href="cart.php">Cart</a>',
     '<a href="cart.php">🛒 Cart</a>'),
    # Help Center
    ('<a href="help_center/help_center.php">Help Center</a>',
     '<a href="help_center/help_center.php">🛠️ Help Center</a>'),
]

# Diseases page specific nav replacements
diseases_replacements = [
    ('<a href="index.php">Home</a>',
     '<a href="index.php">🏠 Home</a>'),
    ('<a href="Flower_Plants_Shop.php">Flower Plants</a>',
     '<a href="Flower_Plants_Shop.php">🌸 Flower Plants</a>'),
    ('<a href="Fruit_Plants_Shop.php">Fruit Plants</a>',
     '<a href="Fruit_Plants_Shop.php">🍋 Fruit Plants</a>'),
    ('<a href="Medicinal_Plants_Shop.php">Medicinal Plants</a>',
     '<a href="Medicinal_Plants_Shop.php">🌿 Medicinal Plants</a>'),
    ('<a href="air_purification_plants_detailed.php">AirPurify Plants</a>',
     '<a href="air_purification_plants_detailed.php">💨 AirPurify Plants</a>'),
    ('<a href="plant_diseases_100.php">Diseases</a>',
     '<a href="plant_diseases_100.php">🦠 Diseases</a>'),
    ('<a href="contact.php">Contact</a>',
     '<a href="contact.php">💬 Contact</a>'),
    ('<a href="help_center/help_center.php">Help Center</a>',
     '<a href="help_center/help_center.php">🛠️ Help Center</a>'),
]

def apply_replacements(filepath, replacements):
    with open(filepath, 'r', encoding='utf-8') as f:
        content = f.read()
    changed = False
    for old, new in replacements:
        if old in content:
            content = content.replace(old, new)
            changed = True
            print(f"  Replaced: {old[:50]!r}")
        else:
            print(f"  SKIPPED (not found): {old[:50]!r}")
    if changed:
        with open(filepath, 'w', encoding='utf-8') as f:
            f.write(content)
        print(f"Saved {filepath}")
    else:
        print(f"No changes for {filepath}")

print("=== Shop pages ===")
for fp in shop_files:
    print(f"\n>> {fp}")
    apply_replacements(fp, shop_replacements)

print(f"\n=== Diseases page ===")
print(f"\n>> {diseases_file}")
apply_replacements(diseases_file, diseases_replacements)

print("\nAll done!")
