@php
    use Filament\Forms\Components\Contracts\HasNestedRecursiveValidationRules;
    use Filament\Forms\Components\Field;

    $errorMessage = null;

    foreach ($getChildComponentContainer()->getComponents() as $childComponent) {
        if (! ($childComponent instanceof Field)) {
            continue;
        }

        $statePath = $childComponent->getStatePath();

        if (blank($statePath)) {
            continue;
        }

        if ($errors->has($statePath)) {
            $errorMessage = $errors->first($statePath);
            $areHtmlValidationMessagesAllowed = $childComponent->areHtmlValidationMessagesAllowed();

            break;
        }

        if (! ($childComponent instanceof HasNestedRecursiveValidationRules)) {
            continue;
        }

        if ($errors->has("{$statePath}.*")) {
            $errorMessage = $errors->first("{$statePath}.*");
            $areHtmlValidationMessagesAllowed = $childComponent->areHtmlValidationMessagesAllowed();

            break;
        }
    }
@endphp

<div
    {{
        $attributes
            ->merge([
                'id' => $getId(),
            ], escape: false)
            ->merge($getExtraAttributes(), escape: false)
            ->class(['fi-sc-fused-group'])
    }}
>
    @if (filled($label = $getLabel()))
        <div class="fi-sc-fused-group-label-ctn">
            {{ $getChildSchema($schemaComponent::BEFORE_LABEL_SCHEMA_KEY) }}

            <div class="fi-sc-fused-group-label">
                {{ $label }}@if ($isMarkedAsRequired())<sup class="fi-sc-fused-group-label-required-mark">*</sup>
                @endif
            </div>

            {{ $getChildSchema($schemaComponent::AFTER_LABEL_SCHEMA_KEY) }}
        </div>
    @endif

    @if ($aboveContentContainer = $getChildSchema($schemaComponent::ABOVE_CONTENT_SCHEMA_KEY)?->toHtmlString())
        {{ $aboveContentContainer }}
    @endif

    {{ $getChildSchema() }}

    @if ($belowContentContainer = $getChildSchema($schemaComponent::BELOW_CONTENT_SCHEMA_KEY)?->toHtmlString())
        {{ $belowContentContainer }}
    @endif

    @if (filled($errorMessage))
        @if ($aboveErrorMessageSchema = $getChildSchema($schemaComponent::ABOVE_ERROR_MESSAGE_SCHEMA_KEY)?->toHtmlString())
            {{ $aboveErrorMessageSchema }}
        @endif

        @if ($areHtmlValidationMessagesAllowed)
            <div data-validation-error class="fi-sc-fused-group-error-message">
                {!! $errorMessage !!}
            </div>
        @else
            <p data-validation-error class="fi-sc-fused-group-error-message">
                {{ $errorMessage }}
            </p>
        @endif

        @if ($belowErrorMessageSchema = $getChildSchema($schemaComponent::BELOW_ERROR_MESSAGE_SCHEMA_KEY)?->toHtmlString())
            {{ $belowErrorMessageSchema }}
        @endif
    @endif
</div>
