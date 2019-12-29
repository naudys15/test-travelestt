@extends('back_office.layouts.template')
@section('content')
    <div class="panel">
        <div class="panel-body">
            @php
                $permissions = unserialize(session('permissions'));
                $add = permissionValid($permissions, 33);
                $edit = permissionValid($permissions, 34);
                $delete = permissionValid($permissions, 36);
            @endphp
            <div class="col-xs-12" id="setLanguage" onclick="setLanguage($('html').attr('lang'))">
                <input type="hidden" name="edit" id="val_edit" value="{{$edit}}">
                @if($add)
                    <a href="#" id="btn_add" class="btn btn-success sep_button_panel">{{ __('language.add') }}</a>
                @else
                    <button disabled class="btn btn-success sep_button_panel">{{ __('language.add') }}</button>
                @endif
                @if($delete)
                    <button id="btn_delete_rows" onclick="eraseRol()" class="btn btn-default" disabled><i class="ti-trash"></i> {{ __('language.delete') }}</button>
                @else
                    <button class="btn btn-default" disabled><i class="ti-trash"></i> {{ __('language.delete') }}</button>
                @endif
                <table id="table_content" @if (app()->getLocale() == 'es') data-locale="es-ES" @elseif (app()->getLocale() == 'en') data-locale="en-EN" @elseif (app()->getLocale() == 'fr') data-locale="fr-FR"@endif>
                    <thead>
                        <tr>
                        <th width="5%" data-field="id" data-checkbox="true">Id</th>
                        </tr>
                    </thead>
                </table>
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