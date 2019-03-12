/* jshint node:true */
var shell = require( 'shelljs' );

module.exports = function( grunt ) {

	// Project configuration.
	grunt.initConfig(
		{
			pkg: grunt.file.readJSON( 'package.json' ),

			concat: {
				options: {
					separator: ';'
				},
				core: {
					src: [
						'AvadaReduxCore/assets/js/vendor/cookie.js',
						'AvadaReduxCore/assets/js/vendor/jquery.typewatch.js',
						'AvadaReduxCore/assets/js/vendor/jquery.serializeForm.js',
						'AvadaReduxCore/assets/js/vendor/jquery.alphanum.js',
						'AvadaReduxCore/assets/js/avadaredux.js'
					],
					dest: 'AvadaReduxCore/assets/js/avadaredux.min.js'
				},
				vendor: {
					src: [
						'AvadaReduxCore/assets/js/vendor/cookie.js',
						'AvadaReduxCore/assets/js/vendor/jquery.serializeForm.js',
						'AvadaReduxCore/assets/js/vendor/jquery.typewatch.js',
						'AvadaReduxCore/assets/js/vendor/jquery.alphanum.js'
					],
					dest: 'AvadaReduxCore/assets/js/vendor.min.js'
				}
			},
			'gh-pages': {
				options: {
					base: 'docs',
					message: 'Update docs and files to distribute'
				},
				dev: {
					src: ['docs/**/*', 'bin/CNAME']
				},
				travis: {
					options: {
						repo: 'https://' + process.env.GH_TOKEN + '@github.com/AvadaReduxFramework/docs.avadareduxframework.com.git',
						user: {
							name: 'Travis',
							email: 'travis@travis-ci.org'
						},
						silent: false
					},
					src: ['**/*']
				}
			},
			uglify: {
				fields: {
					files: [
						{
							expand: true,
							cwd: 'AvadaReduxCore/inc/fields',
							src: ['**/*.js', '!**/*.min.js', '!ace_editor/vendor/*.js', '!ace_editor/vendor/snippets/*.js', '!slider/vendor/nouislider/*.*', '!spinner/vendor/*.*'],
							ext: '.min.js',
							dest: 'AvadaReduxCore/inc/fields'
						}
					]
				},
				extensions: {
					files: [
						{
							expand: true,
							cwd: 'AvadaReduxCore/inc/extensions',
							src: ['**/*.js', '!**/*.min.js'],
							ext: '.min.js',
							dest: 'AvadaReduxCore/inc/extensions'
						}
					]
				},
				core: {
					files: {
						'AvadaReduxCore/assets/js/avadaredux.min.js': [
							'AvadaReduxCore/assets/js/avadaredux.min.js'
						],
						'AvadaReduxCore/assets/js/vendor/spectrum/avadaredux-spectrum.min.js': [
							'AvadaReduxCore/assets/js/vendor/spectrum/avadaredux-spectrum.js'
						],
						'AvadaReduxCore/assets/js/vendor/avadaredux.select2.sortable.min.js': [
							'AvadaReduxCore/assets/js/vendor/avadaredux.select2.sortable.js'
						],
						'AvadaReduxCore/assets/js/media/media.min.js': [
							'AvadaReduxCore/assets/js/media/media.js'
						]
					}

				},
				vendor: {
					files: {
						'AvadaReduxCore/assets/js/vendor.min.js': [
							'AvadaReduxCore/assets/js/vendor.min.js'
						]
					}
				}
			},
			qunit: {
				files: ['test/qunit/**/*.html']
			},

			// JavaScript linting with JSHint.
			jshint: {
				options: {
					jshintrc: '.jshintrc'
				},
				files: [
					//'Gruntfile.js',
					//'AvadaReduxCore/assets/js/import_export/import_export.js',
					'AvadaReduxCore/assets/js/media/media.js',
					'AvadaReduxCore/inc/fields/ace_editor/field_ace_editor.js',
					'AvadaReduxCore/inc/fields/background/field_background.js',
					'AvadaReduxCore/inc/fields/border/field_border.js',
					'AvadaReduxCore/inc/fields/button_set/field_button_set.js',
					'AvadaReduxCore/inc/fields/checkbox/field_checkbox.js',
					'AvadaReduxCore/inc/fields/color/field_color.js',
					'AvadaReduxCore/inc/fields/color_rgba/field_color_rgba.js',
					'AvadaReduxCore/inc/fields/date/field_date.js',
					'AvadaReduxCore/inc/fields/dimensions/field_dimensions.js',
					'AvadaReduxCore/inc/fields/editor/field_editor.js',
					'AvadaReduxCore/inc/fields/gallery/field_gallery.js',
					'AvadaReduxCore/inc/fields/image_select/field_image_select.js',
					'AvadaReduxCore/inc/fields/multi_text/field_multitext.js',
					'AvadaReduxCore/inc/fields/palette/field_palette.js',
					'AvadaReduxCore/inc/fields/select/field_select.js',
					'AvadaReduxCore/inc/fields/select_image/field_select_image.js',
					'AvadaReduxCore/inc/fields/slider/field_slider.js',
					'AvadaReduxCore/inc/fields/slides/field_slides.js',
					'AvadaReduxCore/inc/fields/sortable/field_sortable.js',
					'AvadaReduxCore/inc/fields/sorter/field_sorter.js',
					'AvadaReduxCore/inc/fields/spacing/field_spacing.js',
					'AvadaReduxCore/inc/fields/spinner/field_spinner.js',
					'AvadaReduxCore/inc/fields/switch/field_switch.js',
					'AvadaReduxCore/inc/fields/typography/field_typography.js',
					// 'AvadaReduxCore/inc/fields/**/*.js',
					'AvadaReduxCore/extensions/**/*.js',
					'AvadaReduxCore/extensions/**/**/*.js',
					'AvadaReduxCore/assets/js/avadaredux.js'
				]
			},

			// Watch changes for files.
			watch: {
				ui: {
					files: ['<%= jshint.files %>'],
					tasks: ['jshint']
				},
				php: {
					files: ['AvadaReduxCore/**/*.php'],
					tasks: ['phplint:core']
				},
				css: {
					files: ['AvadaReduxCore/**/*.less'],
					tasks: ['less:development']
				}
			},

			// Add textdomain.
			addtextdomain: {
				options: {
					textdomain: 'avadaredux-framework',    // Project text domain.
					updateDomains: ['avadaredux', 'avadaredux-framework-demo', 'v']  // List of text domains to replace.
				},
				target: {
					files: {
						src: ['*.php', '**/*.php', '!node_modules/**', '!tests/**', '!sample/**']
					}
				}
			},

			// Generate POT files.
			makepot: {
				avadaredux: {
					options: {
						type: 'wp-plugin',
						domainPath: 'AvadaReduxCore/languages',
						potFilename: 'avadaredux-framework.pot',
						include: [],
						exclude: [
							'sample/.*'
						],
						potHeaders: {
							poedit: true,
							'report-msgid-bugs-to': 'https://github.com/AvadaReduxFramework/AvadaReduxFramework/issues',
							'language-team': 'LANGUAGE <support@avadareduxframework.com>'
						}
					}
				}
			},

			// Check textdomain errors.
			checktextdomain: {
				options: {
					keywords: [
						'__:1,2d',
						'_e:1,2d',
						'_x:1,2c,3d',
						'esc_html__:1,2d',
						'esc_html_e:1,2d',
						'esc_html_x:1,2c,3d',
						'esc_attr__:1,2d',
						'esc_attr_e:1,2d',
						'esc_attr_x:1,2c,3d',
						'_ex:1,2c,3d',
						'_n:1,2,4d',
						'_nx:1,2,4c,5d',
						'_n_noop:1,2,3d',
						'_nx_noop:1,2,3c,4d'
					]
				},
				avadaredux: {
					cwd: 'AvadaReduxCore/',
					options: {
						text_domain: 'avadaredux-framework',
					},
					src: ['**/*.php'],
					expand: true
				},
				sample: {
					cwd: 'sample',
					options: {
						text_domain: 'avadaredux-framework-demo',
					},
					src: ['**/*.php'],
					expand: true
				}
			},

			// Exec shell commands.
			shell: {
				options: {
					stdout: true,
					stderr: true
				},
				// Limited to Maintainers so
				// txpush: {
				//  command: 'tx push -s' // push the resources
				// },
				txpull: {
					command: 'tx pull -a --minimum-perc=25' // pull the .po files
				}
			},

			// Generate MO files.
			potomo: {
				dist: {
					options: {
						poDel: true
					},
					files: [{
						expand: true,
						cwd: 'AvadaReduxCore/languages/',
						src: ['*.po'],
						dest: 'AvadaReduxCore/languages/',
						ext: '.mo',
						nonull: true
					}]
				}
			},

			phpdocumentor: {
				options: {
					directory: 'AvadaReduxCore/',
					target: 'docs/'
				},
				generate: {}
			},

			phplint: {
				options: {
					swapPath: './'
				},
				core: ["AvadaReduxCore/**/*.php"],
				plugin: ["class-avadaredux-plugin.php", "index.php", "avadaredux-framework.php"]
			},

			sass: {
				fields: {
					options: {
						// sourcemap: 'none',
						style: 'compressed',
						noCache: true,
					},

					files: [{
						expand: true,                   // Enable dynamic expansion.
						cwd: 'AvadaReduxCore/inc/fields',    // Src matches are relative to this path.
						src: ['**/*.scss'],             // Actual pattern(s) to match.
						dest: 'AvadaReduxCore/inc/fields',   // Destination path prefix.
						ext: '.css'                     // Dest filepaths will have this extension.
					}]
				},
				extensions: {
					options: {
						// sourcemap: 'none',
						style: 'compressed',
						noCache: true,
					},

					files: [{
						expand: true,                   // Enable dynamic expansion.
						cwd: 'AvadaReduxCore/inc/extensions',    // Src matches are relative to this path.
						src: ['**/*.scss'],             // Actual pattern(s) to match.
						dest: 'AvadaReduxCore/inc/extensions',   // Destination path prefix.
						ext: '.css'                     // Dest filepaths will have this extension.
					}]
				},
				vendor: {
					options: {
						// sourcemap: 'none',
						style: 'compressed',
						noCache: true
					},

					files: {
						"AvadaReduxCore/assets/css/vendor/jquery-ui-bootstrap/jquery-ui-1.10.0.custom.css": [
							"AvadaReduxCore/assets/css/vendor/jquery-ui-bootstrap/jquery-ui-1.10.0.custom.scss"
						],
						"AvadaReduxCore/assets/css/vendor/elusive-icons/elusive-icons.css": [
							"AvadaReduxCore/assets/css/vendor/elusive-icons/scss/elusive-icons.scss"
						],
					}
				},

				admin: {
					options: {
						// sourcemap: 'none',
						style: 'compressed',
						noCache: true
					},

					files: {
						"AvadaReduxCore/assets/css/color-picker/color-picker.css": [
							"AvadaReduxCore/assets/css/color-picker/color-picker.scss"
						],
						"AvadaReduxCore/assets/css/media/media.css": [
							"AvadaReduxCore/assets/css/media/media.scss"
						],
						"AvadaReduxCore/assets/css/avadaredux-admin.css": [
							"AvadaReduxCore/assets/css/avadaredux-admin.scss"
						],
						"AvadaReduxCore/assets/css/rtl.css": [
							"AvadaReduxCore/assets/css/rtl.scss"
						]
					}
				},
				welcome: {
					options: {
						// sourcemap: 'none',
						style: 'compressed',
						noCache: true
					},

					files: {
						"AvadaReduxCore/inc/welcome/css/avadaredux-welcome.css": [
							"AvadaReduxCore/inc/welcome/css/avadaredux-welcome.scss"
						]
					}
				}
			},

			cssmin: {
				fields: {
					files: {
						'AvadaReduxCore/assets/css/avadaredux-fields.css': [
							'AvadaReduxCore/inc/fields/**/*.css',
							"AvadaReduxCore/assets/css/color-picker/color-picker.css",
							"AvadaReduxCore/assets/css/media/media.css"
						]
					}
				},
			}
		}
	);

	// Load NPM tasks to be used here
	grunt.loadNpmTasks( 'grunt-shell' );
	grunt.loadNpmTasks( 'grunt-potomo' );
	grunt.loadNpmTasks( 'grunt-wp-i18n' );
	grunt.loadNpmTasks( 'grunt-checktextdomain' );
	grunt.loadNpmTasks( 'grunt-contrib-jshint' );
	grunt.loadNpmTasks( 'grunt-contrib-sass' );
	grunt.loadNpmTasks( 'grunt-contrib-uglify' );
	grunt.loadNpmTasks( 'grunt-contrib-watch' );
	grunt.loadNpmTasks( 'grunt-contrib-cssmin' );
	grunt.loadNpmTasks( 'grunt-contrib-concat' );
	grunt.loadNpmTasks( 'grunt-phpdocumentor' );
	grunt.loadNpmTasks( 'grunt-gh-pages' );
	grunt.loadNpmTasks( "grunt-phplint" );
	//grunt.loadNpmTasks( 'grunt-recess' );

	grunt.registerTask(
		'langUpdate', [
			'addtextdomain',
			'makepot',
			'shell:txpull',
			'potomo'
		]
	);

	// Default task(s).
	grunt.registerTask(
		'default', [
			'jshint',
			'concat:core',
			'uglify:core',
			'concat:vendor',
			'uglify:vendor',
			'uglify:fields',
			'uglify:extensions',
			"sass:admin",
			"sass:fields",
			"sass:extensions",
			"sass:vendor",
			'cssmin'
		]
	);
	grunt.registerTask( 'travis', ['jshint', 'lintPHP'] );

	// this would be run by typing "grunt test" on the command line
	grunt.registerTask( 'testJS', ['jshint', 'concat:core', 'concat:vendor'] );

	grunt.registerTask( 'watchUI', ['watch:ui'] );
	grunt.registerTask( 'watchPHP', ['watch:php', 'phplint:core', 'phplint:plugin'] );

	grunt.registerTask( "lintPHP", ["phplint:plugin", "phplint:core"] );
	grunt.registerTask( "compileSCSS", ["sass:admin", "sass:fields", "sass:extensions", "sass:vendor", "sass:welcome"] );
	grunt.registerTask(
		'compileJS',
		['jshint', 'concat:core', 'uglify:core', 'concat:vendor', 'uglify:vendor', 'uglify:fields', 'uglify:extensions']
	);
	grunt.registerTask( 'compileTestJS', ['jshint', 'concat:core', 'concat:vendor'] );
	grunt.registerTask( 'compileCSS', ['cssmin'] );
};
