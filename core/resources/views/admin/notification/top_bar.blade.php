@can(['admin.setting.notification.global.email', 'admin.setting.notification.email', 'admin.setting.notification.sms', 'admin.setting.notification.push', 'admin.setting.notification.templates'])
    <ul class="nav nav-tabs mb-4 topTap breadcrumb-nav" role="tablist">
        <button class="breadcrumb-nav-close"><i class="las la-times"></i></button>

        @php
            $globalTemplateRoutes = ['admin.setting.notification.global.email', 'admin.setting.notification.global.sms', 'admin.setting.notification.global.push'];
        @endphp
        @can($globalTemplateRoutes)
            <li class="nav-item {{ menuActive($globalTemplateRoutes) }}" role="presentation">

                @foreach ($globalTemplateRoutes as $route)
                    @can($route)
                        <a href="{{ route($route) }}" class="nav-link text-dark" type="button">
                            <i class="las la-globe"></i> @lang('Global Template')
                        </a>
                        @break
                    @endif
                @endforeach
            </li>
        @endcan

        @can('admin.setting.notification.email')
            <li class="nav-item {{ menuActive('admin.setting.notification.email') }}" role="presentation">
                <a href="{{ route('admin.setting.notification.email') }}" class="nav-link text-dark" type="button">
                    <i class="las la-envelope"></i> @lang('Email Setting')
                </a>
            </li>
        @endcan

        @can('admin.setting.notification.sms')
            <li class="nav-item {{ menuActive('admin.setting.notification.sms') }}" role="presentation">
                <a href="{{ route('admin.setting.notification.sms') }}" class="nav-link text-dark" type="button">
                    <i class="las la-sms"></i> @lang('SMS Setting')
                </a>
            </li>
        @endcan
        @can('admin.setting.notification.push')
            <li class="nav-item {{ menuActive('admin.setting.notification.push') }}" role="presentation">
                <a href="{{ route('admin.setting.notification.push') }}" class="nav-link text-dark" type="button">
                    <i class="las la-bell"></i> @lang('Push Notification Setting')
                </a>
            </li>
        @endcan
        @can('admin.setting.notification.templates')
            <li class="nav-item {{ menuActive(['admin.setting.notification.templates', 'admin.setting.notification.template.edit']) }}" role="presentation">
                <a href="{{ route('admin.setting.notification.templates') }}" class="nav-link text-dark" type="button">
                    <i class="las la-list"></i> @lang('Notification Templates')
                </a>
            </li>
        @endcan
    </ul>
@endcan
