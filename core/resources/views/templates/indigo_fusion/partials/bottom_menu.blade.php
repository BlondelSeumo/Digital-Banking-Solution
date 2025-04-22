
<div class="section--bg2 p-2 bottom-menu-section">
    <div class="container">
        <nav class="navbar navbar-expand-lg  bottom-menu p-0">
            <div class="container-lg">
                <button class="navbar-toggler text-white align-items-center py-2 ms-auto" type="button" data-bs-toggle="collapse" data-bs-target="#bottomMenu" aria-controls="bottomMenu" aria-expanded="false" aria-label="Toggle navigation">
                    <p class="d-flex align-items-center"><span class="fs--14px me-2"></span><i class="las la-bars"></i></p>
                </button>
                <div class="collapse navbar-collapse justify-content-center" id="bottomMenu">
                    <ul class="navbar-nav text-center">
                        @stack('bottom-menu')
                    </ul>
                </div>
            </div>
        </nav>
    </div>
</div>

@push('script')
    @push('script')
        <script>
            let removeMenu = () => {
                if($('.bottom-menu ul li').length == 0){
                    $('.bottom-menu-section').remove();
                }
            };
            removeMenu();
        </script>
    @endpush
@endpush
