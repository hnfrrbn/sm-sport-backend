from pathlib import Path
import re

root = Path(__file__).resolve().parent.parent
blade = root / 'resources' / 'views' / 'layouts' / 'app.blade.php'
css = root / 'resources' / 'css' / 'app.css'

text = blade.read_text(encoding='utf-8')
m = re.search(r'<style>(.*?)</style>', text, flags=re.S)
if not m:
    raise SystemExit('No <style> block found in layout.')

style = m.group(1).strip()

new_blade = text[:m.start()] + text[m.end():]
blade.write_text(new_blade, encoding='utf-8')

css_text = css.read_text(encoding='utf-8').rstrip() + '\n\n/* Global layout styles extracted from resources/views/layouts/app.blade.php */\n' + style + '\n'
css.write_text(css_text, encoding='utf-8')

print('CSS extraction successful')
