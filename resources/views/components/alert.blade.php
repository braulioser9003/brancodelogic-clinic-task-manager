<script>
    $(function(){
        @if(Session::has('success'))
            $.NotificationApp.send("Mensaje de éxito!", "{{ Session::get('success') }}", 'top-right', '#5ba035', 'success');
        @endif

        @if(Session::has('info'))
            $.NotificationApp.send("{{__('alert_info')}}Mensaje info!", "{{ Session::get('info') }}", 'top-right', '#3b98b5', 'info');
        @endif

        @if(Session::has('warning'))
            $.NotificationApp.send("Mensaje de advertencia!", "{{ Session::get('warning') }}", 'top-right', '#da8609', 'warning');
        @endif

        @if(Session::has('error'))
            $.NotificationApp.send("Mensaje de error!", "{{ Session::get('error') }}", 'top-right', '#bf441d', 'error');
        @endif
    });
</script>
