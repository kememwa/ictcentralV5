<?php

namespace App\Http\Controllers;

use App\Models\Requisition;
use Barryvdh\DomPDF\Facade\Pdf;

class ContractController extends Controller
{
    /**
     * Print the contracts for a given requisition.
     */
    public function print($requisitionId)
    {

        $requisition = Requisition::findOrFail($requisitionId);

        // Ensure requisition is eligible
        abort_if(
            $requisition->hrm_approval_status !== 'approved' ||
            !$requisition->casual_assignment_status,
            403,
            'This requisition cannot be printed'
        );

        // Load assignments and casuals
        $assignments = $requisition->CasualAssignments()
            ->with('casual')
            ->where('status', 'assigned')
            ->get();

        abort_if($assignments->isEmpty(), 404, 'No assigned casuals found');

        // Generate PDF
        $pdf = Pdf::loadView('contracts.casual', compact('assignments'));

        // Stream PDF to new tab
        return $pdf->stream("Casual-Contracts-{$requisition->id}.pdf");
    }
}