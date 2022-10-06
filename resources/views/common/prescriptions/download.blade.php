<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ 'Prescription' }}</title>
    <style type="text/css">
        :root {
            --blue: #007bff;
            --indigo: #6610f2;
            --purple: #6f42c1;
            --pink: #e83e8c;
            --red: #dc3545;
            --orange: #fd7e14;
            --yellow: #ffc107;
            --green: #28a745;
            --teal: #20c997;
            --cyan: #17a2b8;
            --white: #fff;
            --gray: #6c757d;
            --gray-dark: #343a40;
            --primary: #336cfb;
            --secondary: #626364;
            --success: #b7ce63;
            --info: #64b5f6;
            --warning: #e9e165;
            --danger: #ed5564;
            --light: #fbfbfb;
            --dark: #0a0b0c;
            --breakpoint-xs: 0;
            --breakpoint-sm: 576px;
            --breakpoint-md: 768px;
            --breakpoint-lg: 992px;
            --breakpoint-xl: 1200px;
            --font-family-sans-serif: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
            --font-family-monospace: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace
        }

        *, :after, :before {
            box-sizing: border-box
        }

        html {
            font-family: sans-serif;
            line-height: 1.15;
            -webkit-text-size-adjust: 100%;
            -webkit-tap-highlight-color: rgba(0, 0, 0, 0)
        }

        article, aside, figcaption, figure, footer, header, hgroup, main, nav, section {
            display: block
        }

        body {
            margin: 0;
            font-family: -apple-system, BlinkMacSystemFont, Segoe UI, Roboto, Helvetica Neue, Arial, Noto Sans, sans-serif, Apple Color Emoji, Segoe UI Emoji, Segoe UI Symbol, Noto Color Emoji;
            font-size: 1rem;
            font-weight: 400;
            line-height: 1.5;
            color: #212529;
            text-align: left;
            background-color: #fff
        }

        [tabindex="-1"]:focus {
            outline: 0 !important
        }

        hr {
            box-sizing: content-box;
            height: 1px;
            background-color: #ddd;
            border: none;;
            overflow: visible
        }

        h1, h2, h3, h4, h5, h6 {
            margin-top: 0;
            margin-bottom: .5rem
        }

        p {
            margin-top: 0;
            margin-bottom: 1rem
        }

        abbr[data-original-title], abbr[title] {
            text-decoration: underline;
            -webkit-text-decoration: underline dotted;
            text-decoration: underline dotted;
            cursor: help;
            border-bottom: 0;
            -webkit-text-decoration-skip-ink: none;
            text-decoration-skip-ink: none
        }

        address {
            font-style: normal;
            line-height: inherit
        }

        address, dl, ol, ul {
            margin-bottom: 1rem
        }

        dl, ol, ul {
            margin-top: 0
        }

        ol ol, ol ul, ul ol, ul ul {
            margin-bottom: 0
        }

        dt {
            font-weight: 700
        }

        dd {
            margin-bottom: .5rem;
            margin-left: 0
        }

        blockquote {
            margin: 0 0 1rem
        }

        b, strong {
            font-weight: bolder
        }

        small {
            font-size: 80%
        }

        sub, sup {
            position: relative;
            font-size: 75%;
            line-height: 0;
            vertical-align: baseline
        }

        sub {
            bottom: -.25em
        }

        sup {
            top: -.5em
        }

        a {
            color: #336cfb;
            text-decoration: none;
            background-color: transparent
        }

        a:hover {
            color: #0442dd;
            text-decoration: underline
        }

        a:not([href]):not([tabindex]), a:not([href]):not([tabindex]):focus, a:not([href]):not([tabindex]):hover {
            color: inherit;
            text-decoration: none
        }

        a:not([href]):not([tabindex]):focus {
            outline: 0
        }

        code, kbd, pre, samp {
            font-family: SFMono-Regular, Menlo, Monaco, Consolas, Liberation Mono, Courier New, monospace;
            font-size: 1em
        }

        pre {
            margin-top: 0;
            margin-bottom: 1rem;
            overflow: auto
        }

        figure {
            margin: 0 0 1rem
        }

        img {
            border-style: none
        }

        img, svg {
            vertical-align: middle
        }

        svg {
            overflow: hidden
        }

        table {
            border-collapse: collapse
        }

        caption {
            padding-top: .75rem;
            padding-bottom: .75rem;
            color: #6c757d;
            text-align: left;
            caption-side: bottom
        }

        th {
            text-align: inherit
        }

        label {
            display: inline-block;
            margin-bottom: .5rem
        }

        button {
            border-radius: 0
        }

        button:focus {
            outline: 1px dotted;
            outline: 5px auto -webkit-focus-ring-color
        }

        button, input, optgroup, select, textarea {
            margin: 0;
            font-family: inherit;
            font-size: inherit;
            line-height: inherit
        }

        button, input {
            overflow: visible
        }

        button, select {
            text-transform: none
        }

        select {
            word-wrap: normal
        }

        [type=button], [type=reset], [type=submit], button {
            -webkit-appearance: button
        }

        [type=button]:not(:disabled), [type=reset]:not(:disabled), [type=submit]:not(:disabled), button:not(:disabled) {
            cursor: pointer
        }

        [type=button]::-moz-focus-inner, [type=reset]::-moz-focus-inner, [type=submit]::-moz-focus-inner, button::-moz-focus-inner {
            padding: 0;
            border-style: none
        }

        input[type=checkbox], input[type=radio] {
            box-sizing: border-box;
            padding: 0
        }

        input[type=date], input[type=datetime-local], input[type=month], input[type=time] {
            -webkit-appearance: listbox
        }

        textarea {
            overflow: auto;
            resize: vertical
        }

        fieldset {
            min-width: 0;
            padding: 0;
            margin: 0;
            border: 0
        }

        legend {
            display: block;
            width: 100%;
            max-width: 100%;
            padding: 0;
            margin-bottom: .5rem;
            font-size: 1.5rem;
            line-height: inherit;
            color: inherit;
            white-space: normal
        }

        progress {
            vertical-align: baseline
        }

        [type=number]::-webkit-inner-spin-button, [type=number]::-webkit-outer-spin-button {
            height: auto
        }

        [type=search] {
            outline-offset: -2px;
            -webkit-appearance: none
        }

        [type=search]::-webkit-search-decoration {
            -webkit-appearance: none
        }

        ::-webkit-file-upload-button {
            font: inherit;
            -webkit-appearance: button
        }

        output {
            display: inline-block
        }

        summary {
            display: list-item;
            cursor: pointer
        }

        template {
            display: none
        }

        [hidden] {
            display: none !important
        }

        .h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6 {
            margin-bottom: .5rem;
            font-weight: 500;
            line-height: 1.2
        }

        .h1, h1 {
            font-size: 2.5rem
        }

        .h2, h2 {
            font-size: 2rem
        }

        .h3, h3 {
            font-size: 1.75rem
        }

        .h4, h4 {
            font-size: 1.5rem
        }

        .h5, h5 {
            font-size: 1.25rem
        }

        .h6, h6 {
            font-size: 1rem
        }

        .lead {
            font-size: 1.25rem;
            font-weight: 300
        }

        .display-1 {
            font-size: 6rem
        }

        .display-1, .display-2 {
            font-weight: 300;
            line-height: 1.2
        }

        .display-2 {
            font-size: 5.5rem
        }

        .m-0 {
            margin: 0
        }

        .p-0 {
            padding: 0
        }

        .list-unstyled {
            list-style: none;
        }
    </style>
