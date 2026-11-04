<?php

namespace App\Services;

use InvalidArgumentException;

class CasualRequisitionWorkflow
{
    public const HOD = 'hod';
    public const COO = 'coo';
    public const HR_REP = 'hr_rep';
    public const HRM = 'hrm';
    public const REQUESTER = 'requester';

    /**
     * Return the next approval stage.
     */
    public static function nextStage(string $stage): ?string
    {
        return match ($stage) {
            self::HOD => self::COO,
            self::COO => self::HR_REP,
            self::HR_REP => self::HRM,
            self::HRM => null,

            default => throw new InvalidArgumentException(
                "Invalid approval stage: {$stage}"
            ),
        };
    }

    /**
     * Determine where a returned requisition resumes
     * after the requester makes corrections.
     */
    public static function returnToStage(string $stage): string
    {
        return match ($stage) {
            self::HOD => self::HOD,
            self::COO => self::HOD,
            self::HR_REP => self::HR_REP,
            self::HRM => self::HR_REP,

            default => throw new InvalidArgumentException(
                "Invalid return stage: {$stage}"
            ),
        };
    }

    /**
     * Get a readable stage name.
     */
    public static function stageLabel(string $stage): string
    {
        return match ($stage) {
            self::HOD => 'Head of Department',
            self::COO => 'Chief Operating Officer',
            self::HR_REP => 'HR Representative',
            self::HRM => 'Head of People & Culture',
            self::REQUESTER => 'Requester',

            default => 'Unknown Stage',
        };
    }
}