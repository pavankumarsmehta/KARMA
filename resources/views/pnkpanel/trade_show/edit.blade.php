@extends('pnkpanel.layouts.app')
@section('content')


<form action="{{ route('pnkpanel.trade-show.update') }}" method="post" name="frmTradeShow" id="frmTradeShow" enctype="multipart/form-data" class="ecommerce-form action-buttons-fixed">
	<input type="hidden" name="treadeshow_id" value="{{ $tradeshow->treadeshow_id }}">
	<input type="hidden" name="actType" id="actType" value="{{ $tradeshow->treadeshow_id > 0 ? 'update' : 'add' }}">
	<input type="hidden" id="is_delete" name="is_delete" value="no">
	@csrf

	<div class="row">
		<div class="col">
			<section class="card">
				<header class="card-header">
					<div class="card-actions">
						<a href="#" class="card-action card-action-toggle" data-card-toggle=""></a>
					</div>
					<h2 class="card-title">Trade Show Information</h2>
				</header>
				<div class="card-body">
					<div class="form-group row">
						<label class="col-lg-12 control-label text-right mb-0"><span class="required">*</span> <strong>Required Fields</strong></label>
					</div>

					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="treadeshow_name">Tradeshow Name <span class="required">*</span></label>
						<div class="col-lg-6">
							<input type="text" maxlength="100" class="form-control @error('treadeshow_name') error @enderror" id="treadeshow_name" name="treadeshow_name" value="{{ old('treadeshow_name', $tradeshow->treadeshow_name) }}">
							@error('treadeshow_name')
							<label class="error" for="treadeshow_name" role="alert">{{ $message }}</label>
							@enderror
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="tradeshow_logo">Tradeshow Logo Image</label>
						<div class="col-lg-6">
							<div class="fileupload @if (!empty($tradeshow->tradeshow_logo) && File::exists(config('const.TRADESHOW_IMAGE_PATH').$tradeshow->tradeshow_logo)) fileupload-exists @else fileupload-new @endif" data-provides="fileupload">
								<div class="input-append">
									<div class="uneditable-input">
										<i class="fas fa-file fileupload-exists"></i>
										<span class="fileupload-preview">@if (!empty($tradeshow->tradeshow_logo) && File::exists(config('const.TRADESHOW_IMAGE_PATH').$tradeshow->tradeshow_logo)) {{ $tradeshow->tradeshow_logo }} @endif</span>
									</div>
									<span class="btn btn-default btn-file">
										<span class="fileupload-exists">Change</span>
										<span class="fileupload-new">Select file</span>
										<input type="file" name="tradeshow_logo" id="tradeshow_logo">
									</span>
									@if (!empty($tradeshow->tradeshow_logo) && File::exists(config('const.TRADESHOW_IMAGE_PATH').$tradeshow->tradeshow_logo))
									<a href="#" class="btn btn-default fileupload-view btnViewImage" data-type="tradeshow_logo" data-subtype="tradeshow_logo" data-id="{{ $tradeshow->treadeshow_id }}" data-src="{{ config('const.TRADESHOW_IMAGE_URL').$tradeshow->tradeshow_logo }}" data-caption="Brand Logo Image">View</a>
									@endif
									<a href="#" class="btn btn-default fileupload-exists btnDeleteImage" data-type="tradeshow_logo" data-subtype="tradeshow_logo" data-id="{{ $tradeshow->treadeshow_id }}" data-image-name="{{ $tradeshow->tradeshow_logo }}" data-dismiss="fileupload">Remove</a>
								</div>
								<span class="help-block">(Note: Recommended Image size should be {{ config('const.TRADESHOW_IMAGE_THUMB_WIDTH')}} X {{ config('const.TRADESHOW_IMAGE_THUMB_HEIGHT')}})</span>
								@error('tradeshow_logo')
								<label class="error" for="tradeshow_logo" role="alert">{{ $message }}</label>
								@enderror
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="booth_no">Booth No</label>
						<div class="col-lg-6">
							<input type="text" maxlength="50" class="form-control @error('booth_no') error @enderror" id="booth_no" name="booth_no" value="{{ old('booth_no', $tradeshow->booth_no) }}">
							@error('booth_no')
							<label class="error" for="booth_no" role="alert">{{ $message }}</label>
							@enderror
						</div>
					</div>
					<?php
						$start_time   = "7:00";
						$end_time     = "23:30";
						$interval     = "30";

						$from_start_time  = $to_start_time = strtotime($start_time);
						$from_end_time    = $to_end_time   = strtotime($end_time);
						$duration         = $interval * 60;
					?>
					<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="date1">Date 1</label>
							<div class="col-lg-3">
								<div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{"format": "mm/dd/yyyy"}'>
									<span class="input-group-prepend">
										<span class="input-group-text">
											<i class="fas fa-calendar-alt"></i>
										</span>
									</span>
									<input type="text" class="form-control" name="date1" id="date1" value="{{ old('date1', (!empty($tradeshow->date1)) ? date('m/d/Y',strtotime($tradeshow->date1)) : $tradeshow->date1) }}">
									@error('date1')
										<label class="error" for="date1" role="alert">{{ $message }}</label>
									@enderror
								</div>
							</div>
							<div class="col-lg-3">
								<div class="input-group">
									<span class="input-group-text border-left-0 border-right-0 rounded-0">
										From 1
									</span>
									<select class="form-control"  name="from_time1" id="from_time1">
									<?
									while($from_start_time <= $from_end_time)
									{?>
										<option value="<?=date("h:i:s", $from_start_time);?>" <? if($tradeshow->from_time1==date("h:i:s", $from_start_time)){echo "Selected";}?>><?=date("g:i A", $from_start_time);?></option>
										<?php
										$from_start_time = $from_start_time + $duration;
									}?>
									</select>
									<!-- <input type="text" class="form-control" name="from_time1" id="from_time1" value="{{ old('from_time1', $tradeshow->from_time1) }}"> -->
									@error('from_time1')
										<label class="error" for="from_time1" role="alert">{{ $message }}</label>
									@enderror
								</div>
							</div>
							<div class="col-lg-3">
								<div class="input-group">
									<span class="input-group-text border-left-0 border-right-0 rounded-0">
										to 1
									</span>
									<select class="form-control"  name="to_time1" id="to_time1">
										<?
										while($to_start_time <= $to_end_time)
										{?>
											<option value="<?=date("h:i:s", $to_start_time);?>" <? if($tradeshow->to_time1==date("h:i:s", $to_start_time)){echo "Selected";}?>><?=date("g:i A", $to_start_time);?></option>
											<?
											$to_start_time = $to_start_time + $duration;
										}?>
										</select>
									@error('to_time1')
										<label class="error" for="to_time1" role="alert">{{ $message }}</label>
									@enderror
								</div>
							</div>
					</div>
						<?php
							$start_time   = "7:00";
							$end_time     = "23:30";
							$interval     = "30";

							$from_start_time  = $to_start_time = strtotime($start_time);
							$from_end_time    = $to_end_time   = strtotime($end_time);
							$duration         = $interval * 60;
						?>
						<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="date2">Date 2</label>
							<div class="col-lg-3">
								<div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{"format": "mm/dd/yyyy"}'>
									<span class="input-group-prepend">
										<span class="input-group-text">
											<i class="fas fa-calendar-alt"></i>
										</span>
									</span>
									<input type="text" class="form-control" name="date2" id="date2" value="{{ old('date2', (!empty($tradeshow->date7)) ? date('m/d/Y',strtotime($tradeshow->date2)): $tradeshow->date2 ) }}">
									@error('date2')
										<label class="error" for="date2" role="alert">{{ $message }}</label>
									@enderror
								</div>
							</div>
							<div class="col-lg-3">
								<div class="input-group">
									<span class="input-group-text border-left-0 border-right-0 rounded-0">
										From 2
									</span>
									<select class="form-control"  name="from_time2" id="from_time2">
									<?
									while($from_start_time <= $from_end_time)
									{?>
										<option value="<?=date("h:i:s", $from_start_time);?>" <? if($tradeshow->from_time2==date("h:i:s", $from_start_time)){echo "Selected";}?>><?=date("g:i A", $from_start_time);?></option>
										<?php
										$from_start_time = $from_start_time + $duration;
									}?>
									</select>
									<!-- <input type="text" class="form-control" name="from_time1" id="from_time1" value="{{ old('from_time1', $tradeshow->from_time1) }}"> -->
									@error('from_time2')
										<label class="error" for="from_time2" role="alert">{{ $message }}</label>
									@enderror
								</div>
							</div>
							<div class="col-lg-3">
								<div class="input-group">
									<span class="input-group-text border-left-0 border-right-0 rounded-0">
										to 2
									</span>
									<select class="form-control"  name="to_time2" id="to_time2">
										<?
										while($to_start_time <= $to_end_time)
										{?>
											<option value="<?=date("h:i:s", $to_start_time);?>" <? if($tradeshow->to_time2==date("h:i:s", $to_start_time)){echo "Selected";}?>><?=date("g:i A", $to_start_time);?></option>
											<?
											$to_start_time = $to_start_time + $duration;
										}?>
										</select>
									@error('to_time2')
										<label class="error" for="to_time2" role="alert">{{ $message }}</label>
									@enderror
								</div>
							</div>
					</div>
					<?php
						$start_time   = "7:00";
						$end_time     = "23:30";
						$interval     = "30";

						$from_start_time  = $to_start_time = strtotime($start_time);
						$from_end_time    = $to_end_time   = strtotime($end_time);
						$duration         = $interval * 60;
					?>
					<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="date3">Date 3</label>
							<div class="col-lg-3">
								<div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{"format": "mm/dd/yyyy"}'>
									<span class="input-group-prepend">
										<span class="input-group-text">
											<i class="fas fa-calendar-alt"></i>
										</span>
									</span>
									<input type="text" class="form-control" name="date3" id="date3" value="{{ old('date3', (!empty($tradeshow->date3)) ? date('m/d/Y',strtotime($tradeshow->date3)) : $tradeshow->date3) }}">
									@error('date2')
										<label class="error" for="date3" role="alert">{{ $message }}</label>
									@enderror
								</div>
							</div>
							<div class="col-lg-3">
								<div class="input-group">
									<span class="input-group-text border-left-0 border-right-0 rounded-0">
										From 3
									</span>
									<select class="form-control"  name="from_time3" id="from_time3">
									<?
									while($from_start_time <= $from_end_time)
									{?>
										<option value="<?=date("h:i:s", $from_start_time);?>" <? if($tradeshow->from_time3==date("h:i:s", $from_start_time)){echo "Selected";}?>><?=date("g:i A", $from_start_time);?></option>
										<?php
										$from_start_time = $from_start_time + $duration;
									}?>
									</select>
									<!-- <input type="text" class="form-control" name="from_time1" id="from_time1" value="{{ old('from_time1', $tradeshow->from_time1) }}"> -->
									@error('from_time3')
										<label class="error" for="from_time3" role="alert">{{ $message }}</label>
									@enderror
								</div>
							</div>
							<div class="col-lg-3">
								<div class="input-group">
									<span class="input-group-text border-left-0 border-right-0 rounded-0">
										to 4
									</span>
									<select class="form-control"  name="to_time4" id="to_time4">
										<?
										while($to_start_time <= $to_end_time)
										{?>
											<option value="<?=date("h:i:s", $to_start_time);?>" <? if($tradeshow->to_time4==date("h:i:s", $to_start_time)){echo "Selected";}?>><?=date("g:i A", $to_start_time);?></option>
											<?
											$to_start_time = $to_start_time + $duration;
										}?>
										</select>
									@error('to_time4')
										<label class="error" for="to_time4" role="alert">{{ $message }}</label>
									@enderror
								</div>
							</div>
					</div>
					<?php
						$start_time   = "7:00";
						$end_time     = "23:30";
						$interval     = "30";

						$from_start_time  = $to_start_time = strtotime($start_time);
						$from_end_time    = $to_end_time   = strtotime($end_time);
						$duration         = $interval * 60;
					?>
					<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="date4">Date 4</label>
							<div class="col-lg-3">
								<div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{"format": "mm/dd/yyyy"}'>
									<span class="input-group-prepend">
										<span class="input-group-text">
											<i class="fas fa-calendar-alt"></i>
										</span>
									</span>
									<input type="text" class="form-control" name="date4" id="date4" value="{{ old('date4',  (!empty($tradeshow->date4)) ? date('m/d/Y',strtotime($tradeshow->date4)) : $tradeshow->date4) }}">
									@error('date2')
										<label class="error" for="date4" role="alert">{{ $message }}</label>
									@enderror
								</div>
							</div>
							<div class="col-lg-3">
								<div class="input-group">
									<span class="input-group-text border-left-0 border-right-0 rounded-0">
										From 4
									</span>
									<select class="form-control"  name="from_time4" id="from_time4">
									<?
									while($from_start_time <= $from_end_time)
									{?>
										<option value="<?=date("h:i:s", $from_start_time);?>" <? if($tradeshow->from_time4==date("h:i:s", $from_start_time)){echo "Selected";}?>><?=date("g:i A", $from_start_time);?></option>
										<?php
										$from_start_time = $from_start_time + $duration;
									}?>
									</select>
									<!-- <input type="text" class="form-control" name="from_time1" id="from_time1" value="{{ old('from_time1', $tradeshow->from_time1) }}"> -->
									@error('from_time4')
										<label class="error" for="from_time4" role="alert">{{ $message }}</label>
									@enderror
								</div>
							</div>
							<div class="col-lg-3">
								<div class="input-group">
									<span class="input-group-text border-left-0 border-right-0 rounded-0">
										to 3
									</span>
									<select class="form-control"  name="to_time3" id="to_time3">
										<?
										while($to_start_time <= $to_end_time)
										{?>
											<option value="<?=date("h:i:s", $to_start_time);?>" <? if($tradeshow->to_time3==date("h:i:s", $to_start_time)){echo "Selected";}?>><?=date("g:i A", $to_start_time);?></option>
											<?
											$to_start_time = $to_start_time + $duration;
										}?>
										</select>
									@error('to_time3')
										<label class="error" for="to_time3" role="alert">{{ $message }}</label>
									@enderror
								</div>
							</div>
					</div>
					<?php
						$start_time   = "7:00";
						$end_time     = "23:30";
						$interval     = "30";

						$from_start_time  = $to_start_time = strtotime($start_time);
						$from_end_time    = $to_end_time   = strtotime($end_time);
						$duration         = $interval * 60;
					?>
					<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="date6">Date 5</label>
							<div class="col-lg-3">
								<div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{"format": "mm/dd/yyyy"}'>
									<span class="input-group-prepend">
										<span class="input-group-text">
											<i class="fas fa-calendar-alt"></i>
										</span>
									</span>
									<input type="text" class="form-control" name="date6" id="date6" value="{{ old('date6', (!empty($tradeshow->date5)) ? date('m/d/Y',strtotime($tradeshow->date5)) : $tradeshow->date5) }}">
									@error('date2')
										<label class="error" for="date6" role="alert">{{ $message }}</label>
									@enderror
								</div>
							</div>
							<div class="col-lg-3">
								<div class="input-group">
									<span class="input-group-text border-left-0 border-right-0 rounded-0">
										From 5
									</span>
									<select class="form-control"  name="from_time5" id="from_time5">
									<?
									while($from_start_time <= $from_end_time)
									{?>
										<option value="<?=date("h:i:s", $from_start_time);?>" <? if($tradeshow->from_time5==date("h:i:s", $from_start_time)){echo "Selected";}?>><?=date("g:i A", $from_start_time);?></option>
										<?php
										$from_start_time = $from_start_time + $duration;
									}?>
									</select>
									<!-- <input type="text" class="form-control" name="from_time1" id="from_time1" value="{{ old('from_time1', $tradeshow->from_time1) }}"> -->
									@error('from_time5')
										<label class="error" for="from_time5" role="alert">{{ $message }}</label>
									@enderror
								</div>
							</div>
							<div class="col-lg-3">
								<div class="input-group">
									<span class="input-group-text border-left-0 border-right-0 rounded-0">
										to 5
									</span>
									<select class="form-control"  name="to_time5" id="to_time5">
										<?
										while($to_start_time <= $to_end_time)
										{?>
											<option value="<?=date("h:i:s", $to_start_time);?>" <? if($tradeshow->to_time5==date("h:i:s", $to_start_time)){echo "Selected";}?>><?=date("g:i A", $to_start_time);?></option>
											<?
											$to_start_time = $to_start_time + $duration;
										}?>
										</select>
									@error('to_time5')
										<label class="error" for="to_time5" role="alert">{{ $message }}</label>
									@enderror
								</div>
							</div>
					</div>
					<?php
						$start_time   = "7:00";
						$end_time     = "23:30";
						$interval     = "30";

						$from_start_time  = $to_start_time = strtotime($start_time);
						$from_end_time    = $to_end_time   = strtotime($end_time);
						$duration         = $interval * 60;
					?>
					<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="date5">Date 6</label>
							<div class="col-lg-3">
								<div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{"format": "mm/dd/yyyy"}'>
									<span class="input-group-prepend">
										<span class="input-group-text">
											<i class="fas fa-calendar-alt"></i>
										</span>
									</span>
									<input type="text" class="form-control" name="date5" id="date5" value="{{ old('date5',(!empty($tradeshow->date6)) ? date('m/d/Y',strtotime($tradeshow->date6)) : $tradeshow->date6) }}">
									@error('date2')
										<label class="error" for="date5" role="alert">{{ $message }}</label>
									@enderror
								</div>
							</div>
							<div class="col-lg-3">
								<div class="input-group">
									<span class="input-group-text border-left-0 border-right-0 rounded-0">
										From 6
									</span>
									<select class="form-control"  name="from_time6" id="from_time6">
									<?
									while($from_start_time <= $from_end_time)
									{?>
										<option value="<?=date("h:i:s", $from_start_time);?>" <? if($tradeshow->from_time6==date("h:i:s", $from_start_time)){echo "Selected";}?>><?=date("g:i A", $from_start_time);?></option>
										<?php
										$from_start_time = $from_start_time + $duration;
									}?>
									</select>
									<!-- <input type="text" class="form-control" name="from_time1" id="from_time1" value="{{ old('from_time1', $tradeshow->from_time1) }}"> -->
									@error('from_time6')
										<label class="error" for="from_time6" role="alert">{{ $message }}</label>
									@enderror
								</div>
							</div>
							<div class="col-lg-3">
								<div class="input-group">
									<span class="input-group-text border-left-0 border-right-0 rounded-0">
										to 6
									</span>
									<select class="form-control"  name="to_time6" id="to_time6">
										<?
										while($to_start_time <= $to_end_time)
										{?>
											<option value="<?=date("h:i:s", $to_start_time);?>" <? if($tradeshow->to_time6==date("h:i:s", $to_start_time)){echo "Selected";}?>><?=date("g:i A", $to_start_time);?></option>
											<?
											$to_start_time = $to_start_time + $duration;
										}?>
										</select>
									@error('to_time6')
										<label class="error" for="to_time6" role="alert">{{ $message }}</label>
									@enderror
								</div>
							</div>
					</div>
					<?php
						$start_time   = "7:00";
						$end_time     = "23:30";
						$interval     = "30";

						$from_start_time  = $to_start_time = strtotime($start_time);
						$from_end_time    = $to_end_time   = strtotime($end_time);
						$duration         = $interval * 60;
					?>
					<div class="form-group row">
							<label class="col-lg-3 control-label text-lg-right pt-2" for="date7">Date 7</label>
							<div class="col-lg-3">
								<div class="input-daterange input-group" data-plugin-datepicker data-plugin-options='{"format": "mm/dd/yyyy"}'>
									<span class="input-group-prepend">
										<span class="input-group-text">
											<i class="fas fa-calendar-alt"></i>
										</span>
									</span>
									<input type="text" class="form-control" name="date7" id="date7" value="{{ old('date7', (!empty($tradeshow->date7)) ? date('m/d/Y',strtotime($tradeshow->date7)): $tradeshow->date7)   }}">
									@error('date2')
										<label class="error" for="date7" role="alert">{{ $message }}</label>
									@enderror
								</div>
							</div>
							<div class="col-lg-3">
								<div class="input-group">
									<span class="input-group-text border-left-0 border-right-0 rounded-0">
										From 7
									</span>
									<select class="form-control"  name="from_time7" id="from_time7">
									<?
									while($from_start_time <= $from_end_time)
									{?>
										<option value="<?=date("h:i:s", $from_start_time);?>" <? if($tradeshow->from_time7==date("h:i:s", $from_start_time)){echo "Selected";}?>><?=date("g:i A", $from_start_time);?></option>
										<?php
										$from_start_time = $from_start_time + $duration;
									}?>
									</select>
									<!-- <input type="text" class="form-control" name="from_time1" id="from_time1" value="{{ old('from_time1', $tradeshow->from_time1) }}"> -->
									@error('from_time7')
										<label class="error" for="from_time7" role="alert">{{ $message }}</label>
									@enderror
								</div>
							</div>
							<div class="col-lg-3">
								<div class="input-group">
									<span class="input-group-text border-left-0 border-right-0 rounded-0">
										to 7
									</span>
									<select class="form-control"  name="to_time7" id="to_time7">
										<?
										while($to_start_time <= $to_end_time)
										{?>
											<option value="<?=date("h:i:s", $to_start_time);?>" <? if($tradeshow->to_time7==date("h:i:s", $to_start_time)){echo "Selected";}?>><?=date("g:i A", $to_start_time);?></option>
											<?
											$to_start_time = $to_start_time + $duration;
										}?>
										</select>
									@error('to_time7')
										<label class="error" for="to_time7" role="alert">{{ $message }}</label>
									@enderror
								</div>
							</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="appointment_url">Appointment URL</label>
						<div class="col-lg-6">
							<input type="url"  class="form-control @error('appointment_url') error @enderror" id="appointment_url" name="appointment_url" value="{{ old('appointment_url', $tradeshow->appointment_url) }}">
							@error('appointment_url')
							<label class="error" for="appointment_url" role="alert">{{ $message }}</label>
							@enderror
						</div>
						
					</div>
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="address1">Address1 <span class="required">*</span></label>
						<div class="col-lg-9">
							<textarea name="address1" id="address1" class="mceEditor" cols="40" rows="5">{{ stripslashes(old('address1', $tradeshow->address1)) }}</textarea>
							@error('address1')
							<label class="error" for="address1" role="alert">{{ $message }}</label>
							@enderror
						</div>
					</div>
					
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="address2">Address2</label>
						<div class="col-lg-9">
							<textarea name="address2" id="address2" class="mceEditor" cols="40" rows="5">{{ stripslashes(old('address2', $tradeshow->address2)) }}</textarea>
							@error('address2')
							<label class="error" for="address1" role="alert">{{ $message }}</label>
							@enderror
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="city">City <span class="required">*</span></label>
						<div class="col-lg-6">
							<input type="text"  maxlength="50"  class="form-control @error('city') error @enderror" id="city" name="city" value="{{ old('city', $tradeshow->city) }}">
							@error('city')
							<label class="error" for="city" role="alert">{{ $message }}</label>
							@enderror
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="state">State <span class="required">*</span></label>
						<div class="col-lg-6">
							<input type="text"   maxlength="50" class="form-control @error('state') error @enderror" id="state" name="state" value="{{ old('state', $tradeshow->state) }}">
							@error('state')
							<label class="error" for="state" role="alert">{{ $message }}</label>
							@enderror
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="country">Country <span class="required">*</span></label>
						<div class="col-lg-6">
							<input type="text"  maxlength="50"   class="form-control @error('state') error @enderror" id="country" name="country" value="{{ old('country', $tradeshow->country) }}">
							@error('country')
							<label class="error" for="country" role="alert">{{ $message }}</label>
							@enderror
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="zip">Zip <span class="required">*</span></label>
						<div class="col-lg-6">
							<input type="number" maxlength="8"   class="form-control @error('zip') error @enderror" id="zip" name="zip" value="{{ old('zip', $tradeshow->zip) }}">
							@error('country')
							<label class="error" for="zip" role="alert">{{ $message }}</label>
							@enderror
						</div>
					</div>
					<div class="form-group row">
						<label class="col-lg-3 control-label text-lg-right pt-2" for="display_position">Display Position <span class="required">*</span></label>
						<div class="col-lg-6">
							<input type="number" class="form-control @error('display_position') error @enderror" id="position" name="display_position" value="{{ old('display_position', $tradeshow->display_position) }}">
							@error('display_position')
							<label class="error" for="display_position" role="alert">{{ $message }}</label>
							@enderror
						</div>
					</div>

					<div class="form-group row align-items-center">
						<label class="col-lg-5 col-xl-3 control-label text-lg-right mb-0" for="status">Status</label>
						<div class="col-lg-7 col-xl-6">
							<select name="status" id="status" class="form-control form-control-modern">
								<option value="1" {{ old('status', $tradeshow->status) == '1' ? 'selected' : '' }}>
									Active</option>
								<option value="0" {{ old('status', $tradeshow->status) == '0' ? 'selected' : '' }}>
									Inactive</option>
							</select>
						</div>
					</div>
				</div>
			</section>
		</div>
	</div>


	<div class="row action-buttons">
		<div class="col-12 col-md-auto">
			<!-- <button type="button" class="submit-button btn btn-primary btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnSaveRecord" data-loading-text="Loading..."> <i class="bx bx-save text-4 mr-2"></i> Save </button> -->
			<button type="submit"
                    class="submit-button btn btn-primary btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnSaveRecord"
                    data-loading-text="Loading..."> <i class="bx bx-save text-4 mr-2"></i> Save</button>
		</div>
		<div class="col-12 col-md-auto px-md-0 mt-3 mt-md-0"> <a href="javascript:void(0);" class="cancel-button btn btn-light btn-px-4 py-3 border font-weight-semibold text-color-dark text-3 btnCancelSaveRecord">Cancel</a>
		</div>
		@if($tradeshow->treadeshow_id > 0)
		<div class="col-12 col-md-auto ml-md-auto mt-3 mt-md-0">
			<a href="javascript:void(0);" data-id="{{ $tradeshow->treadeshow_id }}" class="delete-button btn btn-danger btn-px-4 py-3 d-flex align-items-center font-weight-semibold line-height-1 btnDeleteRecord"> <i class="bx bx-trash text-4 mr-2"></i> Delete </a>
		</div>
		@endif
	</div>

</form>
@endsection

@push('scripts')
<script>
	let url_list = "{{ route('pnkpanel.trade-show.list') }}";
	let url_edit = "{{ route('pnkpanel.trade-show.edit', ':id') }}";
	let url_update = "{{ route('pnkpanel.trade-show.update') }}";
	let url_delete = "{{ route('pnkpanel.trade-show.delete', ':id') }}";
	let url_delete_image = "{{ route('pnkpanel.trade-show.delete_image') }}";
</script>
<script src="{{ asset('pnkpanel/js/trade_show_edit.js') }}"></script>
<script src="{{ asset('pnkpanel/js/tiny_custom.js') }}"></script>
<script type="text/javascript">
/*	var err_msg1_for_cache = '<?= Session::get('site_common_msg')  ?>';
		if (err_msg1_for_cache != ""){
			$.ajax({
				type: 'POST',
				data: {
		        "_token": "{{ csrf_token() }}",
		       "brand_id": "{{ $tradeshow->treadeshow_id }}"
		        },
				url: site_url + '/clearfrontcachebrandmenu',
				success: function(data) {
					console.log('Brand menu  cache clear sucessfully');

				}
			});
		}*/
</script>
@endpush