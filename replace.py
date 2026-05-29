import sys

filepath = 'resources/views/agent/ticket-detail.blade.php'
with open(filepath, 'r', encoding='utf-8') as f:
    content = f.read()

content = content.replace("{{ $ticket->condition == 'Closed' ? 'disabled' : '' }}", "{{ (!$canEdit || $ticket->condition == 'Closed') ? 'disabled' : '' }}")

with open(filepath, 'w', encoding='utf-8') as f:
    f.write(content)
