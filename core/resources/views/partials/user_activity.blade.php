@if (auth()->check() && gs()->detect_activity)
    @push('script')
        <script>
            "use strict";
            (function($) {
                let isIdle = false;
                const idleTime = +@json(gs()->idle_time_threshold) * 1000;
                let countRequest = 0;
                let interval;
                let debounceTimeout;

                function startIdleTimer() {
                    interval = setInterval(function() {
                        countRequest++;
                        if (countRequest > idleTime / 1000) {
                            isIdle = true;
                            clearInterval(interval);
                            logoutIfIdle();
                        }
                    }, 1000);
                }

                function logoutIfIdle() {
                    if (isIdle) {
                        window.location.href = "{{ route('user.logout') }}";
                        setTimeout(() => location.reload(true), 100);
                    }
                }

                function makeRequest() {
                    if (!isIdle) {
                        clearTimeout(debounceTimeout);
                        debounceTimeout = setTimeout(function() {
                            $.ajax({
                                url: "{{ route('session.status') }}",
                                method: "get",
                                data: {
                                    'reload': true
                                }
                            });
                        }, 500);
                    }
                }

                const events = ["mousemove", "keydown", "click", "scroll"];

                $(document).on(events.join(' '), function() {
                    countRequest = 0;
                    makeRequest();
                });

                startIdleTimer();
            })(jQuery)
        </script>
    @endpush
@endif
