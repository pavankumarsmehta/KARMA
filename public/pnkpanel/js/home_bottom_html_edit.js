
tinymce.init({
	selector: '.mceEditor',
	height: 500,
	menubar: false, //true
	/*plugins: [
		'advlist autolink lists link image charmap print preview anchor',
		'searchreplace visualblocks code fullscreen',
		'insertdatetime media table paste code help wordcount'
	],*/
	/*plugins: [
		'advlist autolink lists link image charmap print preview anchor',
		'searchreplace visualblocks code fullscreen',
		'insertdatetime media table paste code help wordcount',
		'safari spellchecker pagebreak style layer save advhr advimage advlink emotions iespell inlinepopups contextmenu directionality noneditable visualchars nonbreaking xhtmlxtras template ibrowser'
	],*/
	plugins: [
		'advlist autolink lists link image charmap print preview anchor',
		'searchreplace visualblocks code fullscreen',
		'insertdatetime media table paste code help wordcount',
		'pagebreak save contextmenu directionality noneditable visualchars nonbreaking template'
	],
	toolbar: 'undo redo | formatselect | ' +
	'bold italic backcolor | alignleft aligncenter ' +
	'alignright alignjustify | bullist numlist outdent indent | ' +
	'removeformat | help',
	//content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
	extended_valid_elements : "+@[data-options],a[href|onclick|class|align|style|target=_blank],svg[*],use[*],strong/b,div[id|dir|class|align|style]",
	allow_script_urls: true,
	valid_children : "+body[style]",
	verify_html: false,
	force_br_newlines : false,
	force_p_newlines : false,
	forced_root_block : '',
});
