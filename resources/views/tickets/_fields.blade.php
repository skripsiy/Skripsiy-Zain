@php
    $fieldDisabled = isset($isDisabled) ? $isDisabled : ((!$canEdit || $ticket->condition == 'Closed') ? 'disabled' : '');
@endphp

{{-- Row 1: Resume + Eksalasi via --}}
<div class="form-grid">
    <div class="form-group">
        <label class="form-label">Resume</label>
        <input type="text" name="resume" class="form-input"
            value="{{ old('resume', $ticket->resume) }}"
            placeholder="Masukkan resume"
            {{ $fieldDisabled }}>
    </div>
    <div class="form-group">
        <label class="form-label">Eskalasi via</label>
        <select name="eksalasiVia" class="form-select" {{ $fieldDisabled }} {{ (old('eksalasiTicket', $ticket->eksalasiTicket) !== 'Yes') ? 'disabled' : '' }}>
            <option value="">Select</option>
            @foreach (config('tickets.eksalasiVia') as $key => $val)
                <option value="{{ $key }}" @selected(old('eksalasiVia', $ticket->eksalasiVia) === $key)>{{ $val }}</option>
            @endforeach
        </select>
    </div>
</div>

{{-- Row 2: Status Call + Reason Not ODS --}}
<div class="form-grid">
    <div class="form-group">
        <label class="form-label">Status Call (Kontak)</label>
        <select name="contact" class="form-select" {{ $fieldDisabled }}>
            <option value="">Select</option>
            @foreach (config('tickets.contact') as $key => $val)
                <option value="{{ $key }}" @selected(old('contact', $ticket->contact) === $key)>{{ $val }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Reason Not ODS</label>
        <select name="reasonnoODS" class="form-select" {{ $fieldDisabled }}>
            <option value="">Select</option>
            @foreach (config('tickets.reasonnoODS') as $key => $val)
                <option value="{{ $key }}" @selected(old('reasonnoODS', $ticket->reasonnoODS) === $key)>{{ $val }}</option>
            @endforeach
        </select>
    </div>
</div>

{{-- Row 3: Respon Backend + Classification --}}
<div class="form-grid">
    <div class="form-group">
        <label class="form-label">Respon Backend</label>
        <select name="responBE" class="form-select" {{ $fieldDisabled }}>
            <option value="">Select</option>
            @foreach (config('tickets.responBE') as $key => $val)
                <option value="{{ $key }}" @selected(old('responBE', $ticket->responBE) === $key)>{{ $val }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Classification</label>
        <select name="klasifikasi" class="form-select" {{ $fieldDisabled }}>
            <option value="">Select</option>
            @foreach (config('tickets.klasifikasi') as $key => $val)
                <option value="{{ $key }}" @selected(old('klasifikasi', $ticket->klasifikasi) === $key)>{{ $val }}</option>
            @endforeach
        </select>
    </div>
</div>

{{-- Row 4: Topic + Topic Detail + No SC + Status SC --}}
<div class="form-grid-4">
    <div class="form-group">
        <label class="form-label">Topic</label>
        <select name="topic" class="form-select" {{ $fieldDisabled }}>
            <option value="">Select</option>
            @foreach (config('tickets.topic') as $key => $val)
                <option value="{{ $key }}" @selected(old('topic', $ticket->topic) === $key)>{{ $val }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Topic Detail</label>
        <select name="topicDetail" class="form-select" {{ $fieldDisabled }} {{ !old('topic', $ticket->topic) ? 'disabled' : '' }}>
            <option value="">Select</option>
            @if($currentTopic = old('topic', $ticket->topic))
                @if(isset(config('tickets.topicDetail')[$currentTopic]))
                    @foreach (config('tickets.topicDetail')[$currentTopic] as $key => $val)
                        <option value="{{ $key }}" @selected(old('topicDetail', $ticket->topicDetail) === $key)>{{ $val }}</option>
                    @endforeach
                @endif
            @endif
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">No SC/Track ID</label>
        <select name="noSC" class="form-select" {{ $fieldDisabled }}>
            <option value="">Select</option>
            @foreach (config('tickets.noSC') as $key => $val)
                <option value="{{ $key }}" @selected(old('noSC', $ticket->noSC) === $key)>{{ $val }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label class="form-label">Status Call (opsional)</label>
        <select name="statusSC" class="form-select" {{ $fieldDisabled }}>
            <option value="">Select</option>
            @foreach (config('tickets.statusSC') as $key => $val)
                <option value="{{ $key }}" @selected(old('statusSC', $ticket->statusSC) === $key)>{{ $val }}</option>
            @endforeach
        </select>
    </div>
</div>

{{-- Row 5: Validasi Close --}}
<div class="form-grid">
    <div class="form-group">
        <label class="form-label">Validasi Close</label>
        <select name="validateClose" class="form-select" {{ $fieldDisabled }}>
            <option value="">Select</option>
            @foreach (config('tickets.validateClose') as $key => $val)
                <option value="{{ $key }}" @selected(old('validateClose', $ticket->validateClose) === $key)>{{ $val }}</option>
            @endforeach
        </select>
    </div>
</div>

{{-- Row 6: Deskripsi --}}
<div class="form-group" style="margin-bottom:14px;">
    <label class="form-label">Deskripsi</label>
    <textarea name="description" class="form-textarea"
        placeholder="Deskripsi"
        {{ $fieldDisabled }}>{{ old('description', $ticket->description) }}</textarea>
</div>