</head>
<body>
<table border="0" style="margin-bottom: 30px; width: 100%">
    <tbody>
    <tr style="vertical-align: baseline;">
        <td style="text-align: left; margin-top: 5px;">
            <h4 style="margin-bottom: 2px;">{{ $prescription->doctor->name }}</h4>
            <strong>{{ $prescription->doctor->department->name ?? null }}</strong>
            <p>{{ $prescription->doctor->getMeta('user_education_title') }}</p>
        </td>
        <td style="text-align: center;">
            <img style="max-height: 40px;" src="{{ asset('assets/img/logo.svg') }}"
                 alt="{{ config('app.name', env('APP_NAME')) }}">
            {{-- <h3>{{ config('app.name', env('APP_NAME')) }}</h3> --}}
            <p>{{ settings('app_tagline') }}</p>
        </td>
        <td style="text-align: right; margin-top: 5px;">
            <h4>Appointment: {{ $prescription->appointment->appointment_code ?? '' }}</h4>
            <p>Date: {{ optional(optional($prescription->appointment)->scheduled_at)->format('d M, Y') }}</p>
        </td>
    </tr>
    </tbody>
</table>
<hr style="margin: 5px 0;padding: 0;">
<table style="width: 100%">
    <tbody>
    <tr>
        <td><strong>Name: <i>{{ $prescription->patient->name }}</i></strong></td>
        <td style="padding-left: 12px; border-left: 1px solid #ddd;">
            Age: {{ $prescription->patient->getMeta('user_age') }}</td>
        <td style="padding-left: 12px; border-left: 1px solid #ddd;">
            Gender: {{ ucfirst($prescription->patient->getMeta('user_gender')) }}</td>
        <td style="padding-left: 12px; border-left: 1px solid #ddd;">Blood
            Group: {{ strtoupper($prescription->patient->getMeta('user_blood_group')) }}</td>
        <td style="padding-left: 12px; border-left: 1px solid #ddd;">
            Date: {{ $prescription->created_at->format('d M, Y') }}</td>
    </tr>
    </tbody>
