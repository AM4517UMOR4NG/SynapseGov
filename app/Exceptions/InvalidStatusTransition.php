<?php

namespace App\Exceptions;

use App\Models\Report;
use DomainException;
use Illuminate\Http\Request;

/**
 * Thrown when a report status change is not allowed by Report::STATUS_TRANSITIONS.
 */
class InvalidStatusTransition extends DomainException
{
    public function __construct(public readonly ?string $from, public readonly string $to)
    {
        parent::__construct(sprintf(
            'Laporan berstatus "%s" tidak dapat diubah menjadi "%s".',
            $from ? Report::statusLabel($from) : '-',
            Report::statusLabel($to)
        ));
    }

    /**
     * Render the rejected transition as a normal validation-style error instead of a 500 page.
     */
    public function render(Request $request)
    {
        if ($request->expectsJson()) {
            return response()->json(['message' => $this->getMessage()], 422);
        }

        return back()->with('error', $this->getMessage());
    }
}
