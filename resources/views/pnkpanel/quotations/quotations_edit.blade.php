@extends('pnkpanel.layouts.app')
@section('content')

    <form action="{{ route('pnkpanel.manage-quotations.update') }}" method="post" enctype="multipart/form-data"
        name="frmRepresentative" id="frmRepresentative" class="ecommerce-form action-buttons-fixed">
        <input type="hidden" name="id" value="{{$quotations->quotation_id}}">
        <input type="hidden" name="actType" id="actType" value="{{ $quotations->quotation_id > 0 ? 'update' : 'add' }}">
        <input type="hidden" name="type" value="shop">
        <input type="hidden" id="is_delete" name="is_delete" value="no">
        <input type="hidden" id="del_chk" name="del_chk" value="">
        @csrf
        <div class="row">
            <div class="col">
                <section class="card">
                    <header class="card-header">
                        <h2 class="card-title">Quotation Information<!--Edit : {{ $quotations->title }}--></h2>
                    </header>
                    <div class="card-body">
                        <form class="form-horizontal form-bordered" method="get">
                            <div class="form-group row">
                                <label class="col-lg-12 control-label text-right mb-0" for="sourcing_product"><span class="required">*</span> <strong>Required Fields</strong></label>
                            </div>
                            <div class="form-group row">
                                <label class="col-lg-3 control-label text-lg-right pt-2" for="sourcing_product">Sourcing Product<span class="required">*</span></label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control" id="sourcing_product" name="sourcing_product" value="{{ old('sourcing_product', $quotations->sourcing_product) }}">
                                    @error('sourcing_product')
                                        <label class="error" for="sourcing_product" role="alert">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-lg-3 control-label text-lg-right pt-2" for="quantity">Quantity<span class="required">*</span></label>
                                <div class="col-lg-6">
                                    <input type="number" min="1" class="form-control @error('quantity') error @enderror" id="quantity" name="quantity" value="{{ old('quantity', $quotations->quantity) }}">
                                    @error('quantity')
                                        <label class="error" for="quantity" role="alert">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="form-group row">
                                <label class="col-lg-3 control-label text-lg-right pt-2" for="unit">Select Unit</label>
                                <div class="col-lg-6">
                                    <input type="text" class="form-control @error('unit') error @enderror" id="unit" name="unit" value="{{ old('unit', $quotations->unit) }}">
                                    <!-- <select name="unit" id="unit" class="form-control form-control-modern">
                                        <option value="">Select Unit</option>
                                        <option value="1" {{ old('unit', $quotations->unit) == '1' ? 'selected' : '' }}>Pieces</option>
                                        <option value="2" {{ old('unit', $quotations->unit) == '2' ? 'selected' : '' }}>20' Container</option>
                                        <option value="3" {{ old('unit', $quotations->unit) == '3' ? 'selected' : '' }}>40' Container</option>
                                        <option value="4" {{ old('unit', $quotations->unit) == '4' ? 'selected' : '' }}>40' HQ Container</option>
                                        <option value="5" {{ old('unit', $quotations->unit) == '5' ? 'selected' : '' }}>Acre/Acres</option>
                                        <option value="6" {{ old('unit', $quotations->unit) == '6' ? 'selected' : '' }}>Ampere/Amperes</option>
                                        <option value="7" {{ old('unit', $quotations->unit) == '7' ? 'selected' : '' }}>Bags</option>
                                        <option value="8" {{ old('unit', $quotations->unit) == '8' ? 'selected' : '' }}>Boxes</option>
                                        <option value="9" {{ old('unit', $quotations->unit) == '9' ? 'selected' : '' }}>Bushel/Bushels</option>
                                        <option value="10" {{ old('unit', $quotations->unit) == '10' ? 'selected' : '' }}>Carat/Carats</option>
                                        <option value="11" {{ old('unit', $quotations->unit) == '11' ? 'selected' : '' }}>Carton/Cartons</option>
                                        <option value="12" {{ old('unit', $quotations->unit) == '12' ? 'selected' : '' }}>Case/Cases</option>
                                        <option value="13" {{ old('unit', $quotations->unit) == '13' ? 'selected' : '' }}>Centimeter/Centimeters</option>
                                        <option value="14" {{ old('unit', $quotations->unit) == '14' ? 'selected' : '' }}>Chain/Chains</option>
                                        <option value="15" {{ old('unit', $quotations->unit) == '15' ? 'selected' : '' }}>Cubic Centimeter/Cubic Centimeters</option>
                                        <option value="16" {{ old('unit', $quotations->unit) == '16' ? 'selected' : '' }}>Cubic Foot/Cubic Feet</option>
                                        <option value="17" {{ old('unit', $quotations->unit) == '17' ? 'selected' : '' }}>Cubic Inch/Cubic Inches</option>
                                        <option value="18" {{ old('unit', $quotations->unit) == '18' ? 'selected' : '' }}>Cubic Meter/Cubic Meters</option>
                                        <option value="19" {{ old('unit', $quotations->unit) == '19' ? 'selected' : '' }}>Cubic Yard/Cubic Yards</option>
                                        <option value="20" {{ old('unit', $quotations->unit) == '20' ? 'selected' : '' }}>Degrees Celsius</option>
                                        <option value="21" {{ old('unit', $quotations->unit) == '21' ? 'selected' : '' }}>Degrees Fahrenheit</option>
                                        <option value="22" {{ old('unit', $quotations->unit) == '22' ? 'selected' : '' }}>Dozen/Dozens</option>
                                        <option value="23" {{ old('unit', $quotations->unit) == '23' ? 'selected' : '' }}>Dram/Drams</option>
                                        <option value="24" {{ old('unit', $quotations->unit) == '24' ? 'selected' : '' }}>Fluid Ounce/Fluid Ounces</option>
                                        <option value="25" {{ old('unit', $quotations->unit) == '25' ? 'selected' : '' }}>Foot</option>
                                        <option value="26" {{ old('unit', $quotations->unit) == '26' ? 'selected' : '' }}>Furlong/Furlongs</option>
                                        <option value="27" {{ old('unit', $quotations->unit) == '27' ? 'selected' : '' }}>Gallon/Gallons</option>
                                        <option value="28" {{ old('unit', $quotations->unit) == '28' ? 'selected' : '' }}>Gill/Gills</option>
                                        <option value="29" {{ old('unit', $quotations->unit) == '29' ? 'selected' : '' }}>Grain/Grains</option>
                                        <option value="30" {{ old('unit', $quotations->unit) == '30' ? 'selected' : '' }}>Gram/Grams</option>
                                        <option value="31" {{ old('unit', $quotations->unit) == '31' ? 'selected' : '' }}>Gross</option>
                                        <option value="32" {{ old('unit', $quotations->unit) == '32' ? 'selected' : '' }}>Hectare/Hectares</option>
                                        <option value="33" {{ old('unit', $quotations->unit) == '33' ? 'selected' : '' }}>Hertz</option>
                                        <option value="34" {{ old('unit', $quotations->unit) == '34' ? 'selected' : '' }}>Inch/Inches</option>
                                        <option value="35" {{ old('unit', $quotations->unit) == '35' ? 'selected' : '' }}>Kiloampere/Kiloamperes</option>
                                        <option value="36" {{ old('unit', $quotations->unit) == '36' ? 'selected' : '' }}>Kilogram/Kilograms</option>
                                        <option value="37" {{ old('unit', $quotations->unit) == '37' ? 'selected' : '' }}>Kilohertz</option>
                                        <option value="38" {{ old('unit', $quotations->unit) == '38' ? 'selected' : '' }}>Kilometer/Kilometers</option>
                                        <option value="39" {{ old('unit', $quotations->unit) == '39' ? 'selected' : '' }}>Kiloohm/Kiloohms</option>
                                        <option value="40" {{ old('unit', $quotations->unit) == '40' ? 'selected' : '' }}>Kilovolt/Kilovolts</option>
                                        <option value="41" {{ old('unit', $quotations->unit) == '41' ? 'selected' : '' }}>Kilowatt/Kilowatts</option>
                                        <option value="42" {{ old('unit', $quotations->unit) == '42' ? 'selected' : '' }}>Liter/Liters</option>
                                        <option value="43" {{ old('unit', $quotations->unit) == '43' ? 'selected' : '' }}>Long Ton/Long Tons</option>
                                        <option value="44" {{ old('unit', $quotations->unit) == '44' ? 'selected' : '' }}>Megahertz</option>
                                        <option value="45" {{ old('unit', $quotations->unit) == '45' ? 'selected' : '' }}>Meter</option>
                                        <option value="46" {{ old('unit', $quotations->unit) == '46' ? 'selected' : '' }}>Metric Ton/Metric Tons</option>
                                        <option value="47" {{ old('unit', $quotations->unit) == '47' ? 'selected' : '' }}>Mile/Miles</option>
                                        <option value="48" {{ old('unit', $quotations->unit) == '48' ? 'selected' : '' }}>Milliampere/Milliamperes</option>
                                        <option value="49" {{ old('unit', $quotations->unit) == '49' ? 'selected' : '' }}>Milligram/Milligrams</option>
                                        <option value="50" {{ old('unit', $quotations->unit) == '50' ? 'selected' : '' }}>Millihertz</option>
                                        <option value="51" {{ old('unit', $quotations->unit) == '51' ? 'selected' : '' }}>Milliliter/Milliliters</option>
                                        <option value="52" {{ old('unit', $quotations->unit) == '52' ? 'selected' : '' }}>Millimeter/Millimeters</option>
                                        <option value="53" {{ old('unit', $quotations->unit) == '53' ? 'selected' : '' }}>Milliohm/Milliohms</option>
                                        <option value="54" {{ old('unit', $quotations->unit) == '54' ? 'selected' : '' }}>Millivolt/Millivolts</option>
                                        <option value="55" {{ old('unit', $quotations->unit) == '55' ? 'selected' : '' }}>Milliwatt/Milliwatts</option>
                                        <option value="56" {{ old('unit', $quotations->unit) == '56' ? 'selected' : '' }}>Nautical Mile/Nautical Miles</option>
                                        <option value="57" {{ old('unit', $quotations->unit) == '57' ? 'selected' : '' }}>Ohm/Ohms</option>
                                        <option value="58" {{ old('unit', $quotations->unit) == '58' ? 'selected' : '' }}>Ounce/Ounces</option>
                                        <option value="59" {{ old('unit', $quotations->unit) == '59' ? 'selected' : '' }}>Pack/Packs</option>
                                        <option value="60" {{ old('unit', $quotations->unit) == '60' ? 'selected' : '' }}>Pairs</option>
                                        <option value="61" {{ old('unit', $quotations->unit) == '61' ? 'selected' : '' }}>Pallet/Pallets</option>
                                        <option value="62" {{ old('unit', $quotations->unit) == '62' ? 'selected' : '' }}>Parcel/Parcels</option>
                                        <option value="63" {{ old('unit', $quotations->unit) == '63' ? 'selected' : '' }}>Perch/Perches</option>
                                        <option value="64" {{ old('unit', $quotations->unit) == '64' ? 'selected' : '' }}>Pint/Pints</option>
                                        <option value="65" {{ old('unit', $quotations->unit) == '65' ? 'selected' : '' }}>Plant/Plants</option>
                                        <option value="66" {{ old('unit', $quotations->unit) == '66' ? 'selected' : '' }}>Pole/Poles</option>
                                        <option value="67" {{ old('unit', $quotations->unit) == '67' ? 'selected' : '' }}>Pound/Pounds</option>
                                        <option value="68" {{ old('unit', $quotations->unit) == '68' ? 'selected' : '' }}>Quart/Quarts</option>
                                        <option value="69" {{ old('unit', $quotations->unit) == '69' ? 'selected' : '' }}>Quarter/Quarters</option>
                                        <option value="70" {{ old('unit', $quotations->unit) == '70' ? 'selected' : '' }}>Reams</option>
                                        <option value="71" {{ old('unit', $quotations->unit) == '71' ? 'selected' : '' }}>Rod/Rods</option>
                                        <option value="72" {{ old('unit', $quotations->unit) == '72' ? 'selected' : '' }}>Rolls</option>
                                        <option value="73" {{ old('unit', $quotations->unit) == '73' ? 'selected' : '' }}>Sets</option>
                                        <option value="74" {{ old('unit', $quotations->unit) == '74' ? 'selected' : '' }}>Sheet/Sheets</option>
                                        <option value="75" {{ old('unit', $quotations->unit) == '75' ? 'selected' : '' }}>Short Ton/Short Tons</option>
                                        <option value="76" {{ old('unit', $quotations->unit) == '76' ? 'selected' : '' }}>Square Centimeter/Square Centimeters</option>
                                        <option value="77" {{ old('unit', $quotations->unit) == '77' ? 'selected' : '' }}>Square Feet</option>
                                        <option value="78" {{ old('unit', $quotations->unit) == '78' ? 'selected' : '' }}>Square Meters</option>
                                        <option value="79" {{ old('unit', $quotations->unit) == '79' ? 'selected' : '' }}>Square Inch/Square Inches</option>
                                        <option value="80" {{ old('unit', $quotations->unit) == '80' ? 'selected' : '' }}>Square Mile/Square Miles</option>
                                        <option value="81" {{ old('unit', $quotations->unit) == '81' ? 'selected' : '' }}>Square Yard/Square Yards</option>
                                        <option value="82" {{ old('unit', $quotations->unit) == '82' ? 'selected' : '' }}>Stone/Stones</option>
                                        <option value="83" {{ old('unit', $quotations->unit) == '83' ? 'selected' : '' }}>Strand/Strands</option>
                                        <option value="84" {{ old('unit', $quotations->unit) == '84' ? 'selected' : '' }}>Tonne/Tonnes</option>
                                        <option value="85" {{ old('unit', $quotations->unit) == '85' ? 'selected' : '' }}>Tons</option>
                                        <option value="86" {{ old('unit', $quotations->unit) == '86' ? 'selected' : '' }}>Tray/Trays</option>
                                        <option value="87" {{ old('unit', $quotations->unit) == '87' ? 'selected' : '' }}>Unit/Units</option>
                                        <option value="88" {{ old('unit', $quotations->unit) == '88' ? 'selected' : '' }}>Volt/Volts</option>
                                        <option value="89" {{ old('unit', $quotations->unit) == '89' ? 'selected' : '' }}>Watt/Watts</option>
                                        <option value="90" {{ old('unit', $quotations->unit) == '90' ? 'selected' : '' }}>Wp</option>
                                        <option value="91" {{ old('unit', $quotations->unit) == '91' ? 'selected' : '' }}>Yard/Yards</option>
                                        <option value="92" {{ old('unit', $quotations->unit) == '92' ? 'selected' : '' }}>Barrel/Barrels</option>
                                        <option value="93" {{ old('unit', $quotations->unit) == '93' ? 'selected' : '' }}>Others</option>
                                    </select> -->
                                    @error('unit')
                                        <label class="error" for="unit" role="alert">{{ $message }}</label>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row align-items-center">
                                <label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0" for="status">Status</label>
                                <div class="col-lg-7 col-xl-6">
                                    <select name="status" id="status" class="form-control form-control-modern">
                                        <option value="1" {{ old('status', $quotations->status) == '1' ? 'selected' : '' }}> Active</option>
                                        <option value="0" {{ old('status', $quotations->status) == '0' ? 'selected' : '' }}> Inactive</option>
                                    </select>
                                </div>
                            </div>
                    </div>
            </div>
        </div>
        <!--Added code for Category Beauty Images as on 06-10-2023 Start-->

      

    <!--Added code for Category Tile Beauty as on 06-10-2023 End-->
        <div class="row action-buttons">
            <div class="col-12 col-md-auto">
                <button type="submit"
                    class="submit-button btn btn-primary btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnSaveRecord"
                    data-loading-text="Loading..."> <i class="bx bx-save text-4 mr-2"></i> Save Quotation </button>
            </div>
            <div class="col-12 col-md-auto px-md-0 mt-3 mt-md-0"> <a href="javascript:void(0);"
                    class="cancel-button btn btn-light btn-px-4 py-3 border font-weight-semibold text-color-dark text-3 btnCancelSaveRecord">Cancel</a>
            </div>
            @if ($quotations->quotation_id > 0)
                <div class="col-12 col-md-auto ml-md-auto mt-3 mt-md-0"> <a href="javascript:void(0);"
                        data-id="{{ $quotations->quotation_id }}"
                        class="delete-button btn btn-danger btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnDeleteRecord">
                        <i class="bx bx-trash text-4 mr-2"></i> Delete Quotation </a> </div>
        </div>
        @endif
    </form>
