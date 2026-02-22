<?php

namespace App\Services;

use App\Models\Vendor;
use App\Models\VendorPerformanceScore;
use Illuminate\Support\Facades\DB;

class VendorService
{
    /**
     * Create a new vendor.
     *
     * @param array $data Vendor attributes (name, contact_person, email, phone, address, city, state, country, postal_code, tax_id, payment_terms, website, notes)
     * @return Vendor
     */
    public function createVendor(array $data): Vendor
    {
        $data['status'] = $data['status'] ?? 'pending';

        return Vendor::create($data);
    }

    /**
     * Approve a vendor, making them active and available for purchase orders.
     *
     * @param Vendor $vendor
     * @return Vendor
     *
     * @throws \InvalidArgumentException If the vendor is not in pending status.
     */
    public function approveVendor(Vendor $vendor): Vendor
    {
        if (!in_array($vendor->status->value ?? $vendor->status, ['pending', 'under_review', 'suspended'])) {
            throw new \InvalidArgumentException(
                "Vendor '{$vendor->name}' cannot be approved. Current status: " . ($vendor->status->value ?? $vendor->status)
            );
        }

        $vendor->update([
            'status' => 'approved',
        ]);

        $vendor->refresh();

        return $vendor;
    }

    /**
     * Suspend a vendor, preventing new purchase orders from being placed.
     *
     * @param Vendor $vendor
     * @return Vendor
     *
     * @throws \InvalidArgumentException If the vendor is already suspended or inactive.
     */
    public function suspendVendor(Vendor $vendor): Vendor
    {
        $currentStatus = $vendor->status->value ?? $vendor->status;

        if (in_array($currentStatus, ['suspended', 'blacklisted', 'inactive'])) {
            throw new \InvalidArgumentException(
                "Vendor '{$vendor->name}' is already suspended or blacklisted."
            );
        }

        $vendor->update([
            'status' => 'suspended',
        ]);

        $vendor->refresh();

        return $vendor;
    }

    /**
     * Calculate and store a weighted performance score for a vendor.
     *
     * Weights:
     *   - delivery:       30%
     *   - quality:        30%
     *   - price:          20%
     *   - responsiveness: 20%
     *
     * @param Vendor $vendor
     * @param array $scores ['delivery' => float, 'quality' => float, 'price' => float, 'responsiveness' => float] (each 0-100)
     * @return VendorPerformanceScore
     */
    public function calculatePerformanceScore(Vendor $vendor, array $scores): VendorPerformanceScore
    {
        $delivery = (float) ($scores['delivery'] ?? 0);
        $quality = (float) ($scores['quality'] ?? 0);
        $price = (float) ($scores['price'] ?? 0);
        $responsiveness = (float) ($scores['responsiveness'] ?? 0);

        $overallScore = ($delivery * 0.3)
            + ($quality * 0.3)
            + ($price * 0.2)
            + ($responsiveness * 0.2);

        $performanceScore = VendorPerformanceScore::create([
            'vendor_id' => $vendor->id,
            'delivery_score' => $delivery,
            'quality_score' => $quality,
            'price_score' => $price,
            'responsiveness_score' => $responsiveness,
            'overall_score' => round($overallScore, 2),
            'evaluation_date' => now(),
        ]);

        // Update vendor's aggregate rating
        $averageRating = $vendor->performanceScores()->avg('overall_score');
        $vendor->update(['rating' => round($averageRating, 2)]);

        return $performanceScore;
    }

    /**
     * Get a summary of vendor performance across all historical evaluations.
     *
     * @param Vendor $vendor
     * @return array{vendor_id: int, vendor_name: string, total_evaluations: int, average_overall: float, average_delivery: float, average_quality: float, average_price: float, average_responsiveness: float, latest_score: float|null, trend: string}
     */
    public function getVendorPerformanceSummary(Vendor $vendor): array
    {
        $scores = $vendor->performanceScores()
            ->orderBy('evaluation_date', 'desc')
            ->get();

        if ($scores->isEmpty()) {
            return [
                'vendor_id' => $vendor->id,
                'vendor_name' => $vendor->name,
                'total_evaluations' => 0,
                'average_overall' => 0,
                'average_delivery' => 0,
                'average_quality' => 0,
                'average_price' => 0,
                'average_responsiveness' => 0,
                'latest_score' => null,
                'trend' => 'no_data',
            ];
        }

        $averageOverall = round($scores->avg('overall_score'), 2);
        $averageDelivery = round($scores->avg('delivery_score'), 2);
        $averageQuality = round($scores->avg('quality_score'), 2);
        $averagePrice = round($scores->avg('price_score'), 2);
        $averageResponsiveness = round($scores->avg('responsiveness_score'), 2);

        $latestScore = $scores->first()->overall_score;

        // Determine trend based on last two evaluations
        $trend = 'stable';
        if ($scores->count() >= 2) {
            $previousScore = $scores->get(1)->overall_score;
            if ($latestScore > $previousScore) {
                $trend = 'improving';
            } elseif ($latestScore < $previousScore) {
                $trend = 'declining';
            }
        }

        return [
            'vendor_id' => $vendor->id,
            'vendor_name' => $vendor->name,
            'total_evaluations' => $scores->count(),
            'average_overall' => $averageOverall,
            'average_delivery' => $averageDelivery,
            'average_quality' => $averageQuality,
            'average_price' => $averagePrice,
            'average_responsiveness' => $averageResponsiveness,
            'latest_score' => (float) $latestScore,
            'trend' => $trend,
        ];
    }
}
