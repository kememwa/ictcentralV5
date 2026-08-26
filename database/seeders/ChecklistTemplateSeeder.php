<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ChecklistTemplate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ChecklistTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $checklists = [
            'Immediate Supervisor' => [
                'Customer accounts cleared',
                'Order books returned',
                'Handover completed satisfactorily',
                'All company items returned',
            ],
            'Administration' => [
                'Petty cash receipts/balances',
                'Fuel card returned',
                'Keys returned',
                'Stationery items returned',
                'Mobile phone returned',
                'Company car and keys returned',
                'Medical Insurance Card returned',
                'Removed from airtime list',
            ],
            'IT' => [
                'Desktop computer and accessories',
                'Laptop and accessories',
                'Revoke ERP Acumatica access',
                'Revoke Kim-Fay Central access',
                'Revoke Office 365 access',
                'Revoke Solutech SAT Access',
                'Revoke Zoho CRM Access',
                'Revoke Sage Access',
                'Revoke Time and attendance access (Hardware)',
                'Revoke M-Files access',
                'Terminate VPN access',
                'Control Panel',
                'Revoke access to Company SharePoint',
                'Revoke access to Network Attached Storage',
                'Revoke access to Kim-Fay Website back office',
            ],
            'Finance' => [
                'Salary advances cleared',
                'Loans cleared',
                'Customer accounts cleared',
                'Kim-Fay Sacco clearance',
            ],
            'HR' => [
                'Outstanding leave days',
                'Off Days',
                'Overtime',
                'Staff ID Card',
                'Power financial loan',
                'Certificate of service',
                'Bank notification',
                'Time and attendance (Software)',
            ],
        ];

        foreach ($checklists as $department => $items) {
            foreach ($items as $item) {
                ChecklistTemplate::create([
                    'department' => $department,
                    'item' => $item,
                ]);
            }
        }
    }
}