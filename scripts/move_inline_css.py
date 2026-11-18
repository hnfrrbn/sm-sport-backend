from pathlib import Path

layout = Path('resources/views/layouts/app.blade.php')
app_css = Path('resources/css/app.css')
text = layout.read_text(encoding='utf-8')
start = text.find('<style>')
end = text.find('</style>', start)
if start == -1 or end == -1:
    raise SystemExit('Style block not found')
css_block = text[start + len('<style>'):end]
new_layout = text[:start] + text[end + len('</style>'):]
marker = '<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>'
pos = new_layout.find(marker)
if pos != -1:
    insert_pos = pos + len(marker) + 1
    new_layout = new_layout[:insert_pos] + "    @vite(['resources/css/app.css', 'resources/js/app.js'])\n" + new_layout[insert_pos:]
else:
    title_end = new_layout.find('</title>')
    insert_pos = title_end + len('</title>\n') if title_end != -1 else 0
    new_layout = new_layout[:insert_pos] + "    @vite(['resources/css/app.css', 'resources/js/app.js'])\n" + new_layout[insert_pos:]
layout.write_text(new_layout, encoding='utf-8')
app_text = app_css.read_text(encoding='utf-8')
if css_block.strip() not in app_text:
    app_css.write_text(app_text.rstrip() + '\n\n' + css_block.lstrip(), encoding='utf-8')
print('done')
