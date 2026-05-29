import re

with open('resources/views/agent/ticket-detail.blade.php', 'r', encoding='utf-8') as f:
    content = f.read()

# find where the form grids start
start_idx = content.find('<div class="form-grid">')
end_idx = content.find('<div class="form-group">\n                        <label class="form-label">Deskripsi</label>')

grids_area = content[start_idx:end_idx]

# We can match all <div class="form-group"> ... </div>
# Since there are nested divs, regex might be tricky, but here form-group has no inner divs.
pattern = re.compile(r'<div class="form-group">.*?</div>', re.DOTALL)
groups = pattern.findall(grids_area)

print(f"Found {len(groups)} form groups")

# Identify them by their name attribute
order = [
    'resolved_by_agent',
    'eksalasiVia',
    'contact',
    'reasonnoODS',
    'responBE'
]

group_map = {}
for g in groups:
    # extract name="something"
    m = re.search(r'name="([^"]+)"', g)
    if m:
        name = m.group(1)
        group_map[name] = g
    else:
        print("Could not find name in:", g[:50])

ordered_groups = []
# 1. first the ones from user
for o in order:
    if o in group_map:
        ordered_groups.append(group_map[o])
        del group_map[o]

# 2. then the rest
for k, v in group_map.items():
    ordered_groups.append(v)

# Now rebuild the form-grid wrappers. Each takes 4 items.
new_grids_area = ""
for i in range(0, len(ordered_groups), 4):
    chunk = ordered_groups[i:i+4]
    new_grids_area += "                    <div class=\"form-grid\">\n"
    for g in chunk:
        # indent each line of the group by 24 spaces (or let's just replace the exact padding)
        # the original g might already have some indentation. Let's see.
        padded_g = "\n".join("                        " + line.strip() if line.strip() else "" for line in g.split("\n"))
        # fix the first line which doesn't need "                        " if it was already stripped, but let's just do it manually
        
        # better: just append the raw g, as it already has the 24 space indentation except the first `<div class="form-group">` maybe.
        # Actually in the original, `<div class="form-group">` is indented 24 spaces.
        new_grids_area += "                        " + g.strip().replace("\n", "\n                        ") + "\n"
    new_grids_area += "                    </div>\n\n"

# replace in content
new_content = content[:start_idx] + new_grids_area + content[end_idx:]

with open('resources/views/agent/ticket-detail.blade.php', 'w', encoding='utf-8') as f:
    f.write(new_content)

print("Reordered successfully!")
