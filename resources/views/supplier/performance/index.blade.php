@extends('layouts.supplier')
@section('title', 'Performance Scores')
@section('content')
<div class="py-6">
    @if(count($scores))
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-xs text-gray-500 uppercase">
                <tr><th class="px-4 py-3 text-left">Period</th><th class="px-4 py-3 text-center">Delivery</th><th class="px-4 py-3 text-center">Quality</th><th class="px-4 py-3 text-center">Price</th><th class="px-4 py-3 text-center">Responsiveness</th><th class="px-4 py-3 text-center">Overall</th></tr>
            </thead>
            <tbody class="divide-y">
                @foreach($scores as $score)
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3 text-gray-700">{{ optional($score->period_start)->format('M Y') }}</td>
                    <td class="px-4 py-3 text-center">{{ $score->delivery_score }}</td>
                    <td class="px-4 py-3 text-center">{{ $score->quality_score }}</td>
                    <td class="px-4 py-3 text-center">{{ $score->price_score }}</td>
                    <td class="px-4 py-3 text-center">{{ $score->responsiveness_score }}</td>
                    <td class="px-4 py-3 text-center font-bold {{ $score->overall_score >= 80 ? 'text-green-600' : ($score->overall_score >= 60 ? 'text-yellow-600' : 'text-red-600') }}">{{ $score->overall_score }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <x-empty-state title="No performance scores yet" description="Your performance scores will appear here once reviewed." />
    @endif
</div>
@endsection
