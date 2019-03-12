var shell = require('shelljs');

module.exports = function(grunt) {
	// Project configuration.
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		po2mo: {
			options: {
			},
			files: {
				src: 'languages/*.po',
				expand: true,
			},
		},
		less: {
			options : {
				plugins : [ new (require('less-plugin-autoprefix'))({browsers : [ "last 2 versions" ]}) ]
			},
			development: {
				files: {
					'style.css': 'assets/less/style.less',
					'shortcodes.css': 'assets/less/shortcodes.less',
					'ilightbox.css': 'assets/less/plugins/iLightbox/iLightbox.less',
					'animations.css': 'assets/less/theme/animations.less',
					'assets/css/rtl.css': 'assets/less/theme/rtl.less',
					'assets/css/woocommerce.css': 'assets/less/theme/woocommerce.less',
					'assets/css/bbpress.css': 'assets/less/theme/bbpress.less',
					'includes/avadaredux/assets/style.css': 'includes/avadaredux/assets/style.less',
				}
			}
		},
		concat: {
			options: {
				separator: ';'
			},
			main: {
				src: [
					'assets/js/bootstrap.js',
					'assets/js/cssua.js',
					'assets/js/excanvas.js',
					'assets/js/Froogaloop.js',
					'assets/js/imagesLoaded.js',
					'assets/js/isotope.js',
					'assets/js/jquery.appear.js',
					'assets/js/jquery.touchSwipe.js',
					'assets/js/jquery.carouFredSel.js',
					'assets/js/jquery.countTo.js',
					'assets/js/jquery.countdown.js',
					'assets/js/jquery.cycle.js',
					'assets/js/jquery.easing.js',
					'assets/js/jquery.easyPieChart.js',
					'assets/js/jquery.elasticslider.js',
					'assets/js/jquery.fitvids.js',
                    'assets/js/jquery.nicescroll.js',
					'assets/js/jquery.flexslider.js',
					'assets/js/jquery.fusion_maps.js',
					'assets/js/jquery.hoverflow.js',
					'assets/js/jquery.hoverIntent.js',
					'assets/js/jquery.infinitescroll.js',
					'assets/js/jquery.placeholder.js',
					'assets/js/jquery.toTop.js',
					'assets/js/jquery.waypoints.js',
					'assets/js/modernizr.js',
					'assets/js/jquery.requestAnimationFrame.js',
					'assets/js/jquery.mousewheel.js',
					'assets/js/ilightbox.js',
					'assets/js/avada-lightbox.js',
					'assets/js/avada-select.js',
					'assets/js/avada-nicescroll.js',
					'assets/js/avada-bbpress.js',
					'assets/js/avada-events.js',
					'assets/js/avada-woocommerce.js',
					'assets/js/avada-parallax.js',
					'assets/js/avada-video-bg.js',
					'assets/js/avada-header.js',
					'assets/js/theme.js'
				],
				dest: 'assets/js/main.js'
			}
		},
		uglify: {
			main: {
				options: {
					mangle: true,
					compress: {
						sequences: true,
						dead_code: true,
						conditionals: true,
						booleans: true,
						unused: true,
						if_return: true,
						join_vars: true,
						drop_console: true
					}
				},
				files: {
					'assets/js/main.min.js': ['assets/js/main.js']
				}
			}
		},
		watch: {
			css: {
				files: ['**/*.less'],
				tasks: ['less:development']
			}
		},
		webfont: {
			icons: {
				src: 'fusion-icon/svg/*.svg',
				dest: 'assets/fonts/fusion-icon',
				destCss: 'assets/less/',
				engine: 'node',
				options: {
					font: 'fusion-icon',
					//classPrefix: "fusion-icon-",
					//baseClass: "fusion-icon-",
					syntax: "bootstrap",
					types: "eot,woff,ttf,svg",
					'relativeFontPath' : 'assets/fonts/fusion-icon/',
					templateOptions: {
						baseClass: '',
						classPrefix: 'fusion-icon-',
						//mixinPrefix: 'fusion-icon-'
					},
					template: 'fusion-icon/template/template.css',
					stylesheet: "less",
					destHtml: "assets/fonts/fusion-icon",
					htmlDemoTemplate: "fusion-icon/template/template.html",
					//ie7: true,
				}
			}
		},
		// Generate .pot translation file
		makepot: {
			target: {
				options: {
					type: 'wp-theme',
					domainPath: 'languages',
					exclude: [
						'includes/avadaredux/avadaredux-framework/.*'
					]
				}
			}
		},
		// copy .pot to .po
		copy: {
			main: {
				src: 'languages/Avada.pot',
				dest: 'languages/Avada.po',
			},
		},
		// Get json file from the google-fonts API
		curl: {
			'google-fonts-source': {
				src: 'https://www.googleapis.com/webfonts/v1/webfonts?sort=alpha&key=AIzaSyCDiOc36EIOmwdwspLG3LYwCg9avqC5YLs',
				dest: 'includes/avadaredux/custom-fields/typography/googlefonts.json'
			}
		},
		// converts the googlefonts json file to a PHP array
		json2php: {
			convert: {
				expand: true,
				ext: '-array.php',
				src: ['includes/avadaredux/custom-fields/typography/googlefonts.json']
			}
		},
		// Delete the json array
		clean: ['includes/avadaredux/custom-fields/typography/googlefonts.json']
	});

	grunt.loadNpmTasks('grunt-contrib-less');
	grunt.loadNpmTasks('grunt-webfont');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-po2mo');
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-wp-i18n');
	grunt.loadNpmTasks('grunt-contrib-copy');
	grunt.loadNpmTasks('grunt-curl');
	grunt.loadNpmTasks('grunt-json2php');
	grunt.loadNpmTasks('grunt-contrib-clean');

	grunt.registerTask('watchCSS', ['watch:css']);
	grunt.registerTask('default', ['less:development', 'concat:main', 'uglify:main', 'makepot', 'copy', 'po2mo']);
	grunt.registerTask('googlefonts', ['curl:google-fonts-source', 'json2php', 'clean']);

	grunt.registerTask('langUpdate', 'Update languages', function() {
		shell.exec('tx pull -r avada.avadapo -a --minimum-perc=10');
		shell.exec('tx pull -r avada.fusion-corepo -a --minimum-perc=10');
		shell.exec('grunt po2mo');
	});
};
