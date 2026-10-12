<?php

namespace App\Http\Controllers;

use App\Models\Requisition;
use Barryvdh\DomPDF\Facade\Pdf;

class ContractController extends Controller
{
    public function print($requisitionId)
    {
        $requisition = Requisition::where('uuid', $requisitionId)->firstOrFail();

        abort_if(
            $requisition->hrm_approval_status !== 'approved' ||
            ! $requisition->casual_assignment_status,
            403,
            'This requisition cannot be printed'
        );

        $assignments = $requisition->CasualAssignments()
            ->with('casual')
            ->where('status', 'assigned')
            ->get();

        abort_if($assignments->isEmpty(), 404, 'No assigned casuals found');

        $pdf = Pdf::setOption([
                'isPhpEnabled'         => true,   // lets the footer script run
                'isRemoteEnabled'      => false,
                'defaultFont'          => 'Helvetica',
                'chroot'               => base_path(), // allows public_path() images
            ])
            ->loadView('contracts.casual', compact('assignments', 'requisition'))
            ->setPaper('a4', 'portrait');

        return $pdf->stream("Casual-Contracts-{$requisition->id}.pdf");
    }
}