</table>
<hr style="margin: 5px 0;padding: 0;">
<h5 style="margin: 15px 0; text-align: left;">Chief Complaint: {{ $prescription->chief_complain }}</h5>
<hr style="margin: 0;padding: 0;">
<table border="0" style="margin-bottom: 30px; width: 100%;">
    <tbody>
    <tr style="vertical-align: baseline;">
        <td width="40%" style="text-align: left; margin-top: 5px; border-right: 1px solid #ddd;">
            <h4 style="margin-bottom: 2px;">Diagnosis</h4>
            <hr style="margin: 5px 0;padding: 0;">
            <table border="0" style="width: 100%;border-color: #ddd;">
                <tbody>
                @foreach($prescription->getNotes('diagnosis') as $item)
                    <tr>
                        <td><h5>{{ $item->title }}</h5></td>
                        <td><h5>:</h5></td>
                        <td><h5>{{ $item->details }}</h5></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </td>
        <td width="60%" style="text-align: left; margin-top: 5px;">
            <h4>Medicines</h4>
            <hr style="margin: 5px 0;padding: 0;">
            <table style="width: 100%;border-color: #ddd;" border="0">
                <thead>
                <tr>
                    <th style="padding-left: 10px;">Name</th>
                    <th>Type</th>
                    <th>Dosing</th>
                    <th>Days</th>
                    <th>Instruction</th>
                </tr>
                </thead>
                <tbody>
                @forelse($prescription->medicines as $medicine)
                    <tr>
                        <td style="padding-left: 10px;">{{ $medicine->name }}</td>
                        <td>{{ $medicine->type }}</td>
                        <td>{{ $medicine->quantity }}</td>
                        <td>{{ $medicine->days }}</td>
                        <td>{{ $medicine->instruction }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No Item Found!</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </td>
    </tr>
    <tr style="vertical-align: baseline;">
        <td width="40%" style="text-align: left; margin-top: 5px; border-right: 1px solid #ddd;">
            <hr style="margin: 0;padding: 0;">
            <h5> <strong><u>Investigations:</u></strong> {{ $prescription->investigations }}</h5>
        </td>
        <td width="60%" style="text-align: left; margin-top: 5px;">
            <hr style="margin: 0;padding: 0;">
            <div>
                <strong><u>Advice:</u></strong><label>
                    <div readonly style="margin-left:2px; border: 0px; height: 50px">{{ $prescription->advice }}</div>
                </label>
            </div>
            {{--<h5> <strong><u>Advice:</u></strong> {{ $prescription->advice }}</h5>--}}
        </td>
    </tr>
    </tbody>
</table>
<div style=" position: absolute;bottom: 100px;">
    @if( $prescription->doctor->getMeta('user_signature') && file_exists(public_path($prescription->doctor->getMeta('user_signature'))) )
        <img style="margin-left:500px; margin-right:50px;
            max-height: 50px;"
             src="{{ request('print') ? asset($prescription->doctor->getMeta('user_signature')) :  public_path($prescription->doctor->getMeta('user_signature')) }}"
             alt="Signature">
    @endif
    <p style="margin-left:500px; margin-right:50px;text-align: center; border-top: 1px dashed #ddd;">Signature</p>
</div>
<div style="border-top: 1px solid #ddd;text-align: center;position: absolute;bottom: 0;display: inline-block;width: 100%;clear: both;">
    <p style="
            text-align: center;">{{ config('app.name') }} | {{ settings('app_address') }}</p>
</div>

<div style="page-break-before: always; "></div>
<h5>Patient History</h5>
<hr style="margin: 5px 0;padding: 0;">
<ul style="
    list-style: none;">
    @foreach($prescription->patient->history as $item)
        <li><strong>{{ $item->title }}</strong><i>({{ optional($item->created_at)->format('d M Y') }}
                )</i>: {{ $item->details }}</li>
    @endforeach
</ul>
<h5>Patient Investigation</h5>
<hr style="margin: 5px 0;padding: 0;">
<ul style="
    list-style: none;">
    @foreach($investigations as $investigation)
        <li><strong>{{ @$investigation->title }}</strong><i>({{ optional(@$investigation->updated_at)->format('d M Y') }}
                )</i>: {{ $investigation->details }}</li>
        @if($investigation->data)
            <ul style="
    list-style: none;">
                @foreach($investigation->data as $data)
                    <li><strong>{{ @$data['date'] }}</strong>: {{ @$data['details'] }}</li>
                @endforeach
            </ul>
        @endif
    @endforeach
</ul>
</body>
</html>
