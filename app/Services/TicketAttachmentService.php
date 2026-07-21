<?php

namespace App\Services;

use App\Models\Ticket;
use App\Models\TicketAttachment;
use App\Models\TicketReply;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TicketAttachmentService
{
    /**
     * @param  array<int, UploadedFile>  $files
     */
    public function storeForTicket(Ticket $ticket, array $files, ?TicketReply $reply = null): void
    {
        foreach ($files as $file) {
            if (! $file instanceof UploadedFile) {
                continue;
            }
        
            $disk = match (config('filesystems.default')) {
                's3' => 's3',
                default => 'public',
            };

            $path = $file->store('ticket-attachments/'.$ticket->id, $disk);

            TicketAttachment::create([
                'ticket_id' => $ticket->id,
                'ticket_reply_id' => $reply?->id,
                'filename' => $path,
                'disk' => $disk,
                'original_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType() ?? 'application/octet-stream',
                'size' => $file->getSize() ?? 0,
            ]);
        }
    }


    public function extractReplyTokenFromEmail(string $recipient): ?string
    {
        if (preg_match('/reply\+([a-zA-Z0-9]+)@/', $recipient, $matches)) {
            return $matches[1];
        }

        return null;
    }


    public function stripQuotedReply(string $body): string
    {
        if (!$body) {
            return '';
        }


        /*
        --------------------------------------------------------
        Normalize MIME parser output
        --------------------------------------------------------
        */
        $body = str_replace(
            [
                "\xC2\xA0", // non-breaking space
                "\xE2\x80\xAF", // Gmail narrow no-break space
                "\r\n",
                "\r",
            ],
            [
                ' ',
                ' ',
                "\n",
                "\n",
            ],
            $body
        );


        /*
        --------------------------------------------------------
        Gmail folded reply header cleanup

        Converts:

        On Thu, Jun 25, 2026 at 9:17 AM Ticket System <
        support@visperity.com> wrote:

        into:

        On Thu, Jun 25, 2026 at 9:17 AM Ticket System < support@visperity.com> wrote:

        --------------------------------------------------------
        */
        $body = preg_replace(
            '/(On\s.+?)\n(\s*.+?>\s*wrote:)/is',
            '$1 $2',
            $body
        );


        /*
        --------------------------------------------------------
        Detect reply separators
        Cloudflare + MailMimeParser + Gmail/Outlook
        --------------------------------------------------------
        */
        $replyMarkers = [

            // Gmail:
            // On Thu, Jun 25, 2026 at 9:17 AM Name wrote:
            '/^On\s+.+?\s+wrote:\s*$/mi',

            // Outlook
            '/^-{3,}\s*Original Message\s*-{3,}/mi',

            // Apple Mail
            '/^Begin forwarded message:/mi',

            // Standard headers
            '/^From:\s.*$/mi',
            '/^Sent:\s.*$/mi',
            '/^To:\s.*$/mi',
            '/^Subject:\s.*$/mi',
        ];


        foreach ($replyMarkers as $pattern) {

            if (preg_match($pattern, $body, $match, PREG_OFFSET_CAPTURE)) {

                $body = substr(
                    $body,
                    0,
                    $match[0][1]
                );

                break;
            }
        }


        /*
        --------------------------------------------------------
        Remove quoted > reply blocks
        --------------------------------------------------------
        */
        $lines = preg_split('/\R/', $body) ?: [];

        $clean = [];

        foreach ($lines as $line) {

            if (preg_match('/^\s*>/', $line)) {
                break;
            }

            $clean[] = $line;
        }


        return trim(implode("\n", $clean));
    }
}