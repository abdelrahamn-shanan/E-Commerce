@extends('layouts.site')

@section('content')
<nav data-depth="1" class="breadcrumb-bg">
    <div class="container no-index">
        <div class="breadcrumb">

            <ol itemscope="" itemtype="http://schema.org/BreadcrumbList">
                <li itemprop="itemListElement" itemscope="" itemtype="http://schema.org/ListItem">
                    <a itemprop="item" href="{{route('home')}}">
                        <span itemprop="name">Home</span>
                    </a>
                    <meta itemprop="position" content="1">
                </li>
            </ol>
        </div>
    </div>
</nav>
<div class="container no-index">
    <div class="row">
        <div id="content-wrapper" class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
            <div id="main">
                <div class="page-header">
                    <h2 class="page_title">
                        please Enter the code we sent to your mobile
                    </h2>
                </div>
                <section id="content" class="page-content">
                    <section class="login-form">
                        <form method="POST" action="{{route('verify-user')}}">
                            @csrf
                            <section>
                                <div class="form-group row no-gutters">
                                    <label class="col-md-2 form-control-label mb-xs-5 required">
                                        Verification Code :
                                    </label>
                                    <div class="col-md-6">

                                        <input class="form-control" name="code" value="" type="text">
                                        @error('code')
                                        <span class="invalid-feedback text-danger" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                    <div class="col-md-4 form-control-comment right">
                                    </div>
                                </div>



                            </section>
                            <footer class="form-footer clearfix">
                                <div class="row no-gutters">
                                    <div class="col-md-10 offset-md-2">
                                        <input type="hidden" name="submitLogin" value="1">
                                        <button class="btn btn-primary" data-link-action="sign-in" type="submit" class="form-control-submit">
                                            Confirm
                                        </button>
                                     </div>

                                     </div>
                            </footer>
                        </form>

                                     <form method="POST" action="">
                                        @csrf 
                                     <button id="btnCounter" disabled>إعادة إرسال رمز التحقق  : <span id="count"></span></button>
                                    </form>

                                    @error('resend')
                                            <span  class="text-danger invalid-feedback" role="alert">
                                        <h1>{{ $message }}</h1>
                                    </span>
                                     @enderror
                                    
                    </section>      

                </section>
                <footer class="page-footer">
                    <!-- Footer content -->
                </footer>
            </div>
        </div>
    </div>
</div>
<br>
@stop
@section('scripts')
<script>
    var spn = document.getElementById("count");
var btn = document.getElementById("btnCounter");

var count = 10;     // Set count
var timer = null;  // For referencing the timer

(function countDown(){
  // Display counter and start counting down
  spn.textContent = count;
  
  // Run the function again every second if the count is not zero
  if(count !== 0){
    timer = setTimeout(countDown, 1000);
    count--; // decrease the timer
  } else {
    // Enable the button
    btn.removeAttribute('disabled');
  }
}());
</script>

@endsection