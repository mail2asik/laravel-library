@if(session()->has('flash_message'))
  <div class="alert alert-{{ (session('flash_message')['status'] == 'success') ? 'success' : 'danger' }} alert-dismissible" style="margin:0 15px 15px">
          <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
          <h4>
              <i class="icon fa fa-check"></i>
              Alert!
          </h4>
          {{ session('flash_message')['message'] }}
          @if(isset(session('flash_message')['error_fields']) && !empty(session('flash_message')['error_fields']))
              <ul>
              @if(is_array(session('flash_message')['error_fields']))
                  @foreach(session('flash_message')['error_fields'] as $key=>$val)
                          @if(is_array(session('flash_message')['error_fields'][$key]))
                              <li>{{ session('flash_message')['error_fields'][$key][0] }}</li>
                          @else
                              <li>{{ session('flash_message')['error_fields'][$key] }}</li>
                          @endif
                  @endforeach
              @else
                      <li>{{ session('flash_message')['error_fields'] }}</li>
              @endif
              </ul>
          @endif
        {{-- <b>{{ session('flash_message')['message']}}</b> {{session('flash_message')['error_fields']}} --}}
  </div>
@endif
