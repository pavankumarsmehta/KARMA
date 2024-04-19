@extends('pnkpanel.layouts.app')
@section('content')
<div class="middle-admin">
	<div class="row">
		<div class="col-lg-12">
			<div class="row mb-3">
				<div class="col-xl-6">
					<section class="card card-featured-left card-featured-quaternary mb-3">
						<div class="card-body">
							<div class="widget-summary">
								<div class="widget-summary-col widget-summary-col-icon">
									<div class="summary-icon bg-quaternary">
										<i class="fas fa-user"></i>
									</div>
								</div>
								<div class="widget-summary-col">
									<div class="summary">
										<h4 class="title">Today's Customers</h4>
										<div class="info">
											<strong class="amount">{{ $tot_customer }}</strong>
										</div>
									</div>
									<div class="summary-footer">
										<a class="text-muted text-uppercase" href="{{ route('pnkpanel.customer.list') }}">VIEW ALL</a>
									</div>
								</div>
							</div>
						</div>
					</section>
				</div>
				<div class="col-xl-6">
					<section class="card card-featured-left card-featured-tertiary">
						<div class="card-body">
							<div class="widget-summary">
								<div class="widget-summary-col widget-summary-col-icon">
									<div class="summary-icon bg-tertiary">
										<i class="fas fa-shopping-cart"></i>
									</div>
								</div>
								<div class="widget-summary-col">
									<div class="summary">
										<h4 class="title">Today's Orders</h4>
										<div class="info">
											<strong class="amount">{{ $tot_order }}</strong>
											<span class="text-primary">${{ $tot_order_amt }}</span>
										</div>
									</div>
									<div class="summary-footer">
										<a class="text-muted text-uppercase" href="{{ route('pnkpanel.order.list') }}">VIEW ALL</a>
									</div>
								</div>
							</div>
						</div>
					</section>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection
