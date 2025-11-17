@extends('layouts.app')

@section('content')
<style>
    .badge-status {
        font-size: 0.8em;
        padding: 0.5em 0.8em;
        border-radius: 1rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    .badge-todo { background-color: #f8d7da; color: #721c24; }
    .badge-inprogress { background-color: #fff3cd; color: #856404; }
    .badge-finish { background-color: #d1e7dd; color: #155724; }
    .badge-pending { background-color: #e2e3e5; color: #495057; }
</style>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>#{{ $dandory->ddcnk_id }}</h2>
        <a class="btn btn-secondary" href="{{ route('dandories.index') }}">
            <i class="fa fa-arrow-left"></i> Back to list
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success mt-3 rounded-3 shadow-sm" role="alert">
            {{ session('success') }}
        </div>
    @endif
    
    <div id="message-container" class="my-3"></div>

    <div class="row g-4 mt-3">
        <div class="col-md-8 offset-md-2">
            <div class="card shadow-sm p-4 h-100">
                <h5 class="card-title mb-3">Ticket Details</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong>Key:</strong><br>
                        {{ $dandory->ddcnk_id }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Requestor:</strong><br>
                        {{ App\Models\User::find($dandory->added_by)->name }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Customer:</strong><br>
                        {{ $dandory->customer }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Line Produksi:</strong><br>
                        {{ $dandory->line_production ?? 'N/A' }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Part Name:</strong><br>
                        {{ $dandory->nama_part }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Part Number:</strong><br>
                        {{ $dandory->nomor_part }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Process:</strong><br>
                        {{ $dandory->proses }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Machine:</strong><br>
                        {{ $dandory->mesin }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Quantity:</strong><br>
                        {{ $dandory->qty_pcs }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Planning Shift/Mulai Pengantian Dies:</strong><br>
                        {{ $dandory->planning_shift ?? 'N/A' }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Dies Type/Tipe Dies:</strong><br>
                        <span class="badge bg-secondary">{{ strtoupper($dandory->dies_type) }}</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>EEstimasi Selesai Pengerjaan:</strong><br>
                        <span class="badge bg-info text-dark">{{ $dandory->estimate_completion ?? 'N/A' }}</span>
                    </div>

                    <div class="col-md-6 mb-3">
                        <strong>Check-in Time:</strong><br>
                        {{ $dandory->check_in ? \Carbon\Carbon::parse($dandory->check_in)->setTimezone('Asia/Jakarta')->format('d-m-Y H:i:s') : 'N/A' }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Check-out Time:</strong><br>
                        {{ $dandory->check_out ? \Carbon\Carbon::parse($dandory->check_out)->setTimezone('Asia/Jakarta')->format('d-m-Y H:i:s') : 'N/A' }}
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Status:</strong><br>
                        <span class="badge badge-status badge-{{ strtolower(str_replace(' ', '', $dandory->status)) }}">{{ $dandory->status }}</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Assigned To:</strong><br>
                        @if ($dandory->assigned_to)
                            {{ App\Models\User::find($dandory->assigned_to)->name }}
                        @else
                            N/A
                        @endif
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong>Date Created:</strong><br>
                        {{ $dandory->created_at }}
                    </div>
                    <div class="col-12 mb-3">
                        <strong>Notes:</strong><br>
                        @if (Auth::user()->hasRole('Admin') || (Auth::user()->hasRole('Teknisi') && $dandory->status == 'PENDING' && $dandory->assigned_to == Auth::id()))
                            <form action="{{ route('dandories.updateNotes', $dandory->id) }}" method="POST" id="notes-form">
                                @csrf
                                @method('PUT')
                                <textarea name="notes" class="form-control" rows="3">{{ $dandory->notes }}</textarea>
                                <button type="submit" class="btn btn-success btn-sm mt-2">Save Notes</button>
                            </form>
                        @else
                            {{ $dandory->notes }}
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
