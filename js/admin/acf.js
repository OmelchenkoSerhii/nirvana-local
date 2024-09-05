acf.add_filter('color_picker_args', function( args, $field ){
	
	// Set default list of args for color picker
	args.palettes = ['#B66BA8','#1E2243','#29255B','#CFD1E6','#F7F8FA', '#ffffff']
	
	
	// return
	return args;
			
});