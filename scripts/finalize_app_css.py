from pathlib import Path
import re

root = Path(__file__).resolve().parent.parent
css_path = root / 'resources' / 'css' / 'app.css'
text = css_path.read_text(encoding='utf-8')

# Remove duplicate first theme block, keep the later dark theme definitions.
# Find the first two :root occurrences after the initial metadata.
root_positions = [m.start() for m in re.finditer(r'^:root\s*\{', text, flags=re.M)]
if len(root_positions) >= 2:
    first_start = root_positions[0]
    second_start = root_positions[1]
    # Keep the initial header content and the second theme block plus everything after it
    header = text[:first_start]
    tail = text[second_start:]
    # Remove any duplicate comment markers if they are repeated
    if header.endswith('\n\n') and tail.startswith('\n'):
        tail = tail.lstrip('\n')
    new_text = header + tail
    text = new_text

# Add global SweetAlert + custom modal styles if missing
swal_css = """
/* Global SweetAlert2 custom styles */
.swal-animate { animation: popin .32s cubic-bezier(.2,.9,.4,1); }
@keyframes popin { from { transform: translateY(8px) scale(.98); opacity: 0 } to { transform: translateY(0) scale(1); opacity: 1 } }
.swal-custom-popup { border-radius: 16px; box-shadow: 0 18px 40px rgba(2,6,23,0.18); }
.swal-custom-title { font-size: 1.25rem; color: #0f172a; margin-bottom: 0.5rem; }
.swal-custom-content { color: #374151; }
.swal-json-content pre, .swal-json-pre { text-align: left; white-space: pre-wrap; word-break: break-word; background: #f8f9fa; padding: 16px; border-radius: 12px; color: #111827; margin: 0; }
.swal-payment-box { display: flex; gap: 14px; align-items: center; }
.swal-payment-img { flex: 0 0 auto; width: 110px; height: 110px; border-radius: 14px; box-shadow: 0 6px 18px rgba(2,6,23,.08); object-fit: cover; }
.swal-payment-body { text-align: left; }
.swal-payment-body strong { display: block; margin-top: 0.5rem; }
.swal-payment-detail { color: #6b7280; margin-top: 8px; }
.swal-payment-total { margin-top: 10px; font-size: 1rem; color: #111827; font-weight: 700; }
.swal-payment-btn { border-radius: 12px; padding: 10px 18px; font-weight: 600; }
.swal-payment-btn-primary { background: linear-gradient(90deg,#0d6efd,#0b5ed7); color:#fff; border:none; }
.swal-payment-btn-secondary { background:#f3f4f6; color:#374151; border:none; }
"""
if '/* Global SweetAlert2 custom styles */' not in text:
    text = text.rstrip() + '\n\n' + swal_css.strip() + '\n'

css_path.write_text(text, encoding='utf-8')
print('app.css finalized')
