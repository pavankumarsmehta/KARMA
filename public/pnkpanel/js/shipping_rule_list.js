(function($) {

	dtOptions = {

		ajax: url_list,

		columns: [

			{data: 'checkbox', name: 'checkbox', orderable: false, searchable: false, exportable: false},

            {data: 'country', name: 'country'},
            {data: 'shipping_mode_id', name: 'shipping_mode_id'},

          	{data: 'state', name: 'state'},

			/* {data: 'zipcode_from', name: 'zipcode_from'},

            {data: 'zipcode_to', name: 'zipcode_to'}, */

			{data: 'action', name: 'action', orderable: false, searchable: false, exportable: false},

		],

	};

    adminGridDataTableInit();

}(jQuery));

