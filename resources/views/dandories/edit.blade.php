@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Edit Dandory Ticket</h2>
        <a class="btn btn-secondary" href="{{ route('dandories.index') }}">
            <i class="fa fa-arrow-left"></i> Back
        </a>
    </div>

    @if ($errors->any())
    <div class="alert alert-danger shadow-sm rounded-3">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul class="mb-0">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="card shadow-sm rounded-3 p-4">
        <form action="{{ route('dandories.update', $dandory->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="requestor" class="form-label"><strong>Requestor:</strong></label>
                        <input type="text" name="requestor" class="form-control" value="{{ App\Models\User::find($dandory->added_by)->name }}" required readonly>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="customer" class="form-label"><strong>Customer:</strong></label>
                        <input list="customers" name="customer" id="customer-input" 
                            class="form-control" value="{{ old('customer', $dandory->customer) }}" required autocomplete="off" oninput="this.value = this.value.toUpperCase()">

                        <datalist id="customers">
                            @foreach ($customers as $customer)
                                <option value="{{ $customer->name }}">
                            @endforeach
                        </datalist>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="line_production" class="form-label"><strong>Line Produksi:</strong></label>
                        <input type="text" name="line_production" class="form-control" placeholder="Line Produksi" value="{{ old('line_production', $dandory->line_production) }}" required autocomplete="off" oninput="this.value = this.value.toUpperCase()">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nama_part" class="form-label"><strong>Nama Part:</strong></label>
                        <input type="text" name="nama_part" class="form-control" placeholder="Nama Part" value="{{ old('nama_part', $dandory->nama_part) }}" required autocomplete="off" oninput="this.value = this.value.toUpperCase()">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nomor_part" class="form-label"><strong>Nomor Part:</strong></label>
                        <input type="text" name="nomor_part" class="form-control" placeholder="Nomor Part" value="{{ old('nomor_part', $dandory->nomor_part) }}" required autocomplete="off" oninput="this.value = this.value.toUpperCase()">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="proses" class="form-label"><strong>Proses:</strong></label>
                        <input type="text" name="proses" class="form-control" placeholder="Proses" value="{{ old('proses', $dandory->proses) }}" required autocomplete="off" oninput="this.value = this.value.toUpperCase()">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="mesin" class="form-label"><strong>Mesin:</strong></label>
                        <input type="text" name="mesin" class="form-control" placeholder="Mesin" value="{{ old('mesin', $dandory->mesin) }}" required autocomplete="off" oninput="this.value = this.value.toUpperCase()">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="qty_pcs" class="form-label"><strong>Qty. PCS:</strong></label>
                        <input type="number" name="qty_pcs" class="form-control" placeholder="Qty. PCS" value="{{ old('qty_pcs', $dandory->qty_pcs) }}" required autocomplete="off">
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="dies_type" class="form-label"><strong>Dies Type/Tipe Dies:</strong></label>
                        <select name="dies_type" id="dies_type" class="form-control" required>
                            <option value="">-- Select Dies Type --</option>
                            <option value="small" {{ old('dies_type', $dandory->dies_type) == 'small' ? 'selected' : '' }}>Small (20 min)</option>
                            <option value="medium" {{ old('dies_type', $dandory->dies_type) == 'medium' ? 'selected' : '' }}>Medium (30 min)</option>
                            <option value="big" {{ old('dies_type', $dandory->dies_type) == 'big' ? 'selected' : '' }}>Big (45 min)</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="planning_shift" class="form-label"><strong>Planning Shift/Mulai Pengantian Dies:</strong></label>
                        <select name="planning_shift" id="planning_shift" class="form-control" required>
                            <option value="">-- Select Shift Time --</option>
                            @for ($i = 7; $i < 24; $i++)
                                @foreach(['00', '30'] as $minute)
                                    @php
                                        $time = sprintf('%02d:%s', $i, $minute);
                                        $shift = ($i < 16) || ($i == 16 && $minute == '00') ? 1 : 2; 
                                        $value = "$shift/$time";
                                    @endphp
                                    <option value="{{ $value }}" {{ old('planning_shift', $dandory->planning_shift) == $value ? 'selected' : '' }}>
                                        {{ $value }}
                                    </option>
                                @endforeach
                            @endfor
                        </select>
                    </div>
                </div>
                
                <div class="col-12">
                    <div class="card bg-light p-3 border-info">
                        <strong>Estimasi Selesai Pengerjaan:</strong>
                        <span id="estimated-completion-display" class="fs-4 text-info fw-bold">{{ $dandory->estimate_completion ?? 'N/A' }}</span>
                        <small class="text-muted">Dihitung berdasarkan Planning Shift + Durasi Tipe Dies.</small>
                    </div>
                </div>

                @if(Auth::user()->hasRole(['Admin', 'AdminTeknisi']))
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="status" class="form-label"><strong>Status:</strong></label>
                        <select name="status" class="form-control">
                            <option value="TO DO" {{ old('status', $dandory->status) == 'TO DO' ? 'selected' : '' }}>TO DO</option>
                            <option value="IN PROGRESS" {{ old('status', $dandory->status) == 'IN PROGRESS' ? 'selected' : '' }}>IN PROGRESS</option>
                            <option value="PENDING" {{ old('status', $dandory->status) == 'PENDING' ? 'selected' : '' }}>PENDING</option>
                            <option value="FINISH" {{ old('status', $dandory->status) == 'FINISH' ? 'selected' : '' }}>FINISH</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="assigned_to" class="form-label"><strong>Assign To:</strong></label>
                        <select name="assigned_to" class="form-control">
                            <option value="">-- Assign --</option>
                            @foreach($teknisiUsers as $user)
                                <option value="{{ $user->id }}" {{ old('assigned_to', $dandory->assigned_to) == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                @endif
                <div class="col-12">
                    <div class="form-group">
                        <label for="notes" class="form-label"><strong>Notes:</strong></label>
                        <textarea class="form-control" style="height:150px" name="notes" placeholder="Notes">{{ old('notes', $dandory->notes) }}</textarea>
                    </div>
                </div>
                <div class="col-12 text-center mt-4">
                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="fa-solid fa-floppy-disk me-2"></i> Update Ticket
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const diesTypeSelect = document.getElementById('dies_type');
        const planningShiftSelect = document.getElementById('planning_shift');
        const displayElement = document.getElementById('estimated-completion-display');
        
        const diesDurations = { 'small': 20, 'medium': 30, 'big': 45 };

        function calculateEstimate() {
            const planningShiftValue = planningShiftSelect.value;
            const diesTypeValue = diesTypeSelect.value;

            if (!planningShiftValue || !diesTypeValue) {
                displayElement.textContent = 'N/A';
                return;
            }

            const minutesToAdd = diesDurations[diesTypeValue];
            const timePart = planningShiftValue.split('/')[1];

            if (!timePart || minutesToAdd === undefined) {
                displayElement.textContent = 'N/A';
                return;
            }

            let [hours, minutes] = timePart.split(':').map(Number);
            
            minutes += minutesToAdd;

            hours += Math.floor(minutes / 60);
            minutes %= 60;
            
            hours %= 24; 

            const estimatedCompletion = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}`;
            
            displayElement.textContent = estimatedCompletion;
        }

        diesTypeSelect.addEventListener('change', calculateEstimate);
        planningShiftSelect.addEventListener('change', calculateEstimate);

        if (diesTypeSelect.value && planningShiftSelect.value) {
            calculateEstimate();
        }
    });
</script>
@endsection
