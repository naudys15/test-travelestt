@extends('back_office.layouts.template')

@section('content')
    <div class="row" id="setLanguage" onclick="setLanguage('spanish')">
        <!-- informacion -->
        <div class="col-lg-12">
            <div class="panel">
                <div class="panel-body">
                	<h4 class="mar-btm">{{ __('language.permissions_role') }}</h4>
					<div id="permissions"></div>
            	</div>
            </div>
        </div>
    </div>
    <div id="loading_modal" class="modal fade in" tabindex="-1" data-backdrop="static">
        <div class="modal-dialog modal-lg">
            <div class="modal-content modal-backdrop">
                <div class="modal-body">
                    <div class="d-flex justify-content-center">
                        <div class="lds-ring"><div></div><div></div><div></div><div></div></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
@endsection