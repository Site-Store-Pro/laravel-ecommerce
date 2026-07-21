<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\TicketReply;
use App\Models\User;
use App\Services\TicketAttachmentService;
use App\Services\TicketNotificationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use ZBateson\MailMimeParser\MailMimeParser;

class InboundEmailController extends Controller
{
    public function __construct(
        private TicketAttachmentService $attachmentService,
        private TicketNotificationService $notificationService,
    ) {}

    public function __invoke(Request $request): JsonResponse
    {
        /*
        --------------------------------------------------------
        SECURITY CHECK (ALL PROVIDERS)
        --------------------------------------------------------
        */
        $secret = $request->header('X-Webhook-Secret');

        if (!$secret || $secret !== config('services.inbound.secret')) {

            Log::warning('Inbound email rejected: invalid webhook secret', [
                'ip' => $request->ip(),
            ]);

            return response()->json(['message' => 'Unauthorized'], 403);
        }

        Log::info('Inbound email webhook received');

        $payload = $this->parsePayload($request);

        if (! $payload) {
            Log::warning('Inbound email could not be parsed');
            return response()->json(['message' => 'Unable to parse inbound email.'], 422);
        }

        $ticket = Ticket::query()
            ->where('reply_token', $payload['reply_token'])
            ->first();

        if (! $ticket) {
            Log::warning('Ticket not found for reply_token', [
                'reply_token' => $payload['reply_token']
            ]);

            return response()->json(['message' => 'Ticket not found.'], 404);
        }

        $user = User::query()
            ->where('email', $payload['from_email'])
            ->first();

        Log::info('Inbound email user resolved', [
            'email' => $payload['from_email'],
            'user_id' => $user?->id
        ]);

        $reply = TicketReply::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user?->id,
            'body' => $payload['body'],
            'via' => 'email',
            'author_name' => $payload['from_name'],
            'author_email' => $payload['from_email'],
        ]);

        $this->notificationService->replyAdded($reply);

        return response()->json([
            'message' => 'Reply recorded.',
            'reply_id' => $reply->id
        ]);
    }

    /**
     * Unified payload parser:
     * - Mailgun
     * - PostMark
     * - Cloudflare (MIME)
     */
    private function parsePayload(Request $request): ?array
    {
        /*
        --------------------------------------------------------
        1. MAILGUN (unchanged)
        --------------------------------------------------------
        */
        if ($request->has('sender') && $request->has('body-plain')) {

            $token = $this->attachmentService
                ->extractReplyTokenFromEmail(
                    $request->string('recipient')->toString()
                );

            if (! $token) {
                return null;
            }

            return [
                'reply_token' => $token,
                'from_email' => $request->string('sender')->toString(),
                'from_name' => $request->string('From', 'Email User')->toString(),
                'body' => $this->attachmentService->stripQuotedReply(
                    $request->string('body-plain')->toString()
                ),
            ];
        }

        /*
        --------------------------------------------------------
        2. POSTMARK (unchanged)
        --------------------------------------------------------
        */
        if ($request->has('FromFull') || $request->has('from')) {

            $to = collect($request->input('ToFull'))->pluck('Email')->first();

            $token = $to
                ? $this->attachmentService->extractReplyTokenFromEmail($to)
                : null;

            if (! $token) {
                return null;
            }

            return [
                'reply_token' => $token,
                'from_email' => $request->string('FromFull.Email', $request->string('from'))->toString(),
                'from_name' => $request->string('FromFull.Name', 'Email User')->toString(),
                'body' => $this->attachmentService->stripQuotedReply(
                    $request->string('TextBody', '')->toString()
                ),
            ];
        }

        /*
        --------------------------------------------------------
        3. CLOUDFLARE (MIME PARSER)
        --------------------------------------------------------
        */
        if ($request->has('recipient') && $request->has('raw')) {

            $token = $this->attachmentService
                ->extractReplyTokenFromEmail(
                    $request->string('recipient')->toString()
                );

            if (! $token) {
                return null;
            }

            $parser = new MailMimeParser();
            $message = $parser->parse($request->string('raw')->toString(), false);

            /*
            FROM
            */
            $fromHeader = $message->getHeader('From');

            $fromEmail = 'unknown@example.com';
            $fromName = 'Email User';

            if ($fromHeader && method_exists($fromHeader, 'getAddresses')) {

                $addresses = $fromHeader->getAddresses();

                if (!empty($addresses)) {
                    $fromEmail = $addresses[0]->getEmail();
                    $fromName = $addresses[0]->getName() ?: $fromName;
                }
            }

            /*
            BODY
            */
            $body = $message->getTextContent();

            if (!$body) {
                $html = $message->getHtmlContent();

                if ($html) {
                    $body = strip_tags(html_entity_decode($html));
                }
            }

            $body = $this->attachmentService->stripQuotedReply($body ?? '');

            return [
                'reply_token' => $token,
                'from_email' => $fromEmail,
                'from_name' => $fromName,
                'body' => trim($body),
            ];
        }

        return null;
    }
}