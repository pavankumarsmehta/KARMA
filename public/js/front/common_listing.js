$('#itemsortby').on('change', function () {
	$('.list-more').attr('data-page', '1');
	GetProducts();
});