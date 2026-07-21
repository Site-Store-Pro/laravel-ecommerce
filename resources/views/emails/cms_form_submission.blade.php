<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Form Submission — {{ $form->name }}</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background: #f8fafc; margin: 0; padding: 0; }
        .wrapper { max-width: 640px; margin: 32px auto; background: #fff; border-radius: 12px; overflow: hidden; box-shadow: 0 1px 8px rgba(0,0,0,0.08); }
        .header { background: #4f46e5; padding: 32px 36px; }
        .header h1 { margin: 0; color: #fff; font-size: 22px; font-weight: 700; }
        .header p { margin: 6px 0 0; color: #c7d2fe; font-size: 14px; }
        .body { padding: 32px 36px; }
        .meta { display: flex; gap: 24px; flex-wrap: wrap; padding: 16px; background: #f8fafc; border-radius: 8px; border: 1px solid #e2e8f0; margin-bottom: 28px; }
        .meta-item { font-size: 12px; color: #64748b; }
        .meta-item strong { color: #1e293b; display: block; font-size: 13px; margin-bottom: 2px; }
        .field { margin-bottom: 20px; padding-bottom: 20px; border-bottom: 1px solid #f1f5f9; }
        .field:last-child { border-bottom: none; margin-bottom: 0; padding-bottom: 0; }
        .field-label { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; color: #6366f1; margin-bottom: 6px; }
        .field-value { font-size: 14px; color: #1e293b; white-space: pre-wrap; line-height: 1.6; }
        .field-empty { font-size: 13px; color: #94a3b8; font-style: italic; }
        .footer { padding: 20px 36px; background: #f8fafc; border-top: 1px solid #e2e8f0; font-size: 12px; color: #94a3b8; }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="header">
            <h1>New Submission: {{ $form->name }}</h1>
            <p>A visitor has completed and submitted your form.</p>
        </div>
        <div class="body">
            <div class="meta">
                <div class="meta-item">
                    <strong>Submitted</strong>
                    {{ now()->format('M j, Y \a\t g:i A') }}
                </div>
                @if(isset($ipAddress) && $ipAddress)
                <div class="meta-item">
                    <strong>IP Address</strong>
                    {{ $ipAddress }}
                </div>
                @endif
                <div class="meta-item">
                    <strong>Form</strong>
                    {{ $form->name }}
                </div>
            </div>

            @foreach($fields as $field)
                @php
                    $val = $values[$field->id] ?? null;
                @endphp
                <div class="field">
                    <div class="field-label">{{ $field->label }}</div>
                    @if(is_array($val))
                        @php $listed = array_values(array_filter($val)); @endphp
                        @if(count($listed))
                            @foreach($listed as $item)
                                <div class="field-value">• {{ $item }}</div>
                            @endforeach
                        @else
                            <div class="field-empty">No selection</div>
                        @endif
                    @elseif($val !== null && $val !== '')
                        <div class="field-value">{{ $val }}</div>
                    @else
                        <div class="field-empty">No response</div>
                    @endif
                </div>
            @endforeach
        </div>
        <div class="footer">
            This notification was sent automatically when the form was submitted.
        </div>
    </div>
</body>
</html>
