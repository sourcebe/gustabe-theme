import re
import os
import base64
from fontTools.subset import Subsetter, Options, load_font, save_font

def build_inline_icons():
    # 1. Read purged CSS to find which icons are used
    css_path = 'assets/huge-icons/huge-icons-purged.min.css'
    if not os.path.exists(css_path):
        css_path = 'huge-icons/huge-icons-purged.min.css'
        
    with open(css_path, 'r', encoding='utf-8') as f:
        css = f.read()

    # Find all hex unicodes like \e970, \ea28, etc.
    unicodes = [int(x, 16) for x in re.findall(r'content:"\\([0-9a-fA-F]+)"', css)]
    print(f"[build-icons] Found {len(unicodes)} icons in purged CSS")

    # 2. Subset the font to WOFF2
    options = Options()
    options.flavor = 'woff2'
    
    woff_source = 'assets/huge-icons/huge-icons.woff'
    if not os.path.exists(woff_source):
        woff_source = 'huge-icons/huge-icons.woff'
        
    font = load_font(woff_source, options)
    subsetter = Subsetter(options=options)
    subsetter.populate(unicodes=unicodes)
    subsetter.subset(font)
    
    temp_woff2 = 'assets/huge-icons/huge-icons-subset.woff2'
    save_font(font, temp_woff2, options)
    
    woff2_size = os.path.getsize(temp_woff2)
    print(f"[build-icons] Subset WOFF2 font size: {woff2_size} bytes ({woff2_size/1024:.2f} KB)")

    # 3. Base64 encode
    with open(temp_woff2, 'rb') as f:
        b64 = base64.b64encode(f.read()).decode('ascii')
    
    # 4. Replace @font-face src with Base64 data URI
    # Pattern matches: src:url("...") format("..."),...;
    new_src = f'src:url("data:font/woff2;base64,{b64}") format("woff2");'
    
    css_inlined = re.sub(r'src:[^;]+;', new_src, css)
    
    # Save to both locations for safety
    targets = [
        'assets/huge-icons/huge-icons-purged.min.css',
        'huge-icons/huge-icons-purged.min.css'
    ]
    for target in targets:
        os.makedirs(os.path.dirname(target), exist_ok=True)
        with open(target, 'w', encoding='utf-8') as f:
            f.write(css_inlined)
        print(f"[build-icons] Saved inlined CSS to {target} ({os.path.getsize(target)/1024:.2f} KB)")
        
    # Clean up temp
    if os.path.exists(temp_woff2):
        os.remove(temp_woff2)

if __name__ == '__main__':
    build_inline_icons()
