@extends('core::layouts.admin')

@section('content')
    <form action="{{ $action }}" class="form-ajax" method="post">
        @if($model->exists)
            @method('PUT')
        @endif

        <div class="row">
            <div class="col-md-12">
                <a href="{{ $backUrl }}" class="btn btn-warning">
                    <i class="fas fa-arrow-left"></i> {{ __('Back') }}
                </a>

                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> {{ __('Save') }}
                </button>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-md-9">
                <x-card title="{{ __('Information') }}">
                    {{ Field::text(__('Name'), 'name', ['value' => $model->name]) }}

                    {{ Field::number(__('Min payment'), 'min_amount', ['value' => $model->min_amount]) }}
                </x-card>

                <x-card title="{{ __('Fields') }}"
                        description="{{ __('Information the user needs to enter to withdraw money.') }}">

                    <x-core::repeater name="fields"
                       :items="$model->fields ?? []"
                       view="withdraw::withdraw-method.components.field-item">
                    </x-core::repeater>
                </x-card>
            </div>

            <div class="col-md-3">

            </div>
        </div>
    </form>
@endsection

@section('scripts')
    <script type="text/javascript" nonce="{{ csp_script_nonce() }}">
        $(function () {
            $(document).on('input', '.field-label', function () {
                let label = $(this).val();
                let nameInput = $(this).closest('.repeater-item').find('.field-name');

                let name = label.toLowerCase().replace(/[^a-z0-9]+/g, '_').replace(/^_|_$/g, '');
                nameInput.val(name);
            });
        });
    </script>
@endsection
