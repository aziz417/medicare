<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

@if( old('modal-open', session('modal-open')) )
@push('footer')
<script type="text/javascript">
    (function($){
        var modal = "{{old('modal-open', session('modal-open'))}}";
        $(document).ready(function(){
            $(modal).modal('show');
        });
    })(jQuery)
</script>
@endpush
@endif