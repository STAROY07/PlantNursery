import os
import glob

svg_btn = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 22px; height: 22px;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>'
svg_text = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" style="width: 18px; height: 18px; margin-right: 5px; vertical-align: text-bottom;"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>'

# Process root PHP files
for file in glob.glob('c:/xampp/htdocs/plant_nursery/*.php') + glob.glob('c:/xampp/htdocs/plant_nursery/**/*.php', recursive=True):
    try:
        with open(file, 'r', encoding='utf-8') as f:
            content = f.read()
        
        if '👤' in content:
            # Replace specifically in the button
            content = content.replace('>👤</span>', f'>{svg_btn}</span>')
            # Replace remaining occurrences
            content = content.replace('👤', svg_text)
            
            with open(file, 'w', encoding='utf-8') as f:
                f.write(content)
            print(f"Updated {file}")
    except Exception as e:
        print(f"Error reading {file}: {e}")

print("Done")
