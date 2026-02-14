<div class="row">
    <div class="col-md-6">
        {{ Field::text(__('Field Label'), "fields[{$marker}][label]", ['value' => $item['label'] ?? ''])->classes(['field-label']) }}
    </div>

    <div class="col-md-6">
        {{ Field::text(__('Field Name'), "fields[{$marker}][name]", ['value' => $item['name'] ?? ''])->classes(['field-name']) }}
    </div>
</div>
