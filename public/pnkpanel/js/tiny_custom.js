tinymce.init({
	selector: '.mceEditor',
	toolbar_mode: 'wrap',
	height: 300,
	menubar: false, //true
	fontsize_formats: "8px 10px 12px 14px 18px 24px 36px",
	/*plugins: [
		'advlist autolink lists link image charmap print preview anchor',
		'searchreplace visualblocks code fullscreen',
		'insertdatetime media table paste code help wordcount'
	],*/
	/*plugins: [
		'advlist autolink lists link image charmap print preview anchor',
		'searchreplace visualblocks code fullscreen',
		'insertdatetime media table paste code help wordcount',
		'safari spellchecker pagebreak style layer save advhr advimage advlink emotions iespell inlinepopups contextmenu directionality noneditable visualchars nonbreaking xhtmlxtras template ibrowser textcolor'
	],*/
	plugins: [
		'advlist autolink lists link image charmap print preview anchor',
		'searchreplace visualblocks code fullscreen',
		'insertdatetime media table paste code help wordcount',
		'pagebreak save contextmenu directionality noneditable visualchars nonbreaking template'
	],
	toolbar: 'undo redo | fontsizeselect | link | ' +
	'bold italic forecolor backcolor | alignleft aligncenter ' +
	'alignright alignjustify | bullist | ' +
	'removeformat | code | preview | help',
	//content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px }',
	//extended_valid_elements : "+@[data-options],a[href|onclick|class|align|style|target=_blank],svg[*],use[*],strong/b,div[id|dir|class|align|style]",
	extended_valid_elements : "+@[data-options],a[href|onclick|class|align|style|title],svg[*],use[*],strong/b,div[id|dir|class|align|style]",
	allow_script_urls: true,
	valid_children : "+body[style]",
	verify_html: true,
	force_br_newlines : true,
	force_p_newlines : false,
	forced_root_block : '',
});