@extends('layouts.app')
@section('title', 'View Prescription')

@section('content')
<div class="page-content">
    {{-- @dd($prescription) --}}
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            {{-- <h2 class="m-0 mb-2">{{ config('app.name') }}</h2> --}}
            <img src="{{ asset('assets/img/logo.svg') }}" alt="{{ config('app.name', env('APP_NAME')) }}">
            <p>{{ settings('app_tagline') }}</p>
        </div>
    </div>
    <hr class="my-2">
    <div class="row">
        <div class="col-sm-3 ml-1">
            <strong>Name:</strong> {{ $prescription->patient->name }}
        </div>
        <div class="col-sm-2 border-left">
            <strong>Age:</strong> {{ $prescription->patient->getMeta('user_age') }}
        </div>
        <div class="col-sm-2 border-left">
            <strong>Gender:</strong> {{ ucfirst($prescription->patient->getMeta('user_gender')) }}
        </div>
        <div class="col-sm-2 border-left">
            <strong>Blood Group:</strong> {{ strtoupper($prescription->patient->getMeta('user_blood_group')) }}
        </div>
        <div class="col-sm-2 border-left">
            <strong>Date:</strong> {{ $prescription->created_at->format('d M, Y') }}
        </div>
    </div>
    <hr class="my-2">
    <div class="row">
        <div class="col-md-4 border-right">
            {{-- <strong><i>ICD CODE:</i> A{{ $prescription->appointment->appointment_code }}</strong> --}}
            <div><h5>Chief Complaint:</h5> <p>{{ $prescription->chief_complain }}</p></div>

            <h5 class="mb-2">Diagnosis</h5>
            <table class="table-sm table">
                <tbody>
                    @foreach($prescription->getNotes('diagnosis') as $item)
                    <tr>
                        <td width="20%">{{ $item->title }}</td>
                        <td width="2%">:</td>
                        <td width="auto">{{ $item->details }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <h5 class="mb-2">Patient History</h5>

            <ul class="m-0 p-0 list-unstyled">
            @foreach($prescription->patient->history as $item)
                <li><strong>{{ $item->title }}</strong>: {{ $item->details }}</li>
            @endforeach
            </ul>

            <h5 class="mb-2">Patient Investigations</h5>

            <ul class="m-0 p-0 list-unstyled">
                @foreach($investigations as $investigation)
                    <li><strong>{{ $investigation->title }}({{ \Illuminate\Support\Carbon::parse($investigation->updated_at)->format('d M, Y') }})</strong>: {{ $investigation->details }}</li>
                    @if($investigation->data)
                        <ul class="m-0 p-0 list-unstyled">
                            @foreach($investigation->data as $data)
                                <li><strong>{{ $data['date'] }}</strong>: {{ $data['details'] }}</li>
                            @endforeach
                        </ul>
                    @endif
                @endforeach
            </ul>
            <p style="margin-top: 10px;"> <h6 style="font-size: 1.1rem"><strong>Investigations:</strong></h6> {{ $prescription->investigations }}</p>
        </div>
        <div class="col-md-8">
            <h5 class="mb-2">Medicines</h5>
            <table class="table table-sm table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Type</th>
                        <th>Dosing</th>
                        <th>Instruction</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($prescription->medicines as $medicine)
                    <tr>
                        <td>{{ $medicine->name }}</td>
                        <td>{{ $medicine->type }}</td>
                        <td>{{ $medicine->quantity }}</td>
                        <td>{{ $medicine->instruction }}</td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4">No Item Found!</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
            <div>
                <strong><u>Advice:</u></strong><textarea readonly rows="30" cols="100" style="margin-left:2px; border: 0px">  {{ $prescription->advice }}</textarea>
            </div>
        </div>
    </div>
    <div class="add-action-box">
        <a class="btn btn-primary btn-lg btn-square rounded-pill" href="{{ route('common.prescriptions.download', $prescription->id) }}">
            <span class="btn-icon icofont-download"></span>
        </a>
    </div>
</div>
@endsection
