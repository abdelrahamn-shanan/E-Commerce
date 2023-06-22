@if(Session::has('success'))
    <div class="row mr-2 ml-2">
            <button type="text" class="btn btn-lg btn-block btn-outline-success mb-2"
                    id="type-error"><h1>{{Session::get('success')}}</h1>
            </button>
    </div>
@endif
