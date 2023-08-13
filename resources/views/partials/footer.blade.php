<!-- Main Footer -->
<footer class="main-footer">
    <!-- To the right -->
    <div class="pull-right hidden-xs">
        @if(config('developer.show_environment'))
            <strong>Env</strong>&nbsp;&nbsp; {!! config('app.env') !!}
        @endif

        &nbsp;&nbsp;&nbsp;&nbsp;

        @if(config('developer.show_version'))
        <strong>Version</strong>&nbsp;&nbsp; {!! \OpenDeveloper\Developer\Developer::VERSION !!}
        @endif

    </div>
    <!-- Default to the left -->
    <strong>Powered by <a href="https://github.com/wishbone-productions/open-developer" target="_blank">open-developer</a></strong>
</footer>
