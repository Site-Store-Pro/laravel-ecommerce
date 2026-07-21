<?php

namespace App\Http\Controllers;

use App\Models\CmsForm;
use App\Models\CmsFormSubmission;
use App\Services\OptinService;
use App\Services\RecaptchaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CmsFormSubmissionController extends Controller
{
    /**
     * Handle a public form submission.
     * Route: POST /forms/{slug}/submit
     */
    public function submit(Request $request, string $slug): JsonResponse
    {
        // Load the form (by slug or numeric ID)
        $form = is_numeric($slug)
            ? CmsForm::where('id', $slug)->where('is_active', true)->first()
            : CmsForm::where('slug', $slug)->where('is_active', true)->first();

        if (! $form) {
            return response()->json(['error' => 'Form not found.'], 404);
        }

        // ── reCAPTCHA v3 verification (skipped when keys are not configured) ─
        $recaptcha = app(RecaptchaService::class);
        if (! $recaptcha->verify($request->input('recaptcha_token', ''), 'cms_form')) {
            return response()->json([
                'errors' => ['_recaptcha' => 'Security verification failed. Please try again.'],
            ], 422);
        }

        $fields = $form->fields()->orderBy('sort_order')->get();
        $values = $request->input('values', []);

        // ── Server-side validation ────────────────────────────────────────────
        $errors = [];

        foreach ($fields as $field) {
            $value = $values[$field->id] ?? null;

            // Normalise: treat empty strings / empty arrays as null
            if (is_array($value)) {
                $empty = count(array_filter($value)) === 0;
            } else {
                $empty = ($value === null || $value === '');
            }

            if (! $field->is_required) {
                continue;
            }

            if ($empty) {
                $errors[$field->id] = $field->required_error_message
                    ?: "The \"{$field->label}\" field is required.";
                continue;
            }

            if ($field->required_type === 'email' && ! filter_var($value, FILTER_VALIDATE_EMAIL)) {
                $errors[$field->id] = $field->required_error_message
                    ?: 'Please enter a valid email address.';
            }

            if ($field->required_type === 'numeric' && ! is_numeric($value)) {
                $errors[$field->id] = $field->required_error_message
                    ?: 'Please enter a valid number.';
            }
        }

        if (! empty($errors)) {
            return response()->json(['errors' => $errors], 422);
        }

        // ── Persist submission ────────────────────────────────────────────────
        CmsFormSubmission::create([
            'form_id'      => $form->id,
            'data'         => $values,
            'ip_address'   => $request->ip(),
            'user_agent'   => $request->userAgent(),
            'submitted_at' => now(),
        ]);

        // ── Auto opt-in (silent — never blocks response) ──────────────────────
        try {
            app(OptinService::class)->subscribe($form, $values);
        } catch (\Throwable $e) {
            logger()->error('OptinService failed', ['form_id' => $form->id, 'error' => $e->getMessage()]);
        }

        // ── Email notification ────────────────────────────────────────────────
        if ($form->email_to) {
            try {
                $fieldMap = $fields->keyBy('id');
                $subject  = $form->email_subject ?: "New form submission: {$form->name}";

                Mail::send(
                    'emails.cms_form_submission',
                    compact('form', 'fields', 'fieldMap', 'values'),
                    function ($message) use ($form, $subject) {
                        $message->to($form->email_to)
                                ->subject($subject);
                    }
                );
            } catch (\Throwable $e) {
                // Log email failure but do not block the success response
                logger()->error('CmsForm email failed', [
                    'form_id' => $form->id,
                    'error'   => $e->getMessage(),
                ]);
            }
        }

        return response()->json(['success' => true]);
    }
}
