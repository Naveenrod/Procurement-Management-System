<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vendor;
use App\Models\VendorContact;
use App\Models\User;

class VendorSeeder extends Seeder
{
    public function run(): void
    {
        $vendors = [
            ['name' => 'Global Office Supplies Ltd', 'contact_person' => 'John Smith', 'email' => 'orders@globaloffice.com', 'phone' => '+1-555-0101', 'country' => 'USA', 'payment_terms' => 'net30', 'status' => 'approved'],
            ['name' => 'TechPro Solutions', 'contact_person' => 'Sarah Johnson', 'email' => 'sales@techpro.com', 'phone' => '+1-555-0102', 'country' => 'USA', 'payment_terms' => 'net30', 'status' => 'approved'],
            ['name' => 'Industrial Equipment Corp', 'contact_person' => 'James Wilson', 'email' => 'supply@indequip.com', 'phone' => '+44-20-7000-0001', 'country' => 'UK', 'payment_terms' => 'net60', 'status' => 'approved'],
            ['name' => 'Steel Masters International', 'contact_person' => 'Hans Mueller', 'email' => 'orders@steelmasters.com', 'phone' => '+49-30-1234567', 'country' => 'Germany', 'payment_terms' => 'net60', 'status' => 'approved'],
            ['name' => 'Pacific Rim Trading Co', 'contact_person' => 'Wei Chen', 'email' => 'trade@pacificrim.com', 'phone' => '+65-6000-0001', 'country' => 'Singapore', 'payment_terms' => 'net30', 'status' => 'approved'],
            ['name' => 'SafeGuard Products', 'contact_person' => 'Emily Brown', 'email' => 'sales@safeguard.com', 'phone' => '+1-555-0106', 'country' => 'Canada', 'payment_terms' => 'net30', 'status' => 'approved'],
            ['name' => 'Raw Materials Direct', 'contact_person' => 'Michael Davis', 'email' => 'bulk@rawdirect.com', 'phone' => '+61-2-9000-0001', 'country' => 'Australia', 'payment_terms' => 'net60', 'status' => 'approved'],
            ['name' => 'EcoClean Supplies', 'contact_person' => 'Lisa Green', 'email' => 'orders@ecoclean.com', 'phone' => '+1-555-0108', 'country' => 'USA', 'payment_terms' => 'immediate', 'status' => 'approved'],
            ['name' => 'LogiTech Hardware', 'contact_person' => 'Pierre Dubois', 'email' => 'orders@logitech-hw.com', 'phone' => '+41-21-000-0001', 'country' => 'Switzerland', 'payment_terms' => 'net30', 'status' => 'approved'],
            ['name' => 'FastShip Logistics', 'contact_person' => 'Tom Rodriguez', 'email' => 'ops@fastship.com', 'phone' => '+1-555-0110', 'country' => 'USA', 'payment_terms' => 'immediate', 'status' => 'approved'],
            ['name' => 'Primo Supplies Co', 'contact_person' => 'Alice Supplier', 'email' => 'supplier@procurement.test', 'phone' => '+1-555-0111', 'country' => 'USA', 'payment_terms' => 'net30', 'status' => 'approved'],
            ['name' => 'Second Supplier Ltd', 'contact_person' => 'Bob Supplier', 'email' => 'supplier2@procurement.test', 'phone' => '+1-555-0112', 'country' => 'UK', 'payment_terms' => 'net30', 'status' => 'approved'],
            ['name' => 'Pending Vendor Inc', 'contact_person' => 'Carol Newvendor', 'email' => 'newvendor@example.com', 'phone' => '+1-555-0113', 'country' => 'USA', 'payment_terms' => 'net30', 'status' => 'pending'],
        ];

        foreach ($vendors as $data) {
            $vendor = Vendor::firstOrCreate(['email' => $data['email']], $data);

            // Add contacts
            VendorContact::firstOrCreate(
                ['vendor_id' => $vendor->id, 'email' => 'contact@'.str_replace('@', '-at-', $data['email'])],
                ['name' => 'Primary Contact', 'designation' => 'Sales Manager', 'phone' => $data['phone'], 'is_primary' => true]
            );
        }

        // Link supplier users to vendors
        $supplierVendor1 = Vendor::where('email', 'supplier@procurement.test')->first();
        $supplierVendor2 = Vendor::where('email', 'supplier2@procurement.test')->first();

        if ($supplierVendor1) {
            User::where('email', 'supplier@procurement.test')->update(['vendor_id' => $supplierVendor1->id]);
        }
        if ($supplierVendor2) {
            User::where('email', 'supplier2@procurement.test')->update(['vendor_id' => $supplierVendor2->id]);
        }
    }
}
