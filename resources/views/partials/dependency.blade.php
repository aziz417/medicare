<script type="text/javascript">
    const __App = {
        csrf_token: "{{ csrf_token() }}",
        base_url: "{{ url('/') }}",
        auth: {{ auth()->check() ? 'true' : 'false' }},
        @if( auth()->check() )
        user: {
            id: {{ $auth->id }},
            name: "{{ $auth->name }}",
            email: "{{ $auth->email }}",
            mobile: "{{ $auth->mobile }}",
            role: "{{ $auth->role }}",
            auth_token: "{{ $auth->api_token }}",
        },
        @endif
    }
</script>