@endsection

@push('scripts')
    <script>
        let url_list = "{{ route('pnkpanel.manage-quotations.list') }}";
        let url_edit = "{{ route('pnkpanel.manage-quotations.edit', ':id') }}";
        let url_update = "{{ route('pnkpanel.manage-quotations.update') }}";
        let url_delete = "{{ route('pnkpanel.manage-quotations.delete', ':id') }}";
        let url_bulk_action = "{{ route('pnkpanel.manage-static-page.bulk_action') }}";
    </script>
    <script src="{{ asset('pnkpanel/js/quotations_edit.js') }}"></script>
    <script type="text/javascript">
        function add_shop_row(type) {
        var beauty_rows = parseInt($("#" + type + "_count").val());

        if (beauty_rows > 0) {
            var index = beauty_rows + 1;
        } else {
            var index = 1;
        }

        var rclass = 'odd';
        if (beauty_rows % 2 == 0) {
            rclass = 'even';
        }

        var shop_html = '<tr role="row" class="' + rclass + '" id="' + type + '_row' + index + '"><td><input type="checkbox" class="list_checkbox checkbox-style-1 p-relative top-2" id="' + type + '_chkbox' + beauty_rows + '" name="' + type + '_chkbox' + beauty_rows + '" value="" checked></td><td>Question ' + index + ': <input type="text" class="form-control " id="' + type + '_question' + beauty_rows + '" name="' + type + '_question' + beauty_rows + '" value=""></td><td>Answer ' + index + ': <input type="text" class="form-control " id="' + type + '_answer' + beauty_rows + '" name="' + type + '_answer' + beauty_rows + '" value=""></td><tr>';

        if (beauty_rows > 0) {
            $("#" + type + "_row" + beauty_rows).after(shop_html);
        } else {
            $("#" + type + "_row").after(shop_html);
        }

        $("#" + type + "_count").val(index);
    }


    function delete_shop_row(type) {
        var beauty_rows = parseInt($("#" + type + "_count").val());
        var org_beauty_rows = parseInt($("#" + type + "_count_org").val());
        var delete_row = 0;
        var chbox_sel = 'no';
        var del_flag = "false";

        var del_chk = $('.list_checkbox:checked').map(function() {
            return this.value;
        }).get();

        for (var d = 0; d < beauty_rows; d++) {
            if ($("#" + type + "_chkbox" + d).prop('checked') == true) {
                chbox_sel = 'true';
                if (d >= org_beauty_rows) {
                    $("#" + type + "_row" + (d + 1)).remove();
                    delete_row = delete_row + 1;
                } else {
                    del_flag = "true";
                }
            }
        }

        if (del_flag == "true") {
            var ans = window.confirm("Are you sure you want to delete?");
            if (ans) {
                $("#is_delete").val("yes");
                $("#del_chk").val(del_chk);
                $(".btnSaveRecord").trigger('click');
            }
        }

        if (chbox_sel == 'no') {
            alert('Please select checkbox to delete.');
            return false;
        }
        if (delete_row > 0) {
            $("#" + type + "_count").val((beauty_rows - delete_row));
        }
    }
    </script>
@endpush
