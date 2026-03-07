<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\MaintenanceRecord;
use App\Models\PurchaseOrder;
use App\Models\Shipment;
use App\Models\Trip;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CalendarController extends Controller
{
    public function index(): View
    {
        return view('calendar.index');
    }

    public function events(Request $request): JsonResponse
    {
        $start = $request->get('start');
        $end   = $request->get('end');
        $types = $request->get('types', ['delivery', 'contract', 'maintenance', 'trip', 'shipment']);

        $events = collect();

        // PO Deliveries
        if (in_array('delivery', $types)) {
            PurchaseOrder::whereNotNull('expected_delivery_date')
                ->whereNotIn('status', ['cancelled', 'rejected'])
                ->when($start, fn($q) => $q->whereDate('expected_delivery_date', '>=', $start))
                ->when($end,   fn($q) => $q->whereDate('expected_delivery_date', '<=', $end))
                ->with('vendor')
                ->get()
                ->each(function ($po) use ($events) {
                    $events->push([
                        'id'    => 'po-' . $po->id,
                        'title' => $po->po_number . ($po->vendor ? ' — ' . $po->vendor->name : ''),
                        'start' => $po->expected_delivery_date->format('Y-m-d'),
                        'color' => '#3b82f6',
                        'url'   => route('procurement.purchase-orders.show', $po),
                        'extendedProps' => [
                            'type'   => 'delivery',
                            'status' => $po->status?->value,
                            'badge'  => 'PO Delivery',
                        ],
                    ]);
                });
        }

        // Contract expiry dates
        if (in_array('contract', $types)) {
            Contract::whereNotNull('end_date')
                ->whereNotIn('status', ['terminated'])
                ->when($start, fn($q) => $q->whereDate('end_date', '>=', $start))
                ->when($end,   fn($q) => $q->whereDate('end_date', '<=', $end))
                ->with('vendor')
                ->get()
                ->each(function ($contract) use ($events) {
                    $daysLeft = now()->diffInDays($contract->end_date, false);
                    $color    = $daysLeft <= 30 ? '#ef4444' : ($daysLeft <= 90 ? '#f97316' : '#8b5cf6');
                    $events->push([
                        'id'    => 'contract-' . $contract->id,
                        'title' => $contract->title . ' — ' . ($contract->vendor?->name ?? ''),
                        'start' => $contract->end_date->format('Y-m-d'),
                        'color' => $color,
                        'url'   => route('contracts.show', $contract),
                        'extendedProps' => [
                            'type'   => 'contract',
                            'status' => $contract->status?->value,
                            'badge'  => 'Contract Expiry',
                        ],
                    ]);
                });
        }

        // Vehicle maintenance
        if (in_array('maintenance', $types)) {
            MaintenanceRecord::whereNotNull('scheduled_date')
                ->whereNull('completed_date')
                ->when($start, fn($q) => $q->whereDate('scheduled_date', '>=', $start))
                ->when($end,   fn($q) => $q->whereDate('scheduled_date', '<=', $end))
                ->with('vehicle')
                ->get()
                ->each(function ($record) use ($events) {
                    $events->push([
                        'id'    => 'maintenance-' . $record->id,
                        'title' => ($record->vehicle?->registration_number ?? 'Vehicle') . ' — ' . ucfirst($record->type),
                        'start' => $record->scheduled_date->format('Y-m-d'),
                        'color' => '#f59e0b',
                        'url'   => route('fleet.maintenance.show', $record),
                        'extendedProps' => [
                            'type'  => 'maintenance',
                            'badge' => 'Maintenance',
                        ],
                    ]);
                });
        }

        // Trip schedules
        if (in_array('trip', $types)) {
            Trip::whereNotNull('scheduled_at')
                ->whereNotIn('status', ['cancelled', 'completed'])
                ->when($start, fn($q) => $q->whereDate('scheduled_at', '>=', $start))
                ->when($end,   fn($q) => $q->whereDate('scheduled_at', '<=', $end))
                ->with(['vehicle', 'driver'])
                ->get()
                ->each(function ($trip) use ($events) {
                    $events->push([
                        'id'    => 'trip-' . $trip->id,
                        'title' => $trip->trip_number . ' — ' . ($trip->vehicle?->registration_number ?? '') . ($trip->driver ? ' / ' . $trip->driver->name : ''),
                        'start' => $trip->scheduled_at->format('Y-m-d\TH:i:s'),
                        'color' => '#10b981',
                        'url'   => route('fleet.trips.show', $trip),
                        'extendedProps' => [
                            'type'   => 'trip',
                            'status' => $trip->status?->value,
                            'badge'  => 'Trip',
                        ],
                    ]);
                });
        }

        // Shipment arrivals
        if (in_array('shipment', $types)) {
            Shipment::whereNotNull('estimated_arrival')
                ->whereNotIn('status', ['cancelled', 'delivered'])
                ->when($start, fn($q) => $q->whereDate('estimated_arrival', '>=', $start))
                ->when($end,   fn($q) => $q->whereDate('estimated_arrival', '<=', $end))
                ->with('purchaseOrder')
                ->get()
                ->each(function ($shipment) use ($events) {
                    $events->push([
                        'id'    => 'shipment-' . $shipment->id,
                        'title' => ($shipment->tracking_number ?? 'Shipment') . ($shipment->carrier ? ' (' . $shipment->carrier . ')' : ''),
                        'start' => $shipment->estimated_arrival->format('Y-m-d'),
                        'color' => '#6366f1',
                        'url'   => $shipment->purchaseOrder
                            ? route('procurement.purchase-orders.show', $shipment->purchaseOrder)
                            : null,
                        'extendedProps' => [
                            'type'   => 'shipment',
                            'status' => $shipment->status?->value,
                            'badge'  => 'Shipment ETA',
                        ],
                    ]);
                });
        }

        return response()->json($events->values());
    }
}
