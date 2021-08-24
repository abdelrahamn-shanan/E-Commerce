@extends('layouts.admin')
@section('content')

    <div class="app-content content">
        <div class="content-wrapper">
            <div class="content-header row">
                <div class="content-header-left col-md-6 col-12 mb-2">
                    <div class="row breadcrumbs-top">
                        <div class="breadcrumb-wrapper col-12">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{route('admin.dashboard')}}">الرئيسية </a>
                                </li>
                                <li class="breadcrumb-item"><a href="{{route('index.product')}}">
                                        المنتجات </a>
                                </li>
                                <li class="breadcrumb-item active"> أضافه منتج
                                </li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
            <div class="content-body">
                <!-- Basic form layout section start -->
                <section id="basic-form-layouts">
                    <div class="row match-height">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title" id="basic-layout-form"> أضافة منتج جديد </h4>
                                    <a class="heading-elements-toggle"><i
                                            class="la la-ellipsis-v font-medium-3"></i></a>
                                    <div class="heading-elements">
                                        <ul class="list-inline mb-0">
                                            <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                            <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                            <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                            <li><a data-action="close"><i class="ft-x"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                                @include('dashboard.includes.alerts.success')
                                @include('dashboard.includes.alerts.errors')
                                <div class="card-content collapse show">
                                    <div class="card-body">
                                        <form class="form"
                                              action="{{route('price.store.product')}}"
                                              method="POST"
                                              enctype="multipart/form-data">
                                            @csrf

                                            <input type="hidden" name="product_id" value="{{$product_id->id}}">
                                            <div class="form-body">

                                                <h4 class="form-section"><i class="ft-home"></i> البيانات الاساسية للمنتج   </h4>
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="projectinput1"> سعر  المنتج
                                                            </label>
                                                            <input type="number" id="price"
                                                                   class="form-control"
                                                                   placeholder="Required"
                                                                   value="{{$product_id->price}}"
                                                                   name="price">
                                                            @error("price")
                                                            <span class="text-danger">{{$message}}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                     
                                                    
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="projectinput1">  هل يوجد سعر خاص ؟
                                                            </label>
                                                            <select    id = "special_price_exist" name="special_price_exist"  class="select2 form-control"  >
                                                                <optgroup >
                                                                    <option name="special_price"  value=0 @if ($product_id->special_price == null) {{ 'selected' }} @endif>لا يوجد سعر خاص</option>
                                                                    <option name="special_price"  value=1  @if ($product_id->special_price != null) {{ 'selected' }} @endif  >نعم يوجد سعر خاص</option>
                                                                </optgroup>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>

                                            <div id="dvspecial_price" style="display: none">
                                                <div class="row" >
                                                     <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="projectinput1"> السعر الخاص  
                                                            </label>
                                                            <input type="number" id="special_price"
                                                                   class="form-control"
                                                                   placeholder=""
                                                                   value="{{$product_id->special_price}}"
                                                                   name="special_price">
                                                            @error("special_price")
                                                            <span class="text-danger">{{$message}}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                        <label for="projectinput1">  نوع السعر  
                                                            </label> <br>
                                                            <select  name="special_price_type" class="select2 form-control" multiple>
                                                                <optgroup label="من فضلك أختر النوع ">
                                                                    <option value="percent" @if ($product_id->special_price_type == "percent") {{ 'selected' }} @endif >precent</option>
                                                                    <option value="fixed"  @if ($product_id->special_price_type == "fixed") {{ 'selected' }} @endif >fixed</option>
                                                                </optgroup>
                                                            </select>
                                                            @error('special_price_type')
                                                            <span class="text-danger"> {{$message}}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="row" >
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="projectinput1"> تاريخ البداية
                                                            </label>

                                                            <input type="date"  id="price"
                                                                   class="form-control"
                                                                   placeholder="  "
                                                                   value="{{$product_id->special_price_start}}"
                                                                   name="special_price_start">

                                                            @error('special_price_start')
                                                            <span class="text-danger"> {{$message}}</span>
                                                            @enderror
                                                        </div>
                                                    </div>

                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <label for="projectinput1"> تاريخ النهاية
                                                            </label>
                                                            <input type="date"  id="price"
                                                                   class="form-control"
                                                                   placeholder="  "
                                                                   value="{{$product_id->special_price_end}}"
                                                                   name="special_price_end">

                                                            @error('special_price_end')
                                                            <span class="text-danger"> {{$message}}</span>
                                                            @enderror
                                                        </div>
                                                    </div>
                                                </div>


                                            </div>
                                            </div>


                                            <div class="form-actions">
                                                <button type="button" class="btn btn-warning mr-1"
                                                        onclick="history.back();">
                                                    <i class="ft-x"></i> تراجع
                                                </button>
                                                <button type="submit" class="btn btn-primary">
                                                    <i class="la la-check-square-o"></i> حفظ
                                                </button>
                                            </div>
                                        </form>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                <!-- // Basic form layout section end -->
            </div>
        </div>
    </div>

@stop

@section('script')
    <script>
    if((document.getElementById("special_price_exist").value) == "1"){
        $('#dvspecial_price').show();

    }else{
        $('#dvspecial_price').hide();
    }
    $(document).on('change','#special_price_exist',function(){
           if($(this).val() == 1 ){
                $('#dvspecial_price').show();
           }else{
               $('#dvspecial_price').hide();
           }
        });
    
      </script>
    @stop