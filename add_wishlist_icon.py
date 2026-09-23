"""
Force inject wishlist HTML before profile-icon-wrap div in all shop pages.
"""

files = [
    'c:/xampp/htdocs/plant_nursery/Flower_Plants_Shop.php',
    'c:/xampp/htdocs/plant_nursery/Fruit_Plants_Shop.php',
    'c:/xampp/htdocs/plant_nursery/Medicinal_Plants_Shop.php',
    'c:/xampp/htdocs/plant_nursery/air_purification_plants_detailed.php',
    'c:/xampp/htdocs/plant_nursery/contact.php',
    'c:/xampp/htdocs/plant_nursery/plant_diseases_100.php',
]

wishlist_html = '  <!-- Wishlist Icon -->\r\n  <div class="wishlist-icon-wrap">\r\n    <a href="wishlist.php" class="wishlist-icon-btn" title="My Wishlist">\r\n      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width:22px;height:22px;"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg>\r\n    </a>\r\n  </div>\r\n'

target = '  <!-- Profile Icon Top Right -->\r\n  <div class="profile-icon-wrap" id="profileWrap">'

for filepath in files:
    try:
        with open(filepath, 'r', encoding='utf-8') as f:
            content = f.read()

        # Check if wishlist div is already in the HTML body (not just CSS)
        if 'class="wishlist-icon-wrap">' in content:
            print(f"  Already has wishlist HTML: {filepath}")
            continue

        if target in content:
            content = content.replace(
                target,
                wishlist_html + '  <!-- Profile Icon Top Right -->\r\n  <div class="profile-icon-wrap" id="profileWrap">',
                1
            )
            with open(filepath, 'w', encoding='utf-8', errors='ignore') as f:
                f.write(content)
            print(f"  Injected wishlist HTML: {filepath}")
        else:
            print(f"  Target not found: {filepath}")

    except Exception as e:
        print(f"  ERROR {filepath}: {e}")

print("\nDone!")
