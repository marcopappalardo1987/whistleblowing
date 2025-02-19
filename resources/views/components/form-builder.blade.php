@foreach ($fields as $field)
    
    <?php $name = preg_replace('/[^a-z0-9_]/', '', strtolower(str_replace(' ', '_', $field['label']))); ?>
    @if(isset($field['column_length']))
        @if($field['column_length'] == 100)
            <?php $class = 'col-md-12'; ?>
        @elseif($field['column_length'] == 75)
            <?php $class = 'col-md-9'; ?>
        @elseif($field['column_length'] == 50)
            <?php $class = 'col-md-6'; ?>
        @elseif($field['column_length'] == 25)
            <?php $class = 'col-md-3'; ?>
        @elseif($field['column_length'] == 20)
            <?php $class = 'col-md-2'; ?>
        @endif
        <?php $columnLength = $field['column_length']; ?>
    @endif
    
    @switch($field['type'])
        @case('text')
            <div class="form-group {{$class}} mb-3">
                <label for="{{ $name }}">{{ $field['label'] }} @if($field['required'])<span class="text-danger">*</span>@endif</label>
                <input type="text" class="form-control" id="{{ $name }}" name="{{ $name }}" value="{{ old($name, $field['value']) }}" aria-describedby="{{ $name }}_help" @if($field['required'])required @endif maxlength="255">
                <input type="hidden" name="{{ $name }}_label" value="{{ $field['label'] }}">
                @if($field['help_text'])<small id="{{ $name }}_help" class="form-text text-muted">{{ $field['help_text'] }}</small>@endif
            </div>
            @break

        @case('textarea')
            <div class="form-group {{$class}} mb-3">
                <label for="{{ $name }}">{{ $field['label'] }} @if($field['required'])<span class="text-danger">*</span>@endif</label>
                <textarea class="form-control" id="{{ $name }}" name="{{ $name }}" aria-describedby="{{ $name }}_help" @if($field['required'])required @endif maxlength="10000">{{ old($name, $field['value']) }}</textarea>
                <input type="hidden" name="{{ $name }}_label" value="{{ $field['label'] }}">
                @if($field['help_text'])<small id="{{ $name }}_help" class="form-text text-muted">{{ $field['help_text'] }}</small>@endif
            </div>
            @break

        @case('select')
            <div class="form-group {{$class}} mb-3">
                <label for="{{ $name }}">{{ $field['label'] }} @if($field['required'])<span class="text-danger">*</span>@endif</label>
                <select class="form-control" id="{{ $name }}" name="{{ $name }}" @if($field['required'])required @endif>
                    <option value="" disabled {{ !$field['value'] ? 'selected' : '' }}>{{ __('Scegli un opzione') }}</option>
                    @foreach (explode("\r\n", $field['options']) as $option)
                        <option value="{{ $option }}" {{ $option == $field['value'] ? 'selected' : '' }}>{{ $option }}</option>
                    @endforeach
                </select>
                <input type="hidden" name="{{ $name }}_label" value="{{ $field['label'] }}">
                @if($field['help_text'])<small id="{{ $name }}_help" class="form-text text-muted">{{ $field['help_text'] }}</small>@endif
            </div>
            @break

        @case('radio')
            <div class="form-group {{$class}} mb-3">
                <label for="{{ $name }}">{{ $field['label'] }} @if($field['required'])<span class="text-danger">*</span>@endif</label>
                @foreach (explode("\r\n", $field['options']) as $option)
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="{{ $name }}" id="{{ $name }}_{{ trim($option) }}" value="{{ trim($option) }}" {{ trim($option) == old($name, $field['value']) ? 'checked' : '' }} @if($field['required'])required @endif>
                        <input type="hidden" name="{{ $name }}_label" value="{{ $field['label'] }}">
                        <label class="form-check-label" for="{{ $name }}_{{ trim($option) }}">
                            {{ trim($option) }}
                        </label>
                    </div>
                @endforeach
                @if($field['help_text'])<small id="{{ $name }}_help" class="form-text text-muted">{{ $field['help_text'] }}</small>@endif
            </div>
            @break

        @case('checkbox')
            <div class="form-group {{$class}} mb-3">
                <label>{{ $field['label'] }} @if($field['required'])<span class="text-danger">*</span>@endif</label>
                @foreach (explode("\r\n", $field['options']) as $option)
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" name="{{ $name }}[]" id="{{ $name }}_{{ trim($option) }}" value="{{ trim($option) }}" {{ in_array(trim($option), old($name, $field['value']) ?: []) ? 'checked' : '' }} @if($field['required'])required @endif>
                        <input type="hidden" name="{{ $name }}_label" value="{{ $field['label'] }}">
                        <label class="form-check-label" for="{{ $name }}_{{ trim($option) }}">
                            {{ trim($option) }}
                        </label>
                    </div>
                @endforeach
                @if($field['help_text'])<small id="{{ $name }}_help" class="form-text text-muted">{{ $field['help_text'] }}</small>@endif
            </div>
            @break

        @case('file')
            <div class="form-group {{$class}} mb-3">
                <label for="{{ $name }}">{{ $field['label'] }} @if($field['required'])<span class="text-danger">*</span>@endif</label>
                <input type="file" class="form-control-file" id="{{ $name }}" name="{{ $name }}" @if($field['required'])required @endif>
                <input type="hidden" name="{{ $name }}_label" value="{{ $field['label'] }}">
                @if($field['help_text'])<small id="{{ $name }}_help" class="form-text text-muted">{{ $field['help_text'] }}</small>@endif
            </div>
            @break

        @case('date')
            <div class="form-group {{$class}} mb-3">
                <label for="{{ $name }}">{{ $field['label'] }} @if($field['required'])<span class="text-danger">*</span>@endif</label>
                <input type="date" class="form-control" id="{{ $name }}" name="{{ $name }}" @if($field['required'])required @endif>
                <input type="hidden" name="{{ $name }}_label" value="{{ $field['label'] }}">
                @if($field['help_text'])<small id="{{ $name }}_help" class="form-text text-muted">{{ $field['help_text'] }}</small>@endif
            </div>
            @break

        @case('date-time')
            <div class="form-group {{$class}} mb-3">
                <label for="{{ $name }}">{{ $field['label'] }} @if($field['required'])<span class="text-danger">*</span>@endif</label>
                <input type="datetime-local" class="form-control" id="{{ $name }}" name="{{ $name }}" @if($field['required'])required @endif>
                <input type="hidden" name="{{ $name }}_label" value="{{ $field['label'] }}">
            </div>
            @break
            
        @case('public-data')
            <div class="row">
                <div class="col-md-12 anonymous-data-reporter">
                    <div class="form-check mb-3">
                        <input class="form-check-input" type="checkbox" id="toggle_reporter_fields" onclick="toggleReporterFields()">
                        <label class="form-check-label" for="toggle_reporter_fields">{{ __('Rivela la tua identità') }}</label>
                    </div>
                </div>
            </div>
            <div id="reporter_fields" style="display: none;">
                <div class="row">
                    <div class="col-md-12">
                        <h4>{{__('I tuoi dati')}}</h4>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="reporter_name">{{__('Nome')}}</label>
                            <input type="text" class="form-control" id="reporter_name" name="reporter_name" value="{{ old('reporter_name') }}" aria-describedby="reporter_name_help" maxlength="255">
                            <input type="hidden" name="reporter_name_label" value="{{__('Nome')}}">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="reporter_surname">{{__('Cognome')}}</label>
                            <input type="text" class="form-control" id="reporter_surname" name="reporter_surname" value="{{ old('reporter_surname') }}" aria-describedby="reporter_surname_help" maxlength="255">
                            <input type="hidden" name="reporter_surname_label" value="{{__('Cognome')}}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <div class="form-group">
                            <label for="reporter_email">{{__('Email')}}</label>
                            <input type="email" class="form-control" id="reporter_email" name="reporter_email" value="{{ old('reporter_email') }}" aria-describedby="reporter_email_help" maxlength="255">
                            <input type="hidden" name="reporter_email_label" value="{{__('Email')}}">
                        </div>
                    </div>
                    <div class="col-md-6 mb-4">
                        <div class="form-group">
                            <label for="reporter_phone">{{__('Telefono')}}</label>
                            <input type="tel" class="form-control" id="reporter_phone" name="reporter_phone" value="{{ old('reporter_phone') }}" aria-describedby="reporter_phone_help" maxlength="25">
                            <input type="hidden" name="reporter_phone_label" value="{{__('Telefono')}}">
                        </div>
                    </div>
                    <div class="col-md-12">
                        <hr class="mb-4">
                    </div>
                </div>
            </div>
            <script>
                function toggleReporterFields() {
                    var checkbox = document.getElementById('toggle_reporter_fields');
                    var fields = document.getElementById('reporter_fields');
                    fields.style.display = checkbox.checked ? 'block' : 'none';
                }
            </script>
            @break

        @default
            <p>Unsupported field type: {{ $field['type'] }}</p>
    @endswitch
@endforeach
