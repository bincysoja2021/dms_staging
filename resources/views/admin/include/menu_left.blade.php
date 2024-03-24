<div class="menu-left">
    <h4 class="dash-title"><a href="{{url('/home')}}">DMS Dashboard</a></h4>    
    <div id="accordion">
      <div class="card">
        <div class="card-header">
          <a class="card-link" data-toggle="collapse" href="#collapseOne">
            <i class="fa fa-file-pdf-o first" aria-hidden="true"></i> Manage Documents <i class="fa fa-caret-right second" aria-hidden="true"></i>
          </a>
        </div>
        <div id="collapseOne" class="collapse" data-parent="#accordion">
          <div class="card-body">
            <a href="{{ url('/all_document') }}">
              All Documents <i class="fa fa-caret-right second" aria-hidden="true"></i>
            </a>
            <a href="{{ url('/all_invoices') }}">
              All Invoices <i class="fa fa-caret-right second" aria-hidden="true"></i>
            </a>
            <a href="{{ url('/schedule_document') }}">
              Schedule upload Documents <i class="fa fa-caret-right second" aria-hidden="true"></i>
            </a>
            <a href="{{ url('/upload_document') }}">
              Manual Document Upload <i class="fa fa-caret-right second" aria-hidden="true"></i>
            </a>
            <a href="{{ url('/failed_document') }}">
              Uploading Failed Documents <i class="fa fa-caret-right second" aria-hidden="true"></i>
            </a>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-header">
          <a class="collapsed card-link" data-toggle="collapse" href="#collapseTwo">
          <i class="fa fa-search first" aria-hidden="true"></i> Search <i class="fa fa-caret-right second" aria-hidden="true"></i>
        </a>
        </div>
        <div id="collapseTwo" class="collapse" data-parent="#accordion">
          <div class="card-body">
            <a href="{{url('/search')}}">Normal Search <i class="fa fa-caret-right" aria-hidden="true"></i></a>
            <a href="{{ url('/advanced_search') }}">Advanced Search <i class="fa fa-caret-right" aria-hidden="true"></i></a>
          </div>
        </div>
      </div>
      @if(Auth::user()->user_type=="Super admin")
        <div class="card">
          <div class="card-header">
            <a class="collapsed card-link" data-toggle="collapse" href="#collapseThree">
              <i class="fa fa-users first" aria-hidden="true"></i> User Management <i class="fa fa-caret-right second" aria-hidden="true"></i>
            </a>
          </div>
          <div id="collapseThree" class="collapse" data-parent="#accordion">
            <div class="card-body">
              <a href="{{url('/all_users')}}">All users <i class="fa fa-caret-right" aria-hidden="true"></i></a>
                <a href="{{url('add_users')}}">Add users <i class="fa fa-caret-right" aria-hidden="true"></i></a>
            </div>
          </div>
        </div>
       @endif
    </div>
    <ul>      
      <li><a href="{{url('/tags')}}"><img src="{{ asset ('images/tag-icon.svg') }}"> Tags <i class="fa fa-caret-right" aria-hidden="true"></i></a></li>
      <li><a href="{{ url('/notification') }}"><img src="{{ asset ('images/notification-small-icon.svg') }}"> Notifications <i class="fa fa-caret-right" aria-hidden="true"></i></a></li>
      <li><a href="{{url('/settings') }}"><img src="{{ asset ('images/settings-icon.svg') }}"> Settings <i class="fa fa-caret-right" aria-hidden="true"></i></a></li>
      <li><a href="{{ url('user_logout') }}"><img src="{{ asset ('images/logout-icon.svg') }}"> Logout <i class="fa fa-caret-right" aria-hidden="true"></i></a></li>      
    </ul>
  </div>