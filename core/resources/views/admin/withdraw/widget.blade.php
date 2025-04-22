<div class="col-12">
    <div class="card custom--card mb-4">
        <div class="card-body">
            <div class="widget-card-inner">
                <div class="widget-card bg--success">
                    <a href="{{ can('admin.withdraw.data.approved') ? url()->route('admin.withdraw.data.approved',request()->all()) : 'javascript:void(0)' }}" class="widget-card-link"></a>
                    <div class="widget-card-left">
                        <div class="widget-card-icon">
                            <i class="las la-check-circle"></i>
                        </div>
                        <div class="widget-card-content">
                            <h6 class="widget-card-amount">{{ showAmount($successful) }}</h6>
                            <p class="widget-card-title">@lang('Approved Withdrawal')</p>
                        </div>
                    </div>
                    <span class="widget-card-arrow">
                        <i class="las la-angle-right"></i>
                    </span>
                </div>

                <div class="widget-card bg--warning">
                    <a href="{{ can('admin.withdraw.data.pending') ? url()->route('admin.withdraw.data.pending',request()->all()) : 'javascript:void(0)' }}" class="widget-card-link"></a>
                    <div class="widget-card-left">
                        <div class="widget-card-icon">
                            <i class="fas fa-spinner"></i>
                        </div>
                        <div class="widget-card-content">
                            <h6 class="widget-card-amount">{{ showAmount($pending) }}</h6>
                            <p class="widget-card-title">@lang('Pending Withdrawals')</p>
                        </div>
                    </div>
                    <span class="widget-card-arrow">
                        <i class="las la-angle-right"></i>
                    </span>
                </div>

                <div class="widget-card bg--danger">
                    <a href="{{ can('admin.withdraw.data.rejected') ? url()->route('admin.withdraw.data.rejected',request()->all()) : 'javascript:void(0)' }}" class="widget-card-link"></a>
                    <div class="widget-card-left">
                        <div class="widget-card-icon">
                            <i class="fas fa-ban"></i>
                        </div>
                        <div class="widget-card-content">
                            <h6 class="widget-card-amount">{{ showAmount($rejected) }}</h6>
                            <p class="widget-card-title">@lang('Rejected Withdrawals')</p>
                        </div>
                    </div>
                    <span class="widget-card-arrow">
                        <i class="las la-angle-right"></i>
                    </span>
                </div>

            </div>
        </div>
    </div>
</div>
