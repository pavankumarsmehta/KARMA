<div class="datatable-footer">
	<div class="row align-items-center justify-content-between mt-3">
		@if(!in_array($CurrentRoute, ['pnkpanel.coupon.coupon_order_list', 'pnkpanel.email-templates.list', 'pnkpanel.order-report.list', 'pnkpanel.salestax-report.list', 'pnkpanel.order-summary']))
		<div class="col-md-auto mb-3 mb-lg-0">
			<div class="d-flex align-items-stretch">
				<select name="bulk_action" id="bulk_action" class="form-control select-style-1 bulk-action mr-3" style="min-width: 170px;">
					<option value="" selected>Bulk Actions</option>
					@if(in_array($CurrentRoute, ['pnkpanel.product-review.list']))
					<option value="yes">Approve</option>
					<option value="no">Un Approve</option>
					@else
					@if(!in_array($CurrentRoute, ['pnkpanel.order.list','pnkpanel.shipping-method-charge.list','pnkpanel.shipping-rule.list']))
					<option value="active">Active</option>
					<option value="inactive">Inactive</option>
					@endif
					@endif
					@if(!in_array($CurrentRoute, ['pnkpanel.country.list','pnkpanel.state.list','pnkpanel.shipping-method.list']))
					<option value="delete">Delete</option>
					@endif
					@if(in_array($CurrentRoute, ['pnkpanel.category.list','pnkpanel.shipping-method.list', 'pnkpanel.instagram.post.list']))
					<option value="update_rank">Update Rank</option>
					@endif
					@if(in_array($CurrentRoute, ['pnkpanel.product.list']))
						<option value="update_rank">Update Display Rank</option>
					@endif
					@if(in_array($CurrentRoute, ['pnkpanel.customer.list']))
					<option value="email_forgot_password">Email Forgot Password</option>
					@endif
				</select>
				<a href="javascript:void(0);" class="btn btn-light btn-px-4 py-3 border font-weight-semibold text-color-dark text-3 btnBulkAction">Apply</a>
			</div>
		</div>
		@endif
		@if(in_array($CurrentRoute, ['pnkpanel.category.list','pnkpanel.brand.list','pnkpanel.manufacturer.list','pnkpanel.frontmenu.list']))
			<div class="col-12 col-lg-auto mb-lg-0 ml-auto align-items-center">
				<div class="results-info-wrapper text-center text-md-right pr-3"></div>
			</div>
		@elseif(in_array($CurrentRoute, ['pnkpanel.order-report.list']))
			<div class="col-12 col-lg-auto mb-lg-0 ml-auto align-items-center">
				<div class="text-center text-md-right pr-3"></div>
			</div>
		@else
		<div class="col-4 col-lg-auto mb-3 mb-lg-0">
			<div class="d-flex align-items-lg-center flex-column flex-lg-row">
				<label class="ws-nowrap mr-3 mb-0">Show:</label>
				<select class="form-control select-style-1 results-per-page" name="results-per-page">
					<option value="10">10</option>
					<option value="25" selected>25</option>
					<option value="50">50</option>
					<option value="100">100</option>
					<option value="200">200</option>
					<option value="500">500</option>
				</select>
			</div>
		</div>
		<div class="col-lg-auto order-2 order-lg-3 mb-3 mb-lg-0">
			<div class="pagination-wrapper"></div>
		</div>
		@endif
	</div>
	@if(!in_array($CurrentRoute, ['pnkpanel.category.list','pnkpanel.brand.list','pnkpanel.manufacturer.list','pnkpanel.frontmenu.list']))
	<div class="row align-items-center justify-content-between mt-2">
		<div class="col-lg-auto mb-lg-0 ml-auto">
			<div class="results-info-wrapper text-right pr-3"></div>
		</div>
	</div>
	@endif
</div>
