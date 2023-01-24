<ul class="nav navbar-nav">
    <li class="dropdown dropdown-notification nav-item">
        <a class="nav-link nav-link-label {!! $tip_class !!}" href="#" data-toggle="dropdown" aria-expanded="true">
            <i class="ficon feather icon-bell"></i>
            @if($total > 0)
                <span class="badge badge-pill badge-primary badge-up">{{$total}}</span>
            @endif
        </a>
        <ul class="dropdown-menu dropdown-menu-media dropdown-menu-right ">
            @if($total > 0)
            <li class="dropdown-menu-header">
                <div class="dropdown-header m-0 p-2">
                    <h3 class="white">{{$total}}</h3><span class="grey darken-2">{{ trans('notification.new_notifications') }}</span>
                </div>
            </li>
            <li class="scrollable-container media-list ps ps--active-y">

                @foreach($notifications as $notification)
                    <a class="justify-content-between" href="{!! $read_all_url !!}">
                        <div class="media align-items-start">
                            <div class="media-left"><i class="{!! $notification['icon'] !!} font-medium-5"></i></div>
                            <div class="media-body" style="padding-left: 10px">
                                <h6 class="primary media-heading">{!! $notification['count'] !!} {{ trans('notification.'.$notification['title']) }}</h6>
                            </div>
                            <small><time class="media-meta">{!! $notification['last_date'] !!}</time></small>
                        </div>
                    </a>
                @endforeach

                <div class="ps__rail-x" style="left: 0px; bottom: 0px;"><div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div></div><div class="ps__rail-y" style="top: 0px; right: 0px; height: 254px;"><div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 184px;"></div></div>
            </li>
            @endif

            <li class="dropdown-menu-footer"><a class="dropdown-item p-1 text-center" href="{!! $read_all_url !!}">{{ trans('notification.read_all') }}</a></li>
        </ul>
    </li>
</ul>