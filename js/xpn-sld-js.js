	
	
	$(document).on("click", ".img-add", function(){
		
		var fm = $("<div/>").dialogelfinder({
			url : "plugins/elFinder/php/connector.php",
			lang : "en",
			width : 840,
			destroyOnClose : true,
			UTCDate: true,
			dateFormat: 'M d, Y h:i',
			fancyDateFormat : '$1 H:m:i',
			getFileCallback : function(files, fm){
				pievBildes(files);
			},
			commandsOptions : {
				getfile : {
					// send only URL or URL+path if false
					onlyURL  : false,
					// allow to return multiple files info
					multiple : false,
					// allow to return folders info
					folders  : false,
					// action after callback (close/destroy)
					oncomplete : 'close'
				}
			},
			uiOptions : {
				// toolbar configuration
				toolbar : [
					["back", "forward"],
					["reload"],
					["home", "up"],
					["mkdir", "mkfile", "upload"],
					["open", "download", "getfile"],
					["info"],
					["quicklook"],
					["copy", "cut", "paste"],
					["rm"],
					["duplicate", "rename", "edit", "resize"],
					["extract", "archive"],
					["search"],
					["view"],
					["help"]
				],
				// directories tree options
				tree : {
					// expand current root on init
					openRootOnLoad : true,
					// auto load current dir parents
					syncTree : true
				},
				// navbar options
				navbar : {
					minWidth : 150,
					maxWidth : 500
				},
				// current working directory options
				cwd : {
					// display parent directory in listing as ".."
					oldSchool : false
				}
			}
		}).dialogelfinder("instance");
		
		fm.bind("upload", function(e){ 
			//console.log(e);
		});
		
	});	
