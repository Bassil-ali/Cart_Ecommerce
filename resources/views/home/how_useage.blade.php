@extends('layouts.home.app')

@section('content')

    <!--header-->

    <div class="breadcrumb-bar">
        <div class="container">
            <ol class="breadcrumb">
               <li class="breadcrumb-item"><a href="/"><i class="fa fa-home"></i></a></li>
               <li class="breadcrumb-item"> @lang('home.shipping_method')</li>
            </ol>
        </div>
    </div>

    <!--breadcrumb-bar-->
    {{-- expr --}}
@if ($how_useage->count() > 0){{-- expr --}}

    <section class="section_ticit_supp">
        <div class="container">
            <div class="content-faq">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                        @foreach ($all_how_useage as $use)
                            <li class="nav-item">
                                <a class="nav-link active" href="{{ route('How-Useage',$use->sub_category->id) }}" role="tab" aria-controls="tabOne" aria-selected="true">
                                    {{ $use->sub_category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade active show" id="tabOne" role="tabpanel" aria-labelledby="tabOne-tab">
                            <div class="title-fq">
                                    @foreach ($how_useage as $element)
    {{-- expr --}}
                                        <h2>{!! $element->description !!}</h2>

                                    @endforeach 
                            </div>
                        </div>
                      <div class="tab-pane fade" id="tabTow" role="tabpanel" aria-labelledby="tabTow-tab">2</div>
                      <div class="tab-pane fade" id="tabThree" role="tabpanel" aria-labelledby="tabThree-tab">3</div>
                    </div>
            </div>
        </div>
    </section>
@else

<section class="section_ticit_supp">
        <div class="container">
            <div class="content-faq">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                        @foreach ($all_how_useage as $use)
                            <li class="nav-item">
                                <a class="nav-link active" href="{{ route('How-Useage',$use->sub_category->id) }}" role="tab" aria-controls="tabOne" aria-selected="true">
                                    {{ $use->sub_category->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade active show" id="tabOne" role="tabpanel" aria-labelledby="tabOne-tab">
                            <div class="title-fq">
                                    <h2 class="text-center">@lang('dashboard.no_data_found')</h2>
                            </div>
                        </div>
                      <div class="tab-pane fade" id="tabTow" role="tabpanel" aria-labelledby="tabTow-tab">2</div>
                      <div class="tab-pane fade" id="tabThree" role="tabpanel" aria-labelledby="tabThree-tab">3</div>
                    </div>
            </div>
        </div>
    </section>

@endif

<!--section_ticit_supp-->

@endsection
        